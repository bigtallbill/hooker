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
        'preCommit' => array(),
        'commitMsg' => array(
            'firstWordImperative' => true,
            'maxSummaryLength' => 50,
            'maxContentLength' => 72,
            'summaryIgnoreUrls' => true,
            'contentIgnoreUrls' => true,
            'lineAfterSummaryMustBeBlank' => true
        ),
    );

    protected $loadedConfig;

    protected $hooks = array(
        'pre-commit',
        'commit-msg',
        'pre-push'
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

    /**
     * Install the commit hook
     */
    public function install()
    {
        $hookDir = $this->resolveHookDir($this->projectDir);

        $config = json_decode(file_get_contents($this->hookerRoot . '/hooker-draft.json'));

        $dSep = DIRECTORY_SEPARATOR;

        if (!file_exists($this->projectDir . $dSep . 'hooker.json')) {
            file_put_contents($this->projectDir . $dSep . 'hooker.json', json_encode($config));
        }

        foreach ($this->hooks as $hook) {

            $hookPath = $hookDir . $dSep . $hook;

            if (file_exists($hookPath)) {

                // remove previous installed
                file_put_contents(
                    $hookPath,
                    preg_replace(
                        "/#HOOKERSTART#.+#HOOKEREND#/s",
                        "",
                        file_get_contents($hookPath)
                    )
                );

                $fh = fopen($hookPath, 'a');
                fwrite($fh, $this->createHook($hook, true));
                fclose($fh);
            } else {
                file_put_contents($hookDir . $dSep . $hook, $this->createHook($hook));
            }

            chmod($hookDir . $dSep . $hook, 0754);
        }
    }

    public function createHook($type, $append = false)
    {
        $hookerPath = `which hooker`;

        $hook = '';
        if (!$append) {
            $hook .= '#!/bin/bash' . PHP_EOL . '' . PHP_EOL;
        }
        $hook .= '#HOOKERSTART#' . PHP_EOL;
        $cmd = trim($hookerPath) . ' execute ' . $type . ' "$@"';
        $hook .= $cmd . PHP_EOL;
        $hook .= '#HOOKEREND#' . PHP_EOL;

        return $hook;
    }

    /**
     * @param $type
     * @param $argv
     *
     * @return bool|string
     */
    public function execute($type, $argv)
    {
        $class = "Bigtallbill\\Hooker\\" . $this->transformHookNameToClass($type);

        /** @var Hook $hook */
        $hook = new $class;
        return $hook->process($argv, $this->loadedConfig, $type);
    }

    /**
     * @param string $hookName
     *
     * @return string
     */
    public function transformHookNameToClass($hookName)
    {
        $parts = explode('-', $hookName);
        return 'Hook' . ucfirst($parts[0]) . ucfirst($parts[1]);
    }
}
