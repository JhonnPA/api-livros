# API RESTful de Gerenciamento de Livros (PHP + SQLite)

API RESTful desenvolvida em PHP nativo e SQLite, focada em segurança, boas práticas de arquitetura (Front Controller / MVC) e tratamento rigoroso de dados sem o uso de frameworks externos.

---

## 🛠️ Tecnologias Utilizadas

- **Linguagem:** PHP 8.2+
- **Banco de Dados:** SQLite
- **Persistência:** PDO (PHP Data Objects) com Prepared Statements
- **Controle de Versão:** Git & GitHub

---

## 🔒 Camadas de Segurança e Boas Práticas

- **SQL Injection Prevention:** Todas as consultas no banco utilizam *Prepared Statements* com binding explícito de parâmetros (`PDO::PARAM_INT`, `PDO::PARAM_STR`).
- **XSS (Cross-Site Scripting):** Sanitização de dados de entrada com `htmlspecialchars()` na camada de validação.
- **Mass Assignment Protection:** Whitelisting estrito dos campos recebidos nos payloads JSON (`title`, `author`, `price`), descartando atributos extras não autorizados.
- **HTTP Security Headers:** Emissão de cabeçalhos de segurança globais (`X-Content-Type-Options: nosniff`, `X-Frame-Options: DENY`).
- **Erros Estruturados:** Trata exceções do PDO sem expor o *stack trace* para o cliente HTTP em ambiente de produção (retornando Status Code 500 padronizado).

---

## 📍 Endpoints da API

| Método | Endpoint | Descrição | Status Sucesso |
| :--- | :--- | :--- | :--- |
| **GET** | `/api/books` | Lista todos os livros cadastrados | `200 OK` |
| **GET** | `/api/books/{id}` | Retorna detalhes de um livro específico | `200 OK` |
| **POST** | `/api/books` | Cadastra um novo livro | `201 Created` |
| **PUT** | `/api/books/{id}` | Atualiza os dados de um livro existente | `200 OK` |
| **DELETE** | `/api/books/{id}` | Remove um livro do banco | `200 OK` |

---

## 🚀 Como Executar o Projeto

1. **Clonar o repositório:**
   ```bash
   git clone [https://github.com/JhonnPA/api-livros.git](https://github.com/JhonnPA/api-livros.git)
   cd api-livros

2. Inicializar o banco de dados SQLite com a tabela:
    ```bash
    sqlite3 database.sqlite < schema.sql

3. Iniciar o servidor embutido do PHP:
    ```bash
        php -S localhost:8000 -t public

4. Exemplo de Requisição (POST):
    ```bash
    curl -X POST http://localhost:8000/api/books \
        -H "Content-Type: application/json" \
        -d '{"title": "Clean Code", "author": "Robert C. Martin", "price": 89.90}'