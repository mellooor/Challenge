<?php
use \App\PDO\Parameters as PdoParameters;
use \App\DB\MySqlDriver;
use \App\DB\MysqlDsnString;

// The DB parameters are loaded from the config file
$dbConfig = require_once(dirname(__DIR__) . '/config/db.php');

/**
 * Set up the DB connection
 */
$dsn = MysqlDsnString::toString($dbConfig['host'], $dbConfig['db'], $dbConfig['charset']);
$dbDriver = new MySqlDriver(new PdoParameters($dsn, $dbConfig['user'], $dbConfig['pass']));
$dbConnection = $dbDriver->connect();

// If an error array is returned, stop and return the error message
if (is_array($dbConnection))
{
    die($dbConnection['error']);
}
