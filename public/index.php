<?php

require_once __DIR__ . "/../config/database.php";
require_once __DIR__ . "/../src/Validators/BookValidator.php";
require_once __DIR__ . "/../src/Models/Book.php";

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
try {
    $db = getConnection();
    $bookModel = new Book($db);

    switch ($method) {
        case 'GET':
            if ($idParam !== null) {
                $id = BookValidator::validateId($idParam);

                $book = $bookModel->findById($id);

                if (!$book) {
                    http_response_code(404);
                    echo json_encode(["error" => "Livro nao encontrado"]);
                    exit();
                }

                echo json_encode(["data" => $book]);
            } else {
                echo json_encode(["data" => $bookModel->findAll()]);
            }
            break;

        case 'POST':
            $rawInput = file_get_contents("php://input");
            $data = json_decode($rawInput, true);

            $validatedData = BookValidator::validatePayload($data);

            $newId = $bookModel->create($validatedData);

            http_response_code(201); 
                echo json_encode([
                    "message" => "Livro criado com sucesso",
                    "id" => $newId
                ]);
                break;
        
        case 'PUT':
            if ($idParam === null) {
                http_response_code(400);
                echo json_encode(["error" => "ID do livro nao informado na URL."]);
                exit();
            }

            $id = BookValidator::validateId($idParam);

            if (!$bookModel->findById($id)) {
                http_response_code(404);
                echo json_encode(["error" => "Livro nao encontrado para atualizacao."]);
                exit();
            }

            $rawInput = file_get_contents("php://input");
            $data = json_decode($rawInput, true);

            $validatedData = BookValidator::validatePayload($data);
            $bookModel->update($id, $validatedData);

            echo json_encode(["message" => "Livro atualizado com sucesso"]);
            break;
        
        case 'DELETE':
            if ($idParam === null) {
                http_response_code(400);
                echo json_encode(["error" => "ID do livro nao informado na URL."]);
                exit();
            }

            $id = BookValidator::validateId($idParam);

            if (!$bookModel->findById($id)) {
                http_response_code(404);
                echo json_encode(["error" => "Livro nao encontrado para remocao."]);
                exit();
            }

            $bookModel->delete($id);

            http_response_code(200);
            echo json_encode(["message" => "Livro removido com sucesso"]);
            break;

    default:
        http_response_code(405);
        echo json_encode(["error" => "Metodo nao permitido"]);
        break;
    }
    
    

} catch (\PDOException $e) {
    error_log("Database Error: " . $e->getMessage());
http_response_code(500);

    echo json_encode(["error" => "Erro interno no servidor de banco de dados."]);
} catch (\Throwable $e) {
    error_log("General Error: " . $e->getMessage());
    
    http_response_code(500);
    echo json_encode(["error" => "Ocorreu um erro inesperado."]);
}