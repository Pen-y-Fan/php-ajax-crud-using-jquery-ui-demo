<?php

declare(strict_types=1);

namespace App\Tests;

use App\database\DatabaseConnection;
use PHPUnit\Framework\Error;
use PHPUnit\Framework\TestCase;

class StoreTest extends TestCase
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
    }

    public function tearDown(): void
    {
        parent::tearDown();
        foreach ($_POST as $key => $post) {
            unset($_POST[$key]);
        }
    }

    public function testItCanStoreAValidRecord(): void
    {
        $database = $this->database;

        $_POST['first_name'] = 'George';
        $_POST['last_name'] = 'Evans';

        ob_start();
        require_once __DIR__ . '/../public/api/store/index.php';
        $output = ob_get_clean();

        if ($output === false) {
            throw new Error('Unable to test output of api/store/index.php');
        }

        self::assertSame('{"data":"Data inserted..."}', $output);
    }

    public function testItCanNotStoreWithoutAnFirstName(): void
    {
        $database = $this->database;

        unset($_POST['first_name']);
        $_POST['last_name'] = 'Evans';

        ob_start();
        require_once __DIR__ . '/../public/api/store/index.php';
        $output = ob_get_contents();
        if (! $output) {
            hasStoreValidPostData();
            $output = ob_get_contents();
        }

        ob_get_clean();

        if ($output === false) {
            throw new Error('Unable to test output of api/store/index.php');
        }

        $expected = '{"error":"\'first_name\' and \'last_name\' are required."}';
        self::assertSame($expected, $output);
    }

    public function testItCanNotStoreWithoutAnLastName(): void
    {
        $database = $this->database;

        $_POST['action'] = 'insert';
        $_POST['first_name'] = 'George';
        unset($_POST['last_name']);

        ob_start();
        require_once __DIR__ . '/../public/api/store/index.php';
        $output = ob_get_contents();
        if (! $output) {
            hasStoreValidPostData();
            $output = ob_get_contents();
        }

        ob_get_clean();

        if ($output === false) {
            throw new Error('Unable to test output of api/store/index.php');
        }

        $expected = '{"error":"\'first_name\' and \'last_name\' are required."}';
        self::assertSame($expected, $output);
    }

    public function testItCanNotStoreWithoutAnyNames(): void
    {
        $database = $this->database;

        unset($_POST['first_name'], $_POST['last_name']);

        ob_start();
        require_once __DIR__ . '/../public/api/store/index.php';
        $output = ob_get_contents();
        if (! $output) {
            hasStoreValidPostData();
            $output = ob_get_contents();
        }

        ob_get_clean();

        if ($output === false) {
            throw new Error('Unable to test output of api/store/index.php');
        }

        $expected = '{"error":"\'first_name\' and \'last_name\' are required."}';
        self::assertSame($expected, $output);
    }
}
