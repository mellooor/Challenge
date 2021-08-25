<?php


namespace App\PDO;


class Parameters
{
    /** @var string
     *
     * The dsn string that will be later passed into the PDO constructor as an argument.
     */
    private string $dsn;

    /** @var array
     *
     * The PDO options array that will later be passed into the PDO constructor as an argument.
     */
    private array $options;

    /** @var string
     *
     * An user string that will later be passed into the PDO constructor as an argument.
     */
    private string $user;

    /** @var string
     *
     * The pass string that will later be passed into the PDO constructor as an argument.
     */
    private string $pass;

    /**
     * Set the dsn, user, pass and options parameters upon creation.
     *
     * @param string $dsn - The dsn string that is to be passed to the PDO constructor as an argument.
     * @param string $user - The user of the DB to be connected to.
     * @param string $pass - The password associated with the user of the DB to be connected to.
     * @param array $options - (Optional) An array of PDO arguments to be passed into the PDO constructor as an argument.
     *
     * @return void
     */
    public function __construct(string $dsn, string $user, string $pass, array $options = [])
    {
        $this->dsn = $dsn;
        $this->user = $user;
        $this->pass = $pass;
        $this->options = $options;
    }

    /**
     * Retrieve the dsn parameter of the class instance.
     *
     * @return string - The dsn parameter.
     */
    public function getDsn():   string
    {
        return $this->dsn;
    }

    /**
     * Retrieve the options parameter of the class instance.
     *
     * @return array - The options parameter.
     */
    public function getOptions():   array
    {
        return $this->options;
    }

    /**
     * Retrieve the user parameter of the class instance.
     *
     * @return string - The user parameter.
     */
    public function getUser():  string
    {
        return $this->user;
    }

    /**
     * Retrieve the pass parameter of the class instance.
     *
     * @return string - The pass parameter.
     */
    public function getPass():  string
    {
        return $this->pass;
    }
}