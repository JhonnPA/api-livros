<?php

header("Content-Type: application/json; charset=UTF-8");

header("X-Content-Type-Options: nosniff"); 
header("X-Frame-Options: DENY");           

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uriSegments = explode('/', trim($uri, '/'));

if (!isset($uriSegments[0]) || $uriSegments[0] !== 'api' || !isset($uriSegments[1]) || $uriSegments[1] !== 'books') {
    http_response_code(404);
    echo json_encode(["error" => "Endpoint não encontrado"]);
    exit();
}

$idParam = $uriSegments[2] ?? null;

switch ($method) {
    case 'GET':
        if ($idParam !== null) {
            echo json_encode([
                "message" => "Buscando livro especifico",
                "id_solicitado" => $idParam
            ]);
        } else {
            echo json_encode([
                "message" => "Listando todos os livros"
            ]);
        }
        break;

    case 'POST':
        echo json_encode([
            "message" => "Rota de criacao de livro"
        ]);
        break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Metodo nao permitido"]);
        break;
}