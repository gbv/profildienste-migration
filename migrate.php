<?php

require __DIR__ . '/vendor/autoload.php';

// Forbid executing this script from elsewhere than the CLI
if (php_sapi_name() !== 'cli') {
    echo 'Please run this script from the command line.';
    exit();
}

// initialize and run the app
$app = new App;
$app->run($argc, $argv);