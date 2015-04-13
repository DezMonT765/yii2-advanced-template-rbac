<?php
return [
    'components' => [
        'db' => [
            'class' => 'yii\db\Connection',
            'dsn' => 'mysql:host=localhost;dbname=advanced_template',
            'username' => 'root',
            'password' => '',
            'charset' => 'utf8',
            'enableSchemaCache' => true,
        ],
        'mailer' => [
            'config' => [
                'mailer' => 'mail'
            ]
        ],
    ],
];
