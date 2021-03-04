<?php

declare(strict_types=1);

namespace App\database;

use PDO;
use PDOException;

class DatabaseConnection
{
    /**
     * @var PDO
     */
    private $connect;

    public function __construct()
    {
        $databaseSettings = new DatabaseSettings();

        if (getenv('APP_ENV') && getenv('APP_ENV') === 'TESTING') {
            try {   // PDO Connection string for SQLite in memory DB for testing
                $this->connect = new PDO('sqlite::memory:');
                $this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->connect->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die('Error unable to connect: <br/>' . $e->getMessage());
            }
        } else {
            // Test connection to the database
            try {   // PDO Connection string
                $this->connect = new PDO(
                    "mysql:host={$databaseSettings->getDbHost()};dbname={$databaseSettings->getDbName()}",
                    $databaseSettings->getDbUser(),
                    $databaseSettings->getDbPassword(),
                    $databaseSettings->getDbOptions()
                );
                $this->connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
                $this->connect->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
            } catch (PDOException $e) {
                die('Error unable to connect: <br/>' . $e->getMessage());
            }
        }
    }

    public function getConnection(): PDO
    {
        return $this->connect;
    }
}
