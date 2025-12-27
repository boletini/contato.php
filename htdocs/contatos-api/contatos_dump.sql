-- Dump do banco para Contatos API

CREATE DATABASE IF NOT EXISTS contatos_db DEFAULT CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
USE contatos_db;

CREATE TABLE IF NOT EXISTS contatos (
  id INT AUTO_INCREMENT PRIMARY KEY,
  nome VARCHAR(255) NOT NULL,
  email VARCHAR(255) NOT NULL,
  telefone VARCHAR(50),
  mensagem TEXT,
  data_criacao TIMESTAMP DEFAULT CURRENT_TIMESTAMP
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

-- Dados de exemplo
INSERT INTO contatos (nome, email, telefone, mensagem) VALUES
('João Silva', 'joao@example.com', '11999999999', 'Olá, gostaria de mais informações'),
('Maria Santos', 'maria@example.com', '21988776655', 'Solicito orçamento'),
('Pedro Oliveira', 'pedro@example.com', '85988776655', 'Contato para teste');
