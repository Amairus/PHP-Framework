<?php 

declare(strict_types=1);

namespace Magma\DatabaseConnection;

use Magma\DatabaseConnection\DatabaseConnectionInterface;
use Magma\DatabaseConnection\Exception\DatabaseConnectionException;
use PDO;
use PDOException;

class DatabaseConnection implements DatabaseConnectionInterface
{

    /**
     *
     * @var PDO
     */
    protected PDO $dbh;

    /**
     *
     * @var array
     */
    protected array $credentials;

    /**
     * Main constructor class
     *
     * @param array $credentials
     */
    public function __construct(array $credentials)
    {
        $this->credentials = $credentials;
    }


    public function open () : PDO
    {
        try{
            $params =[
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ];

            $this->dbh = new PDO(
                $this->credentials['dns'],
                $this->credentials['username'],
                $this->credentials['password'],
                $params
            );
        }catch(PDOException $exception){
            throw new DatabaseConnectionException($exception->getMessage(), (int)$exception->getCode());
        }
        
        return $this->dbh;
    }

    /**
     * Closing Database connection
     *
     * @return void
     */
    public function close(): void
    {
        $this->dbh = null;
    }

}