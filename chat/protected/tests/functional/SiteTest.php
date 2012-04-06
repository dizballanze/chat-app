<?php

class SiteTest extends WebTestCase {
    public function testIndex() {
        $this->open(Yii::app()->createUrl('/'));
        $this->assertTextPresent('Приветствуем вас!');
        $this->assertTitle('Chat app');
    }
}
