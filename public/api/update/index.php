<?php

declare(strict_types=1);

include_once(__DIR__ . '/../../../vendor/autoload.php');
include_once(__DIR__ . '/../getPeopleController.php');

use App\controller\PeopleController;
use App\database\DatabaseConnection;

function hasUpdateValidPostData(): bool
{
    if (! isset($_POST['hidden_id'], $_POST['first_name'], $_POST['last_name'])) {
        http_response_code(400);
        echo json_encode([
            'error' => "'id', 'first_name' and 'last_name' are required.",
        ]);
        return false;
    }
    return true;
}

function callUpdate(PeopleController $people): void
{
    if ($people->update((int) $_POST['hidden_id'], $_POST['first_name'], $_POST['last_name'])) {
        echo json_encode([
            'data' => 'Data updated...',
        ]);
    } else {
        http_response_code(400);
        echo json_encode([
            'error' => 'There was an error adding the data.',
        ]);
    }
}

if (hasUpdateValidPostData()) {
    /** @var DatabaseConnection $database */
    $people = (isTesting()) ? getPeopleController($database) : getPeopleController();
    callUpdate($people);
}
