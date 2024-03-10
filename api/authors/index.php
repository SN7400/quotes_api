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

/*
In my index.php I handle some conditionals before routing. 

For example, all HTTP methods except for POST need to confirm the id if submitted. That makes it a good place to add a conditional along the lines of: 
If the method is not equal to POST and the id was submitted, then verify the author actually exists in your database. 
From there, I created a helper function called isValid that verifies something is in a database related to an id. It returns a Boolean.
*/