<?php


namespace App\DB;


abstract class Driver
{
    abstract protected function connect();
}