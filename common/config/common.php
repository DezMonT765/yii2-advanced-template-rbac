<?php
use common\models\Languages;
use dezmont765\yii2bundle\views\MainView;

return [
    'language' => 'en-EN',
    'vendorPath' => dirname(dirname(__DIR__)) . '/vendor',
    'aliases' => [
        '@file_save_dir' => '@common/files/',
        '@file_view_url' => '/files/',
        '@backend_file_view_dir' => '@backend/web/files',
        '@frontend_file_view_dir' => '@frontend/web/files'
    ],
    'components' => [
        'cache' => [
            'class' => 'yii\caching\FileCache',
        ],
        'assetManager'=>[
            'appendTimestamp' => true,
            'bundles' =>  [
                \yii\web\JqueryAsset::className() => [
                    'js'=> [
                        "http://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js",
                    ],
                    'jsOptions' =>
                        [
                            'position' => MainView::POS_HEAD,
                        ],
                ],
            ]
        ],
        'authManager' => [
            'class' => yii\rbac\DbManager::className(),
            'cache' =>'cache',
            'defaultRoles' => ['super_admin','admin','user']
        ],
        'view' => [
            'class' => MainView::className(),
        ],
        'mailer' => [
            'class' =>'common\components\Mailer',
            'viewPath' => '@common/mail',
            // send all mails to a file by default. You have to set
            // 'useFileTransport' to false and configure a transport
            // for the mailer to send real emails.
            'useFileTransport' => false,
            'messageConfig'    => [
                'from' => ['support@test.com'=>'Test Mailer'],
            ],

        ],
        'reCaptcha' => [
            'name' => 'reCaptcha',
            'class' => 'himiklab\yii2\recaptcha\ReCaptcha',
            'siteKey' => '',
            'secret' => '',
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
                'app' => [
                    'class' => 'yii\i18n\DbMessageSource',
                    //'basePath' => '@app/messages',
                    'sourceLanguage' => 'en-US',
                    'forceTranslation' => true
                ],
                '*' => [
                    'class' => 'yii\i18n\DbMessageSource',
                    //'basePath' => '@app/messages',
                    'sourceLanguage' => 'en-US',
                    'forceTranslation' => true
                ]
            ],
        ],
        'languagepicker' => [
            'class' => '\lajax\languagepicker\widgets\LanguagePicker',
            'languages' => function(){
                return Languages::getLanguageNames(true);
            }
        ]
    ],
];
