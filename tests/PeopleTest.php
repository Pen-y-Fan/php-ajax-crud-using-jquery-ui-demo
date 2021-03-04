<?php

declare(strict_types=1);

namespace App\Tests;

use App\model\PeopleModel;
use PHPUnit\Framework\TestCase;

class PeopleTest extends TestCase
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

    public function testItCanAddAPerson(): void
    {
        $database = $this->createSQLiteTable->createSQLiteTableWithData();

        $_POST['action'] = 'insert';
        $_POST['first_name'] = 'George';
        $_POST['last_name'] = 'Evans';

        $peopleModel = new PeopleModel($database);
        $peopleModel->insert('George', 'Evans');

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
        $peopleModel->update($id, $firstName, $lastName);

        $result = $peopleModel->selectById($id);
        self::assertCount(1, $result);

        self::assertSame($firstName, $result[count($result) - 1]['first_name']);
        self::assertSame($lastName, $result[count($result) - 1]['last_name']);
    }

    public function testItCanDeleteAPersonById(): void
    {
        $database = $this->createSQLiteTable->createSQLiteTableWithData();

        $id = 3;

        $peopleModel = new PeopleModel($database);
        $peopleModel->deleteById($id);

        $result = $peopleModel->selectAll();
        self::assertCount(2, $result);

        self::assertSame('David', $result[count($result) - 1]['first_name']);
        self::assertSame('Williams', $result[count($result) - 1]['last_name']);
    }
}
