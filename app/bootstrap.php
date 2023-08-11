<?php

require_once(__DIR__ . '/vendor/autoload.php'); # this autoloads all vendor packages

$dotenv = new Symfony\Component\Dotenv\Dotenv();
$dotenv->overload('/.env');

