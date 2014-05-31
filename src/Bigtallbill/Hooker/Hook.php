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
     * @return bool True if the hook passes checks False if any errors are encountered
     */
    abstract public function execute($argv, array $config);
}
