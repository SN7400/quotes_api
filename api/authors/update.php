<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    if(!isset($data) || !isset($data->id) || !isset($data->author)) {
        echo json_encode(
            array('message' => 'Missing Required Parameters')
        );
    } elseif (false) { // ADD check that author id exists

    } else {
        // Instantiate Author object
        $author = new Author($db);

        // Set parameters for update
        $author->id = $data->id;
        $author->author = $data->author;

        // Update author
        $author->update();
        // Create array
        $author_arr = array(
            'id' => $author->id,
            'author' => $author->author,
        );

        // Make JSON
        print_r(json_encode($author_arr));
    }