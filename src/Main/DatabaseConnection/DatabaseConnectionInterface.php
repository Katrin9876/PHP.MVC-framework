<?php

declare(strict_types=1);

namespace Main\DatabaseConnection;

use PDO;

interface DatabaseConnectionInterface
{

    /**
     * Create a new database connection
     */
    public function open() : PDO;

    /**
     * Close database connection
     */
    public function close() : void;

}