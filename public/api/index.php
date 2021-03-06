<?php

declare(strict_types=1);

include_once(__DIR__ . '/../../vendor/autoload.php');
include_once(__DIR__ . '/getPeopleController.php');

$people = (isset($database)) ? getPeopleController($database) : getPeopleController();
$result = $people->index();

echo json_encode($result);
