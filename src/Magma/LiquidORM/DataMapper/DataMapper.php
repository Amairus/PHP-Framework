<?php

declare(strict_types=1);

namespace Magma\DataMapper;

use Magma\DatabaseConnection\DatabaseConnectionInterface;
use Magma\DataMapper\DataMapperInterface;
use Magma\DataMapper\Exception\DataMapperException;
use PDOStatement;
use PDO;

class DataMapper implements DataMapperInterface
{
    /**
     * save the Dabase connection obj
     *
     * @var DatabaseConnectionInterface
     */
    private DatabaseConnectionInterface $dbh;

    /**
     * keep PDO statment
     *
     * @var PDOStatement
     */
    private PDOStatement $statement;


    /**
     * Constructor
     *
     * @param DatabaseConnectionInterface $dbh
     */
    public function __construct(DatabaseConnectionInterface $dbh)
    {
         $this->dbh = $dbh;   
    }


    private function isEmpty($value, string $errorMessage = null)
    {
        if(empty($value)){
            throw new DataMapperException($errorMessage);
        }
    }

    private function isArray(array $value)
    {
        if(empty($value)){
            throw new DataMapperException('Your argument should be an array');
        }
    }

    /**
     *
     * @inheritDoc
     */

    public function prepare(string $sqlQuery) :self
    {
        $this->statement = $this->dbh->open()->prepare($sqlQuery);
        return $this;
    }

    /**
     *
     * @inheritDoc
     */

    public function bind($value)
    {
        try{
            switch($value){
                case is_bool($value):
                case intval($value):
                    $dataType = PDO::PARAM_INT;    
                    break;
                case is_null($value):
                    $dataType = PDO::PARAM_NULL;
                    break;    
                default:
                    $dataType = PDO::PARAM_STR;
                    break;    
            }
            return $dataType;
        }catch(DataMapperException $exception){
            throw $exception;
        }
    }

    /**
     *
     * @inheritDoc
     */

    public function bindParameters(array $fields, bool $isSearch = false) : self
    {
        if(is_array($fields)){
            $type = ($isSearch === false) ? $this->bindValues($fields) : $this->bindSearchValues($fields);
            if($type){
                return $this;
            }
        }
        return false;        
    }

    /**
     *
     * @inheritDoc
     */

    public function execute(): void
    {
        if($this->statement){
            $this->statement->execute();
        }
    }

    /**
     *
     * @inheritDoc
     */

    public function numRows(): int
    {
        if($this->statement){
            return $this->statement->rowCount();
        }
    }
    
    /**
     *
     * @inheritDoc
     */

    public function result() : Object
    {
        if($this->statement)
            return $this->statement->fetch(PDO::FETCH_OBJ);
    }


    /**
     *
     * @inheritDoc
     */
    public function results(): array
    {
        if($this->statement){
            return $this->statement->fetchAll();
        }
    }

    /**
     *
     * @inheritDoc
     */
    
    public function getLastId(): int
    {
        try {
            if($this->dbh->open()){
                $lastId = $this->dbh->open()->lastInsertId();
                if(!empty($lastId)) {
                    return intval($lastId);
                }
            }
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    /**
     * Bind a value to a question mark to corresponding field with placeholder in SQL
     * statment that was prepared
     *
     * @param array $fields
     * @return void
     */

    protected function bindValues(array $fields)
    {
        $this->isArray($fields);
        foreach($fields as $key => $value){
            $this->statement->bindValue(':'.$key,$value, (int)$this->bind($value));
        }

        return $this->statement;
    }

    /**
     * Bind a value to a question mark to corresponding field with placeholder only for
     * searching query with like keyword in SQL statment that was prepared
     *
     * @param array $fields
     * @return void
     */

    protected function bindSearchValues(array $fields)
    {
        $this->isArray($fields);
        foreach($fields as $key => $value){
            $this->statement->bindValue(':'.$key, '%'. $value .'%', (int)$this->bind($value));
        }

        return $this->statement;
    }

}