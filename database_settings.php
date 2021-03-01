<?php
declare(strict_types=1);

$ini_array = parse_ini_file(__DIR__ . "/.env");

if ($ini_array !== false) {
    foreach ($ini_array as $key => $value) {
        putenv($key . '=' . $value);
    }
}

if (getenv('DATABASE_SERVER') === false) {
    $dbHost = 'database';  // database for Docker (set in docker-compose)
//       'localhost';  // localhost on LINUX
//        '127.0.0.1';    // 127.0.0.1 on Windows
} else {
    $dbHost = getenv('DATABASE_SERVER');
}

if (getenv('DATABASE_NAME') === false) {
    $dbName = 'ajax_crud';    // Name of your database
} else {
    $dbName = getenv('DATABASE_NAME');
}

if (getenv('DATABASE_USERNAME') === false) {
    $dbUser = 'root';    // Your DB user name
} else {
    $dbUser = getenv('DATABASE_USERNAME');
}

if (getenv('DATABASE_PASSWORD') === false) {
    $dbPassword = '';    // Your DB password
} else {
    $dbPassword = getenv('DATABASE_PASSWORD');
}


$dbCharset = "utf8mb4";

$dbOptions = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
];

if (getenv('APP_ENV') && getenv('APP_ENV') !== 'TESTING') {
    $dbOptions += [
        PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
    ];
}
