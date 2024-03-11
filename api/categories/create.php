<?php
    // Headers
    header('Access-Control-Allow-Origin: *');
    header('Content-Type: application/json');
    header('Access-Control-Allow-Methods: POST');
    header('Access-Control-Allow-Headers: Access-Control-Allow-Headers, Content-Type, Access-Control-Allow-Methods, Authorization, X-Requested-With');

    include_once '../../config/Database.php';
    include_once '../../models/Category.php';

    if(!isset($data) || !isset($data->category)) {
        echo json_encode(
            array('message' => 'Missing Required Parameters')
        );
    } else {
        // Instantiate DB & connect
        $database = new Database();
        $db = $database->connect();

        // Instantiate category object
        $category = new Category($db);
        $category->category = $data->category;
        $category->create();
        $category_arr = array(
            'id' => $category->id,
            'category' => $category->category,
        );

        // Make JSON
        print_r(json_encode($category_arr));
    }