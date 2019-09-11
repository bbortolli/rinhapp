<?php

header('Content-Type: application/json; charset: utf-8');
include_once( $_SERVER['DOCUMENT_ROOT'] . '/rinha-api/config/cfg.php');
include_once( $_SERVER['DOCUMENT_ROOT'] . '/rinha-api/config/database.php');
require_once 'Token.php';

class Vote {

    public function getAllVotes($ignore = null, $token) {

        if(! $token) {
            http_response_code(401);
            $response = array(
                'message' => 'You need a token'
            );
            return json_encode($response);
        }

        $tkn = new Token();
        $helper = $tkn->verifyToken($token);

        $database = open_database();
        $found = null;

        try {
            if ($userid) {
                $sql = "SELECT * FROM VOTES WHERE userid = " . $helper['_id'];
                $result = $database->query($sql);
                
                if ($result->num_rows > 0) {
                $found = $result->fetch_assoc();
                }
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

    public function getData($_id) {

        $data = find('votes', $_id);
        http_response_code(200);
        return json_encode($data);
    }

    public function addData($data, $token) {

        $tkn = new Token();
        $helper = $tkn->verifyToken($token);

        if(! $helper['_id']) {
            http_response_code(401);
            $response = array(
                'message' => 'You need a token'
            );
            return json_encode($response);
        }

        $data = json_decode($data);
        $data->userid = $helper['_id'];
        $dbres = save('votes', $data);
        http_response_code(200);
        $response = array(
            'message' => $dbres
        );
        return json_encode($response);
    }

    public function updateData($data) {

        $data = json_decode($data);
        $dbres = update('votes', $data);
        http_response_code(200);
        $response = array(
            'message' => $dbres
        );
        return json_encode($response);
    }

}