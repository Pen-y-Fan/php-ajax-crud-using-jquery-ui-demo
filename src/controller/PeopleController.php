<?php

declare(strict_types=1);

namespace App\controller;

use App\database\DatabaseConnection;
use App\model\PeopleModel;

class PeopleController
{
    /**
     * @var DatabaseConnection|null
     */
    private $database;

    public function __construct(?DatabaseConnection $database = null)
    {
        $this->database = $database;
    }

    /**
     * @return array<mixed>
     */
    public function index(): array
    {
        $people = new PeopleModel($this->database);
        return $people->selectAll();
    }

    /**
     * @return array<mixed>
     */
    public function show(int $id): array
    {
        $people = new PeopleModel($this->database);
        return $people->selectById($id);
    }

    public function store(string $firstName, string $lastName): bool
    {
        $people = new PeopleModel($this->database);
        return $people->insert($firstName, $lastName);
    }

    public function update(int $id, string $firstName, string $lastName): bool
    {
        $people = new PeopleModel($this->database);
        return $people->update($id, $firstName, $lastName);
    }

    public function delete(int $id): bool
    {
        $people = new PeopleModel($this->database);
        return $people->deleteById($id);
    }
}
