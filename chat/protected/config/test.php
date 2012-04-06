<?php

return CMap::mergeArray(
    require(dirname(__FILE__).'/main.php'),
    array(
        'components'=>array(
            'fixture'=>array(
                'class'=>'system.test.CDbFixtureManager',
            ),
            'db' => array(
                'connectionString' => 'mysql:host=localhost;dbname=chat_test',
                'emulatePrepare' => true,
                'username' => 'root',
                'password' => '11',
                'charset' => 'utf8',
            ),
            'urlManager' => array(
                'urlFormat' => 'path',
                'showScriptName' => true,
            ),
        ),
    )
);
