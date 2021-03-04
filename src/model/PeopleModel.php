<?php

declare(strict_types=1);

namespace App\model;

use App\database\DatabaseConnection;
use PDOStatement;

class PeopleModel
{
    /**
     * @var DatabaseConnection
     */
    private $database;

    public function __construct(?DatabaseConnection $database = null)
    {
        if (is_null($database)){
            $this->database = new DatabaseConnection();
        } else {
            $this->database = $database;
        }
    }

    /**
     * @return array<mixed>
     */
    public function selectAll(): array
    {
        $query = /** @lang MySQL|SQLite */
            'SELECT * FROM tbl_sample';
        $statement = $this->database->getConnection()->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
        if ($result === false) {
            return ["error" => "The result was false"];
        }
        return $result;
    }

    /**
     * @return array<mixed>
     */
    public function selectById(int $id): array
    {
        $query = /** @lang MySQL|SQLite */
            "SELECT * FROM tbl_sample WHERE id = '" . $id . "'";
        $statement = $this->database->getConnection()->prepare($query);
        /** @var PDOStatement $statement */
        $statement->execute();
//        if ($statement === false) {
//            return ["error" => "There was an error with the statement"];
//        }
        $result = $statement->fetchAll();
        if ($result === false) {
            return ["error" => "The result was false"];
        }
        return $result;
    }

    public function insert(string $firstName, string $lastName): void
    {
        $query = /** @lang MySQL|SQLite */
            "
		INSERT INTO tbl_sample (first_name, last_name)
		VALUES ('" . $firstName . "', '" . $lastName . "')
		";
        $statement = $this->database->getConnection()->prepare($query);
        $statement->execute();
    }

    public function update(int $id, string $first_name, string $last_name): void
    {
        $query = /** @lang MySQL|SQLite */
            "
		UPDATE tbl_sample
		SET first_name = '" . $first_name . "',
		last_name = '" . $last_name . "'
		WHERE id = '" . $id . "'
		";
        $statement = $this->database->getConnection()->prepare($query);
        $statement->execute();
    }

    public function deleteById(int $id): void
    {
        $query = /** @lang MySQL|SQLite */
            "DELETE FROM tbl_sample WHERE id = '" . $id . "'";
        $statement = $this->database->getConnection()->prepare($query);
        $statement->execute();
    }
}
