<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    $method = $_SERVER['REQUEST_METHOD'];

    if ($method === 'OPTIONS') {
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
        exit();
    } elseif ($method === 'GET') {
        require('./read.php');
        require('./read_single.php');
    } elseif ($method === 'POST') {
        require('./create.php');
    } elseif ($method === 'PUT') {
        require('./update.php');
    } elseif ($method === 'DELETE') {
        require('./delete.php');
    }