<?php

class SiteController extends Controller {

    /**
     * Главная страница
     */
    public function actionIndex(){
        $this->pageTitle = '';
        $this->render('index');
    }

    /**
     * Обработка ошибок
     */
    public function actionError() {
        if ($error = Yii::app()->errorHandler->error) {
            if (Yii::app()->request->isAjaxRequest)
                echo $error['message'];
            else
                $this->render('error', $error);
        }
    }

    /**
     * Выход
     */
    public function actionLogout() {
        Yii::app()->user->logout();
        $this->redirect(Yii::app()->homeUrl);
    }

    public function filterUserMainRedirect($filterChain){
        if (!Yii::app()->user->isGuest)
            $this->redirect(Yii::app()->createUrl('chats'));
        else
            $filterChain->run();
    }

    public function actions() {
        return array(
            'page' => array(
                'class' => 'CViewAction',
            ),
        );
    }

    public function filters(){
        return array(
            'UserMainRedirect + index',
        );
    }
}