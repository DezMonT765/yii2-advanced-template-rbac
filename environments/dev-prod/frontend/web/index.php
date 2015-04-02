<?php
error_reporting(E_ALL | E_STRICT);
defined('YII_DEBUG') or define('YII_DEBUG', true);
defined('YII_ENV') or define('YII_ENV', 'dev');

require(__DIR__ . '/../../vendor/autoload.php');
require(__DIR__ . '/../../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../../common/config/bootstrap.php');
require(__DIR__ . '/../config/bootstrap.php');

$config = yii\helpers\ArrayHelper::merge(
    require(__DIR__ . '/../../common/config/common.php'),
    require(__DIR__ . '/../../common/config/common-local.php'),
    require(__DIR__ . '/../config/frontend.php'),
    require(__DIR__ . '/../config/frontend-local.php')
);

$application = new yii\web\Application($config);
$application->run();
