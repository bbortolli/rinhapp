<?php
// Insert all routes here
$myRoutes = [
    'User' => [
        'getData' => 'GET',
        'login' => 'POST',
        'addData' => 'POST',
        'updateData' => 'PUT',
        'removeData' => 'DELETE'
    ],
    'Rinha' => [
        'getData' => 'GET',
        'getAll' => 'GET',
        'getByUser' => 'GET',
        'getAllVoted' => 'GET',
        'addData' => 'POST',
        'updateData' => 'PUT',
        'removeData' => 'DELETE'
    ],
    'Vote' => [
        'getAll' => 'GET',
        'addData' => 'POST',
        'updateAccount' => 'PUT',
        'removeAccount' => 'DELETE'
    ],
    'Token' => [
        'generateToken' => 'GET'
    ]
];

?>

