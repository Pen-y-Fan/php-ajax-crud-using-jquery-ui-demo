<?php

declare(strict_types=1);

namespace App\Tests;

use App\database\DatabaseConnection;
use PHPUnit\Framework\TestCase;

class DeleteTest extends TestCase
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

    public function testItCanDeleteAValidRecord(): void
    {
        $database = $this->database;
        $_POST['action'] = 'delete';
        $_POST['id'] = 1;

        ob_start();
        require_once __DIR__ . '/../public/api/delete/index.php';
        $output = ob_get_clean();

        self::assertNotFalse($output, 'Unable to test output of api/delete/index.php');

        self::assertSame('{"data":"Data deleted"}', $output);
    }

    public function testItCanNotDeleteWithoutAnId(): void
    {
        $database = $this->database;
        $_POST['action'] = 'delete';

        ob_start();
        require_once __DIR__ . '/../public/api/delete/index.php';
        $output = ob_get_contents();
        if (! $output) {
            hasDeleteAnId();
            $output = ob_get_contents();
        }

        ob_get_clean();

        self::assertNotFalse($output, 'Unable to test output of api/delete/index.php');

        self::assertSame('{"error":"No user id supplied"}', $output);
    }

    public function testItCanNotDeleteWithoutAnAction(): void
    {
        $database = $this->database;
        $_POST['id'] = 1;

        ob_start();
        require_once __DIR__ . '/../public/api/delete/index.php';
        $output = ob_get_contents();
        if (! $output) {
            hasDeleteAnId();
            $output = ob_get_contents();
        }

        ob_get_clean();

        self::assertNotFalse($output, 'Unable to test output of api/delete/index.php');

        self::assertSame('{"error":"Delete action not set"}', $output);
    }
}
