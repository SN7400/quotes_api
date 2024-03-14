<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';
    include_once '../../models/Author.php';
    include_once '../../models/Category.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    if(!isset($data) || !isset($data->id) || !isset($data->quote) || !isset($data->author_id) || !isset($data->category_id)) {
        echo json_encode(
            array('message' => 'Missing Required Parameters')
        );
    } else {
        // Instantiate Quote object
        $quote = new Quote($db);

        // Set id for update
        $quote->id = $data->id;

        // Check if quote_id, author_id, and category_id are valid before proceeding with create
        $author = new Author($db);
        $author->id = $data->author_id;
        $category = new Category($db);
        $category->id = $data->category_id;
        if(!$author->read_single()) {
            echo json_encode(
                array('message' => 'author_id Not Found')
            );
        } elseif(!$category->read_single()) {
            echo json_encode(
                array('message' => 'category_id Not Found')
            );
        } elseif (!$quote->read_single()) {
            echo json_encode(
                array('message' => 'quote_id Not Found')
            );
        } else {
            // Set quote for update
            $quote->quote = $data->quote;
            $quote->author_id = $data->author_id;
            $quote->category_id = $data->category_id;
            // Update quote
            $quote->update();
            // Read updated record and prepare array
            $quote->read_single();
            $quote_arr = array(
                'id' => $quote->id,
                'quote' => $quote->quote,
                'author' => $quote->author,
                'category' => $quote->category
            );
            // Make JSON
            print_r(json_encode($quote_arr));
        }
    }