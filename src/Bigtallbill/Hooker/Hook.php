<?php
/**
 * hooker
 * Hook.php
 *
 * @author   Bill Nunney <bill@marketmesuite.com>
 * @date     31/05/2014 10:56
 * @license  http://marketmesuite.com/license.txt MMS License
 */
namespace Bigtallbill\Hooker;


abstract class Hook
{
    /**
     * @param array $argv
     *
     * @param array $config
     *
     * @param string $type The commit hook type
     *
     * @return bool True if the hook passes checks False if any errors are encountered
     */
    abstract public function execute($argv, array $config, $type);

    public function process($argv, array $config, $type)
    {
        $result = $this->execute($argv, $config, $type);
        if ($result !== true) {
            return $result;
        }
        $result = $this->executeAfter($argv, $config, $type);
        if ($result !== true) {
            return $result;
        }

        return true;
    }

    public function executeAfter($argv, array $config, $type)
    {
        $typeKey = $this->transformHookNameToKey($type);

        if (isset($config[$typeKey]) && isset($config[$typeKey]['scripts']['after'])) {
            foreach ($config[$typeKey]['scripts']['after'] as $script) {

                $cmd = '';

                if (isset($script['relativeToRepo']) && $script['relativeToRepo'] === true) {
                    $cmd .= getcwd() . DIRECTORY_SEPARATOR;
                }

                $cmd .= $script['cmd'];

                if (isset($script['passGitArgs']) && $script['passGitArgs'] === true) {
                    $cmd .= ' ' . implode(' ', $argv);
                }

                $out = '';
                $exitCode = '';
                exec($cmd, $out, $exitCode);

                return array(implode(PHP_EOL, $out), $exitCode);
            }
        }

        return true;
    }

    public function transformHookNameToKey($hookName)
    {
        $parts = explode('-', $hookName);
        return strtolower($parts[0]) . ucfirst($parts[1]);
    }
}
