<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: PUT');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    // Instantiate DB & connect
    $database = new Database();
    $db = $database->connect();

    if(!isset($data) || !isset($data->id) || !isset($data->category)) {
        echo json_encode(
            array('message' => 'Missing Required Parameters')
        );
    } else {
        // Instantiate Category object
        $category = new Category($db);

        // Set id for update
        $category->id = $data->id;

        // Check if category_id exists
        $category->read_single();
        if(isset($category->category)) {
            // Set category for update
            $category->category = $data->category;
            // Update category
            $category->update();
            // Read updated record and prepare array
            $category->read_single();
            $category_arr = array(
                'id' => $category->id,
                'category' => $category->category,
            );
            // Make JSON
            print_r(json_encode($category_arr));
        } else {
            // Return message from read_single() that category_id is not found
            echo json_encode(
                array('message' => 'category_id Not Found')
            );
        }

    }