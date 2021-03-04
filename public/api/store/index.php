<?php

declare(strict_types=1);

//action.php

include_once(__DIR__ . '/../../../vendor/autoload.php');

use App\controller\PeopleController;
use App\Tests\CreateSQLiteTable;

if (! isset($_POST['action'])) {
    echo "POST 'action' not set, should be 'insert'";
    exit;
}

if ($_POST['action'] !== 'insert') {
    echo "Action 'insert' not set";
    exit;
}


if (getenv('APP_ENV') && getenv('APP_ENV') === 'TESTING') {
    $createSQLiteTable = new CreateSQLiteTable();
    $database = $createSQLiteTable->createSQLiteTableWithData();
    $people = new PeopleController($database);
} else {
    $people = new PeopleController();
}

$people->store($_POST['first_name'], $_POST['last_name']);
echo '<p>Data Inserted...</p>';
