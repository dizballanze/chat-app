<?php
/**
 * Фильтр ограничивающий доступ гостей к действию
 */
class NotAGuestFilter extends CFilter {
    protected function preFilter($filterChain){
        if (Yii::app()->user->isGuest){
            Yii::app()->session['come_back_path'] = '/' . Yii::app()->request->getPathInfo();
            Yii::app()->user->setFlash('error', 'Для того чтобы чатиться - необходимо войти');
            Yii::app()->request->redirect('/');
        }else{
            return true;
        }
    }
}