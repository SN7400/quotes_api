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
    } else {
        // Instantiate Author object
        $author = new Author($db);

        // Set id for update
        $author->id = $data->id;

        // Check if author_id exists before updating
        if($author->read_single()) {
            // Set author for update
            $author->author = $data->author;
            // Update author
            $author->update();
            // Read updated record and prepare array
            $author->read_single();
            $author_arr = array(
                'id' => $author->id,
                'author' => $author->author,
            );
            // Make JSON
            print_r(json_encode($author_arr));
        } else {
            // Return message from read_single() that author_id is not found
            echo json_encode(
                array('message' => 'author_id Not Found')
            );
        }

    }