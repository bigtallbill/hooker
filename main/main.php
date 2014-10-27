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

$sLog = new SimpleLog();
$cmdPrompt = new CommandPrompt();

//--------------------------------------
// Check arguments and display
// help options
//--------------------------------------

if (count($argv) <= 1) {
    showHelp();
}

// get command argument
$command = $argv[1];

// look for utility commands
switch ($command) {
    case 'help':
    case '--help':
    case '-help':
    case '-h':
        showHelp();
        break;
}

// get required args
$repoRoot     = ArgUtils::getArgPair($argv, '-p', null, true);
$repoRoot = $repoRoot['-p'];

// merge all of the arguments together for easy reference
//$arguments = array_merge($algo, $mode, $pass, $file, $hashAlgo);


//--------------------------------------
// MAIN COMMANDS
//--------------------------------------

switch ($command) {
    case 'install':


        if ($repoRoot === null) {
            $repoRoot = getcwd();
        }

        $sLog->log("using current working directory: $repoRoot");

        $hk = new Hooker(HOOKER_ROOT, $repoRoot);
        $hk->install();

        break;
    case 'execute':

        $hk = new Hooker(HOOKER_ROOT, getcwd());

        if (($out = $hk->execute($argv[2], $argv)) !== true) {
            list($output, $exitCode) = $out;
            echo $output . PHP_EOL;
            exit($exitCode);
        }

        break;
    default:
        showHelp();
        break;
}

/**
 * displays the help text
 */
function showHelp()
{
    global $sLog;

    $sLog->log(file_get_contents(dirname(__FILE__) . '/help'));

    exit();
}
