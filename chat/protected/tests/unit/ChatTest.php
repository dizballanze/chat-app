<?php
/**
 * @method User users($name)
 * @method Chat chats($name)
 * @method UserChat user_chats($name)
 */
class ChatTest extends CDbTestCase {
    public $fixtures = array(
        'users' => 'User',
        'chats' => 'Chat',
        'user_chats' => 'UserChat',
    );

    public function testRelation(){
        $chat = $this->chats('chat1');
        $this->assertEquals(User::model()->findByPk($chat->id_user), $chat->owner);
        $this->assertEquals(UserChat::model()->count('id_chat = ' . $chat->id), count($chat->users));
    }
}