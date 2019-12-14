<?php

date_default_timezone_set('UTC');

// change the following paths if necessary
$yii=dirname(__FILE__).'/../../yii/framework/yiilite.php';
$config=dirname(__FILE__).'/protected/config/main.php';

if ($_SERVER['REMOTE_ADDR'] == '109.194.27.21') {
    defined('YII_DEBUG') or define('YII_DEBUG',true);
    defined('YII_TRACE_LEVEL') or define('YII_TRACE_LEVEL',3);
}

require_once($yii);
Yii::createWebApplication($config)->run();