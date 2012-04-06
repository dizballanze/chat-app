<?php

return array(
    'basePath' => dirname(__FILE__) . DIRECTORY_SEPARATOR . '..',
    'name' => 'Chat app',
    'preload' => array('log'),
    'import' => array(
        'application.models.*',
        'application.components.*',
        'ext.eoauth.*',
        'ext.eoauth.lib.*',
        'ext.lightopenid.*',
        'ext.eauth.*',
        'ext.eauth.services.*',
    ),
    'modules' => array(
          'gii'=>array(
              'class'=>'system.gii.GiiModule',
              'password'=>'11',
              'ipFilters'=>array('127.0.0.1','::1'),
          ),
    ),

    'components' => array(
        'user' => array(
            'allowAutoLogin' => true,
            'loginUrl' => '/',
        ),
        'urlManager' => array(
            'urlFormat' => 'path',
            'rules' => array(
                //'login' => '',
                'registration' => 'user/add',
                'settings' => 'user/edit',
                'about' => 'site/page/view/about',
                'logout' => 'site/logout',
                'chats' => 'chat/list',
                'history/<id:\w+>' => 'chat/history/id/<id>',
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
                '<id:\w+>' => 'chat/chat/id/<id>',
            ),
            'showScriptName' => false,
        ),
        'db'=>array(
            'connectionString' => 'mysql:host=localhost;dbname=chat',
            'emulatePrepare' => true,
            'username' => 'root',
            'password' => '11',
            'charset' => 'utf8',
        ),
        'errorHandler' => array(
            'errorAction' => 'site/error',
        ),
        'log' => array(
            'class' => 'CLogRouter',
            'routes' => array(
                array(
                    'class' => 'CFileLogRoute',
                    'levels' => 'error, warning',
                ),
            ),
        ),
        'loid' => array(
            'class' => 'ext.lightopenid.loid',
        ),
        'eauth' => array(
            'class' => 'ext.eauth.EAuth',
            'popup' => false,
            'services' => array(
                'twitter' => array(
                    'class' => 'ext.eauth.custom_services.ChatTwitterService',
                    'key' => 'wbHRVK6UZ5AqpW8zg14sSw',
                    'secret' => 'dAFK9venLlnd0vE9P8WmdczsSQEBh9DhkSD7mFkiyU',
                ),
            ),
        ),
        'image' => array(
            'class' => 'ext.ImageEditor',
        ),
        'redis' => array(
            'class' => "ext.RedisConnection",
            'hostname' => 'localhost',
            'port' => 6379
        ),
    ),

    // using Yii::app()->params['paramName']
    'params' => array(
        'adminEmail' => 'webmaster@example.com',
        'photos_dir' => realpath(dirname(__FILE__) . '/../../photos/'),
        'photo_sizes' => array(
            array('w' => 60, 'h' => 60),
            array('w' => 24, 'h' => 24),
        ),
        'socket.io' => 'http://localhost:3000',
    ),
);