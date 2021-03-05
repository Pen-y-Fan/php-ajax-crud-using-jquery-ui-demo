<?php

declare(strict_types=1);

include_once(__DIR__ . '/../../../vendor/autoload.php');

use App\controller\PeopleController;
use App\Tests\CreateSQLiteTable;

if (! isset($_GET['id'])) {
    http_response_code(400);
    echo json_encode([
        'error' => "'id' required",
    ]);
} else {
    if (getenv('APP_ENV') === 'TESTING') {
        $createSQLiteTable = new CreateSQLiteTable();
        $database = $createSQLiteTable->createSQLiteTableWithData();
        $people = new PeopleController($database);
    } else {
        $people = new PeopleController();
    }

    $result = $people->show((int) $_GET['id']);

    if ($result) {
        echo json_encode($result);
    } else {
        http_response_code(400);
        echo json_encode([
            'error' => 'No user with id ' . $_GET['id'],
        ]);
    }
}
