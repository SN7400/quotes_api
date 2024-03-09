<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');

    include_once '../../config/Database.php';
    include_once '../../models/Author.php';

    // Instantiate DB
    $database = new Database();
    $db = $database->connect();

    // Instantiate Author object
    $author = new Author($db);

    // Get ID
    $author->id = isset($_GET['id']) ? $_GET['id'] : die();

    // Get author
    $author->read_single();

    // Create array
    /* $author_arr = array(
        'id' => $author->id,
        'author' => $author->author,
    );

    // Make JSON
    print_r(json_encode($author_arr));
    */
        // Create author
    if($author->read_single()) {
        //echo json_encode(
        //    array('message' => 'created author (' . $author->id . ', ' . $author->author . ')')
        //);
        // Create array
        $author_arr = array(
            'id' => $author->id,
            'author' => $author->author,
        );

        // Make JSON
        print_r(json_encode($author_arr));
    } else {
        echo json_encode(
            array('message' => 'author_id Not Found')
        );
    }