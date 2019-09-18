<?php

include_once( $_SERVER['DOCUMENT_ROOT'] . '/rinha-api/config/cfg.php');
include_once( $_SERVER['DOCUMENT_ROOT'] . '/rinha-api/config/database.php');

class Token {

    protected $id;
    protected $token;

    public function __construct($id = null) {
        $this->id = $id;
    }

    public function setId($id) {
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
        $now = time();
        $dbres = update('users', $myId, array('token' => $myToken));
        return $dbres;
    }

    public function verifyToken($token = null) {

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

    public function checkExpire($token = null) {

        if(! $token) {
            return false;
        }

        $database = open_database();
        $found = null;

        try {
            $sql = "SELECT _id, expires FROM users WHERE token = " . "'" . $token . "'";
            $result = $database->query($sql);
            if ($result->num_rows > 0) {
                $found = $result->fetch_assoc();
            }
        }
        catch (Exception $e) {
            return $e->GetMessage();
        }

        if($found['expires'] > time()){
            return 'Token expired';
        }
        elseif( ($found['expires'] - time()) < 60) {
            $this->setId($found['_id']);
            $this->generateToken();
            $this->saveToken();
            return 'Token renewed';
        }

        close_database($database);
        return $found;

    }

}