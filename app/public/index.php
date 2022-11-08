<?php

require_once(__DIR__ . '/../vendor/autoload.php'); # this autoloads all vendor packages

$dotenv = new Symfony\Component\Dotenv\Dotenv();
$dotenv->overload('/.env');

$db = new mysqli(
    $_ENV['DB_HOST'],
    $_ENV['DB_USER'],
    $_ENV['DB_PASSWORD'],
    $_ENV['DB_NAME'],
);

$sessionHandler = new \Programster\SessionHandler\SessionHandler($db, 'sessions');

# Tell PHP to use the handler we just created.
session_set_save_handler($sessionHandler, true);

session_start();

if (isset($_SESSION['time_now']))
{
    print "Previous session time variable is: " . $_SESSION['time_now'];
}
else
{
    print "No previous session time variable set.";
}

$_SESSION['time_now'] = time();

