<?php


namespace App\Actions;

use PDO;

abstract class DBAction
{
    protected $dbConnection;

    public function __construct(PDO $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }
}