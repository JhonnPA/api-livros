<?php

class BookValidator {

    public static function validateId(mixed $id): int {
        $filteredId = filter_var($id, FILTER_VALIDATE_INT, [
            "options" => ["min_range" => 1]
        ]);

        if ($filteredId === false) {
            http_response_code(400);
            echo json_encode(["error" => "O ID informado e invalido. Deve ser um numero inteiro positivo."]);
            exit();
        }

        return (int) $filteredId;
    }

    public static function validatePayload(mixed $data): array {
        if (!is_array($data) || empty($data)) {
            http_response_code(400);
            echo json_encode(["error" => "Payload JSON ausente, vazio ou malformado."]);
            exit();
        }

        $errors = [];

        if (empty($data['title']) || !is_string($data['title'])) {
            $errors['title'] = "O campo 'title' e obrigatorio e deve ser um texto.";
        } elseif (mb_strlen(trim($data['title'])) > 150) {
            $errors['title'] = "O campo 'title' nao pode exceder 150 caracteres.";
        }

        if (empty($data['author']) || !is_string($data['author'])) {
            $errors['author'] = "O campo 'author' e obrigatorio e deve ser um texto.";
        } elseif (mb_strlen(trim($data['author'])) > 100) {
            $errors['author'] = "O campo 'author' nao pode exceder 100 caracteres.";
        }

        if (!isset($data['price']) || !is_numeric($data['price']) || $data['price'] < 0) {
            $errors['price'] = "O campo 'price' e obrigatorio e deve ser um numero maior ou igual a zero.";
        }

        if (!empty($errors)) {
            http_response_code(422);
            echo json_encode(["errors" => $errors]);
            exit();
        }

        return [
            'title'  => htmlspecialchars(trim($data['title']), ENT_QUOTES, 'UTF-8'),
            'author' => htmlspecialchars(trim($data['author']), ENT_QUOTES, 'UTF-8'),
            'price'  => (float) $data['price']
        ];
    }
}