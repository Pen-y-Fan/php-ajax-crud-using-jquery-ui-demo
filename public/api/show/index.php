<?php

declare(strict_types=1);

//action.php

include_once(__DIR__ . '/../../../vendor/autoload.php');

use App\controller\PeopleController;
use App\Tests\CreateSQLiteTable;

if (! isset($_POST['action'])) {
    echo "POST 'action' not set, should be 'fetch_single'";
    exit;
}

if ($_POST['action'] !== 'fetch_single') {
    echo "Action 'fetch_single' not set";
    exit;
}


if (getenv('APP_ENV') && getenv('APP_ENV') === 'TESTING') {
    $createSQLiteTable = new CreateSQLiteTable();
    $database = $createSQLiteTable->createSQLiteTableWithData();
    $people = new PeopleController($database);
} else {
    $people = new PeopleController();
}

$result = $people->show((int) $_POST['id']);

echo json_encode($result[0]);
