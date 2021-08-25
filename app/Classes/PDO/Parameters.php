<?php


namespace App\PDO;


class Parameters
{
    private $dsn;
    private $options;
    private $user;
    private $pass;

    public function __construct($dsn, $user, $pass, $options = [])
    {
        $this->dsn = $dsn;
        $this->user = $user;
        $this->pass = $pass;
        $this->options = $options;
    }

    public function getDsn()
    {
        return $this->dsn;
    }
    public function getOptions()
    {
        return $this->options;
    }
    public function getUser()
    {
        return $this->user;
    }
    public function getPass()
    {
        return $this->pass;
    }
}