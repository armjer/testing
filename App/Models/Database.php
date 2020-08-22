<?php

namespace App\Models;

/**
 * Database
 */
class Database
{
    private static $instance = null;

    public static function getInstance()
    {
        if (!self::$instance) {
            try {

                $DBHost = HOST;
                $DBName = MYSQL_DB;
                $DBUsername = LOGIN;
                $DBPassword = PASSWD;

                if ($DBHost === null || $DBName === null || $DBUsername === null || $DBPassword === null)
                    die('Database: one or more configuration variables are not set ($DBHost, $DBName, $DBUsername, $DBPassword)');

                self::$instance = new \PDO("mysql:host=$DBHost;dbname=$DBName", $DBUsername, $DBPassword);

                $st = self::$instance->prepare("SET NAMES 'utf8'");
                $st->execute();
            } catch (\PDOException $e) {
                die($e->getMessage());
            }
        }

        return self::$instance;
    }
}