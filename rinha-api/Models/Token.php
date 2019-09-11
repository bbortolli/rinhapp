<?php

include_once( $_SERVER['DOCUMENT_ROOT'] . '/rinha-api/config/cfg.php');
include_once( $_SERVER['DOCUMENT_ROOT'] . '/rinha-api/config/database.php');

class Token {

    protected $id;
    protected $token;

    public function __construct($id = null) {
        $this->id = $id;
    }

    public function getId() {
        return $this->id;
    }

    public function getToken() {
        return $this->token;
    }

    public function generateToken() {

        if (!$this->id) {
            return false;
        }

        $date = date_create();
        $time = date_timestamp_get($date);

        $string = $id . $time;

        $token = hash('sha256', $string);
        $this->token = $token;

    }

    public function saveToken() {

        $myId = $this->getId();
        $myToken = $this->getToken();
        $dbres = update('users', $myId, array('token' => $myToken));
    }

    public function verifyToken($token) {

        if(! $token) {
            return false;
        }

        $database = open_database();
        $found = null;

        try {
            $sql = "SELECT _id FROM users WHERE token = " . "'" . $token . "'";
            $result = $database->query($sql);
            if ($result->num_rows > 0) {
                $found = $result->fetch_assoc();
            }
        }
        catch (Exception $e) {
            return $e->GetMessage();
        }

        close_database($database);
        return $found;
    }

}