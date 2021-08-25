<?php


namespace App\DB;


class MySqlDsnString
{
    public static function toString($host, $db, $charset)
    {
        return 'mysql:host=' . $host .';dbname=' . $db .';charset=' . $charset;
    }
}