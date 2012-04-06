<?php

/**
 * This is the model class for table "user_chat".
 *
 * The followings are the available columns in table 'user_chat':
 * @property string $id
 * @property string $id_chat
 * @property string $id_user
 * @property string $open_date
 *
 * The followings are the available model relations:
 * @property User $user
 * @property Chat $chat
 */
class UserChat extends BaseModel {
    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return UserChat the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'user_chat';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('id_chat, id_user', 'length', 'max' => 20),
            array('open_date', 'default', 'value' => date('Y-m-d H:i:s'), 'on' => 'insert'),
            array('id_chat, id_user, open_date', 'required'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
            'user' => array(self::BELONGS_TO, 'User', 'id_user'),
            'chat' => array(self::BELONGS_TO, 'Chat', 'id_chat'),
        );
    }
}