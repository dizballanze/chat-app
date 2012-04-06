<div class="row chat-body">
    <div class="span8">
        <div class="well">
            <div class="chat-messages">
                <div class="btn btn-primary btn-history"><a href="<?php echo Yii::app()->createUrl('/history/' . $this->chat->uri)?>" target="_blank"><i class="icon-list icon-white"></i> Посмотреть всю историю</a></div>
            </div>
        </div>
    </div>
    <div class="span3">
        <div class="well">
            <div class="users">
                <h3>Участники</h3>
            </div>
        </div>
    </div>
</div>

<div class="row">
    <div class="span8">
            <div class="form-inline well">
                <input type="text" class="input-xlarge" placeholder="Сообщение" id="message">
                <button class="btn" id="send-message"><i class="icon-comment"></i> Отправить</button>
                <br>
            </div>
    </div>
</div>

<div><em>Для того чтобы пригласить друга в чат, просто скопируй и отправь ему ссылку!</em></div>

<script type="text/javascript" src="<?php echo Yii::app()->params['socket.io'] ?>/socket.io/socket.io.js"></script>
<script type="text/javascript">

    soundManager.flashVersion = 9;
    soundManager.useHTML5Audio = true;
    soundManager.url = '/swf/';
    var new_msg_sound;

    soundManager.onready(function() {
        new_msg_sound = soundManager.createSound({
            id: 'sound_new_msg',
            url: '/sounds/msg.mp3'
        });
    });

    function display_service_message(msg){
        $(".chat-messages").append('<div class="service-message">' + msg + '</div>')
                           .animate({ scrollTop: $(".chat-messages").prop("scrollHeight") }, "slow");
    }

    function add_user(id_user, name){
        $(".users").append(
            '<div class="user" id="user-' + id_user + '">'
                + '<img src="/user/getphoto/id/' + id_user + '">'
                + '<span class="nick-name">' + name + '</span>'
                + '</div>'
        );
    }

    function add_msg(name, msg, animate){
        $(".chat-messages").append(
            '<div class="message">'
                + '<span class="author">' + name + ':</span>'
                + '<span class="message-text">' + msg + '</span>'
          + '</div>'
        )

        if (animate){
            $(".chat-messages").animate({ scrollTop: $(".chat-messages").prop("scrollHeight") }, "slow");
            $(".chat-messages .message .message-text:last-child").emoticonize();
            if (undefined != new_msg_sound){
                new_msg_sound.play();
            }
        }
    }

    function emit_msg_send(){
        var message = $("#message").val();
        if (message.length){
            socket.emit('send-message', {msg: message});
            $("#message").val('').focus();
        }
        return false;
    }

    var socket = io.connect('<?php echo Yii::app()->params['socket.io'] ?>');
    socket.emit('auth', { sessid: '<?php echo session_id() ?>', id_user: <?php echo Yii::app()->user->id ?>, id_chat: '<?php echo $this->chat->uri ?>'});

    socket.on('auth-result', function(data) {
        if (!data['result']){
            alert('Ошибка аутентификации! Попробуйте войти снова');
            location.href = "<?php echo Yii::app()->baseUrl ?>/logout";
        }else{

            // Показываем историю сообщений
            for (var i = data['history'].length-1; i>=0; i--){
                var msg = $.parseJSON(data['history'][i]);
                add_msg(msg['name'], msg['message']);
            }

            // Показываем список пользователей онлайн
            for (var i in data['online']){
                add_user(i, data['online'][i]);
            }
            $(".chat-messages").animate({ scrollTop: $(".chat-messages").prop("scrollHeight") }, "slow");

            var i = 0;
            $(".chat-messages .message .message-text").each(function(){
                var msg = this;
                setTimeout(function(){
                    $(msg).emoticonize();
                },i*10);
                i++;
            });

            // При получении сообщения
            socket.on('receive-message', function(data){
                var msg = $.parseJSON(data);
                add_msg(msg['name'], msg['message'], true);
            });

            // При входе нового пользователя
            socket.on('new-user', function(data){
                var user = $.parseJSON(data);
                if ($("#user-" + user['id']).size())
                    return;
                add_user(user['id'], user['name']);
                display_service_message('Пользователь ' + user['name'] + ' зашёл в чат')
            });

            // При выходе пользователя из чата
            socket.on('leave-user', function(data){
                var user = $.parseJSON(data);
                if (!$("#user-" + user['id']).size())
                    return;
                $("#user-" + user['id']).remove();
                display_service_message('Пользователь ' + user['name'] + ' покинул чат')
            });

            $("#send-message").click(emit_msg_send);
            $("#message").keyup(function(e){
                if (13 == e.keyCode )
                    emit_msg_send();
            })
        }
    });
</script>