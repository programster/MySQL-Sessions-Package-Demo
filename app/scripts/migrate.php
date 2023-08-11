<?php

use iRAP\Migrations\MigrationManager;

require_once(__DIR__ . '/../bootstrap.php');

$db = new mysqli(
    $_ENV['DB_HOST'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASSWORD'],
    $_ENV['DB_NAME'],
);

$migrator = new MigrationManager(
    __DIR__ . '/../migrations',
    $db
);

$migrator->migrate();