<?php

declare(strict_types=1);

include_once(__DIR__ . '/../../../vendor/autoload.php');
include_once(__DIR__ . '/../getPeopleController.php');

use App\controller\PeopleController;
use App\database\DatabaseConnection;

function hasShowAnId(): bool
{
    if (! isset($_GET['id'])) {
        http_response_code(400);
        echo json_encode([
            'error' => 'No user id supplied',
        ]);
        return false;
    }
    return true;
}

if (hasShowAnId()) {
    /** @var DatabaseConnection $database */
    $people = (isTesting()) ? getPeopleController($database) : getPeopleController();
    callShow($people);
}

function callShow(PeopleController $people): void
{
    if ($result = $people->show((int) $_GET['id'])) {
        echo json_encode($result);
    } else {
        http_response_code(400);
        echo json_encode([
            'error' => 'No user with id ' . $_GET['id'],
        ]);
    }
}
