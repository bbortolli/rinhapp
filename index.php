<?php

header('Content-Type: application/json; charset: utf-8');

require 'config/Routes.php';
require 'Models/User.php';
require 'Models/Rinha.php';

if (isset($_REQUEST)) {

    $method = $_SERVER[REQUEST_METHOD];
    $route = explode('/', $_REQUEST['url']);

    // Get model
    $class = ucfirst($route[0]);
    array_shift($route);

    // Get controller
    $classMethod = $route[0];
    array_shift($route);

    // Get params
    //$param = array();
    $param = $route[0];

    // Verify param
    if(! filter_var($param, FILTER_VALIDATE_INT) ) {

        http_response_code(400);

        $response = array(
            'statusMessage' => "Wrong params");
        echo json_encode($response);
        return;
    }

    // Verify if control NO exist
    if(!class_exists($class)) {

        http_response_code(400);

        $response = array(
            'statusMessage' => "Target don't exist");
        echo json_encode($response);
        return;
    }

    if(!method_exists($class, $classMethod)) {

        http_response_code(400);

        $response = array(
            'statusMessage' => "Target don't exist");
        echo json_encode($response);
        return;
    }

    // Validate if  HTTP Request Method is valid for the route
    if ($myRoutes[$class][$classMethod] !== $method) {

        http_response_code(405);

        $response = array(
            'statusMessage' => 'Method Not Allowed');
        echo json_encode($response);
        return;
    }

    // GET and DELETE calls a function sending the param received from the URL
    if ($method === 'GET' || $method === 'DELETE') {
        $response = call_user_func_array( array(new $class, $classMethod), array($param) );
        echo $response;
        return;
    }
    // POST and PUT receive params from php input and calls a function sending those params
    else if ($method === 'POST' || $method === 'PUT') {
        $body = file_get_contents('php://input');
        $response = call_user_func_array( array(new $class, $classMethod), array($body));
        echo $response;
        return;
    }
}