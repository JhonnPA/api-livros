<?php
require_once __DIR__ . "/../src/Validators/BookValidator.php";

header("Content-Type: application/json; charset=UTF-8");

header("X-Content-Type-Options: nosniff"); 
header("X-Frame-Options: DENY");           

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

$uriSegments = explode('/', trim($uri, '/'));

if (!isset($uriSegments[0]) || $uriSegments[0] !== 'api' || !isset($uriSegments[1]) || $uriSegments[1] !== 'books') {
    http_response_code(404);
    echo json_encode(["error" => "Endpoint nao encontrado"]);
    exit();
}

$idParam = $uriSegments[2] ?? null;

switch ($method) {
    case 'GET':
        if ($idParam !== null) {
            $id = BookValidator::validateId($idParam);

            echo json_encode([
                "message" => "ID validado com sucesso!",
                "id_validado" => $id
            ]);
        } else {
            echo json_encode(["message" => "Listagem geral (mock)"]);
        }
        break;

    case 'POST':
        $rawInput = file_get_contents("php://input");
        $data = json_decode($rawInput, true);

        $validatedData = BookValidator::validatePayload($data);

        echo json_encode([
            "message" => "Payload validado e sanitizado com sucesso!",
            "data" => $validatedData
        ]);
        break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Metodo nao permitido"]);
        break;
}