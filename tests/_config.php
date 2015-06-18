<?php
/**
 * application configurations shared by all test types
 */
return [
    'id' => 'test',
    'basePath' => '',
    'components' => [
        'mailer' => [
            'useFileTransport' => true,
        ],
        'urlManager' => [
            'showScriptName' => true,
        ],
        'mongodb' => [
            'class' => 'yii\mongodb\Connection',
            'dsn' => 'mongodb://mongo:27017/mongodb_embedded_test',
        ],
    ],
];
