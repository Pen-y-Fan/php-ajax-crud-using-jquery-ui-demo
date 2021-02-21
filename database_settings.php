<?php

$dbHost = 'database';  // database for Docker (set in docker-compose)
//       'localhost';  // localhost on LINUX
//        '127.0.0.1';    // 127.0.0.1 on Windows
$dbName = 'ajax_crud';    // Name of your database
$dbUser = 'root';    // Your DB user name
$dbPassword = '';    // Your DB password

$dbCharset = "utf8mb4";
$dbOptions = [
    PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
    PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    PDO::ATTR_EMULATE_PREPARES => false,
    PDO::MYSQL_ATTR_INIT_COMMAND => 'SET NAMES utf8',
];
