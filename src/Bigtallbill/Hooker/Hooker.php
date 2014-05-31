<?php
/**
 * hooker
 * Hooker.php
 *
 * @author   Bill Nunney <bill@marketmesuite.com>
 * @date     30/05/2014 22:10
 * @license  http://marketmesuite.com/license.txt MMS License
 */
namespace Bigtallbill\Hooker;


class Hooker
{
    protected $projectName;
    protected $projectDir;
    protected $hookerRoot;

    protected $defaultConfig = array(
        'config' => array(
            'scriptFolder' => 'some path',
        ),
        'preCommit' => array(),
        'commitMsg' => array(
            'firstWordImperative' => true,
            'maxSummaryLength' => 50,
            'maxContentLength' => 72,
            'summaryIgnoreUrls' => true,
            'contentIgnoreUrls' => true,
            'lineAfterSummaryMustBeBlank' => true,
            'scripts' => array(
                'afterBuiltIn' => array(
                    'checkSpelling' => array(
                        'name' => 'chkSpelling.php',
                    ),
                ),
            ),
        ),
    );

    protected $loadedConfig;

    protected $hooks = array(
        'pre-commit',
        'commit-msg'
    );

    public function __construct($hookerRoot, $projectDir)
    {
        $this->assertDirectoryExists($projectDir);
        $this->projectDir = $projectDir;
        $this->hookerRoot = $hookerRoot;

        if (file_exists($this->projectDir . DIRECTORY_SEPARATOR . 'hooker.json')) {
            $this->loadedConfig = json_decode(
                file_get_contents($this->projectDir . DIRECTORY_SEPARATOR . 'hooker.json'),
                true
            );
        }
    }

    protected function resolveHookDir()
    {
        $hookDir = $this->projectDir . DIRECTORY_SEPARATOR . '.git' . DIRECTORY_SEPARATOR . 'hooks';
        $this->assertDirectoryExists($hookDir);
        return $hookDir;
    }

    protected function assertDirectoryExists($dir)
    {
        if (!is_dir($dir)) {
            throw new \Exception('Dir "' . $dir . '" is not a directory');
        }

        if (!file_exists($dir)) {
            throw new \Exception('Dir "' . $dir . '" does not exist');
        }
    }

    public function install()
    {
        $hookDir = $this->resolveHookDir($this->projectDir);

        $config = $this->defaultConfig;
        $config['config']['scriptFolder'] = '.hooker';

        if (!file_exists($this->projectDir . DIRECTORY_SEPARATOR . 'hooker.json')) {
            file_put_contents($this->projectDir . DIRECTORY_SEPARATOR . 'hooker.json', json_encode($config));
        }

        foreach ($this->hooks as $hook) {
            file_put_contents($hookDir . DIRECTORY_SEPARATOR . $hook, $this->createHook($hook));
            chmod($hookDir . DIRECTORY_SEPARATOR . $hook, 0754);
        }
    }

    public function createHook($type)
    {
        $hook = '#!/bin/bash' . PHP_EOL . '' . PHP_EOL;
        $hook .= '#GENERATED BY HOOKER' . PHP_EOL;
        $cmd = './hooker.phar execute ' . $type . ' "$@"';
        $hook .= $cmd . PHP_EOL;

        return $hook;
    }

    public function execute($type, $argv)
    {
        $class = "Bigtallbill\\Hooker\\" . $this->transformHookNameToClass($type);

        /** @var Hook $hook */
        $hook = new $class;
        return $hook->execute($argv, $this->loadedConfig);
    }

    protected function transformHookNameToClass($hookName)
    {
        $parts = explode('-', $hookName);
        return 'Hook' . ucfirst($parts[0]) . ucfirst($parts[1]);
    }
}
