<?php

// change the following paths if necessary
$yiit=dirname(__FILE__).'/../../../yii/framework/yiit.php';
$config=dirname(__FILE__).'/../config/test.php';

require_once($yiit);
require_once(dirname(__FILE__).'/WebTestCase.php');

$_SERVER['SERVER_NAME'] = 'bags.loc';
$_SERVER['PHP_SELF'] = '/index-test.php';
$_SERVER['SCRIPT_NAME'] = '/index-test.php';
$_SERVER['DOCUMENT_ROOT'] = dirname(__FILE__) . '/../../';
$_SERVER['SCRIPT_FILENAME'] = $_SERVER['DOCUMENT_ROOT'] . '/index-test.php';

Yii::createWebApplication($config);
