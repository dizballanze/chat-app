<?php

/**
 * LoginForm class.
 * LoginForm is the data structure for keeping
 * user login form data. It is used by the 'login' action of 'SiteController'.
 */
class LoginForm extends CFormModel {
    public $login;
    public $password;
    public $remember=false;

    private $_identity;

    public function rules() {
        return array(
            array('login, password', 'required'),
            array('password', 'authenticate'),
            array('remember', 'safe'),
        );
    }

    public function authenticate($attribute, $params) {
        if (!$this->hasErrors()) {
            $this->_identity = new UserIdentity($this->login, $this->password);
            if (!$this->_identity->authenticate())
                $this->addError('password', 'Incorrect username or password.');
        }
    }

    /**
     * Logs in the user using the given username and password in the model.
     * @return boolean whether login is successful
     */
    public function login() {
        if ($this->_identity === null) {
            $this->_identity = new UserIdentity($this->login, $this->password);
            $this->_identity->authenticate();
        }
        if ($this->_identity->errorCode === UserIdentity::ERROR_NONE) {
            if ($this->remember)
                $duration = 3600 * 24 * 15; // 15 days
            else
                $duration = 0;
            Yii::app()->user->login($this->_identity, $duration);
            $user = User::model()->findByPk(Yii::app()->user->id);
            $user->updateUserSessionInfo();
            return true;
        }else
            return false;
    }
}