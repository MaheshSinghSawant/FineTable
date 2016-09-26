<?php
  // var_dump($_POST);
  // var_dump($_FILES);
    if (empty($_POST)) {
        header('Location: index.php');
    } else {
        require_once '../lib/Restaurant.class.php';
        require_once '../lib/User.class.php';
        // add restaurant to database
        $owner_user_id = $_POST['userId'];
        $name = addslashes($_POST['name']);
        $address = addslashes($_POST['address']);
        $city = addslashes($_POST['city']);
        $state = addslashes($_POST['state']);
        $zipcode = $_POST['zipcode'];
        $phone_number = $_POST['phone'];
        $cuisine_type = $_POST['cuisine'];
        $description = addslashes($_POST['description']);
        $max_capacity = 0;
        $rating = 5.0;
        $approved = 0;
        if (isset($_POST['hasCapacity2']) && $_POST['hasCapacity2'] === 'true') {
            $max_capacity += (intval($_POST['numberCapacity2']) * 2);
        }
        if (isset($_POST['hasCapacity4']) && $_POST['hasCapacity4'] === 'true') {
            $max_capacity += (intval($_POST['numberCapacity4']) * 4);
        }
        if (isset($_POST['hasCapacity6']) && $_POST['hasCapacity6'] === 'true') {
            $max_capacity += (intval($_POST['numberCapacity6']) * 6);
        }
        if (isset($_POST['hasCapacity8']) && $_POST['hasCapacity8'] === 'true') {
            $max_capacity += (intval($_POST['numberCapacity8']) * 8);
        }
        // echo $max_capacity;
        $fields = array(
            'name' => $name,
            'address' => $address,
            'city' => $city,
            'state' => $state,
            'zipcode' => $zipcode,
            'phone_number' => $phone_number,
            'cuisine_type' => $cuisine_type,
            'description' => $description,
            'max_capacity' => $max_capacity,
            'rating' => $rating,
            'approved' => 0,
            'owner_user_id' => $owner_user_id
        );
        try {
            $newRestaurant = new Restaurant();
            $newRestaurant->create($fields);
            $success = true;
            $message = 'Restaurant sign up complete!';
            $restaurant_id = $newRestaurant->get_restaurant_id();
            // after insert restaurant info without errors
            // add image to images table
            require_once '../lib/helper.functions.php';
            $image_client_path = $_FILES['file']['name'];
            $image_mime = $_FILES['file']['type'];
            $image_size = $_FILES['file']['size'];
            $image_error = $_FILES['file']['error'];
            $image_original_path = 'upload/original/';
            $image_medium_path = 'upload/medium/';
            $image_small_path = 'upload/small/';
            $tmp_file = $_FILES["file"]["tmp_name"];
            if ($image_error > 0) {
                throw new Exception('Image file is not correct!');
            } else {
                date_default_timezone_set('UTC');
                $ext = pathinfo($image_client_path, PATHINFO_EXTENSION);
                $base = pathinfo($image_client_path, PATHINFO_FILENAME);
                $image_server_name = date("YmdHis").md5($base).'.'.$ext;
                $image_server_file = $image_original_path . $image_server_name;
                if (move_uploaded_file($tmp_file, $image_server_file)) {
                    $image_medium_server_file = resizeImage($image_server_name, $image_mime, 600, $image_medium_path);
                    $image_small_server_file = resizeImage($image_server_name, $image_mime, 208, $image_small_path);
                    $image_fields = array(
                        'original_path' => $image_server_file,
                        'thumb_medium_path' => $image_medium_server_file,
                        'thumb_small_path' => $image_small_server_file,
                        'restaurant_id' => $restaurant_id
                    );
                    $new_image = new Image();
                    $new_image->add_image_to_restaurant($image_fields);
                } else {
                    throw new Exception('Image uploading failed! Please try again!');
                }
            }
            $owner = new User();
            if ($owner->find($owner_user_id)) {
                $owner->switche_user_group_to('owner');
            }
        } catch (Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }
        // add image after add restaurant
        $result = array(
            'success' => $success,
            'message' => $message,
        );
        echo json_encode($result);
    }
