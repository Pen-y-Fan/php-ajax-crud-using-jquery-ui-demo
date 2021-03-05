<?php

declare(strict_types=1);

include_once(__DIR__ . '/../../../vendor/autoload.php');

use App\controller\PeopleController;
use App\Tests\CreateSQLiteTable;

if (! isset($_POST['id'])) {
    http_response_code(400);
    echo json_encode([
        'error' => 'No user id supplied',
    ]);
} elseif (! isset($_POST['action']) || $_POST['action'] !== 'delete') {
    http_response_code(400);
    echo json_encode([
        'error' => 'Delete action not set',
    ]);
} else {
    if (getenv('APP_ENV') === 'TESTING') {
        $createSQLiteTable = new CreateSQLiteTable();
        $database = $createSQLiteTable->createSQLiteTableWithData();
        $people = new PeopleController($database);
    } else {
        $people = new PeopleController();
    }

    if ($people->delete((int) $_POST['id'])) {
        echo '<p>Data Deleted</p>';
    } else {
        http_response_code(400);
        echo json_encode([
            'error' => 'There was a problem deleting the data!',
        ]);
    }
}
