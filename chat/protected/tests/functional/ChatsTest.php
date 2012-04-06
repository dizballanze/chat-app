<?php

class ChatsTest extends WebTestCase {

    public function testList(){
        $user = $this->login();
        $this->open(Yii::app()->createUrl('/chats'));
        $this->assertTitle('Мои чаты');

        foreach ($user->used_chats as $chat){
            $this->assertTextPresent($chat->name);
        }
    }

    public function testAdd(){
        $user = $this->login();
        $this->open(Yii::app()->createUrl('/chat/add'));
        $this->assertTitle('Добавить чат');
        $this->submitAndWait('id=chat-form');
        $this->assertTextPresent('Введите название чата');

        $count = $user->owned_chats_count;
        $this->type('id=chat-name', 'Тестовый чат');
        $this->submitAndWait('id=chat-form');
        $this->assertTextPresent('Чат успешно добавлен');
        $user->refresh();
        $this->assertEquals(++$count, $user->owned_chats_count);
    }
}