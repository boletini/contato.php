<?php

class Contact {
    private $conn;
    private $table = 'contatos';

    public $id;
    public $nome;
    public $email;
    public $telefone;
    public $mensagem;
    public $data_criacao;

    public function __construct(PDO $db) {
        $this->conn = $db;
    }

    public function getAll($limit = null, $offset = 0) {
        $sql = "SELECT * FROM {$this->table} ORDER BY data_criacao DESC";
        if ($limit) {
            $sql .= " LIMIT :limit OFFSET :offset";
        }
        $stmt = $this->conn->prepare($sql);
        if ($limit) {
            $stmt->bindValue(':limit', (int)$limit, PDO::PARAM_INT);
            $stmt->bindValue(':offset', (int)$offset, PDO::PARAM_INT);
        }
        $stmt->execute();
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM {$this->table} WHERE id = :id");
        $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch();
    }

    public function create() {
        $stmt = $this->conn->prepare("INSERT INTO {$this->table} (nome, email, telefone, mensagem) VALUES (:nome, :email, :telefone, :mensagem)");

        $this->nome = trim($this->nome);
        $this->email = filter_var($this->email, FILTER_SANITIZE_EMAIL);
        $this->telefone = trim($this->telefone);
        $this->mensagem = trim($this->mensagem);

        if (empty($this->nome) || empty($this->email)) {
            throw new InvalidArgumentException('Nome e email são obrigatórios');
        }
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Email inválido');
        }

        $stmt->bindValue(':nome', $this->nome);
        $stmt->bindValue(':email', $this->email);
        $stmt->bindValue(':telefone', $this->telefone ?: null);
        $stmt->bindValue(':mensagem', $this->mensagem ?: null);

        $stmt->execute();
        return $this->conn->lastInsertId();
    }

    public function update() {
        $stmt = $this->conn->prepare("UPDATE {$this->table} SET nome = :nome, email = :email, telefone = :telefone, mensagem = :mensagem WHERE id = :id");

        $this->nome = trim($this->nome);
        $this->email = filter_var($this->email, FILTER_SANITIZE_EMAIL);
        $this->telefone = trim($this->telefone);
        $this->mensagem = trim($this->mensagem);

        if (empty($this->nome) || empty($this->email)) {
            throw new InvalidArgumentException('Nome e email são obrigatórios');
        }
        if (!filter_var($this->email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException('Email inválido');
        }

        $stmt->bindValue(':nome', $this->nome);
        $stmt->bindValue(':email', $this->email);
        $stmt->bindValue(':telefone', $this->telefone ?: null);
        $stmt->bindValue(':mensagem', $this->mensagem ?: null);
        $stmt->bindValue(':id', (int)$this->id, PDO::PARAM_INT);

        return $stmt->execute();
    }

    public function delete($id) {
        $stmt = $this->conn->prepare("DELETE FROM {$this->table} WHERE id = :id");
        $stmt->bindValue(':id', (int)$id, PDO::PARAM_INT);
        return $stmt->execute();
    }
}
