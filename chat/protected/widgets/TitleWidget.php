<?php
/**
 * Виджет формирования заголовка
 * @package Widgets
 */
class TitleWidget extends CWidget {
    /**
     * Заголовок страницы
     * @var string
     */
    public $title = '';
    /**
     * Базовый заголовок. Например: название сайта
     * @var string
     */
    public $base_title = '';
    /**
     * Разделитель
     * @var string
     */
    public $separator = " - ";
    /**
     * Позиция заголовка страницы: left, right
     * @var type 
     */
    public $position = "left";

    public function run() {
        if (!$this->base_title){
            $this->base_title = Yii::app()->name?Yii::app()->name:'';
        }
        
        if (strlen($this->title) == 0)
            $this->title = Yii::app()->getController()->getPageTitle();
        
        if (!$this->title){
            echo CHtml::encode($this->base_title);
        }else{
            if ('left' == $this->position){
                echo CHtml::encode($this->title . $this->separator . $this->base_title);
            }else{
                echo CHtml::encode($this->base_title . $this->separator . $this->title);
            }
        }
    }
}