<?php

header('Content-Type: application/json; charset: utf-8');
include_once( $_SERVER['DOCUMENT_ROOT'] . '/config/cfg.php');
include_once( $_SERVER['DOCUMENT_ROOT'] . '/config/database.php');

class Rinha {

    public function getData($_id) {

        $valid = filter_var($_id, FILTER_VALIDATE_INT);
        if(! $valid) {
            http_response_code(401);
            $response = array(
                'message' => 'Insert valid ID'
            );
        return json_encode($response);
        }

        $data = find('rinhas', $_id);
        http_response_code(200);
        return json_encode($data);
        
    }

    public function getAll() {

        $data = findAll('rinhas');
        http_response_code(200);
        return json_encode($data);
    }

    public function addData($data) {

        $data = json_decode($data);
        $dbres = save('rinhas', $data);
        http_response_code(200);
        $response = array(
            'message' => $dbres
        );
        return json_encode($response);
    }

    public function updateData($data) {

        # Verificar se a requisicao veio do owner

        $data = json_decode($data);
        $dbres = update('rinhas', $data);
        http_response_code(200);
        $response = array(
            'message' => $dbres
        );
        return json_encode($response);
    }

    public function removeData($id) {

        # Verificar se a requisicao veio do owner

        $valid = filter_var($_id, FILTER_VALIDATE_INT);
        if(! $valid) {
            http_response_code(401);
            $response = array(
                'message' => 'Insert valid ID'
            );
        }

        $dbres = remove('rinhas', $id);
        http_response_code(200);
        $response = array(
            'message' => $dbres
        );
        return json_encode($response);
    }

    public function getByUser($token) {

        if(!$token) {
            http_response_code(400);
            $response = array(
                'message' => 'Token needed!'
            );
            return json_encode($response);
        }
        else {
            $database = open_database();
            $found = null;

            try {
                $sql = "SELECT userid FROM users WHERE token = " . $token;
                $result = $database->query($sql);
                if ($result->num_rows > 0) {
                    $userid = $result->fetch_assoc();
                    print_r($userid);
                    $sql = "SELECT * FROM rinhas WHERE owner = " . $userid;
                    $result = $database->query($sql);
                }
            }
            catch (Exception $e) {
                http_response_code(400);
                return $e->GetMessage();
            }

            close_database($database);
            http_response_code(200);
            return json_encode($found);
        }
            
    }
}