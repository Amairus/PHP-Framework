<?php 

declare(strict_types=1);

namespace Magma\DatabaseConnection;

use PDO;

interface DatabaseConnectionInterface{

    /**
     * Creates a new DB connection
     */
    public function open(): PDO;

    /**
     * Closes the existing DB connection
     */
    public function close(): void;


}