<?php
/**
 * Created by PhpStorm.
 * User: bigtallbill
 * Date: 3/9/15
 * Time: 9:01 PM
 */

namespace Bigtallbill\Hooker;


class HookUnknown extends Hook {

    /**
     * @param array $argv
     *
     * @param array $config
     *
     * @param string $type The commit hook type
     *
     * @return bool True if the hook passes checks False if any errors are encountered
     */
    public function execute($argv, array $config, $type)
    {
        return true;
    }
}
