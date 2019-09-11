<?php

header('Content-Type: application/json; charset: utf-8');
header("Access-Control-Allow-Origin: *");

include_once( $_SERVER['DOCUMENT_ROOT'] . '/rinha-api/config/cfg.php');
include_once( $_SERVER['DOCUMENT_ROOT'] . '/rinha-api/config/database.php');
require_once 'Token.php';

class User {

    public function login($data) {

        $data = json_decode($data);
        $database = open_database();
        $found = null;
        
        $notallowed = array("'", "=", '"', "*");

        $data->nickname = trim($data->nickname);
        $data->nickname = str_replace($notallowed, "", $data->nickname);

        $data->password = trim($data->password);
        $data->password = str_replace($notallowed, "", $data->password);

        try {
            if ($data->nickname && $data->password) {
                $sql = "SELECT * FROM users WHERE nickname = " . "'" .$data->nickname . "'";
                $result = $database->query($sql);
                if ($result->num_rows > 0) {
                    $found = $result->fetch_assoc();
                    if (hash('sha256', $data->password) === $found['password']) {
                        close_database($database);
                        $token = new Token($found['_id']);
                        $token->generateToken();
                        $token->saveToken();
                        http_response_code(200);
                        $response = array(
                            'token' => $token->getToken()
                        );
                        return json_encode($response);
  
                    }
                    else {
                        close_database($database);
                        http_response_code(401);
                        $response = array(
                            'message' => 'Invalid nick/pass'
                        );
                        return json_encode($response);
                    }
                }
            }
            else {
                http_response_code(400);
                $response = array(
                    'message' => 'Invalid nick/pass'
                );
                return json_encode($response);
            }
        }
        catch (Exception $e) {
            http_response_code(400);
            return $e->GetMessage();
        }
        
    }

    public function getData($_id, $token) {

        $helper = new Token();
        $res = $helper->verifyToken($token);

        if($res) {
            $data = find('users', $_id);
            unset($data['password']);
            http_response_code(200);
            return json_encode($data);
        }
    }

    public function addData($data) {

        $data = json_decode($data);
        if($data->nickname === '' || $data->nickname === null || $data->password === '' || $data->password === null || $data->email === '' || $data->email === null) {
            http_response_code(400);
            $response = array(
                'message' => 'Bad inputs'
            );
            return json_encode($response);
        }

        $data->password = hash('sha256', $data->password);
        $dbres = save('users', $data);
        http_response_code(200);
        $response = array(
            'message' => $dbres
        );
        return json_encode($response);
    }

    public function updateData($data, $token) {

        $helper = new Token();
        $res = $helper->verifyToken($token);

        if(! $res || $res['_id'] !== $data['_id']) {
            http_response_code(401);
            $response = array(
                'message' => "You can't do this"
            );
            return json_encode($response);
        }

        $data = json_decode($data);
        $dbres = update('users', $data);
        http_response_code(200);
        $response = array(
            'message' => $dbres
        );
        return json_encode($response);
    }

    public function removeData($id, $token) {

        $helper = new Token();
        $res = $helper->verifyToken($token);

        if(! $res || $res['_id'] !== $data['_id']) {
            http_response_code(401);
            $response = array(
                'message' => "You can't do this"
            );
            return json_encode($response);
        }

        $data = json_decode($data);
        $dbres = remove('users', $id);
        http_response_code(200);
        $response = array(
            'message' => $dbres
        );
        return json_encode($response);
    }

}