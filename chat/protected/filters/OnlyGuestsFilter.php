<?php
/**
 * Фильтр разрешающий доступ только гостям
 */
class OnlyGuestsFilter extends CFilter {
    protected function preFilter($filterChain){
        if (!Yii::app()->user->isGuest){
            Yii::app()->getController()->redirect('/');
        }else{
            return true;
        }
    }
}