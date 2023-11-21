<?php

use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__) . '/vendor/autoload.php';

define('PROJECT_BASE_PATH', __DIR__ . '/..');
define('TEST_BASE_PATH', __DIR__);
define('TEST_FIXTURE_PATH', __DIR__ . DIRECTORY_SEPARATOR . 'fixtures');

$dotenvFile = __DIR__.'/.env';
if (file_exists($dotenvFile)) {
    $dotenv = new Dotenv();
    $dotenv->load($dotenvFile);
}

