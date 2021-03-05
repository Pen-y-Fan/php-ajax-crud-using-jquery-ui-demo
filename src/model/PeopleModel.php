<?php

declare(strict_types=1);

namespace App\model;

use App\database\DatabaseConnection;
use PDO;
use PDOStatement;

class PeopleModel
{
    /**
     * @var DatabaseConnection
     */
    private $database;

    public function __construct(?DatabaseConnection $database = null)
    {
        if ($database === null) {
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
        $statement = $this->database->getConnection()->query($query);
        if ($statement === false) {
            return [];
        }
        $result = $statement->fetchAll();
        if ($result === false) {
            return [];
        }
        return $result;
    }

    /**
     * @return array<mixed>
     */
    public function selectById(int $id): array
    {
        $query = /** @lang MySQL|SQLite */
            'SELECT * FROM tbl_sample WHERE id = :id ';
        $statement = $this->database->getConnection()->prepare($query);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        /** @var PDOStatement $statement */
        $statement->execute();

        $result = $statement->fetchAll();
        if ($result === false) {
            return [
                'error' => 'The result was false',
            ];
        }
        return $result;
    }

    public function insert(string $firstName, string $lastName): bool
    {
        $insert = /** @lang MySQL|SQLite */
            <<<SQL
                INSERT INTO tbl_sample (`first_name`, `last_name`)
                VALUES (:firstName, :lastName );
SQL;

        $statement = $this->database->getConnection()->prepare($insert);
        $statement->bindParam(':firstName', $firstName, PDO::PARAM_STR, 255);
        $statement->bindParam(':lastName', $lastName, PDO::PARAM_STR, 255);

        return $statement->execute();
    }

    public function update(int $id, string $firstName, string $lastName): bool
    {
        $query = /** @lang MySQL|SQLite */
            '
		UPDATE tbl_sample
		SET first_name = :firstName ,
		last_name = :lastName
		WHERE id = :id
		';

        $statement = $this->database->getConnection()->prepare($query);
        $statement->bindParam(':firstName', $firstName, PDO::PARAM_STR, 255);
        $statement->bindParam(':lastName', $lastName, PDO::PARAM_STR, 255);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        return $statement->execute();
    }

    public function deleteById(int $id): bool
    {
        $query = /** @lang MySQL|SQLite */
            'DELETE FROM tbl_sample WHERE id = :id ';
        $statement = $this->database->getConnection()->prepare($query);
        $statement->bindParam(':id', $id, PDO::PARAM_INT);
        return $statement->execute();
    }
}
