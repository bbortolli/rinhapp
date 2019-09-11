<?php

header('Content-Type: application/json; charset: utf-8');
include_once( $_SERVER['DOCUMENT_ROOT'] . '/rinha-api/config/cfg.php');
include_once( $_SERVER['DOCUMENT_ROOT'] . '/rinha-api/config/database.php');
require_once 'Token.php';

class Rinha {

    public function getData($_id, $token) {

        $tkn = new Token();
        $helper = $tkn->verifyToken($token);
        print_r($helper['_id']);

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

    public function getAll($token) {

        $tkn = new Token();
        $helper = $tkn->verifyToken($token);

        $data = findAll('rinhas');
        http_response_code(200);
        return json_encode($data);
    }

    public function addData($data) {

        
        $data = json_decode($data);
        $token = $data->token;

        $tkn = new Token();
        $helper = $tkn->verifyToken($token);
        
        $data->owner = $helper['_id'];
        unset($data->token);
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

    public function removeData($id, $token) {

        $tkn = new Token();
        $helper = $tkn->verifyToken($token);

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

            $tkn = new Token();
            $helper = $tkn->verifyToken($token);

            $database = open_database();
            $found = null;

            try {
                $sql = "SELECT _id FROM users WHERE token = " . "'" . $token . "'";
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