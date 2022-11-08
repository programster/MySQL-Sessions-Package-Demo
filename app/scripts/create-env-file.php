<?php

/*
 * This script creates a .env file that dotenv can then read later (owned by www-data user).

 * We only write out the variables we expect, rather than all variables as testing showed
 * that just dumping output of env to .env caused dotenv to fail.
 *
 * If we find that an expected environment variable is missing, then we notify you and exit with a -1 exit code.
 *
 * Call it with php create-env-file.php > .env
 */


function main(string $outputFilepath)
{
    $env = shell_exec("env");

    $requiredEnvVars = [
        "DB_HOST",
        "DB_NAME",
        "DB_USER",
        "DB_PASSWORD",
    ];


    $filteredLines = [];
    $lines = explode(PHP_EOL, $env);

    foreach ($lines as $index => $line)
    {
        $parts = explode("=", $line);

        if (in_array($parts[0], $requiredEnvVars))
        {
            $filteredLines[$parts[0]] = $line;
        }
    }

    foreach ($requiredEnvVars as $expectedVariable)
    {
        $missingKeys = array_diff($requiredEnvVars, array_keys($filteredLines));

        if (count($missingKeys) > 0)
        {
            fwrite(STDERR, "Missing required environment variables: " . PHP_EOL);

            foreach ($missingKeys as $missingKey)
            {
                fwrite(STDERR, " - {$missingKey}" . PHP_EOL);
            }

            fwrite(STDERR, "... out of the follwoing env vars: " . PHP_EOL);
            fwrite(STDERR, "{$env}" . PHP_EOL);

            exit(-1);
        }
    }

    $content = implode(PHP_EOL, $filteredLines);
    file_put_contents($outputFilepath, $content);
}

if (count($argv) < 2)
{
    fwrite(STDERR, "Missing expected output filepath as parameter." . PHP_EOL);
    exit(-1);
}

main($argv[1]);
