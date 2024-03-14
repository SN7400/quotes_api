<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Quote.php';

    if(!isset($data) || !isset($data->quote)) {
        echo json_encode(
            array('message' => 'Missing Required Parameters')
        );
    } else {
        // Instantiate DB & connect
        $database = new Database();
        $db = $database->connect();

        // Instantiate quote object
        $quote = new Quote($db);
        $quote->quote = $data->quote;
        $quote->create();
        $quote_arr = array(
            'id' => $quote->id,
            'quote' => $quote->quote,
        );

        // Make JSON
        print_r(json_encode($quote_arr));
    }