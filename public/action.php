<?php

declare(strict_types=1);

//action.php

include_once(__DIR__ . '/../database_connection.php');

function insertData(PDO $connect): void
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
//    return array($query, $statement);
}

if (isset($_POST['action'])) {
    if ($_POST['action'] === 'insert') {
        /** @var PDO $connect */
        insertData($connect);
    }
    if ($_POST['action'] === 'fetch_single') {
        $query = "
		SELECT * FROM tbl_sample WHERE id = '" . $_POST['id'] . "'
		";
        $statement = $connect->prepare($query);
        $statement->execute();
        $result = $statement->fetchAll();
        $output = [];
        foreach ($result as $row) {
            $output['first_name'] = $row['first_name'];
            $output['last_name'] = $row['last_name'];
        }
        echo json_encode($output);
    }
    if ($_POST['action'] === 'update') {
        $query = "
		UPDATE tbl_sample
		SET first_name = '" . $_POST['first_name'] . "',
		last_name = '" . $_POST['last_name'] . "'
		WHERE id = '" . $_POST['hidden_id'] . "'
		";
        $statement = $connect->prepare($query);
        $statement->execute();
        echo '<p>Data Updated</p>';
    }
    if ($_POST['action'] === 'delete') {
        $query = "DELETE FROM tbl_sample WHERE id = '" . $_POST['id'] . "'";
        $statement = $connect->prepare($query);
        $statement->execute();
        echo '<p>Data Deleted</p>';
    }
}
