<?php

use PHPUnit\Framework\TestCase;
use App\DB\MySqlDsnString;

class MySqlDsnStringTest extends TestCase
{
    /**
    * Checks to see that the dsn string can successfully be created.
    */
    public function testCanCreateString():	void
    {
        $host = 'test';
        $db = 'testTwo';
        $charset = 'testThree';

        $result = MySqlDsnString::toString($host, $db, $charset);

        $expectedDsnString = 'mysql:host=test;dbname=testTwo;charset=testThree';

        $this->assertEquals($expectedDsnString, $result);
    }
}