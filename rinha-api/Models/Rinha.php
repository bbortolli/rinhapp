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

    public function getAll($ignore, $token) {

        $tkn = new Token();
        $helper = $tkn->verifyToken($token);

        if(! $helper['_id']) {
            http_response_code(401);
            $response = array(
                'message' => 'You need a token'
            );
        return json_encode($response);
        }

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

        $aux = find('rinhas', $id);

        if(!$aux) {
            http_response_code(404);
            $response = array(
                'message' => 'Data not found'
            );
            return json_encode($response);
        }

        if($aux['owner'] !== $helper['_id']) {
            http_response_code(401);
            $response = array(
                'message' => 'Only the owner can execute this'
            );
            return json_encode($response);
        }

        $dbres = remove('rinhas', $id);
        http_response_code(200);
        $response = array(
            'message' => $dbres
        );
        return json_encode($response);
    }

    public function getByUser($ignore, $token) {

        if(!$token) {
            http_response_code(400);
            $response = array(
                'message' => 'Token needed!'
            );
            return json_encode($response);
        }

        $tkn = new Token();
        $helper = $tkn->verifyToken($token);

        $database = open_database();
        $found = null;

        try {
            $sql = "SELECT * FROM rinhas WHERE owner = " . $helper['_id'];
            $result = $database->query($sql);
            if ($result->num_rows > 0) {
                $found = $result->fetch_all(MYSQLI_ASSOC);
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

    public function getAllVoted($ignore, $token) {

        if(!$token) {
            http_response_code(400);
            $response = array(
                'message' => 'Token needed!'
            );
            return json_encode($response);
        }

        $tkn = new Token();
        $helper = $tkn->verifyToken($token);

        $database = open_database();
        $found = null;

        try {
            $sql = "SELECT rinhas._id, team1, team2, endtime, finished, totalteam1, totalteam2, teamvoted FROM rinhas, votes WHERE rinhas._id = votes.rinhaid AND votes.userid = " . $helper['_id'];
            $result = $database->query($sql);
            if ($result->num_rows > 0) {
                $found = $result->fetch_all(MYSQLI_ASSOC);
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