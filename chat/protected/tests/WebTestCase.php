<?php

/**
 * Change the following URL based on your server configuration
 * Make sure the URL ends with a slash so that we can use relative URLs in test cases
 */
define('TEST_BASE_URL', 'http://chat.loc/index-test.php/');

/**
 * The base class for functional test cases.
 * In this class, we set the base URL for the test application.
 * We also provide some common methods to be used by concrete test classes.
 * @method assertTextPresent($pattern)
 * @method assertTextNotPresent($pattern)
 * @method assertText($locator, $pattern)
 * @method assertNotText($locator, $pattern)
 * @method assertTitle($pattern)
 * @method assertNotTitle($pattern)
 * @method verifyElementPresent($locator)
 * @method verifyElementNotPresent($locator)
 * @method assertVisible($locator)
 * @method assertNotVisible($locator)
 * @method assertAttribute ($attributeLocator, $pattern)
 *
 * Фикстуры
 * @method User users($name)
 * @method Chat chats($name)
 * @method UserChat user_chats($name)
 */
class WebTestCase extends CWebTestCase {
    public $fixtures = array(
        'users' => 'User',
        'chats' => 'Chat',
        'user_chats' => 'UserChat',
    );

    /**
     * Sets up before each test method runs.
     * This mainly sets the base URL for the test application.
     */
    protected function setUp() {
        parent::setUp();
        $this->setBrowserUrl(TEST_BASE_URL);
        $this->screenshotPath = __DIR__ . '/report/';
        $this->screenshotUrl = "http://localhost";
        $this->captureScreenshotOnFailure = true;
    }

    /**
     * Выполняем вход
     * @return User
     */
    protected function login(){
        $this->open(Yii::app()->createUrl('/'));
        /**
         * @var User $user
         */
        $user = $this->users('user1');
        $this->type('id=login-login', $user->name);
        $this->type('id=login-password', '111111');
        $this->submitAndWait('id=login-form');
        return $user;
    }

    protected function waitForAjax(){
        $this->waitForCondition('selenium.browserbot.getCurrentWindow().jQuery.active == 0');
    }
}