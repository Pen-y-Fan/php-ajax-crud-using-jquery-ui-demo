<?php

declare(strict_types=1);

//action.php

include_once(__DIR__ . '/../../../vendor/autoload.php');

//include_once(__DIR__ . '/../../../database_connection.php');

use App\controller\PeopleController;
use App\Tests\CreateSQLiteTable;

//
//if (!isset($_POST['action'])) {
//    echo "action not set";
//}
//if ($_POST['action'] === 'update') {
//    echo "Action type not set";
//}


if (getenv('APP_ENV') && getenv('APP_ENV') === 'TESTING') {
    $createSQLiteTable = new CreateSQLiteTable();
    $database = $createSQLiteTable->createSQLiteTableWithData();
    $people = new PeopleController($database);
} else {
    $people = new PeopleController();
}

$people->update((int) $_POST['hidden_id'], $_POST['first_name'], $_POST['last_name']);
echo '<p>Data Updated</p>';
