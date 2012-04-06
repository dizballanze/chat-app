<?php

class SettingsTest extends WebTestCase {

    public function testSettingsPage(){
        $user = $this->login();
        $this->open(Yii::app()->createUrl('/settings'));
        $this->assertTitle('Настройки');
        $this->assertElementValueEquals('id=user-name', $user->name);
        $this->assertElementValueEquals('id=user-email', $user->email);
    }

    public function testValidation(){
        $user = $this->login();
        $this->open(Yii::app()->createUrl('/settings'));
        $this->type('id=user-name', '');
        $this->submitAndWait('id=user-form');
        $this->assertTextPresent('Заполните все поля формы');
    }

    public function testSaveOk(){
        $new_name = 'new_user_name';
        $user = $this->login();
        $this->open(Yii::app()->createUrl('/settings'));
        $this->type('id=user-name', $new_name);
        $this->submitAndWait('id=user-form');
        $this->assertTextPresent('Настройки сохранены');
        $user->refresh();
        $this->assertEquals($new_name, $user->name);
    }
}