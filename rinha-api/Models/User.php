<?php

header('Content-Type: application/json; charset: utf-8');

include_once( $_SERVER['DOCUMENT_ROOT'] . '/config/cfg.php');
include_once( $_SERVER['DOCUMENT_ROOT'] . '/config/database.php');

class User {

    public function login($data) {

        $data = json_decode($data);
        $database = open_database();
        $found = null;

        try {
            if ($data->nickname && $data->password) {
                $sql = "SELECT * FROM users WHERE nickname = " . "'" .$data->nickname . "'";
                $result = $database->query($sql);
                if ($result->num_rows > 0) {
                    $found = $result->fetch_assoc();
                    if ($data->password === $found['password']) {
                        close_database($database);
                        http_response_code(200);
                        $response = array(
                            'token' => 'token gerado'
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

    public function getData($_id) {

        $data = find('users', $_id);
        http_response_code(200);
        return json_encode($data);
    }

    public function addData($data) {

        $data = json_decode($data);
        $dbres = save('users', $data);
        http_response_code(200);
        $response = array(
            'message' => $dbres
        );
        return json_encode($response);
    }

    public function updateData($data) {

        $data = json_decode($data);
        $dbres = update('users', $data);
        http_response_code(200);
        $response = array(
            'message' => $dbres
        );
        return json_encode($response);
    }

    public function removeData($id) {

        $data = json_decode($data);
        $dbres = remove('users', $id);
        http_response_code(200);
        $response = array(
            'message' => $dbres
        );
        return json_encode($response);
    }

}