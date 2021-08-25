<?php


namespace App\DB;
use PDO;

abstract class Driver
{
    abstract protected function connect():  PDO|array;
}