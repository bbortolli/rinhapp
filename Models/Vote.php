<?php

header('Content-Type: application/json; charset: utf-8');
include_once( $_SERVER['DOCUMENT_ROOT'] . '/config/cfg.php');
include_once( $_SERVER['DOCUMENT_ROOT'] . '/config/database.php');

class Vote {

    public function getAllVotes($userid = null) {

        $database = open_database();
        $found = null;

        try {
            if ($userid) {
                $sql = "SELECT * FROM VOTES WHERE userid = " . $userid;
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

    public function addData($data) {

        $data = json_decode($data);
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