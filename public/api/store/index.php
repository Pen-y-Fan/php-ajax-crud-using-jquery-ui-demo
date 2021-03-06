<?php

declare(strict_types=1);

include_once(__DIR__ . '/../../../vendor/autoload.php');
include_once(__DIR__ . '/../getPeopleController.php');

use App\controller\PeopleController;
use App\database\DatabaseConnection;

function hasStoreValidPostData(): bool
{
    if (! isset($_POST['first_name'], $_POST['last_name'])) {
        http_response_code(400);
        echo json_encode([
            'error' => "'first_name' and 'last_name' are required.",
        ]);
        return false;
    }
    return true;
}

function callStore(PeopleController $people): void
{
    if ($people->store($_POST['first_name'], $_POST['last_name'])) {
        echo json_encode([
            'data' => 'Data inserted...',
        ]);
    } else {
        http_response_code(400);
        echo json_encode([
            'error' => 'There was an error inserting the data...',
        ]);
    }
}

if (hasStoreValidPostData()) {
    /** @var DatabaseConnection $database */
    $people = (isTesting()) ? getPeopleController($database) : getPeopleController();
    callStore($people);
}
