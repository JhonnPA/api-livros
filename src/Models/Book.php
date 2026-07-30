<?php

class Book {
    private PDO $db;

    public function __construct(PDO $db) {
        $this->db = $db;
    }

    /**
     * Busca todos os livros cadastrados
     */
    public function findAll(): array {
        $stmt = $this->db->query("SELECT id, title, author, price, created_at FROM books ORDER BY id DESC");
        return $stmt->fetchAll();
    }

    /**
     * Busca um livro específico pelo ID
     */
    public function findById(int $id): ?array {
        $stmt = $this->db->prepare("SELECT id, title, author, price, created_at FROM books WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        $stmt->execute();

        $result = $stmt->fetch();
        return $result ?: null;
    }

    /**
     * Cria um novo livro no banco de dados
     */
    public function create(array $data): int {
        $sql = "INSERT INTO books (title, author, price) VALUES (:title, :author, :price)";
        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':title', $data['title'], PDO::PARAM_STR);
        $stmt->bindValue(':author', $data['author'], PDO::PARAM_STR);
        $stmt->bindValue(':price', $data['price']);
        $stmt->execute();

        return (int) $this->db->lastInsertId();
    }

    /**
     * Atualiza um livro existente
     */
    public function update(int $id, array $data): bool {
        $sql = "UPDATE books SET title = :title, author = :author, price = :price WHERE id = :id";
        $stmt = $this->db->prepare($sql);

        $stmt->bindValue(':title', $data['title'], PDO::PARAM_STR);
        $stmt->bindValue(':author', $data['author'], PDO::PARAM_STR);
        $stmt->bindValue(':price', $data['price']);
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    /**
     * Remove um livro do banco
     */
    public function delete(int $id): bool {
        $stmt = $this->db->prepare("DELETE FROM books WHERE id = :id");
        $stmt->bindValue(':id', $id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}