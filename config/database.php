<?php

function getconnection(): PDO {
    $databasefile = __DIR__ . "/../" . (getenv('DB_PATH') ?: 'database.sqlite');
    
    $dsn = "sqlite:" . $databasefile;
    
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ];

    return new PDO($dsn, null, null, $options);
}