<?php

class CreateSessionsTable implements \iRAP\Migrations\MigrationInterface
{

    public function up(\mysqli $mysqliConn)
    {
        # Set to true if you wish to manually create the sessions table, rather than rely on the tool creating it for you.
        if (false)
        {
            $query = 'CREATE TABLE IF NOT EXISTS `sessions` (
                `id` varchar(32) NOT NULL,
                `modified_timestamp` timestamp DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                `data` mediumtext,
                PRIMARY KEY (`id`),
                KEY `modified_timestamp` (`modified_timestamp`)
            ) ENGINE=InnoDB DEFAULT CHARSET=utf8';

            $mysqliConn->query($query);
        }
    }

    public function down(\mysqli $mysqliConn)
    {

    }
}