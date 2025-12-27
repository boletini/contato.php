# Contatos API (PHP 7+)

Projeto simples de backend em PHP 7+ com CRUD para gerenciamento de contatos e uma tela de exemplo (front-end) que consome a API.

## Estrutura do projeto

```
contatos-api/
├── api.php                 # Roteador da API
├── contatos_dump.sql       # Dump do banco (cria DB, tabela e dados de exemplo)
├── config/
│   └── Database.php        # Configuração de conexão PDO
├── controllers/
│   └── ContactController.php
├── models/
│   └── Contact.php
├── public/
│   └── index.html         # Interface de exemplo (CRUD)
└── README.md
```

## Requisitos

- PHP 7.0+
- MySQL (ou MariaDB)
- Servidor web (XAMPP, WAMP, etc.)

## Instalação e execução (XAMPP)

1. Copie a pasta `contatos-api` para o diretório `htdocs` do XAMPP (ex.: `C:/xampp/htdocs/contatos-api`).

2. Abra o painel do XAMPP e inicie o Apache e o MySQL.

3. Importe o arquivo SQL (`contatos_dump.sql`) no phpMyAdmin:
   - Acesse `http://localhost/phpmyadmin`
   - Clique em "Importar" e selecione `contatos_dump.sql`.
   - O script criará a base `contatos_db` e a tabela `contatos` com dados de exemplo.

4. Ajuste credenciais se necessário:
   - Abra `config/Database.php` e altere `host`, `db_name`, `username` e `password` conforme seu ambiente.
   - Valores padrão são compatíveis com XAMPP (`root` e senha em branco).

5. Acesse a interface de exemplo:
   - Abra no navegador: `http://localhost/contatos-api/public/index.html`

6. Testar API diretamente (curl / Postman):
   - Listar: `GET http://localhost/contatos-api/api.php/contatos` ou `GET http://localhost/contatos-api/api/contatos` (ambos funcionam)
   - Obter: `GET http://localhost/contatos-api/api.php/contatos/1`
   - Criar (POST JSON): `POST http://localhost/contatos-api/api.php/contatos`
   - Atualizar (PUT JSON): `PUT http://localhost/contatos-api/api.php/contatos/1`
   - Deletar: `DELETE http://localhost/contatos-api/api.php/contatos/1`

Exemplos com curl:

```bash
curl "http://localhost/contatos-api/api/contatos"

curl -X POST "http://localhost/contatos-api/api/contatos" -H "Content-Type: application/json" -d '{"nome":"Teste","email":"teste@example.com"}'

curl -X PUT "http://localhost/contatos-api/api/contatos/1" -H "Content-Type: application/json" -d '{"nome":"Novo Nome","email":"novo@example.com"}'

curl -X DELETE "http://localhost/contatos-api/api/contatos/1"
```

## Autenticação (API Key)

- A API exige uma `API Key` para operações que modificam dados (POST, PUT, DELETE).
- Por padrão a chave está definida em `config/config.php` como `MINHA_API_KEY_TESTE_12345`. Troque por uma chave segura em produção.
- Para usar no Postman, importe `postman_contatos_api.json` e defina a variável `apiKey`.

## Rodando localmente com servidor PHP embutido

Você pode rodar a API sem Apache usando o servidor embutido do PHP (útil para testes):

Linux/WSL/macOS:
```bash
cd C:/xampp/htdocs/contatos-api
./run_local.sh
```

Windows (cmd.exe):
```bat
cd C:\xampp\htdocs\contatos-api
run_local.bat
