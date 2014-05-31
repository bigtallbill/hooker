<?php

$buildRoot = "./bin";
$applicationName = 'hooker';

$phar = new Phar(
    $buildRoot . "/$applicationName.phar"
);

$phar->buildFromDirectory(dirname(__FILE__));


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
