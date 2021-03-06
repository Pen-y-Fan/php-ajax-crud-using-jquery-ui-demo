<?php

declare(strict_types=1);

include_once(__DIR__ . '/../../../vendor/autoload.php');
include_once(__DIR__ . '/../getPeopleController.php');

use App\controller\PeopleController;
use App\database\DatabaseConnection;

function hasDeleteAnId(): bool
{
    if (! isset($_POST['id'])) {
        http_response_code(400);
        echo json_encode([
            'error' => 'No user id supplied',
        ]);
        return false;
    }
    if (! isset($_POST['action']) || $_POST['action'] !== 'delete') {
        http_response_code(400);
        echo json_encode([
            'error' => 'Delete action not set',
        ]);
        return false;
    }
    return true;
}

function callDelete(PeopleController $people): void
{
    if ($people->delete((int) $_POST['id'])) {
        echo json_encode([
            'data' => 'Data deleted',
        ]);
    } else {
        http_response_code(400);
        echo json_encode([
            'error' => 'There was a problem deleting the data!',
        ]);
    }
}

if (hasDeleteAnId()) {
    /** @var DatabaseConnection $database */
//    $people = (isset($database)) ? getDatabase($database) : getDatabase();
    $people = (isTesting()) ? getPeopleController($database) : getPeopleController();
    callDelete($people);
}
