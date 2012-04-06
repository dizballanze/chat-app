<?php

class AutoIdentity extends CUserIdentity {

    private $_id;

    protected $id;

    public function __construct($id) {
        $this->id = $id;
    }

    /**
     * Authenticates a user.
     * The example implementation makes sure if the username and password
     * are both 'demo'.
     * In practical applications, this should be changed to authenticate
     * against some persistent user identity storage (e.g. database).
     * @return boolean whether authentication succeeds.
     */
    public function authenticate() {

        $user = User::model()->findByPk($this->id);

        if (is_null($user))
            $this->errorCode = self::ERROR_USERNAME_INVALID;
        else{
            $this->_id = $user->id;
            $this->setState('email', $user->email);
            $this->setState('name', $user->name);
            $this->errorCode = self::ERROR_NONE;
        }

        return!$this->errorCode;
    }

    public function getId(){
        return $this->_id;
    }
}