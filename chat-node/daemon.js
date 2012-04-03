var redis_host = '127.0.0.1',
    redis_port = 6379,
    history_msg_count = 50; // Количество сообщений, которые грузятся из истории при заходе в чат

var redis = require("redis"),
    client = redis.createClient(redis_port, redis_host);
var io = require('socket.io')
        .listen(3000);

io.set('log level', 1);
io.set('transports', [
    'websocket'
    , 'flashsocket'
    , 'htmlfile'
    , 'xhr-polling'
    , 'jsonp-polling'
]);

/** API для работы с БД */

/**
 * Добавляем сообщение в БД
 * @param id_chat
 * @param id_user
 * @param message
 */
function addMessageToList(id_chat, id_user, message, callback){
    client.get("user:name:" + id_user, function(err, user_name){

        if (err || (false == user_name))
            return;

        message = message.replace(/<(?:.|\n)*?>/gm, '');

        var json = JSON.stringify({
                'id_user': id_user,
                'name': user_name,
                'message': message
            }
        );

        if (undefined != callback){
            callback(json);
        }

        client.lpush("chat:" + id_chat, json, function(err, res){
            if (err)
                return console.error("error response - " + err);
        });
    });
}

/**
 * Аутентификация пользователя
 * @param id_user
 * @param sessid
 * @param ip
 * @param callback
 */
function auth(id_user, sessid, ip, callback){
    var sessid_key = "user:session:id:" + id_user;
    var ip_key = "user:session:ip:" + id_user;

    client.mget([sessid_key, ip_key], function(err, res){
        if (err || (res.length != 2)){
            if (undefined != callback)
                callback(false);
            return;
        }

        if (undefined != callback){
            callback( ((res[0] == sessid) && (res[1] == ip)) );
        }
    });
}

/**
 * Получаем историю сообщений чата
 * @param id_chat
 * @param callback
 */
function getHistory(id_chat, callback){
    client.lrange("chat:" + id_chat, 0, history_msg_count, function(err, data){
        if (err) {
            if (undefined != callback)
                callback(false);
            return;
        }

        callback(data);
    });
}

/**
 * Получаем список онлайн пользователей чата
 * @param id_chat
 * @param id_user
 * @param callback
 */
function getOnlineUsers(id_chat, id_user, callback){
    var name_keys = [];
    var ids = [];
    name_keys.push('user:name:' + id_user);
    ids.push(id_user);
    if (undefined != sockets[id_chat]){
        for (var i in sockets[id_chat]){
            name_keys.push('user:name:' + i);
            ids.push(i);
        }
    }
    client.mget(name_keys, function(err, data){
        if (err)
            return;

        var res = {};
        for (i in data){
            res[ids[i]] = data[i];
        }
        if (undefined != callback)
            callback(res);
    })
}


var sockets = [];

io.sockets.on('connection', function (socket) {
    // Аутентификация и получение истории
    socket.on('auth', function(data){
        if ((undefined == data['id_user']) || (undefined == data['sessid']) || (undefined == data['id_chat'])){
            socket.emit('auth-result', {result: false});
            return;
        }

        auth(data['id_user'], data['sessid'], socket.handshake.address.address, function(res){
            var user_name;
            var auth_data = {result: res};
            if (res){
                var combo = new Combo(function(init_data){
                    auth_data['history'] = init_data[0][0];
                    auth_data['online']  = init_data[1][0];
                    socket.emit('auth-result', auth_data);

                    // Оповещаем других пользователей чата о новом участнике
                    if (undefined != sockets[data['id_chat']]){
                        client.get('user:name:' + data['id_user'], function(err, name){
                            if (err)
                                return;
                            user_name = name;
                            var json_user = JSON.stringify({id: data['id_user'], name:name});
                            for (var i in sockets[data['id_chat']]){
                                if (i == data['id_user'])
                                    continue;
                                sockets[data['id_chat']][i].emit('new-user', json_user);
                            }
                        });
                    }

                    // Сохраняем данные клиента / начинаем ожидание сообщений
                    if (undefined == sockets[data['id_chat']])
                        sockets[data['id_chat']] = {};
                    sockets[data['id_chat']][data['id_user']] = socket;

                    // Получаем сообщение от пользователя
                    socket.on('send-message', function (msg) {
                        addMessageToList(data['id_chat'], data['id_user'], msg['msg'], function(json_msg){
                            for (i in sockets[data['id_chat']]){
                                sockets[data['id_chat']][i].emit('receive-message', json_msg);
                            }
                        });
                    });

                    // При отключении
                    socket.on('disconnect', function(){
                        if (undefined != sockets[data['id_chat']]){
                            var json_user = JSON.stringify({id: data['id_user'], name:user_name});
                            delete sockets[data['id_chat']][data['id_user']];
                            for (var i in sockets[data['id_chat']]){
                                sockets[data['id_chat']][i].emit('leave-user', json_user);
                            }
                        }
                    });
                });

                getHistory(data['id_chat'], combo.add());
                getOnlineUsers(data['id_chat'], data['id_user'], combo.add());
            }else
                socket.emit('auth-result', auth_data);
        })
    });
});


// combo library
function Combo(callback) {
    this.callback = callback;
    this.items = 0;
    this.results = [];
}
Combo.prototype = {
    add: function () {
        var self = this;
        var id = this.items;
        this.items++;
        return function () {
            self.check(self.items - 1, arguments, id);
        };
    },
    check: function (id, arguments_in, identifier) {
        this.results[identifier] = arguments_in;
        this.items--;
        if (this.items == 0) {
            this.callback.call(this, this.results);
        }
    }
};