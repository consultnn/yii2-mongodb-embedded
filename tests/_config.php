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
            'dsn' => 'mongodb://salenn_mongodb_1:27017/salenn_test',
        ],
    ],
];
