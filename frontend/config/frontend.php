<?php
require_once('../../common/components/MainView.php');
use common\components\MainView;
use frontend\models\User;

$params = array_merge(
    require(__DIR__ . '/../../common/config/params.php'),
    require(__DIR__ . '/../../common/config/params-local.php'),
    require(__DIR__ . '/params.php'),
    require(__DIR__ . '/params-local.php')
);

return [
    'id' => 'app-frontend',
    'basePath' => dirname(__DIR__),
    'bootstrap' => ['log'],
    'controllerNamespace' => 'frontend\controllers',
    'aliases' => [
        '@file_view_dir' => '@frontend/web/files',
    ],
    'components' => [
        'user' => [
            'identityClass' => User::className(),
            'enableAutoLogin' => true,
        ],
        'assetManager' => [
          'bundles' =>[
            \yii\web\JqueryAsset::className() => [
                'js'=> [
                    "http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js",
                ],
                'jsOptions' =>
                    [
                        'position' => MainView::POS_HEAD,
                    ],
            ],
            \yii\bootstrap\BootstrapAsset::className() => [
                'baseUrl' => '@web',
                'basePath' => '@webroot',
                'css' => ['https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/css/bootstrap.min.css']
            ],
            \yii\bootstrap\BootstrapPluginAsset::className() =>[
                'js' => ['https://maxcdn.bootstrapcdn.com/bootstrap/3.3.4/js/bootstrap.min.js'],
            ]
        ],
        ],
        'log' => [
            'traceLevel' => YII_DEBUG ? 3 : 0,
            'targets' => [
                [
                    'class' => 'yii\log\FileTarget',
                    'levels' => ['error', 'warning'],
                ],
            ],
        ],
        'errorHandler' => [
            'errorAction' => 'site/error',
        ],
    ],
    'params' => $params,
];
