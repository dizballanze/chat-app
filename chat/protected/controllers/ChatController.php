<?php

class ChatController extends Controller {
    /**
     * @var Chat
     */
    protected $chat;

    /**
     * Список чатов юзера
     */
    public function actionList(){
        $this->pageTitle = 'Мои чаты';
        $user = User::model()->findByPk(Yii::app()->user->id);
        $this->render('list', array('user' => $user));
    }

    /**
     * Выводим форму добавления чата
     */
    public function actionAdd(){
        $this->pageTitle = 'Добавить чат';
        $this->render('form', array('type' => 'add', 'chat' => new Chat('new')));
    }

    /**
     * Добавляем чат
     */
    public function actionAddPost(){
        $this->pageTitle = 'Добавить чат';
        $chat = new Chat;
        $chat->attributes = $_POST['chat'];
        if ($chat->save()){
            Yii::app()->user->setFlash('success', 'Чат успешно добавлен');
            $this->redirect(Yii::app()->createUrl('chat/edit/id/' . $chat->id));
        }else{
            $this->render('form', array('type' => 'add', 'chat' => $chat, 'errors' => $chat->getUniqueErrors()));
        }
    }

    /**
     * Форма редактирования чата
     */
    public function actionEdit(){
        $this->pageTitle = 'Настройки чата';
        $this->render('form', array('type' => 'edit', 'chat' => $this->chat));
    }

    /**
     * Сохранение чата
     */
    public function actionEditPost(){
        $this->pageTitle = 'Настройки чата';
        $this->chat->scenario = 'edit';
        $this->chat->attributes = $_POST['chat'];
        if ($this->chat->save()){
            Yii::app()->user->setFlash('success', 'Настройки чата сохранены');
            $this->redirect(Yii::app()->createUrl('chats'));
        }else{
            $this->render('form', array('type' => 'edit', 'chat' => $this->chat, 'errors' => $this->chat->getUniqueErrors()));
        }
    }

    /**
     * Собственно чат
     */
    public function actionChat(){
        $this->pageTitle = $this->chat->name;
        $user = User::model()->findByPk(Yii::app()->user->id);
        $user->openChat($this->chat);
        $this->render('chat');
    }

    /**
     * История сообщений
     */
    public function actionHistory(){
        $this->pageTitle = 'История - ' . $this->chat->name;
        /** @var Redis $redis  */
        $redis = Yii::app()->redis->getClient();
        $msgs = $redis->lRange('chat:' . $this->chat->uri, 0, -1);
        foreach ($msgs as &$msg){
            $msg = json_decode($msg, true);
        }
        $this->render('history', array('msgs' => $msgs));
    }

    public function filterChatExists($filterChain){
        $id = Yii::app()->request->getParam('id', false);
        if ($id && ($this->chat = Chat::model()->findByAttributes(array('uri' => $id)))){
            $filterChain->run();
        }else
            throw new CHttpException(404, 'Чат не найден');
    }

    public function filterChatEditAccess($filterChain){
        $id = Yii::app()->request->getParam('id', false);
        if ($id && ($this->chat = Chat::model()->findByAttributes(array('id' => $id, 'id_user' => Yii::app()->user->id))))
            $filterChain->run();
        else
            throw new CHttpException(403, 'Доступ к этому чату запрещён');
    }

    public function filters() {
        return array(
            'postOnly + addPost,editPost',
            array('application.filters.NotAGuestFilter'),
            'ChatEditAccess + edit,editPost',
            'ChatExists + chat,history',
        );
    }
}