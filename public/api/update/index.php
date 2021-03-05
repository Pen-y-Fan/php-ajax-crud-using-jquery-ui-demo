<?php

declare(strict_types=1);

//action.php

include_once(__DIR__ . '/../../../vendor/autoload.php');

use App\controller\PeopleController;
use App\Tests\CreateSQLiteTable;

if (! isset($_POST['hidden_id']) || ! isset($_POST['first_name']) || ! isset($_POST['last_name'])) {
    http_response_code(400);
    echo json_encode([
        'error' => "'id', 'first_name' and 'last_name' are required.",
    ]);
} else {
    if (getenv('APP_ENV') === 'TESTING') {
        $createSQLiteTable = new CreateSQLiteTable();
        $database = $createSQLiteTable->createSQLiteTableWithData();
        $people = new PeopleController($database);
    } else {
        $people = new PeopleController();
    }

    if ($people->update((int) $_POST['hidden_id'], $_POST['first_name'], $_POST['last_name'])) {
        echo '<p>Data Updated</p>';
    } else {
        http_response_code(400);
        echo json_encode([
            'error' => 'There was an error adding the data.',
        ]);
    }
}
