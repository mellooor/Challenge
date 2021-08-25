<?php


namespace App\DB;


class MySqlDsnString
{
    /**
     * Create a dsn string for the creation of an instance of the PDO class.
     *
     * @param string $host - The DB host
     * @param string $db - The name of the DB.
     * @param string $charset - The charset used by the DB.
     *
     * @return string - The complete dsn string to be used in the creation of a PDO instance.
     */
    public static function toString(string $host, string $db, string $charset):  string
    {
        return 'mysql:host=' . $host .';dbname=' . $db .';charset=' . $charset;
    }
}