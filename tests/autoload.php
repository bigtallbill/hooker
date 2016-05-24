<?php
require dirname(dirname(__FILE__)) . '/vendor/autoload.php';

define('TEST_ASSETS_ROOT', dirname(__FILE__) . '/testAssets');
define('TEST_TMP_ROOT', dirname(__FILE__) . '/testTmp');

$loader = new \Composer\Autoload\ClassLoader();
$loader->add('Bigtallbill', dirname(dirname(__FILE__)) . '/src');
$loader->register();
