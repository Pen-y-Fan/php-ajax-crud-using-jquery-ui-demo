<?php

declare(strict_types=1);

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class UpdateTest extends TestCase
{
    public function tearDown(): void
    {
        parent::tearDown();
        foreach ($_POST as $key => $post) {
            unset($_POST[$key]);
        }
    }

    public function testUpdate(): void
    {
        putenv('APP_ENV=TESTING');

        $_POST['action'] = 'update';
        $_POST['first_name'] = 'Jenny';
        $_POST['last_name'] = 'Jones';
        $_POST['hidden_id'] = 1;

        ob_start();
        require_once __DIR__ . '/../public/api/update/index.php';
        $output = ob_get_clean();

        self::assertNotFalse($output, 'Unable to test output of api/update/index.php');
        self::assertSame('<p>Data Updated</p>', $output);
    }
}
