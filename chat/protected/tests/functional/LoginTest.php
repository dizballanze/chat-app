<?php

class LoginTest extends WebTestCase {

    public function testLoginFail(){
        $this->open(Yii::app()->createUrl('/'));
        $this->type('id=login-login', $this->users('user1')->name);
        $this->type('id=login-password', 'wrong password');
        $this->submitAndWait('id=login-form');
        $this->assertTextPresent('Неправильный логин и/или пароль');
    }

    public function testLoginOk(){
        $this->open(Yii::app()->createUrl('/'));
        $this->type('id=login-login', $this->users('user1')->name);
        $this->type('id=login-password', '111111');
        $this->submitAndWait('id=login-form');
        $this->assertText('id=nav-user-name', $this->users('user1')->name);
        $this->verifyElementNotPresent('css=.hero-unit');
    }
}