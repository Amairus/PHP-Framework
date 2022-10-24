<?php

declare(strict_types=1);

namespace Magma\DataMapper;

use Magma\DataMapper\Exception\DataMapperInvalidArgumentException;

class DataMapperEnviromentConnection 
{

    /**
     * Storing Databases credentials
     *
     * @var array
     */
    private array $credentials = [];

    
    public function __construct(array $credentials)
    {

        $this->credentials = $credentials;

    }

    /**
     * Get user database connection credentials array
     *
     * @param string $driver
     * @return array
     */
    public function getDatabaseCredentials(string $driver) : array
    {

        $connectionArray = [];
        foreach($this->credentials as $credentials){
            if(array_key_exists($driver, $credentials))
                $connectionArray = $credentials[$driver];
        }

        return $connectionArray;
    }


    /**
     * Checks if credentials is invalid
     *
     * @param string $driver
     * @return boolean
     */
    private function isCredentialsInvalid(string $driver) 
    {
        if(empty($driver) && !is_string($driver))
            throw new DataMapperInvalidArgumentException('Invalid argument. This is either missing or invalid data type');
        if(!is_array($this->credentials))    
            throw new DataMapperInvalidArgumentException('Invalid credentials');
        if(!in_array($driver, array_keys($this->credentials[$driver])))
            throw new DataMapperInvalidArgumentException('Invalid or unsupport database driver');
    }
}   