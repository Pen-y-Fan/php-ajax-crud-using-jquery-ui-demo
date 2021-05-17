<?php
declare(strict_types=1);

/**
 * @var string $dbHost
 * @var string $dbName
 * @var string $dbCharset
 * @var string $dbUser
 * @var string $dbPassword
 * @var array<int, int|bool> $dbOptions
 */
include_once __DIR__ . '/database_settings.php';

echo 'Building db' . PHP_EOL;

$sql = "CREATE DATABASE IF NOT EXISTS `$dbName` CHARACTER SET $dbCharset COLLATE utf8mb4_unicode_ci;";

try {
    $connect = new PDO("mysql:host=$dbHost", $dbUser, $dbPassword, $dbOptions);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    $connect->exec(
        $sql
    );
} catch (PDOException $e) {
    echo "DB ERROR: " . $e->getMessage() . PHP_EOL;
    if (isset($connect)) {
        die(print_r($connect->errorInfo(), true));
    }
    die("There was a problem with the connection");
}
$connect = null;

echo "Database $dbHost created OK" . PHP_EOL;
echo 'Creating table tbl_sample' . PHP_EOL;

$createTable_SQL = <<<SQL
CREATE TABLE IF NOT EXISTS tbl_sample
(
    id  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
    last_name  varchar(255),
    first_name varchar(255),
    reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
);
SQL;

/** @var $connect PDO */
include __DIR__ . '/database_connection.php';

try {
    $stmt = $connect->prepare($createTable_SQL);
    if ($stmt !== false) {
        $stmt->execute();
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

echo 'Table tbl_sample created' . PHP_EOL;
echo 'Adding sample data' . PHP_EOL;

$table_SQLinsert = /** @lang MYSQL */
    <<<MYSQL
INSERT INTO tbl_sample (`first_name`, `last_name`)
    VALUES (:firstName, :lastName );
MYSQL;

$firstName = '';
$lastName = '';

try {
    $stmt = $connect->prepare($table_SQLinsert);
    if ($stmt !== false) {
        $stmt->bindParam(':firstName', $firstName, PDO::PARAM_STR, 255);
        $stmt->bindParam(':lastName', $lastName, PDO::PARAM_STR, 255);
    }
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}

$names = [
    [
        'firstName' => 'Fred',
        'lastName'  => 'Bloggs'
    ],
    [
        'firstName' => 'David',
        'lastName'  => 'Williams'
    ],
    [
        'firstName' => 'John',
        'lastName'  => 'Smith'
    ],
];

$i = 0;
foreach ($names as $name) {
    try {
        $firstName = $name['firstName'];
        $lastName = $name['lastName'];

        if (isset($stmt) && $stmt !== false) {
            $stmt->execute();
            $i++;
        }
    } catch (PDOException $e) {
        echo "Error adding name" . PHP_EOL . $e->getMessage();
        print_r($name);
    }
}
// Confirm the number of names added
echo "$i names added to table" . PHP_EOL;

$connect = null;
