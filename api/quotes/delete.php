<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: DELETE');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    if(!isset($data) || !isset($data->id)) {
        echo json_encode(
            array('message' => 'Missing Required Parameters')
        );
    } else {
        // Instantiate Quote object
        $quote = new Quote($db);

        // Set id for update
        $quote->id = $data->id;

        // Check if quote_id exists
        $quote->read_single();
        if(isset($quote->quote)) {
            // Save ID
            $id = $quote->id;
            // Delete quote
            $quote->delete();
            $quote_arr = array(
                'id' => $id,
            );
            // Make JSON
            print_r(json_encode($quote_arr));
        } else {
            // Return message from read_single() that quote_id is not found
            echo json_encode(
                array('message' => 'quote_id Not Found')
            );
        }
    }