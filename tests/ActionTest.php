<?php
declare(strict_types=1);


use PHPUnit\Framework\TestCase;

class ActionTest extends TestCase
{
    /** @var CreateSQLiteTable */
    private $createSQLiteTable;

    /**
     * FetchTest constructor.
     * @param string|null $name
     * @param array<mixed> $data
     * @param string $dataName
     */
    public function __construct(?string $name = null, array $data = [], $dataName = '')
    {
        parent::__construct($name, $data, $dataName);
        include_once __DIR__ . '/CreateSQLiteTable.php';
        $this->createSQLiteTable = new CreateSQLiteTable();
    }

    public function testANameCanBeAddedToTheTable(): void
    {
        $connect = $this->createSQLiteTable->createSQLiteTableWithData();

        $_POST['action'] = 'insert';
        $_POST['first_name'] = "George";
        $_POST['last_name'] = "Evans";

        ob_start();
        require_once __DIR__ . '/../public/action.php';
        $output = ob_get_contents();
        ob_end_clean();

        self::assertSame('<p>Data Inserted...</p>', $output);

        $query = "SELECT * FROM tbl_sample";
//        $query = "SELECT * FROM tbl_sample";
        $statement = $connect->query($query);
        self::assertNotFalse($statement);
        $result = $statement->fetchAll();
        self::assertNotFalse($result);

        self::assertCount(4, $result);

        self::assertSame("George",$result[count($result)-1]['first_name']);
        self::assertSame("Evans",$result[count($result)-1]['last_name']);
    }
}
