<?php

declare(strict_types=1);

//action.php

include_once(__DIR__ . '/../../../vendor/autoload.php');

use App\controller\PeopleController;
use App\Tests\CreateSQLiteTable;

if (! isset($_POST['first_name']) || ! isset($_POST['last_name'])) {
    http_response_code(400);
    echo json_encode([
        'error' => "'first_name' and 'last_name' are required.",
    ]);
} else {
    if (getenv('APP_ENV') === 'TESTING') {
        $createSQLiteTable = new CreateSQLiteTable();
        $database = $createSQLiteTable->createSQLiteTableWithData();
        $people = new PeopleController($database);
    } else {
        $people = new PeopleController();
    }

    if ($people->store($_POST['first_name'], $_POST['last_name'])) {
        echo '<p>Data Inserted...</p>';
    } else {
        http_response_code(400);
        echo json_encode([
            'error' => 'There was an error inserting the data...',
        ]);
    }
}
