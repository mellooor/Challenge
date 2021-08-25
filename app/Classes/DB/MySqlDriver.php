<?php


namespace App\DB;


use App\PDO\Parameters as PdoParameters;
use PDO;
use PDOException;

class MySqlDriver extends Driver
{
    /** @var PDO
     *
     * An instance of the PDO class that will be used for database queries within the application.
     */
    private $pdoConnection;

    /** @var PdoParameters
     *
     * An instance of the PdoParameters class that will aid in handling and producing the arguments for the creation of the PDO instance.
     */
    private $pdoParams;

    /**
     * Set the pdoParameters value upon construction
     *
     * @param PdoParameters $pdoParams - An instance of the PdoParameters class that will aid in handling and producing the arguments for the creation of the PDO instance.
     * @return void
     */
    public function __construct(PdoParameters $pdoParams)
    {
        $this->pdoParams = $pdoParams;
    }

    /**
     * Create an instance of the PDO class.
     *
     * @return PDO | array - The fresh (or already-existing) instance of the PDO class if successful, an error array if an issue occurs.
     */
    protected function connect():   PDO
    {
        if (isset($this->pdoConnection))
        {
            return $this->pdoConnection;
        } else
        {
            try
            {
                $this->pdoConnection = new PDO($this->pdoParams->getDsn(), $this->pdoParams->getUser(), $this->pdoParams->getPass(), $this->pdoParams->getOptions());
                return $this->pdoConnection;
            } catch (PDOException $e)
            {
                return [
                    'error' => 'Connection error: ' . $e->getMessage()
                ];
            }
        }
    }
}