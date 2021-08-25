<?php

use PHPUnit\Framework\TestCase;
use App\PDO\Parameters;

class PDOParametersTest extends TestCase
{
    private string $dsn;
    private string $user;
    private string $pass;
    private array $options;

    /**
    *  Set up the dsn, user, pass and options parameters that will be used in the tests.
    */
    public function setUp():	void
    {
        $this->dsn = 'test';
        $this->user = 'testTwo';
        $this->pass = 'testThree';
        $this->options = ['testFour'];
    }

    /**
    * Checks to see that the dsn string can successfully be retrieved.
    */
    public function testCanGetDsnString():	void
    {
        $pdoParameters = new Parameters($this->dsn, $this->user, $this->pass, $this->options);
        $result = $pdoParameters->getDsn();
        $this->assertEquals($this->dsn, $result);
    }

    /**
    * Checks to see that the user string can successfully be retrieved.
    */
    public function testCanGetUserString():	void
    {
        $pdoParameters = new Parameters($this->dsn, $this->user, $this->pass, $this->options);
        $result = $pdoParameters->getUser();
        $this->assertEquals($this->user, $result);
    }

    /**
    * Checks to see that the pass string can successfully be retrieved.
    */
    public function testCanGetPassString():	void
    {
        $pdoParameters = new Parameters($this->dsn, $this->user, $this->pass, $this->options);
        $result = $pdoParameters->getPass();
        $this->assertEquals($this->pass, $result);
    }

    /**
    * Checks to see that the options string can successfully be retrieved.
    */
    public function testCanGetOptionsString():	void
    {
        $pdoParameters = new Parameters($this->dsn, $this->user, $this->pass, $this->options);
        $result = $pdoParameters->getOptions();
        $this->assertEquals($this->options, $result);
    }
}