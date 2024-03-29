<?php
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    $method = $_SERVER['REQUEST_METHOD'];

    // Get raw authored data
    $data = json_decode(file_get_contents("php://input"));

    if ($method === 'OPTIONS') {
        header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE');
        header('Access-Control-Allow-Headers: Origin, Accept, Content-Type, X-Requested-With');
    } elseif ($method === 'GET' && !isset($_GET['id'])) {
        include_once('./read.php');
    } elseif ($method === 'GET' && isset($_GET['id'])) {
        include_once('./read_single.php');
    } elseif ($method === 'POST') {
        include_once('./create.php');
    } elseif ($method === 'PUT') {
        include_once('./update.php');
    } elseif ($method === 'DELETE') {
        include_once('./delete.php');
    }

    exit();
