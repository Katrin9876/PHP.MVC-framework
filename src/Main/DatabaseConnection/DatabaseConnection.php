<?php

declare(strict_types=1);

namespace Main\DatabaseConnection;

use Main\DatabaseConnection\DatabaseConnectionInterface;
use Main\DatabaseConnection\Exception\DatabaseConnectionException;
use PDOException;
use PDO;

class DatabaseConnection implements DatabaseConnectionInterface
{
    protected PDO $dbh;
    protected array $credentials;

    public function __construct(array $credentials)
    {
        $this->credentials = $credentials;
    }

    public function open() : PDO
    {
        try {
            $params = [
                PDO::ATTR_EMULATE_PREPARES => false,
                PDO::ATTR_PERSISTENT => true,
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            ];
            $this->dbh = new PDO(
                $this->credentials['dsn'],
                $this->credentials['username'],
                $this->credentials['password'],
                $params
            );
        } catch(PDOException $exception) {
            throw new DatabaseConnectionException($exception->getMessage(), (int)$exception->getCode());
        }

        return $this->dbh;
    }

    public function close() : void
    {
        $this->dbh = null;
    }


}