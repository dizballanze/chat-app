<?php

class UserController extends Controller {

    /**
     * Форма регистрации
     */
    public function actionAdd(){
        $this->pageTitle = "Регистрация";
        $this->render('form', array('fields' => array(), 'user' => new User('empty'), 'type' => 'add'));
    }

    /**
     * Регистрация - обработка формы
     */
    public function actionAddPost(){
        $this->pageTitle = "Регистрация";
        $user = new User;
        $user->attributes = $_POST['user'];
        $data = array('user' => $user, 'type' => 'add');
        if ($user->save()){
            $user->enter();
            Yii::app()->user->setFlash('success', "Регистрация прошла успешно");
            if (isset(Yii::app()->session['come_back_path']))
                Yii::app()->request->redirect(Yii::app()->session['come_back_path']);
            else
                $this->redirect(Yii::app()->createUrl('chats'));
        }else{
            $data['errors'] = $user->getUniqueErrors();
        }
        $this->render('form', $data);
    }

    /**
     * Вход на сайт
     */
    public function actionLoginPost(){
        $form = new LoginForm;
        $form->attributes = $_POST['login'];
        if ($form->validate()){
            $form->login();
            if (isset(Yii::app()->session['come_back_path']))
                Yii::app()->request->redirect(Yii::app()->session['come_back_path']);
            else
                $this->redirect(Yii::app()->createUrl('chats'));
        }else{
            Yii::app()->user->setFlash('login-error', true);
            Yii::app()->user->setFlash('login', $_POST['login']['login']);
            $this->redirect(Yii::app()->user->returnUrl);
        }
    }

    /**
     * Настройки пользователя
     */
    public function actionEdit(){
        $this->pageTitle = 'Настройки';
        $this->render('form', array('user' => User::model()->findByPk(Yii::app()->user->id), 'type' => 'edit'));
    }

    /**
     * Обработчик настроек
     */
    public function actionEditPost(){
        $this->pageTitle = 'Настройки';
        $user = User::model()->findByPk(Yii::app()->user->id);
        $user->setScenario('edit');
        $user->attributes = $_POST['user'];
        if ($user->save()){
            Yii::app()->user->setFlash('success', 'Настройки сохранены');
        }
        $this->render('form', array('user' => $user, 'type' => 'edit', 'errors' => $user->getUniqueErrors()));
    }

    /**
     * Шлюз на фото пользователя
     * @param $id
     * @throws CHttpException
     */
    public function actionGetPhoto($id){
        $user = User::model()->findByPk($id);
        if (is_null($user))
            throw new CHttpException(404, 'User not found');
        else
            $this->redirect($user->getPhotoPath(24));
    }

    /**
     * Авторизация через твиттер
     */
    public function actionService() {
        $service = Yii::app()->request->getQuery('service');
        if (isset($service)) {
            $authIdentity = Yii::app()->eauth->getIdentity($service);
            $authIdentity->redirectUrl = Yii::app()->user->returnUrl;
            $authIdentity->cancelUrl = $this->createAbsoluteUrl('/');

            if ($authIdentity->authenticate()){
                $identity = new ServiceUserIdentity($authIdentity);
                if(Yii::app()->user->isGuest){
                    if ($identity->authenticate()) {
                        Yii::app()->user->login($identity);
                        $user = User::model()->findByPk(Yii::app()->user->id);
                        $user->updateUserSessionInfo();
                        $this->redirect(Yii::app()->user->returnUrl);
                    }else{
                        if ($user = User::model()->authByIdentity($authIdentity)){
                            $user->enter();
                            $this->redirect(Yii::app()->createUrl(''));
                        }else{
                            Yii::app()->user->setFlash('error', 'Ошибка при регистрации');
                            $this->redirect(Yii::app()->createUrl(''));
                        }
                    }
                }else{
                    if ($identity->authenticate()) {
                        if ($identity->getId() == Yii::app()->user->id)
                            Yii::app()->user->setFlash('settings-info', 'Данный профиль twitter уже прикреплен');
                        else
                            Yii::app()->user->setFlash('settings-info', 'Данный профиль twitter уже прикреплен к другому аккаунту');
                    }else{
                        $user = User::model()->findByPk(Yii::app()->user->id);
                        if ($user->addProfile($authIdentity)){
                            Yii::app()->user->setFlash('settings-success', 'Профиль ' . $authIdentity->getServiceName() . ' успешно прикреплен');
                        }else{
                            Yii::app()->user->setFlash('settings-error', 'Ошибка! Не удалось прикрепить профиль ' . $authIdentity->getServiceName());
                        }
                    }
                    $this->redirect(Yii::app()->createUrl('settings'));
                }
            }
            $this->redirect(Yii::app()->user->returnUrl);
        }
    }

    public function filters() {
        return array(
            'postOnly + addPost,loginPost',
            array('application.filters.OnlyGuestsFilter + add,addpost,loginPost'),
            array('application.filters.NotAGuestFilter + edit,editpost'),
        );
    }
}