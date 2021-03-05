<?php

declare(strict_types=1);

//fetch.php

include_once(__DIR__ . '/../../vendor/autoload.php');

use App\controller\PeopleController;
use App\Tests\CreateSQLiteTable;

if (getenv('APP_ENV') === 'TESTING') {
    $createSQLiteTable = new CreateSQLiteTable();
    $database = $createSQLiteTable->createSQLiteTableWithData();
    $people = new PeopleController($database);
} else {
    $people = new PeopleController();
}

$result = $people->index();

if ($result) {
    echo json_encode($result);
} else {
    http_response_code(404);
    echo json_encode([
        'error' => 'Data not found',
    ]);
}
