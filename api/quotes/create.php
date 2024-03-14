<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';
    include_once '../../models/Author.php';
    include_once '../../models/Category.php';

    if(!isset($data) || !isset($data->quote) || !isset($data->author_id) || !isset($data->category_id)) {
        echo json_encode(
            array('message' => 'Missing Required Parameters')
        );
    } else {
        // Instantiate DB & connect
        $database = new Database();
        $db = $database->connect();

        // Check if author_id and category_id are valid before proceeding with create
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
        } else {
        // Instantiate quote object
        $quote = new Quote($db);
        $quote->quote = $data->quote;
        $quote->author_id = $data->author_id;
        $quote->category_id = $data->category_id;
        $quote->create();
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