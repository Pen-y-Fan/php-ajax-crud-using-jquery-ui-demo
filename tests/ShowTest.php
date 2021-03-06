<?php

declare(strict_types=1);

namespace App\Tests;

use App\database\DatabaseConnection;
use PHPUnit\Framework\TestCase;

class ShowTest extends TestCase
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
        foreach ($_GET as $key => $get) {
            unset($_GET[$key]);
        }
    }

    public function testItCanShowAValidRecordById(): void
    {
        $database = $this->database;

        $_GET['id'] = 1;

        ob_start();
        require __DIR__ . '/../public/api/show/index.php';
        $output = ob_get_clean();

        self::assertNotFalse($output, 'Unable to test output of api/index.php');

        self::assertSame('[{"id":"1","first_name":"Fred","last_name":"Bloggs"}]', $output);
    }

    public function testItCanNotShowWithoutAnId(): void
    {
        $database = $this->database;
        ob_start();
        require_once __DIR__ . '/../public/api/show/index.php';
        $output = ob_get_contents();
        if (! $output) {
            hasShowAnId();
            $output = ob_get_contents();
        }

        ob_get_clean();

        self::assertNotFalse($output, 'Unable to test output of api/show/index.php');

        self::assertSame('{"error":"No user id supplied"}', $output);
    }
}
