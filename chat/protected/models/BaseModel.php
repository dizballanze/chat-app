<?php

class BaseModel extends CActiveRecord {

    /**
     * Получаем уникальные сообщения об ошибках
     * @return array
     */
    public function getUniqueErrors(){
        $errors = array();
        foreach ($this->getErrors() as $attr_errors){
            foreach ($attr_errors as $error){
                $errors[] = $error;
            }
        }
        return array_unique($errors);
    }
}