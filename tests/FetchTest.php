<?php
declare(strict_types=1);

namespace App\Tests;

use PHPUnit\Framework\Error;
use PHPUnit\Framework\TestCase;

class FetchTest extends TestCase
{
    public function tearDown(): void
    {
        parent::tearDown();
        foreach ($_POST as $key => $post) {
            unset($_POST[$key]);
        }
    }

    public function testFetchOutputsTheTableOfUsers(): void
    {
        putenv('APP_ENV=TESTING');

        ob_start();
        require_once __DIR__ . '/../public/api/index.php';
        $output = ob_get_contents();
        ob_end_clean();

        if ($output === false) {
            throw new Error('Unable to test output of api/index.php');
        }

        self::assertStringContainsString('<td width="40%">Fred</td>', $output);
        self::assertStringContainsString('<td width="40%">Bloggs</td>', $output);
        self::assertStringContainsString('<td width="40%">David</td>', $output);
        self::assertStringContainsString('<td width="40%">Williams</td>', $output);
        self::assertStringContainsString('<td width="40%">John</td>', $output);
        self::assertStringContainsString('<td width="40%">Smith</td>', $output);
        self::assertStringContainsString('id="3">Delete</button>', $output);
        self::assertStringContainsString('id="3">Edit</button>', $output);
        self::assertStringContainsString('class="btn btn-primary btn-xs edit"', $output);
        self::assertStringContainsString('class="btn btn-danger btn-xs delete"', $output);
    }

    public function testShow(): void
    {
        putenv('APP_ENV=TESTING');

        $_POST['action'] = 'fetch_single';
        $_POST['id'] = 1;


        ob_start();
        require_once __DIR__ . '/../public/api/show/index.php';
        $output = ob_get_contents();
        ob_end_clean();

        self::assertNotFalse($output, 'Unable to test output of api/show/index.php');

        self::assertSame('{"id":"1","first_name":"Fred","last_name":"Bloggs"}', $output);
    }
}

