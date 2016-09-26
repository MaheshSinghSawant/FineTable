<?php
require_once('../lib/Reservation.class.php');
require_once('../lib/Review.class.php');

// Since reviews need to have reservations first, put tests together.
$fields = array(
    'user_id' => '1',
    'restaurant_id' => '3',
    'people_size' => '5',
    'time_of_reservation' => '01-02-2015 19:03:55',
    'time_made_reservation' => '01-01-2015 11:09:11'
);

echo "Testing for creating a reservation: <br>";
$rsv_id = 0;
$rsv = new Reservation();
try {
    $rsv_id = $rsv->create($fields);
    $success = true;
    $message = 'New reservation creation is complete.<br>';
    echo $message;
} catch (Exception $e) {
    $success = false;
    $message = $e->getMessage();
    echo "Creating a new reservation Error: " . $message . "<br>";
}
echo (($success == true) ? "SUCCESS" : "FAILURE") . "<br>";

try {
	echo "<br>Testing find reservation:<br>";
	$success = $rsv->find($rsv_id);

	print_r($rsv->get_reservation_info());
	echo "<br>";
	echo $rsv->get_reservation_info_json();
	echo "<br>";
} catch (Exception $e) {
    $success = false;
    $message = $e->getMessage();
    echo "Find reservation Error: " . $message . "<br>";
}
echo (($success == true) ? "SUCCESS" : "FAILURE") . "<br>";

try {
    echo "<br>Testing find reservation by restaurant_id and user_id:<br>";
    $success = $rsv->find_by_restaurant_and_user(3, 1, '01-02-2015');

    print_r($rsv->get_reservation_info());
    echo "<br>";
    echo $rsv->get_reservation_info_json();
    echo "<br>";
} catch (Exception $e) {
    $success = false;
    $message = $e->getMessage();
    echo "Find reservation Error: " . $message . "<br>";
}
echo (($success == true) ? "SUCCESS" : "FAILURE") . "<br>";


$fields = array(
    'user_id' => '1',
    'restaurant_id' => '3',
    'title' => 'Review Title',
    'content' => 'Review content ABCD',
    'rating' => '5'
);

echo "<br>Testing for creating a review: <br>";
$review_id = 0;
$review = new Review();
try {
    $review_id = $review->create($fields);
    $success = true;
    $message = 'New review creation is complete.<br>';
    echo $message;
} catch (Exception $e) {
    $success = false;
    $message = $e->getMessage();
    echo "Creating a new review Error: " . $message . "<br>";
}
echo (($success == true) ? "SUCCESS" : "FAILURE") . "<br>";

try {
    echo "<br>Testing find review:<br>";
    print_r( "If found review:" . $review->find($review_id) . "<br>");

    print_r($review->get_review_info());
    echo "<br>";
    echo $review->get_review_info_json();
    echo "<br>";
} catch (Exception $e) {
    $success = false;
    $message = $e->getMessage();
    echo "Find review Error: " . $message . "<br>";
}
echo (($success == true) ? "SUCCESS" : "FAILURE") . "<br>";

try {
    echo "<br>Testing for canceling a review : <br> review_id: $review_id<br>";
    echo "Result: " . $review->cancel($review_id) . "<br>";
} catch (Exception $e) {
    $success = false;
    $message = $e->getMessage();
    echo "Cancel review Error: " . $message . "<br>";
}
echo (($success == true) ? "SUCCESS" : "FAILURE") . "<br>";

try {
	echo "<br>Testing for canceling a reservation : <br> reservation_id: $rsv_id<br>";
	echo "Result: " . $rsv->cancel($rsv_id) . "<br>";
    $success = true;
} catch (Exception $e) {
    $success = false;
    $message = $e->getMessage();
    echo "Cancel reservation Error: " . $message . "<br>";
}
echo (($success == true) ? "SUCCESS" : "FAILURE") . "<br>";

?>
