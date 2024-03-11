<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    if(!isset($data) || !isset($data->id)) {
        echo json_encode(
            array('message' => 'Missing Required Parameters')
        );
    } else {
        // Instantiate Author object
        $author = new Author($db);

        // Set id for update
        $author->id = $data->id;

        // Check if author_id exists
        $author->read_single();
        if(isset($author->author)) {
            // Save ID
            $id = $author->id;
            // Delete author
            $author->delete();
            $author_arr = array(
                'id' => $id,
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