<?php

declare(strict_types=1);

namespace App\database;

use PDO;

class DatabaseSettings
{
    /**
     * @var array<mixed>
     */
    private $dbOptions;

    /**
     * @var string
     */
    private $dbHost;

    /**
     * @var string
     */
    private $dbName;

    /**
     * @var string
     */
    private $dbUser;

    /**
     * @var string
     */
    private $dbCharset;

    /**
     * @var string
     */
    private $dbPassword;

    public function __construct()
    {
        $ini_array = parse_ini_file(__DIR__ . '/../../.env');

        if ($ini_array !== false) {
            foreach ($ini_array as $key => $value) {
                putenv($key . '=' . $value);
            }
        }

        if (getenv('DATABASE_SERVER') === false) {
            $this->dbHost = 'database';  // database for Docker (set in docker-compose)
//       'localhost';  // localhost on LINUX
//        '127.0.0.1';    // 127.0.0.1 on Windows
        } else {
            $this->dbHost = getenv('DATABASE_SERVER');
        }

        if (getenv('DATABASE_NAME') === false) {
            $this->dbName = 'ajax_crud';    // Name of your database
        } else {
            $this->dbName = getenv('DATABASE_NAME');
        }

        if (getenv('DATABASE_USERNAME') === false) {
            $this->dbUser = 'root';    // Your DB user name
        } else {
            $this->dbUser = getenv('DATABASE_USERNAME');
        }

        if (getenv('DATABASE_PASSWORD') === false) {
            $this->dbPassword = '';    // Your DB password
        } else {
            $this->dbPassword = getenv('DATABASE_PASSWORD');
        }

        $this->dbCharset = 'utf8mb4';

        $this->dbOptions = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            PDO::ATTR_EMULATE_PREPARES => false,
        ];

        if (getenv('APP_ENV') && getenv('APP_ENV') !== 'TESTING') {
            $this->dbOptions += [
                PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
            ];
        }
    }

    public function getDbPassword(): string
    {
        return $this->dbPassword;
    }

    /**
     * @return array<mixed>
     */
    public function getDbOptions(): array
    {
        return $this->dbOptions;
    }

    public function getDbHost(): string
    {
        return $this->dbHost;
    }

    public function getDbName(): string
    {
        return $this->dbName;
    }

    public function getDbUser(): string
    {
        return $this->dbUser;
    }

    public function getDbCharset(): string
    {
        return $this->dbCharset;
    }
}
