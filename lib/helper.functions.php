<?php
require_once('DataAccessLayer.class.php');
require_once('Image.class.php');
require_once('User.class.php');
require_once('Review.class.php');
require_once('Reservation.class.php');

/**
 * resize uploaded images into medium and small sizes
 * @param  string $src        original image file name
 * @param  string $mime       resized image mime type
 * @param  string $new_width  resized image width
 * @param  string $path       resized image storing path
 * @return string             resized imag path
 */
function resizeImage($src, $mime, $new_width, $path) {
    list($src_width, $src_height) = getimagesize('upload/original/'.$src);
    $ratio = $new_width / $src_width;
    $new_height = $src_height * $ratio;

    switch ($mime) {
    case 'image/jpeg':
        $create_image_from = 'imagecreatefromjpeg';
        break;
    case 'image/png':
        $create_image_from = 'imagecreatefrompng';
        break;
    default:
        throw Exception('Unknown image type.');
    }

    $create_image_to = 'imagejpeg';
    // $new_file_ext = '.jpg';

    $image_paper = imagecreatetruecolor($new_width, $new_height);
    $original_image = $create_image_from('upload/original/'.$src);
    imagecopyresampled($image_paper, $original_image, 0, 0, 0, 0, $new_width, $new_height, $src_width, $src_height);

    $create_image_to($image_paper, $path.$src, 100);

    imagedestroy($image_paper);
    imagedestroy($original_image);

    return $path.$src;
}


/**
 * [render_search_results description]
 * @param  [type] $data [description]
 * @return [type]       [description]
 */
function render_search_results($data) {
    if (isset($data)) {
        $restaurant_id = $data[0];
        $restaurant_name = $data[1];
        $restaurant_address = $data[2];
        $restaurant_city = $data[3];
        $restaurant_state = $data[4];
        $restaurant_phone = $data[5];
        $restaurant_description = $data[6];
        $restaurant_rating = $data[7];
        $restaurant_cuisine = $data[8];
        $restaurant_image = get_a_image($restaurant_id);

        echo '<div class="row result-row">';
        echo '<div class="col-sm-3 result-image">';
        echo '<img src="' . $restaurant_image . '" alt="' . $restaurant_name . '">';
        echo '</div>';
        echo '<div class="col-sm-9 result-info">';
        echo '<h4><a href="restaurant.php?restaurant_id='.$restaurant_id.'">'. $restaurant_name .'</a><span class="result-cuisine label label-info">' . $restaurant_cuisine . '</span></h4>';
        echo '<div class="stars">';
        echo '<div class="stars-blank"></div>';
        echo '<div class="stars-gold" style="width: ' . ($restaurant_rating / 5) * 95 . 'px;"></div>';
        echo '<a class="goto-review" href="restaurant.php?restaurant_id=' . $restaurant_id . '">Reviews</a>';
        echo '</div>';
        echo '<div class="row result-address text-muted">';
        echo '<span class="glyphicon glyphicon-map-marker"></span>&nbsp;' . $restaurant_address . ', ' . $restaurant_city . ', ' . $restaurant_state . '&nbsp;&nbsp&nbsp&nbsp&nbsp&nbsp<span class="glyphicon glyphicon-earphone"></span>&nbsp' . $restaurant_phone;
        echo '</div>';
        echo '<hr>';
        echo '<div class="row result-reservation">';
        echo '<form class="reservation-form" action="../lib/make-reservation.php" method="post">
                <div class="btn-group btn-group-justified">
                    <div class="btn-group" role="group">
                        <select class="people-size-picker form-control" name="people-size" data-dropup-auto="false" data-restaurant-id="'. $restaurant_id .'">
                            <option value="default">How many people?</option>
                            <option value="2">2 people</option>
                            <option value="4">4 people</option>
                            <option value="6">6 people</option>
                            <option value="8">8 people</option>
                        </select>
                    </div>
                    <div class="btn-group" role="group">
                        <select class="date-picker form-control" name="date" data-dropup-auto="false" data-restaurant-id="'. $restaurant_id .'">
                            <option value="default">What date?</option>
                        </select>
                    </div>
                    <div class="btn-group" role="group">
                        <select class="time-picker form-control" name="time" data-dropup-auto="false" data-restaurant-id="'. $restaurant_id .'">
                            <option value="default">What time?</option>
                        </select>
                    </div>
                    <div class="btn-group">
                        <input class="btn btn-primary btn-sm" type="submit" value="Reserve a table" data-restaurant-id="'. $restaurant_id .'" name="reservation-submit">
                    </div>
                </div>
            </form>';
        echo '</div>';
        echo '<hr>';
        echo '<div class="row result-description">';
        echo '<p>' . cut_long_text($restaurant_description) . '</p>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '<hr>';
    }
}

/**
 * [render_search_results description]
 * @param  [type] $data [description]
 * @return [type]       [description]
 */
function render_restaurant_view($data, $user_id = null) {
    if (isset($data)) {
        $restaurant_id = $data[0];
        $restaurant_name = $data[1];
        $restaurant_address = $data[2];
        $restaurant_city = $data[3];
        $restaurant_state = $data[4];
        $restaurant_phone = $data[5];
        $restaurant_description = $data[6];
        $restaurant_rating = $data[7];
        $restaurant_cuisine = $data[8];
        $restaurant_image = get_a_image($restaurant_id);
        $show_review_link = false;
        if ($user_id) {
            if (Reservation::any_effective_reservation($restaurant_id, $user_id)) {
                if (!Review::any_review_before($restaurant_id, $user_id)) {
                    $show_review_link = true;
                }
            }
        }

        echo '<div class="row result-row">';
        echo '<div class="col-sm-3 result-image">';
        echo '<img src="' . $restaurant_image . '" alt="' . $restaurant_name . '">';
        echo '</div>';
        echo '<div class="col-sm-9 result-info">';
        echo '<h4>'. $restaurant_name .'<span class="result-cuisine label label-info">' . $restaurant_cuisine . '</span></h4>';
        echo '<div class="stars">';
        echo '<div class="stars-blank"></div>';
        echo '<div class="stars-gold" style="width: ' . ($restaurant_rating / 5) * 95 . 'px;"></div>';
        if ($show_review_link) {
            echo '<a class="goto-review label label-success" data-restaurant-id="'.$restaurant_id.'" href="review.form.php">Write a review?</a>';
        }
        echo '</div>';
        echo '<div class="row result-address text-muted">';
        echo '<span class="glyphicon glyphicon-map-marker"></span>&nbsp;' . $restaurant_address . ', ' . $restaurant_city . ', ' . $restaurant_state . '&nbsp;&nbsp&nbsp&nbsp&nbsp&nbsp<span class="glyphicon glyphicon-earphone"></span>&nbsp' . $restaurant_phone;
        echo '</div>';
        echo '<hr>';
        echo '<div class="row result-reservation">';
        echo '<form class="reservation-form" action="../lib/make-reservation.php" method="post">
                <div class="btn-group btn-group-justified">
                    <div class="btn-group" role="group">
                        <select class="people-size-picker form-control" name="people-size" data-dropup-auto="false" data-restaurant-id="'. $restaurant_id .'">
                            <option value="default">How many people?</option>
                            <option value="2">2 people</option>
                            <option value="4">4 people</option>
                            <option value="6">6 people</option>
                            <option value="8">8 people</option>
                        </select>
                    </div>
                    <div class="btn-group" role="group">
                        <select class="date-picker form-control" name="date" data-dropup-auto="false" data-restaurant-id="'. $restaurant_id .'">
                            <option value="default">What date?</option>
                        </select>
                    </div>
                    <div class="btn-group" role="group">
                        <select class="time-picker form-control" name="time" data-dropup-auto="false" data-restaurant-id="'. $restaurant_id .'">
                            <option value="default">What time?</option>
                        </select>
                    </div>
                    <div class="btn-group">
                        <input class="btn btn-primary btn-sm" type="submit" value="Reserve a table" data-restaurant-id="'. $restaurant_id .'" name="reservation-submit">
                    </div>
                </div>
            </form>';
        echo '</div>';
        echo '<hr>';
        echo '<div class="row result-description">';
        echo '<p>' . $restaurant_description . '</p>';
        echo '</div>';
        echo '</div>';
        echo '</div>';
        echo '<hr>';
    }
}

function render_reviews($data) {
    if ($data) {
        // var_dump($data);
        while ($row = $data->fetch_row()) {
            $reviewer_id = $row[5];
            $reviewer = new User();
            if ($reviewer->find($reviewer_id)) {
                $reviewer_name = $reviewer->get_user_name();
            }
            echo '<div class="row restaurant-review">
                <div class="col-sm-4">
                    <p>
                        Reviewer:
                        <span class="reviewer-username">'.$reviewer_name.'</span>
                    </p>
                    <div class="restaurant-rate">
                        <div class="stars-blank"></div>
                        <div class="stars-gold" style="width: '.($row[4] / 5.0 * 95).'px;"></div>
                    </div>
                </div>
                <div class="col-sm-8">'
                    .$row[3].
                '</div>
                <div class="clearfix"></div>
            </div>';
        }
    }
}

/**
 * [get_a_image description]
 * @param  [type] $restaurant_id [description]
 * @return [type]                [description]
 */
function get_a_image($restaurant_id) {
    $a_image = new Image();
    if ($a_image->find($restaurant_id)) {
        return $a_image->get_medium();
    }
    return 'images/default.jpg';
}

/**
 * [cut_long_text description]
 * @param  [type] $text [description]
 * @return [type]       [description]
 */
function cut_long_text($text) {
    $max_length = 200;
    if (strlen($text) > $max_length){
        $offset = ($max_length - 3) - strlen($text);
        $text = substr($text, 0, strrpos($text, ' ', $offset)) . '...';
    }
    return $text;
}

/**
 * [newest_restaurants description]
 * @param  [type] $number [description]
 */
function newest_restaurants($number = null) {
    if ($number) {
        $query_tool = new DataAccessLayer();
        $sql = 'select restaurant_id, name, cuisine_type, rating from restaurants where approved = 1 order by restaurant_id DESC limit 0, '.$number;
        $newest_three_restaurant = $query_tool->query($sql);
        if ($newest_three_restaurant->num_rows > 0) {
            while ($row = $newest_three_restaurant->fetch_row()) {
                render_newest_restaurant($row);
            }
            echo '<div class="clearfix"></div>';
        }
    }

}

/**
 * [render_newest_restaurant description]
 * @param  [type] $data [description]
 */
function render_newest_restaurant($data) {
    $restaurant_id = $data[0];
    $restaurant_name = $data[1];
    $restaurant_cuisine = $data[2];
    $restaurant_rate = $data[3];
    $image_path = get_a_image($restaurant_id);
    echo '<div class="col-md-3 col-sm-6">';
    echo '<div class="restaurant-gallery">';
    echo '<div class="img-stars">';
    echo '<img src="'.$image_path.'" alt="'.$restaurant_name.'" title="'.$restaurant_name.'" />';
    echo '<div class="stars">';
    echo '<div class="stars-blank"></div>';
    echo '<div class="stars-gold" style="width: '.($restaurant_rate / 5) * 95 . 'px;"></div>';
    echo '</div>';
    echo '</div>';
    echo '<div class="info">';
    echo '<h4>'.$restaurant_name.'</h4>';
    echo '<p class="text-muted">'.$restaurant_cuisine.'</p>';
    echo '<a href="restaurant.php?restaurant_id='.$restaurant_id.'" class="btn btn-info btn-sm">View</a>';
    echo '</div>';
    echo '</div>';
    echo '</div>';
}

/**
 * [get_three_dates_from_today description]
 * @return array 3 dates string from today (Server time)
 */
function get_three_dates_from_today() {
    date_default_timezone_set('America/Los_Angeles');
    $dates = array();
    array_push($dates, date('Y-m-d'));
    $tomorrow = new DateTime($dates[0]);
    $tomorrow->modify('+1 day');
    array_push($dates, $tomorrow->format('Y-m-d'));
    $the_day_after_tomorrow = new DateTime($dates[1]);
    $the_day_after_tomorrow->modify('+1 day');
    array_push($dates, $the_day_after_tomorrow->format('Y-m-d'));
    return $dates;
}
?>
