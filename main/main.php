#!/usr/bin/env php
<?php
/**
 * Created by PhpStorm.
 * User: bigtallbill
 * Date: 23/11/2013
 * Time: 20:57
 */

define('HOOKER_ROOT', dirname(dirname(__FILE__)));

require HOOKER_ROOT . '/vendor/autoload.php';

use Bigtallbill\Hooker\Hooker;
use Bigtallbill\Phranken\Commandline\ArgUtils;
use Bigtallbill\Phranken\Commandline\CommandPrompt;
use Bigtallbill\Phranken\Commandline\SimpleLog;

// register local autoloader

$loader = new \Composer\Autoload\ClassLoader();
$loader->add('Bigtallbill', HOOKER_ROOT . '/src');
$loader->register();

$app = new \Symfony\Component\Console\Application('hooker');
$app->add(new \Bigtallbill\Hooker\Commands\CommandInstall(HOOKER_ROOT));
$app->add(new \Bigtallbill\Hooker\Commands\CommandExecute(HOOKER_ROOT));
$app->run();
