<?php

header('Content-Type: application/json; charset: utf-8');

include_once( $_SERVER['DOCUMENT_ROOT'] . '/config/cfg.php');
include_once( $_SERVER['DOCUMENT_ROOT'] . '/config/database.php');

class User {

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