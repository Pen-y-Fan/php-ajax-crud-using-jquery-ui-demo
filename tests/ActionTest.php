<?php
declare(strict_types=1);


use PHPUnit\Framework\TestCase;

class ActionTest extends TestCase
{
    /** @var CreateSQLiteTable */
    private $createSQLiteTable;
    /**
     * @var PDO
     */
    private $connect;

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

protected function setUp(): void
{
    parent::setUp();
    $this->connect = $this->createSQLiteTable->createSQLiteTableWithData();
}

    public function tearDown(): void
    {
        parent::tearDown();
        foreach ($_POST as $key => $post) {
            unset($_POST[$key]);
        }

    }

    public function testANameCanBeAddedToTheTable(): void
    {
        $connect = $this->connect;

        $_POST['action'] = 'insert';
        $_POST['first_name'] = "George";
        $_POST['last_name'] = "Evans";

        ob_start();
        require_once __DIR__ . '/../public/action.php';
        $output = ob_get_contents();
        ob_end_clean();

        self::assertSame('<p>Data Inserted...</p>', $output);

        $query = "SELECT * FROM tbl_sample";
        $statement = $connect->query($query);
        self::assertNotFalse($statement);
        $result = $statement->fetchAll();
        self::assertNotFalse($result);

        self::assertCount(4, $result);

        self::assertSame("George",$result[count($result)-1]['first_name']);
        self::assertSame("Evans",$result[count($result)-1]['last_name']);
    }

    public function testASingleRowCanBeSelectedById(): void
    {
        $connect = $this->connect;

        $_POST['action'] = 'fetch_single';
        $_POST['id'] = 1;

    ob_start();
        require_once __DIR__ . '/../public/action.php';
        $output = ob_get_contents();
        if ($output === "") {
            show($connect);
            $output = ob_get_contents();
        }
        ob_end_clean();

        self::assertSame('{"first_name":"Fred","last_name":"Bloggs"}', $output);
    }

    public function testASingleRecordCanBeUpdated(): void
    {
        $connect = $this->connect;

        $_POST['action'] = 'update';
        $_POST['first_name'] = "Jenny";
        $_POST['last_name'] = "Jones";
        $_POST['hidden_id'] = 1;

        ob_start();
        require_once __DIR__ . '/../public/action.php';
        $output = ob_get_contents();
        if ($output === "") {
            update($connect);
            $output = ob_get_contents();
        }
        ob_end_clean();

        self::assertSame('<p>Data Updated</p>', $output);

        $query = /** @lang SQLite */
            "SELECT * FROM tbl_sample WHERE `first_name` = 'Jenny'";
        $statement = $connect->query($query);
        self::assertNotFalse($statement);
        $result = $statement->fetchAll();
        self::assertNotFalse($result);

        self::assertCount(1, $result);

        self::assertSame("Jenny",$result[count($result)-1]['first_name']);
        self::assertSame("Jones",$result[count($result)-1]['last_name']);
    }
    public function testASingleRecordCanBeDeleted(): void
    {
        $connect = $this->connect;

        $_POST['id'] = 2;

        ob_start();
        require_once __DIR__ . '/../public/action.php';
        $output = ob_get_contents();
        if ($output === "") {
            delete($connect);
            $output = ob_get_contents();
        }
        ob_end_clean();

        self::assertSame('<p>Data Deleted</p>', $output);

        $query = /** @lang SQLite */
            "SELECT * FROM tbl_sample WHERE `last_name` = 'Williams'";
        $statement = $connect->query($query);
        self::assertNotFalse($statement);
        $result = $statement->fetchAll();
        self::assertNotFalse($result);

        self::assertCount(0, $result);
    }
}
