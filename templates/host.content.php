<?php
require_once '../lib/helper.functions.php';
require_once '../lib/Reservation.class.php';
require_once '../lib/Restaurant.class.php';
require_once '../lib/User.class.php';

/*function get_todays_date() {
    date_default_timezone_set('America/Los_Angeles');
    $dates = array();
    array_push($dates, date('Y-m-d'));
    return $dates;
}*/

function time_sort($a, $b){
    if ($a["time_of_reservation"] == $b["time_of_reservation"]) { 
        return 0;
    }
    return ($a["time_of_reservation"] < $b["time_of_reservation"]) ? -1 : 1;
}

date_default_timezone_set('America/Los_Angeles');
$date = date('Y-m-d');

$user = new User();

if (isset($_SESSION['login_user_id'])) {
    $current_user_id = $_SESSION['login_user_id'];
    $user->find($current_user_id);
}
?>

<section class="container-fluid main-body">
    <div class="container" style="margin-top: 70px">
        <h1>Restaurant Host</h1>
        <h2>Today is 
        <?php
            echo $date;
        ?>
        </h2>
        <div class="row">
            <!--div class="col-sm-6">
                <h3>Free Tables</h3>
                <table class="host-table table table-striped table-hover">
                    <thead>
                        <td>Seating Capacity</td>
                        <td>Number of Tables</td>
                    </thead>
                    <tr>
                        <td>1-2 people</td>
                        <td>7</td>
                    </tr>
                    <tr>
                        <td>3-4 people</td>
                        <td>5</td>
                    </tr>
                    <tr>
                        <td>5-6 people</td>
                        <td>2</td>
                    </tr>
                    <tr>
                        <td>7-8 people</td>
                        <td>1</td>
                    </tr>
                </table>
            </div>
            <div class="col-sm-6">
                <h3>Booked Tables</h3>
                <table class="host-table table table-striped table-hover">
                    <thead>
                        <td>Seating Capacity</td>
                        <td>Number of Tables</td>
                    </thead>
                    <tr>
                        <td>1-2 people</td>
                        <td>2</td>
                    </tr>
                    <tr>
                        <td>3-4 people</td>
                        <td>5</td>
                    </tr>
                    <tr>
                        <td>5-6 people</td>
                        <td>1</td>
                    </tr>
                    <tr>
                        <td>7-8 people</td>
                        <td>1</td>
                    </tr>
                </table>
            </div>
        </div-->
        <div class="row">
            <div class="col-sm-12">
                <h3>Reservations</h3>
                <table class="host-table table table-striped table-hover">
                    <thead>
                        <td>Time</td>
                        <td>Customer</td>
                        <td>Number of Persons</td>
                        <td>Actions</td>
                    </thead>
                    <?php
                        try {
                            $ret = Reservation::find_by_restaurant_id($user->get_host_restaurant_id());
                            //var_dump($ret);
                            //echo '<br>';
                            while ($row = $ret->fetch_array(MYSQLI_ASSOC)) {
                                $all_data[] = $row;
                            }
                            usort($all_data, "time_sort");
                            for ($i = 0; $i < $ret->num_rows; ++$i) {
                                //$row = $ret->fetch_row();
                                if ($all_data[$i]['effective'] == 1) {
                                    /* SAMPLE RESERVATION
                                     * <tr>
                                     *   <td>6:00pm</td>
                                     *   <td>Jhon Johson</td>
                                     *   <td>4</td>
                                     *   <td>
                                     *       <button class="btn btn-primary btn-lg">Confirm</button>
                                     *       <button class="btn btn-info btn-lg">Edit</button>
                                     *   </td>
                                     * </tr>
                                     */
                                    $user = new User();
                                    if ($all_data[$i]['user_id'] != NULL) {
                                        $user->find($all_data[$i]['user_id']);
                                        $name = $user->get_user_firstname() . ' ' . $user->get_user_lastname();
                                    } else {
                                        $json = ltrim($all_data[$i]['guest_info'], "'");
                                        $json = ltrim($all_data[$i]['guest_info'], "\"");
                                        $json = rtrim($json, "'");
                                        $json = rtrim($json, "\"");
                                        $guest = json_decode($json, true);
                                        $name = $guest['fullname'];
                                    }
                                    echo '<tr>
                                            <td>' . $all_data[$i]['time_of_reservation'] . ':00</td>
                                            <td>' . $name . '</td>
                                            <td>' . $all_data[$i]['people_size'] . '</td>
                                            <td>
                                                <a href="host.logic.php?id=' . $all_data[$i]['reservation_id'] . '" class="btn btn-primary btn-lg">Confirm</button>
                                                <a href="host.logic.php?id=' . $all_data[$i]['reservation_id'] . '" class="btn btn-info btn-lg">Cancel</button>
                                            </td>
                                          </tr>';
                                }
                            }
                        } catch (Exception $e) {
                            echo '<tr>
                                    <td>
                                        No reservations today.
                                    </td>
                                    <td></td>
                                    <td></td>
                                    <td></td>
                                  </tr>';
                        }
                    ?>
                </table>
            </div>
        </div>
    </div>
</section>
