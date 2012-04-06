<?php

/**
 * This is the model class for table "user".
 *
 * The followings are the available columns in table 'user':
 * @property string $id
 * @property string $name
 * @property string $email
 * @property string $password
 * @property string $twitter_id
 * @property string $photo
 * @property string $reg_date
 *
 * The followings are the available model relations:
 * @property Chat[] $owned_chats
 * @property Chat[] $used_chats
 * @property int $owned_chats_count
 */
class User extends BaseModel {

    public $password_new;
    public $password_new_repeat;

    protected $salt = 'dlgbh39fht01jap;3uic391dc';

    /**
     * Returns the static model of the specified AR class.
     * @param string $className active record class name.
     * @return User the static model class
     */
    public static function model($className = __CLASS__) {
        return parent::model($className);
    }

    /**
     * @return string the associated database table name
     */
    public function tableName() {
        return 'user';
    }

    /**
     * @return array validation rules for model attributes.
     */
    public function rules() {
        return array(
            array('email', 'email', 'message' => 'Неправильный email'),
            array('email', 'unique', 'message' => 'Email уже занят'),
            array('name', 'match', 'pattern' => '/^[a-z][a-z0-9_]+$/i', 'message' => 'Неправильный ник. Должен состоять из латинских букв, цыфр и знаков подчеркивания'),
            array('name', 'unique', 'message' => 'Ник уже занят'),
            array('reg_date', 'default', 'value' => date('Y-m-d H:i:s'), 'on' => 'insert,oauth-twitter'),
            array('password_new_repeat,password_new', 'safe', 'on' => 'insert,edit'),
            array('password_new', 'compare', 'allowEmpty' => true, 'message' => 'Пароли не совпадают'),
            array('reg_date,photo,twitter_id,password', 'unsafe'),
            array('name, reg_date', 'required', 'on' => 'insert,edit', 'message' => 'Заполните все поля формы'),
            array('password_new_repeat, password_new', 'required', 'on' => 'insert', 'message' => 'Заполните все поля формы'),
        );
    }

    /**
     * @return array relational rules.
     */
    public function relations() {
        return array(
            'owned_chats' => array(self::HAS_MANY, 'Chat', 'id_user', 'order' => 'create_date DESC'),
            'used_chats' => array(self::MANY_MANY, 'Chat', 'user_chat(id_user, id_chat)', 'order' => 'open_date DESC'),
            'owned_chats_count' => array(self::STAT, 'Chat', 'id_user'),
        );
    }

    /**
     * Проверяем пароль
     * @param $password
     * @return bool
     */
    public function loginCheck($password){
        return ($this->password == $this->encryptPassword($password));
    }

    /**
     * Войти в систему
     * @return bool
     */
    public function enter(){
        if (!($this->id > 0))
            return false;
        $identity = new AutoIdentity($this->id);
        if ($identity->authenticate()){
            Yii::app()->user->login($identity, 3600 * 24 * 30);
            $this->updateUserSessionInfo();
            return true;
        }
        return false;
    }

    /**
     * Получаем путь к фото юзера.
     * Либо загруженное фото с твиттера, либо фото с gravatar
     * @param $size
     * @return string
     */
    public function getPhotoPath($size){
        if ($this->photo){
            $url = Yii::app()->baseUrl . '/photos/' . $this->id . '_' . $size . '.jpg';
        }elseif($this->email){
            $url = "http://www.gravatar.com/avatar/" . md5( strtolower( trim( $this->email ) ) ) . "?d=" . urlencode( 'http://' . $_SERVER['SERVER_NAME'] . '/' . Yii::app()->baseUrl . 'img/avatar-default-' . $size . '.jpg' ) . "&s=" . $size;
        }else{
            $url = Yii::app()->baseUrl . 'img/avatar-default-' . $size . '.jpg';
        }

        return $url;
    }

    /**
     * Регистрация через твиттер
     * @param EAuthServiceBase $authIdentity
     * @return bool|User
     */
    public function authByIdentity(EAuthServiceBase $authIdentity){
        $user = new User('oauth-twitter');
        $user->twitter_id = $authIdentity->getAttribute('id');
        $user->name = $authIdentity->getAttribute('name');
        if ($photo_url = $authIdentity->getAttribute('photo'))
            $user->photo = 1;
        if ($user->save()){
            $pathname = Yii::app()->params['photos_dir'] . '/' . $user->id . '.jpg';
            @unlink($pathname);
            $http = new Http;
            @$http->execute($photo_url);
            if (!$http->getError())
                file_put_contents($pathname, $http->getResult());
            if (file_exists($pathname)){
                foreach (Yii::app()->params['photo_sizes'] as $sizes){
                    Yii::app()->image->resize($pathname, Yii::app()->params['photos_dir'], $user->id, $sizes['w'], isset($sizes['h'])?$sizes['h']:null);
                }
            }else{
                $user->photo = 0;
                $user->save();
            }
            return $user;
        }
        return false;
    }

    /**
     * Прикрепляем твиттер аккаунт к юзеру
     * @param EAuthServiceBase $auth
     * @return bool
     */
    public function addProfile(EAuthServiceBase $auth){
        $this->{$auth->getServiceName() . '_id'} = $auth->getAttribute('id');
        return $this->save();
    }

    /**
     * Связываем пользователя с чатом
     * @param Chat $chat
     */
    public function openChat(Chat $chat){
        $user_chat = UserChat::model()->findByAttributes(array('id_user' => $this->id, 'id_chat' => $chat->id));
        if (is_null($user_chat)){
            $user_chat = new UserChat;
            $user_chat->id_user = $this->id;
            $user_chat->id_chat = $chat->id;
            $user_chat->save();
        }else{
            $user_chat->open_date = date('Y-m-d H:i:s');
            $user_chat->save();
        }
    }

    /**
     * Записываем сессионную информацию в redis
     */
    public function updateUserSessionInfo(){
        $redis = Yii::app()->redis->getClient();
        $redis->set('user:session:id:' . $this->id, session_id());
        $redis->set('user:session:ip:' . $this->id, $_SERVER['REMOTE_ADDR']);
    }

    /**
     * @param $password
     * @return string
     */
    protected function encryptPassword($password){
        return md5($this->salt . $password);
    }

    protected function beforeSave(){
        if($this->password_new){
            $this->password = $this->encryptPassword($this->password_new);
        }
        return true;
    }

    protected function afterSave(){
        parent::afterSave();
        $redis = Yii::app()->redis->getClient();
        $redis->set('user:name:' . $this->id, $this->name);
    }
}