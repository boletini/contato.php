<?php
// API simples para CRUD de contatos (PHP 7+)

require_once __DIR__ . '/config/Database.php';
require_once __DIR__ . '/controllers/ContactController.php';

// CORS e headers
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
header('Content-Type: application/json; charset=utf-8');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    http_response_code(200);
    exit;
}

// Carregar configuração (API key)
$config = require __DIR__ . '/config/config.php';

$db = (new Database())->connect();
$controller = new ContactController($db);

$method = $_SERVER['REQUEST_METHOD'];
$uri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$script = $_SERVER['SCRIPT_NAME'];

// Normalize path to support both /contatos-api/api.php/contatos and /contatos-api/api/contatos
$base = rtrim(dirname($script), '/\\'); // e.g. /contatos-api
$path = preg_replace('#^' . preg_quote($base) . '#', '', $uri);
$path = preg_replace('#^/api.php#', '', $path);
$path = preg_replace('#^/api#', '', $path);
$path = trim($path, '/');
$parts = $path === '' ? [] : explode('/', $path);

$resource = $parts[0] ?? null;
$id = $parts[1] ?? null;
$input = json_decode(file_get_contents('php://input'), true) ?: [];

// Função auxiliar para checar API key em métodos que modificam dados
function check_api_key($config) {
    $headers = getallheaders();
    $provided = $headers['X-API-KEY'] ?? $headers['x-api-key'] ?? null;
    if (!$provided || $provided !== $config['api_key']) {
        http_response_code(401);
        echo json_encode(['status' => 401, 'mensagem' => 'API key inválida ou ausente']);
        return false;
    }
    return true;
}

try {
    if ($resource === 'contatos' || $resource === null) {
        switch ($method) {
            case 'GET':
                if ($id) echo $controller->show($id);
                else echo $controller->index($_GET);
                break;
            case 'POST':
                // Requer API key
                if (!check_api_key($config)) break;
                echo $controller->store($input);
                break;
            case 'PUT':
                if (!$id) { http_response_code(400); echo json_encode(['status'=>400,'mensagem'=>'ID obrigatório']); break; }
                if (!check_api_key($config)) break;
                echo $controller->update($id, $input);
                break;
            case 'DELETE':
                if (!$id) { http_response_code(400); echo json_encode(['status'=>400,'mensagem'=>'ID obrigatório']); break; }
                if (!check_api_key($config)) break;
                echo $controller->destroy($id);
                break;
            default:
                http_response_code(405);
                echo json_encode(['status'=>405,'mensagem'=>'Método não permitido']);
        }
    } else {
        http_response_code(404);
        echo json_encode(['status'=>404,'mensagem'=>'Rota não encontrada']);
    }
} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['status'=>500,'mensagem'=>'Erro interno: ' . $e->getMessage()]);
}
