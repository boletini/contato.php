<?php

require_once __DIR__ . '/../models/Contact.php';

class ContactController {
    private $db;
    private $model;

    public function __construct(PDO $db) {
        $this->db = $db;
        $this->model = new Contact($db);
    }

    public function index($params = []) {
        $limit = isset($params['limit']) ? (int)$params['limit'] : null;
        $offset = isset($params['offset']) ? (int)$params['offset'] : 0;
        $data = $this->model->getAll($limit, $offset);
        return $this->jsonResponse(200, 'Lista de contatos', $data);
    }

    public function show($id) {
        $contact = $this->model->getById($id);
        if (!$contact) return $this->jsonResponse(404, 'Contato não encontrado');
        return $this->jsonResponse(200, 'Contato encontrado', $contact);
    }

    public function store($input) {
        try {
            $this->model->nome = $input['nome'] ?? '';
            $this->model->email = $input['email'] ?? '';
            $this->model->telefone = $input['telefone'] ?? '';
            $this->model->mensagem = $input['mensagem'] ?? '';

            $id = $this->model->create();
            return $this->jsonResponse(201, 'Contato criado', ['id' => $id]);
        } catch (InvalidArgumentException $e) {
            return $this->jsonResponse(400, $e->getMessage());
        } catch (Exception $e) {
            return $this->jsonResponse(500, 'Erro ao criar contato: ' . $e->getMessage());
        }
    }

    public function update($id, $input) {
        try {
            $existing = $this->model->getById($id);
            if (!$existing) return $this->jsonResponse(404, 'Contato não encontrado');

            $this->model->id = $id;
            $this->model->nome = $input['nome'] ?? $existing['nome'];
            $this->model->email = $input['email'] ?? $existing['email'];
            $this->model->telefone = $input['telefone'] ?? $existing['telefone'];
            $this->model->mensagem = $input['mensagem'] ?? $existing['mensagem'];

            $this->model->update();
            return $this->jsonResponse(200, 'Contato atualizado');
        } catch (InvalidArgumentException $e) {
            return $this->jsonResponse(400, $e->getMessage());
        } catch (Exception $e) {
            return $this->jsonResponse(500, 'Erro ao atualizar contato: ' . $e->getMessage());
        }
    }

    public function destroy($id) {
        $existing = $this->model->getById($id);
        if (!$existing) return $this->jsonResponse(404, 'Contato não encontrado');
        $this->model->delete($id);
        return $this->jsonResponse(200, 'Contato deletado');
    }

    private function jsonResponse($status, $message, $data = null) {
        http_response_code($status);
        header('Content-Type: application/json; charset=utf-8');
        $res = ['status' => $status, 'mensagem' => $message];
        if ($data !== null) $res['dados'] = $data;
        return json_encode($res, JSON_UNESCAPED_UNICODE);
    }
}
