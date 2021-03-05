<?php

declare(strict_types=1);

namespace App\Tests;

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

        self::assertNotFalse($output, 'Unable to test output of api/index.php');

        $expected = '[{"id":"1","first_name":"Fred","last_name":"Bloggs"},';
        $expected .= '{"id":"2","first_name":"David","last_name":"Williams"},';
        $expected .= '{"id":"3","first_name":"John","last_name":"Smith"}]';

        self::assertSame($expected, $output);
    }
}
