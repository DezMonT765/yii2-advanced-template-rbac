<?php
return [
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'aliases' => [
        '@file_save_dir' => '@common/files/',
        '@file_view_url' => '/files/'
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'assetManager'=>[
            'appendTimestamp' => true,
        ],
        'authManager' => [
            'class' => yii\rbac\DbManager::className(),
            'cache' =>'cache',
            'defaultRoles' => ['super_admin','admin','user']
        ],
        'view' => [
            'class' => 'common\components\MainView',
        ],
        'urlManager' => [
            'enablePrettyUrl' => true,
            'showScriptName' => false,
            'rules' => [
                '<controller:\w+>/<id:\d+>' => '<controller>/view',
                '<controller:\w+>/<action:\w+>/<id:\d+>' => '<controller>/<action>',
                '<controller:\w+>/<action:\w+>' => '<controller>/<action>',
            ]
        ],
    ],
];
