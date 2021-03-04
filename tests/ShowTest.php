<?php
declare(strict_types=1);

namespace App\Tests;

use PHPUnit\Framework\TestCase;

class ShowTest extends TestCase
{
    public function tearDown(): void
    {
        parent::tearDown();
        foreach ($_POST as $key => $post) {
            unset($_POST[$key]);
        }
    }

    public function testShow(): void
    {
        putenv('APP_ENV=TESTING');
        foreach ($_POST as $key => $post) {
            unset($_POST[$key]);
        }
        $_GET['id'] = 1;

        ob_start();
        require __DIR__ . '/../public/api/show/index.php';
        $output = ob_get_clean();

        self::assertNotFalse($output, 'Unable to test output of api/index.php');

        self::assertSame('[{"id":"1","first_name":"Fred","last_name":"Bloggs"}]', $output);
    }
}

