<?php
    // print_r($_POST);
    session_start();
    if (empty($_POST)) {
        header('Location: index.php');
    } else {
        require_once('../lib/Review.class.php');
        require_once('../lib/User.class.php');
        require_once('../lib/Restaurant.class.php');
        $user_id = $_POST['user_id'];
        $restaurant_id = $_POST['restaurant_id'];
        $rate = $_POST['rating'];
        $content = $_POST['content'];
        $fields = array (
            'user_id' => $user_id,
            'restaurant_id' => $restaurant_id,
            'rating' => $rate,
            'content' => $content
        );
        try {
            $new_review = new Review();
            $restaurant = new Restaurant();
            if ($restaurant->find($restaurant_id)) {
                $original_rating = $restaurant->get_rating();
                $new_rating = $rate;
                $all_reviews = Review::get_all_reviews_by_restaurant($restaurant_id);
                // var_dump($all_reviews);
                if ($all_reviews) {
                    $overall_rate = 0;
                    while($a_review = $all_reviews->fetch_row()) {
                        $overall_rate += $a_review[4];
                    }
                    $new_rating = ($overall_rate + $rate) / ($all_reviews->num_rows + 1);
                }
                $new_review->create($fields);
                $restaurant->update_rating($new_rating);
                $success = true;
                $message = 'Adding review complete! Your review will be attached to the restaurant page shortly.';
            }
        } catch (Exception $e) {
            $success = false;
            $message = $e->getMessage();
        }
        $return_result = array (
            'success' => $success,
            'message' => $message
        );
        echo json_encode($return_result);

        // //add review
        // $title = $POST['title'];
        // $content = $_POST['content'];
        // $rating = $_POST['rating'];
        // $date = date('Y-m-d');
        // $restaurant_id = $POST['restaurant_id'];
        // $user_id = $POST['user_id'];
        //
        // //needs to update to restaurant table
        // $number_of_reviews = $number_of_reviews +1;
        //
        // $fields = array(
        //         'content' => $content,
        //         'title' => $title,
        //         'rating' => $rating,
        //         'date' => $date,
        //         'restaurant_id' => $restaurant_id,
        //         'user_id' => $user_id,
        //     );
        //
        // try {
        //         $review = new Review();
        //         $review->create($fields);
        //         $success = true;
        //         $message = 'Thank you for your review. =)';
        //
        //     } catch (Exception $e) {
        //         $success = false;
        //         $message = $e->getMessage();
        //     }
        //
        //     $result = array(
        //     'success' => $success,
        //     'message' => $message,
        //     );
        // echo json_encode($result);
    }


?>
