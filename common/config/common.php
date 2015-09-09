<?php
return [
    'language' => 'en-EN',
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
        'mailer' => [
            'class' =>'common\components\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'messageConfig'    => [
                'from' => ['support@asura.com'=>'Consilium Partnership'],
            ],

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
        'session' => [
            'class' => 'yii\web\DbSession'
        ],
        'i18n' => [
            'translations' => [
                '*' => [
                    'class' => 'yii\i18n\PhpMessageSource',
                    'basePath' => '@common/messages',
                    'sourceLanguage' => 'en-US',
                ],
            ],
        ],
    ],
];
