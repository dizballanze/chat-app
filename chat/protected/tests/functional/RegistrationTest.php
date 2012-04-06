<?php

class RegistrationTest extends WebTestCase {

    public function testRegPage(){
        $this->open(Yii::app()->createUrl('/registration'));
        $this->assertTitle('Регистрация');
    }

    public function testValidation(){
        $this->open(Yii::app()->createUrl('/registration'));
        $this->submitAndWait("id=user-form");
        $this->assertTextPresent('Заполните все поля формы');
    }

    public function testRegOk(){
        $this->open(Yii::app()->createUrl('/registration'));
        $this->type('id=user-name', 'new_user');
        $this->type('id=user-email', 'new_user@example.com');
        $this->type('id=user-password-new', '111111');
        $this->type('id=user-password-new-repeat', '111111');

        $users_count = User::model()->count();

        $this->submitAndWait("id=user-form");
        $this->assertEquals($users_count + 1, (int)User::model()->count());

        $this->assertTextPresent('Регистрация прошла успешно');
    }
}