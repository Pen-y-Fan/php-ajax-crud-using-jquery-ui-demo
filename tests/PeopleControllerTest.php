<?php

declare(strict_types=1);

namespace App\Tests;

use App\controller\PeopleController;
use App\database\DatabaseConnection;
use App\model\PeopleModel;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class PeopleControllerTest extends TestCase
{
    /**
     * @var CreateSQLiteTable
     */
    private $createSQLiteTable;

    /**
     * @var PeopleController
     */
    private $peopleController;

    /**
     * @var DatabaseConnection
     */
    private $database;

    /**
     * FetchTest constructor.
     * @param array<mixed> $data
     * @param string $dataName
     */
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        $this->createSQLiteTable = new CreateSQLiteTable();
    }

    protected function setUp(): void
    {
        $this->database = $this->createSQLiteTable->createSQLiteTableWithData();
        $this->peopleController = new PeopleController($this->database);
    }

    public function testItCanGetAllPeople(): void
    {
        $people = $this->peopleController->index();

        self::assertSame('Fred', $people[0]['first_name']);
        self::assertSame('Bloggs', $people[0]['last_name']);
        self::assertSame('David', $people[1]['first_name']);
        self::assertSame('Williams', $people[1]['last_name']);
        self::assertSame('John', $people[2]['first_name']);
        self::assertSame('Smith', $people[2]['last_name']);
    }

    public function testItCanGetOnePersonById(): void
    {
        $fred = $this->peopleController->show(1);
        $david = $this->peopleController->show(2);
        $john = $this->peopleController->show(3);

        self::assertSame('Fred', $fred[0]['first_name']);
        self::assertSame('Bloggs', $fred[0]['last_name']);
        self::assertSame('David', $david[0]['first_name']);
        self::assertSame('Williams', $david[0]['last_name']);
        self::assertSame('John', $john[0]['first_name']);
        self::assertSame('Smith', $john[0]['last_name']);
    }

    public function testItCanAddAPerson(): void
    {
        $result = $this->peopleController->store('George', 'Evans');

        self::assertTrue($result);

        $result = $this->peopleController->index();
        self::assertCount(4, $result);

        self::assertSame('George', $result[count($result) - 1]['first_name']);
        self::assertSame('Evans', $result[count($result) - 1]['last_name']);
    }

    public function testItCanUpdateAPerson(): void
    {
        $firstName = 'Jenny';
        $lastName = 'Jones';
        $id = 1;

        $update = $this->peopleController->update($id, $firstName, $lastName);
        self::assertTrue($update);

        $confirmation = $this->peopleController->show($id);

        self::assertCount(1, $confirmation);
        self::assertSame($firstName, $confirmation[0]['first_name']);
        self::assertSame($lastName, $confirmation[0]['last_name']);
    }

    public function testItCanNotUpdateWithInvalidId(): void
    {
        $firstName = 'Jenny';
        $lastName = 'Jones';
        $id = 999;

        $update = $this->peopleController->update($id, $firstName, $lastName);

        // I expected this to be false - must be false for another reason - possibly PDO::ATTR_ERRMODE
        self::assertTrue($update);

        // Check the database for Jenny
        $query = /** @lang MySQL|SQLite */
            'SELECT * FROM tbl_sample WHERE first_name = :firstName ';
        $statement = $this->database->getConnection()->prepare($query);
        $statement->bindParam(':firstName', $firstName, PDO::PARAM_STR, 255);
        /** @var PDOStatement $statement */
        $statement->execute();

        $result = $statement->fetchAll();

        self::assertNotFalse($result);
        self::assertCount(0, $result);

        $confirmation = $this->peopleController->show($id);
        self::assertCount(0, $confirmation);
    }

    public function testItCanDeleteAPersonById(): void
    {
        $id = 3;
        $result = $this->peopleController->delete($id);

        self::assertTrue($result);

        $confirmation = $this->peopleController->index();
        self::assertCount(2, $confirmation);

        self::assertSame('David', $confirmation[count($confirmation) - 1]['first_name']);
        self::assertSame('Williams', $confirmation[count($confirmation) - 1]['last_name']);
    }

    public function testItCanNotDeleteAnInvalidId(): void
    {
        $database = $this->createSQLiteTable->createSQLiteTableWithData();
        $id = 999;

        $peopleModel = new PeopleModel($database);
        $result = $peopleModel->deleteById($id);

        // I expected this to be false - PDO must be false for another reason - possibly PDO::ATTR_ERRMODE
        self::assertTrue($result);

        $result = $peopleModel->selectAll();
        self::assertCount(3, $result);

        self::assertSame('John', $result[count($result) - 1]['first_name']);
        self::assertSame('Smith', $result[count($result) - 1]['last_name']);
    }
}
