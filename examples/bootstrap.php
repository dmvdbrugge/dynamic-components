<?php

if (!extension_loaded('ui')) {
    die('UI extension not enabled.');
}

// To find the autoloader, we need to find the vendor dir.
$vendorDir = __DIR__ . '/../vendor';

// If we don't have "our own" vendor dir...
if (!is_dir($vendorDir)) {
    // ...we are probably inside one (as dependency).
    $vendorDir = dirname(__DIR__, 3);

    if (basename($vendorDir) !== 'vendor') {
        die("Couldn't find vendor dir. If you cloned the project directly, don't forget to run composer install.");
    }
}

require_once $vendorDir . '/autoload.php';
