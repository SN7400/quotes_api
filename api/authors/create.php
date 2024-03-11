<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    if(!isset($data) || !isset($data->author)) {
        echo json_encode(
            array('message' => 'Missing Required Parameters')
        );
    } else {
        // Instantiate DB & connect
        $database = new Database();
        $db = $database->connect();

        // Instantiate author object
        $author = new Author($db);
        $author->author = $data->author;
        $author->create();
        $author_arr = array(
            'id' => $author->id,
            'author' => $author->author,
        );

        // Make JSON
        print_r(json_encode($author_arr));
    }