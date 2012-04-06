<?php

class UserIdentity extends CUserIdentity {

    private $_id;

    public function authenticate() {
        if (false !== strpos($this->username, '@')){
            $user = User::model()->findByAttributes(array('email' => $this->username));
        }else{
            $user = User::model()->findByAttributes(array('name' => $this->username));
        }

        if (is_null($user))
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        else if (!$user->loginCheck($this->password))
            $this->errorCode = self::ERROR_PASSWORD_INVALID;
        else{
            $this->_id = $user->id;
            $this->setState('email', $user->email);
            $this->setState('name', $user->name);
            $this->errorCode = self::ERROR_NONE;
        }

        return !$this->errorCode;
    }

    public function getId(){
        return $this->_id;
    }
}