<?php
declare(strict_types=1);

include_once(__DIR__ . '/database_settings.php');

if (getenv('APP_ENV') && getenv('APP_ENV') === 'TESTING') {
    if (!isset($connect)) {
        try {   // PDO Connection string for SQLite in memory DB for testing
            $connect = new PDO('sqlite::memory:');
            $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            $connect->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            die("Error unable to connect: <br/>" . $e->getMessage());
        }
    }

} else {
// Test connection to the database
    try {   // PDO Connection string
        $connect = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPassword, $dbOptions);
        $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
        $connect->setAttribute(PDO::ATTR_DEFAULT_FETCH_MODE, PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        die("Error unable to connect: <br/>" . $e->getMessage());
    }
}
