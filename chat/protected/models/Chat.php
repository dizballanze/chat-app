<?php

/**
 * This is the model class for table "chat".
 *
 * The followings are the available columns in table 'chat':
 * @property string $id
 * @property string $name
 * @property string $id_user
 * @property string $create_date
 * @property string $uri
 *
 * The followings are the available model relations:
 * @property User $owner
 * @property User[] $users
 */
class Chat extends BaseModel {

    protected $salt = 'dk20vhtk40dj3nld9';

    protected $symbols = 'abcdefghijklmnopqrstuvwxyz1234567890';

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return Chat the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'chat';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('name', 'length', 'max' => 128),
            array('id_user', 'default', 'value' => Yii::app()->user->id, 'on' => 'insert'),
            array('create_date', 'default', 'value' => date('Y-m-d H:i:s'), 'on' => 'insert'),
            array('name', 'safe'),
            array('id_user, create_date', 'unsafe'),
            array('name, id_user, create_date', 'required', 'on' => 'insert,edit', 'message' => 'Введите название чата'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
            'owner' => array(self::BELONGS_TO, 'User', 'id_user'),
            'users' => array(self::MANY_MANY, 'User', 'user_chat(id_chat, id_user)'),
        );
    }

    /**
     * Генерируем случайный uri чата
     * @return string
     */
    protected function getRandomString(){
        return substr(str_shuffle($this->symbols), 0, 6);
    }

    protected function afterSave(){
        parent::afterSave();
        if ($this->isNewRecord){
            $uri = $this->id . $this->getRandomString();
            $this->updateByPk($this->id, array('uri' => $uri));
        }
        return true;
    }
}