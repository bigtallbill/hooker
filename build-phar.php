<?php

if (!empty($argv[1])) {
    $buildRoot = $argv[1];
} else {
    $buildRoot = "./bin";
}

$applicationName = 'hooker';

$phar = new Phar(
    $buildRoot . "/$applicationName.phar"
);

// build from the root directory but exclude any phar files
$phar->buildFromDirectory(dirname(__FILE__), "/.+(?<!phar)$/i");

// create stub that allows direct commandline usage
$stub = <<<EOT
#!/usr/bin/env php
<?php

Phar::mapPhar('$applicationName.phar');

require 'phar://$applicationName.phar/main/main.php';

__HALT_COMPILER();
EOT;

$phar->setStub($stub);

chmod($buildRoot . "/$applicationName.phar", 0754);
rename($buildRoot . "/$applicationName.phar", $buildRoot . "/$applicationName");
