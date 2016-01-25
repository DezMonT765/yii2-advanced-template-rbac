<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=127.0.0.1;dbname=advanced_template',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
        ],
        'mailer' => [
            'config' => [
                'mailer' => 'mail'
            ]
        ],
        'session'=>[
            'class' => 'yii\web\Session'
        ]
    ],
];
