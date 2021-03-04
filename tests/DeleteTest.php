<?php
declare(strict_types=1);

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class DeleteTest extends TestCase
{
    public function tearDown(): void
    {
        parent::tearDown();
        foreach ($_POST as $key => $post) {
            unset($_POST[$key]);
        }
    }

    public function testDelete(): void
    {
        putenv('APP_ENV=TESTING');

        $_POST['action'] = 'delete';
        $_POST['id'] = 1;

        ob_start();
        require_once __DIR__ . '/../public/api/delete/index.php';
        $output = ob_get_contents();
        ob_end_clean();

        self::assertNotFalse($output, 'Unable to test output of api/delete/index.php');

        self::assertSame('<p>Data Deleted</p>', $output);
    }
}

