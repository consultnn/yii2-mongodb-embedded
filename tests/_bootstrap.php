<?php
// This is global bootstrap for autoloading
defined('TEST_ENTRY_URL') or define('TEST_ENTRY_URL', '/index-test.php');

// the entry script file path for functional and acceptance tests
defined('TEST_ENTRY_FILE') or define('TEST_ENTRY_FILE', dirname(__DIR__) . '/index-test.php');

defined('YII_DEBUG') or define('YII_DEBUG', true);

defined('YII_ENV') or define('YII_ENV', 'test');

require_once(__DIR__ . '/../../../../vendor/autoload.php');

require_once(__DIR__ . '/../../../../vendor/yiisoft/yii2/Yii.php');

require(__DIR__ . '/../../../../common/config/aliases.php');

$config = require('_config.php');

(new yii\web\Application($config));

// set correct script paths
$_SERVER['SCRIPT_FILENAME'] = TEST_ENTRY_FILE;
$_SERVER['SCRIPT_NAME'] = TEST_ENTRY_URL;
$_SERVER['SERVER_NAME'] = 'localhost';
