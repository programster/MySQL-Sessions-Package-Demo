<?php

require_once(__DIR__ . '/../bootstrap.php');

if (true)
{
    $db = new mysqli(
        $_ENV['DB_HOST'],
        $_ENV['DB_USER'],
        $_ENV['DB_PASSWORD'],
        $_ENV['DB_NAME'],
    );

    $sessionHandler = new \Programster\SessionHandler\SessionHandler($db, 'sessions');

    # Tell PHP to use the handler we just created.
    session_set_save_handler($sessionHandler, true);
}
else
{
    print "not using db session." . PHP_EOL;
}


session_start();

if (isset($_SESSION['time_now']))
{
    print "Previous session time variable is: " . $_SESSION['time_now'];
}
else
{
    print "No previous session time variable set.";
}

print "Accessing non-set variable: " . $_SESSION['someRandomIndex'] . PHP_EOL;
print "Accessing session key that has value set to null: " . $_SESSION['null_value'] . PHP_EOL;

$_SESSION['null_value'] = null;
$_SESSION['time_now'] = time();

