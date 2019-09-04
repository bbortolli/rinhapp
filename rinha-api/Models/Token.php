<?php

header('Content-Type: application/json; charset: utf-8');

include_once( $_SERVER['DOCUMENT_ROOT'] . '/config/cfg.php');
include_once( $_SERVER['DOCUMENT_ROOT'] . '/config/database.php');

class Token {

    private $id;
    private $token;

    function __construct($id) {
        $this->$id = $id;
    }

    public function getId() {
        return $this->$id;
    }

    public function getToken() {
        return $this->$token;
    }

    public function generateToken() {

        if (!$this->$id) {
            http_response_code(401);
            $response = array(
                'message' => 'ID needed!'
            );
            return json_encode($response);
        }

        $date = date_create();
        $time = date_timestamp_get($date);

        $string = $id . $time;

        $token = hash('sha256', $string);
        $this->$token = $token;

    }

    public function saveToken() {

        $myId = $this->getId();
        $myToken = $this->getToken();

        update('users', $myId, array('token' => $myToken));


    }

}