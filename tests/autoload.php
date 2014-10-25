<?php
require dirname(dirname(__FILE__)) . '/vendor/autoload.php';

use MarketMeSuite\Core\Config\Config;
use MarketMeSuite\Phranken\Database\Mongo\MongoConnectionManagerV2;
use MarketMeSuite\Phranken\Spl\SplClassLoader;

define('TEST_ASSETS_ROOT', dirname(__FILE__) . '/testAssets');
define('TEST_TMP_ROOT', dirname(__FILE__) . '/testTmp');

$loader = new \Composer\Autoload\ClassLoader();
$loader->add('MarketMeSuite', dirname(dirname(__FILE__)) . '/src');
$loader->add('Bigtallbill', dirname(dirname(__FILE__)) . '/src');
$loader->register();
