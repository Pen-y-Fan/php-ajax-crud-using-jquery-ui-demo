<?php

declare(strict_types=1);

//action.php

//include_once(__DIR__ . '/../database_connection.php');
include_once(__DIR__ . '/../vendor/autoload.php');

function store(PDO $connect): void
{
    $query = /** @lang MySQL|SQLite */
        "
		INSERT INTO tbl_sample (first_name, last_name)
		VALUES ('" . $_POST['first_name'] . "', '" . $_POST['last_name'] . "')
		";
    /** @var PDO $connect */
    $statement = $connect->prepare($query);
    $statement->execute();
    echo '<p>Data Inserted...</p>';
}

function show(PDO $connect): void
{
    $query = /** @lang MySQL|SQLite */
        "SELECT * FROM tbl_sample WHERE id = '" . $_POST['id'] . "'";
    $statement = $connect->query($query);
    $result = [];
    if ($statement !== false) {
        $result = $statement->fetchAll();
    }
    if ($result === false) {
        $result = [];
    }
    $output = [];
    foreach ($result as $row) {
        $output['first_name'] = $row['first_name'];
        $output['last_name'] = $row['last_name'];
    }
    echo json_encode($output);
}

function update(PDO $connect): void
{
    $query = /** @lang MySQL|SQLite */
        "
		UPDATE tbl_sample
		SET first_name = '" . $_POST['first_name'] . "',
		last_name = '" . $_POST['last_name'] . "'
		WHERE id = '" . $_POST['hidden_id'] . "'
		";
    $statement = $connect->prepare($query);
    $statement->execute();
    echo '<p>Data Updated</p>';
}

function delete(PDO $connect): void
{
    $query = /** @lang MySQL|SQLite */
        "DELETE FROM tbl_sample WHERE id = '" . $_POST['id'] . "'";
    $statement = $connect->prepare($query);
    $statement->execute();
    echo '<p>Data Deleted</p>';
}

if (isset($_POST['action'])) {
    if ($_POST['action'] === 'insert') {
        /** @var PDO $connect */
//        store($connect);
        include_once(__DIR__ . '/api/store/index.php');
    }
    if ($_POST['action'] === 'fetch_single') {
        /** @var PDO $connect */
//        show($connect);
        include_once(__DIR__ . '/api/show/index.php');
    }
    if ($_POST['action'] === 'update') {
        /** @var PDO $connect */
//        update($connect);
        include_once(__DIR__ . '/api/update/index.php');
    }
    if ($_POST['action'] === 'delete') {
        /** @var PDO $connect */
        // delete($connect);
        include_once(__DIR__ . '/api/delete/index.php');
    }
}
