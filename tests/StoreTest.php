<?php

declare(strict_types=1);

namespace App\Tests;

use PHPUnit\Framework\Error;
use PHPUnit\Framework\TestCase;

class StoreTest extends TestCase
{
    public function tearDown(): void
    {
        parent::tearDown();
        foreach ($_POST as $key => $post) {
            unset($_POST[$key]);
        }
    }

    public function testStore(): void
    {
        putenv('APP_ENV=TESTING');

        $_POST['action'] = 'insert';
        $_POST['first_name'] = 'George';
        $_POST['last_name'] = 'Evans';

        ob_start();
        require __DIR__ . '/../public/api/store/index.php';
        $output = ob_get_clean();

        if ($output === false) {
            throw new Error('Unable to test output of api/store/index.php');
        }

        self::assertSame('<p>Data Inserted...</p>', $output);
    }
}
