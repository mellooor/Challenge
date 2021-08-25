<?php


namespace App\Actions;

use PDO;

abstract class DBAction
{
    protected PDO $dbConnection;

    public function __construct(PDO $dbConnection)
    {
        $this->dbConnection = $dbConnection;
    }
}