<?php

include(__DIR__ . '/database_settings.php');

// Test connection to the database
try {   // PDO Connection string
    $connect = new PDO("mysql:host=$dbHost;dbname=$dbName", $dbUser, $dbPassword, $dbOptions);
    $connect->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die("Error unable to connect: <br/>" . $e->getMessage());
}
