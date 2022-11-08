<?php


require_once(__DIR__ . '/../bootstrap.php');

$migrator = new \iRAP\Migrations\MigrationManager(
    __DIR__ . '/../migrations',
    SiteSpecific::getDb()
);

$migrator->migrate();