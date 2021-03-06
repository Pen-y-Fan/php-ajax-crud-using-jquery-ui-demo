<?php

declare(strict_types=1);

include_once(__DIR__ . '/../../vendor/autoload.php');

use App\controller\PeopleController;
use App\database\DatabaseConnection;

function isTesting(): bool
{
    return getenv('APP_ENV') === 'TESTING';
}

//function getDatabase() use ($database): PeopleController
function getPeopleController(?DatabaseConnection $database = null): PeopleController
{
    /** @var DatabaseConnection $database */
    return isTesting() ? new PeopleController($database) : new PeopleController();
}
