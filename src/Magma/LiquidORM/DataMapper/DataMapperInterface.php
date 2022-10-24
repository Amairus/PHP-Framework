<?php

namespace Magma\DataMapper;

interface DataMapperInterface 
{

    /**
     * Prepare the query
     *
     * @param string $sqlQuery
     * @return self
     */
    public function prepare(string $sqlQuery) :self;

    /**
     * Bind the values
     *
     * @param [type] $value
     * @return void
     */
    public function bind($value);

    /**
     *
     *
     * @param array $fields
     * @param boolean $isSearch
     * @return void
     */
    public function bindParameters(array $fields, bool $isSearch = false);

    /**
     * Returns row num of data found
     *
     * @return integer
     */
    public function numRows() : int;

    /**
     
     * Executes the query statement 
     * 
     * @return void
     */
    public function execute() : void;

    /**
     * Returns result in Object format
     *
     * @return Object
     */
    public function result() : Object;

    /**
     * Return results from database in array format
     *
     * @return array
     */
    public function results() :array;

    /**
     * Returns last id of row inserted
     *
     * @return integer
     */
    public function getLastId() : int;

}