<?php 

declare(strict_types=1);

namespace Magma\Datamapper;

use Magma\DatabaseConnection\DatabaseConnectionInterface;
use Magma\DataMapper\DataMapper;
use Magma\DataMapper\DataMapperInterface;
use Magma\DataMapper\Exception\DataMapperException;

class DataMapperFactory 
{

    public function __construct()
    {
        
    }

    
    public function create(string $databaseConnectionString, string $dataMapperEnviromentConfiguration) : DataMapperInterface
    {
        $credentials = (new $dataMapperEnviromentConfiguration([]))->getDatabaseCredentials();
        $databaseConnectionObject = (new $databaseConnectionString($credentials));
        if(! $databaseConnectionObject instanceof DatabaseConnectionInterface){
            throw new DataMapperException($databaseConnectionString . 'is not a Valid database connection object');
        }

        return new DataMapper($databaseConnectionObject);
    }

}