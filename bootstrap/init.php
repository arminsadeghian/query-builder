<?php

if (file_exists(__DIR__ . '/../vendor/autoload.php')) {
    require __DIR__ . '/../vendor/autoload.php';
}

if (file_exists(__DIR__ . "/../vendor/larapack/dd/src/helper.php")) {
    require __DIR__ . "/../vendor/larapack/dd/src/helper.php";
}

$basePath = __DIR__ . '/../';

$dotenv = Dotenv\Dotenv::createImmutable($basePath);
$dotenv->load();
