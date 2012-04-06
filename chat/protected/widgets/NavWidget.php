<?php
/**
 * Виджет формирования навигационного
 * меню
 * @package Widgets
 */
class NavWidget extends CWidget {
    public function run() {
        $data = array();
        if (!Yii::app()->user->isGuest){
            $data['user'] = User::model()->findByPk(Yii::app()->user->id);
        }
        $this->render('nav', $data);
    }
}