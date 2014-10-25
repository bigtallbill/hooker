<?php
/**
 * hooker
 * HookPreCommit.php
 *
 * @author   Bill Nunney <bill@marketmesuite.com>
 * @date     31/05/2014 10:57
 * @license  http://marketmesuite.com/license.txt MMS License
 */
namespace Bigtallbill\Hooker;


class HookPreCommit extends Hook
{
    public function execute($argv, array $config, $type)
    {
        return true;
    }
}
