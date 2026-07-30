<?php

function getconnection(): pdo {
    // Busca o caminho do banco de forma flexível sem hardcode absoluto
    $databasefile = __DIR__ . "/../" . (getenv('DB_PATH') ?: 'database.sqlite');
    
    $dsn = "sqlite:" . $databasefile;
    
    $options = [
        // Exceções reais em erros de SQL (não engole erros)
        pdo::attr_errmode => pdo::errmode_exception,
        
        // Retorna arrays associativos limpos
        pdo::attr_default_fetch_mode => pdo::fetch_assoc,
        
        // Desativa prepared statements emulados (força o driver a separar query e dados)
        pdo::attr_emulate_prepares => false,
    ];

    return new pdo($dsn, null, null, $options);
}