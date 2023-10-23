<?php

declare(strict_types=1);

namespace Main\DatabaseConnection\Exception;

use PDOException;

class DatabaseConnectionException extends PDOException
{
    
    /**
     * Main constructor class which overrides the parent constructor and set the message
     * and the code properties which is optional
     */
    public function __construct($message = null, $code = null)
    {
        $this->message = $message; //already defined in PDOException;
        $this->code = $code; //already defined in PDOException;
    }

}