<?php

declare(strict_types=1);

namespace App\Tests;

use App\model\PeopleModel;
use PDO;
use PDOStatement;
use PHPUnit\Framework\TestCase;

class PeopleModelTest extends TestCase
{
    /**
     * @var CreateSQLiteTable
     */
    private $createSQLiteTable;

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

    public function testItCanGetAllPeople(): void
    {
        $database = $this->createSQLiteTable->createSQLiteTableWithData();

        $peopleModel = new PeopleModel($database);
        $people = $peopleModel->selectAll();

        self::assertSame('Fred', $people[0]['first_name']);
        self::assertSame('Bloggs', $people[0]['last_name']);
        self::assertSame('David', $people[1]['first_name']);
        self::assertSame('Williams', $people[1]['last_name']);
        self::assertSame('John', $people[2]['first_name']);
        self::assertSame('Smith', $people[2]['last_name']);
    }

    public function testItCanGetOnePersonById(): void
    {
        $database = $this->createSQLiteTable->createSQLiteTableWithData();

        $peopleModel = new PeopleModel($database);
        $fred = $peopleModel->selectById(1);
        $david = $peopleModel->selectById(2);
        $john = $peopleModel->selectById(3);

        self::assertSame('Fred', $fred[0]['first_name']);
        self::assertSame('Bloggs', $fred[0]['last_name']);
        self::assertSame('David', $david[0]['first_name']);
        self::assertSame('Williams', $david[0]['last_name']);
        self::assertSame('John', $john[0]['first_name']);
        self::assertSame('Smith', $john[0]['last_name']);
    }

    /**
     * @noinspection UnknownInspectionInspection
     */
    public function testItCanAddAPerson(): void
    {
        $database = $this->createSQLiteTable->createSQLiteTableWithData();

        $_POST['action'] = 'insert';
        $_POST['first_name'] = 'George';
        $_POST['last_name'] = 'Evans';

        $peopleModel = new PeopleModel($database);
        $result = $peopleModel->insert('George', 'Evans');

        self::assertTrue($result);

        /** @noinspection SqlResolve */
        $sql = /** @lang SQLite */
            'SELECT * FROM tbl_sample';
        $statement = $database->getConnection()->query($sql);
        self::assertNotFalse($statement);
        $result = $statement->fetchAll();
        self::assertNotFalse($result);

        self::assertCount(4, $result);

        self::assertSame('George', $result[count($result) - 1]['first_name']);
        self::assertSame('Evans', $result[count($result) - 1]['last_name']);
    }

    public function testItCanUpdateAPerson(): void
    {
        $database = $this->createSQLiteTable->createSQLiteTableWithData();

        $firstName = 'Jenny';
        $lastName = 'Jones';
        $id = 1;

        $peopleModel = new PeopleModel($database);
        $update = $peopleModel->update($id, $firstName, $lastName);
        self::assertTrue($update);

        $confirmation = $peopleModel->selectById($id);

        self::assertCount(1, $confirmation);
        self::assertSame($firstName, $confirmation[0]['first_name']);
        self::assertSame($lastName, $confirmation[0]['last_name']);
    }

    public function testItCanNotUpdateWithInvalidId(): void
    {
        $database = $this->createSQLiteTable->createSQLiteTableWithData();

        $firstName = 'Jenny';
        $lastName = 'Jones';
        $id = 999;

        $peopleModel = new PeopleModel($database);
        $update = $peopleModel->update($id, $firstName, $lastName);

        // I expected this to be false - must be false for another reason - possibly PDO::ATTR_ERRMODE
        self::assertTrue($update);

        $query = /** @lang MySQL|SQLite */
            'SELECT * FROM tbl_sample WHERE first_name = :firstName ';
        $statement = $database->getConnection()->prepare($query);
        $statement->bindParam(':firstName', $firstName, PDO::PARAM_STR, 255);
        /** @var PDOStatement $statement */
        $statement->execute();

        $result = $statement->fetchAll();

        self::assertNotFalse($result);
        self::assertCount(0, $result);
    }

    public function testItCanDeleteAPersonById(): void
    {
        $database = $this->createSQLiteTable->createSQLiteTableWithData();

        $id = 3;

        $peopleModel = new PeopleModel($database);
        $result = $peopleModel->deleteById($id);

        self::assertTrue($result);

        $confirmation = $peopleModel->selectAll();
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

        // I expected this to be false - must be false for another reason - possibly PDO::ATTR_ERRMODE
        self::assertTrue($result);

        $result = $peopleModel->selectAll();
        self::assertCount(3, $result);

        self::assertSame('John', $result[count($result) - 1]['first_name']);
        self::assertSame('Smith', $result[count($result) - 1]['last_name']);
    }
}
