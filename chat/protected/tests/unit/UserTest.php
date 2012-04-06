<?php
/**
 * @method User users($name)
 * @method Chat chats($name)
 * @method UserChat user_chats($name)
 */
class UserText extends CDbTestCase {
    public $fixtures = array(
        'users' => 'User',
        'chats' => 'Chat',
        'user_chats' => 'UserChat',
    );

    protected $correct_user_attrs = array(
        'name' => 'not_used_name',
        'email' => 'correct-email@gmail.com',
        'password_new' => '111111',
        'password_new_repeat' => '111111',
    );

    public function testRelations(){
        $user = $this->users('user1');
        $this->assertEquals(Chat::model()->findAll('id_user = ' . $user->id), $user->owned_chats);
        $this->assertEquals(UserChat::model()->count('id_user = ' . $user->id), count($user->used_chats));
    }

    public function testValidation(){
        $user = new User;
        $user->email = 'wrong value';
        $this->assertFalse($user->validate(array('email')));
        $user->email = $this->users('user1')->email;
        $this->assertFalse($user->validate(array('email')));
        $user->email = $this->correct_user_attrs['email'];
        $this->assertTrue($user->validate(array('email')));

        $user->name = "wrong name 11!!@@";
        $this->assertFalse($user->validate(array('name')));
        $user->name = $this->users('user1')->name;
        $this->assertFalse($user->validate(array('name')));
        $user->name = $this->correct_user_attrs['name'];
        $this->assertTrue($user->validate(array('name')));

        $user->password_new = $this->correct_user_attrs['password_new'];
        $user->password_new_repeat = '222222';
        $this->assertFalse($user->validate(array('password_new')));
        $user->password_new_repeat = $this->correct_user_attrs['password_new'];

        $user = new User;
        $user->attributes = $this->correct_user_attrs;
        $this->assertTrue($user->save());
        $this->assertEquals($user->name, $this->correct_user_attrs['name']);
        $this->assertEquals($user->email, $this->correct_user_attrs['email']);
        $this->assertTrue($user->loginCheck($this->correct_user_attrs['password_new']));
    }
}