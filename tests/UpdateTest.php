<?php

declare(strict_types=1);

namespace App\Tests;

use App\database\DatabaseConnection;
use PHPUnit\Framework\TestCase;

class UpdateTest extends TestCase
{
    /**
     * @var DatabaseConnection
     */
    private $database;

    protected function setUp(): void
    {
        putenv('APP_ENV=TESTING');

        $createSQLiteTable = new CreateSQLiteTable();
        $this->database = $createSQLiteTable->createSQLiteTableWithData();
        $_POST['first_name'] = 'Jenny';
        $_POST['last_name'] = 'Jones';
        $_POST['hidden_id'] = 1;
    }

    public function tearDown(): void
    {
        parent::tearDown();
        foreach ($_POST as $key => $post) {
            unset($_POST[$key]);
        }
    }

    public function testUpdate(): void
    {
        $database = $this->database;

        ob_start();
        require_once __DIR__ . '/../public/api/update/index.php';
        $output = ob_get_clean();

        self::assertNotFalse($output, 'Unable to test output of api/update/index.php');
        self::assertSame('{"data":"Data updated..."}', $output);
    }

    public function testItCanNotUpdateWithoutAnId(): void
    {
        $database = $this->database;

        unset($_POST['hidden_id']);

        ob_start();
        require_once __DIR__ . '/../public/api/update/index.php';
        $output = ob_get_contents();
        if (! $output) {
            hasUpdateValidPostData();
            $output = ob_get_contents();
        }

        ob_get_clean();

        self::assertNotFalse($output, 'Unable to test output of api/update/index.php');
        self::assertSame('{"error":"\'id\', \'first_name\' and \'last_name\' are required."}', $output);
    }

    public function testItCanNotUpdateWithoutAnFirstName(): void
    {
        $database = $this->database;

        unset($_POST['first_name']);

        ob_start();
        require_once __DIR__ . '/../public/api/update/index.php';
        $output = ob_get_contents();
        if (! $output) {
            hasUpdateValidPostData();
            $output = ob_get_contents();
        }

        ob_get_clean();

        self::assertNotFalse($output, 'Unable to test output of api/update/index.php');
        self::assertSame('{"error":"\'id\', \'first_name\' and \'last_name\' are required."}', $output);
    }

    public function testItCanNotUpdateWithoutAnLastName(): void
    {
        $database = $this->database;

        unset($_POST['last_name']);

        ob_start();
        require_once __DIR__ . '/../public/api/update/index.php';
        $output = ob_get_contents();
        if (! $output) {
            hasUpdateValidPostData();
            $output = ob_get_contents();
        }

        ob_get_clean();

        self::assertNotFalse($output, 'Unable to test output of api/update/index.php');
        self::assertSame('{"error":"\'id\', \'first_name\' and \'last_name\' are required."}', $output);
    }

    public function testItCanNotUpdateWithoutAnyNames(): void
    {
        $database = $this->database;
        unset($_POST['first_name'], $_POST['last_name'], $_POST['hidden_id']);

        ob_start();
        require_once __DIR__ . '/../public/api/update/index.php';
        $output = ob_get_contents();
        if (! $output) {
            hasUpdateValidPostData();
            $output = ob_get_contents();
        }

        ob_get_clean();

        self::assertNotFalse($output, 'Unable to test output of api/update/index.php');
        self::assertSame('{"error":"\'id\', \'first_name\' and \'last_name\' are required."}', $output);
    }
}
