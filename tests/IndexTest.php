<?php

declare(strict_types=1);

namespace App\Tests;

use PHPUnit\Framework\Error;
use PHPUnit\Framework\TestCase;

class IndexTest extends TestCase
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
        $output = ob_get_clean();

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
}
