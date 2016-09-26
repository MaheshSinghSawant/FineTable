<?php
    require_once '../lib/helper.functions.php';
    require_once '../lib/Reservation.class.php';
    require_once '../lib/Restaurant.class.php';
    require_once '../lib/Mail.class.php';
    // var_dump($_POST);
    if(isset($_POST)) {
        if ($_POST['operation'] == 'get_menu_data') {
            $success = false;
            $message = '';
            /*
            1. get 3 date from today
            2. create dates array
            3. check array's date query time_slots_capacity
                1. if no date
                    1. get restaurant's max_capacity
                    2. create a row defined by the date
                    3. put max_capacity to each slots
                    5. push current date to date array
                    4. push all time slots into current date => array
                2. if has date
                    1. query each time slots capacity
                    2. compare to max_capacity
                        1. if less push slots
                        2. if not do nothing
             */
            try {
                $restaurant_id = $_POST['restaurant_id'];
                $query_tool = new DataAccessLayer();
                $dates = get_three_dates_from_today();
                $current_restaurant_menu_data = array(
                    'restaurant_id' => $restaurant_id,
                    'menu_data' => array()
                );
                foreach($dates as $date) {
                    $sql = 'select date, restaurant_id, slot_5_6, slot_6_7, slot_7_8, slot_8_9, slot_9_10 from time_slots_capacity where restaurant_id = '.$restaurant_id.' and date = "'.$date.'"';
                    $results = $query_tool->query($sql);
                    if ($results->num_rows <=0) {
                        $restaurant = new Restaurant();
                        if ($restaurant->find($restaurant_id)) {
                            $max_capacity = $restaurant->get_max_capacity();
                        } else {
                            throw new Exception("Restaurant id is incorrect!");
                        }
                        $sql = 'insert into time_slots_capacity (date, restaurant_id, slot_5_6, slot_6_7, slot_7_8, slot_8_9, slot_9_10) values ("'.$date.'", '.$restaurant_id.', '.$max_capacity.', '.$max_capacity.', '.$max_capacity.', '.$max_capacity.', '.$max_capacity.')';
                        if (!($query_tool->query($sql))) {
                            throw new Exception("Update database error!");
                        }
                        // TODO put data in json
                        $current_date_menu_data[$date] = array(
                            '05:00PM - 06:00PM' => $max_capacity,
                            '06:00PM - 07:00PM' => $max_capacity,
                            '07:00PM - 08:00PM' => $max_capacity,
                            '08:00PM - 09:00PM' => $max_capacity,
                            '09:00PM - 10:00PM' => $max_capacity
                        );
                    } else {
                        $row = $results->fetch_row();
                        // var_dump($row);
                        if (date('Y-m-d') == $date) {
                            $current_date_menu_data[$date] = array();
                            if (time() < strtotime('17:00:00')) {
                                $current_date_menu_data[$date]['05:00PM - 06:00PM'] = $row[2];
                            }
                            if (time() < strtotime('18:00:00')) {
                                $current_date_menu_data[$date]['06:00PM - 07:00PM'] = $row[3];
                            }
                            if (time() < strtotime('19:00:00')) {
                                $current_date_menu_data[$date]['07:00PM - 08:00PM'] = $row[4];
                            }
                            if (time() < strtotime('20:00:00')) {
                                $current_date_menu_data[$date]['08:00PM - 09:00PM'] = $row[5];
                            }
                            if (time() < strtotime('21:00:00')) {
                                $current_date_menu_data[$date]['09:00PM - 10:00PM'] = $row[6];
                            }
                        } else {
                            $current_date_menu_data[$date] = array(
                                '05:00PM - 06:00PM' => $row[2],
                                '06:00PM - 07:00PM' => $row[3],
                                '07:00PM - 08:00PM' => $row[4],
                                '08:00PM - 09:00PM' => $row[5],
                                '09:00PM - 10:00PM' => $row[6]
                            );
                        }
                    }
                    if (!empty($current_date_menu_data[$date])) {
                        $current_restaurant_menu_data['menu_data'][$date] = $current_date_menu_data[$date];
                    }
                }
                $success = true;
                $message = 'Got menus data.';
            } catch (Exception $e) {
                $success = false;
                $message = $e->getMessage();
            }
            $return_result = array(
                'success' => $success,
                'message' => $message
            );
            if ($success) {
                $return_result['menu_data'] = $current_restaurant_menu_data;
            }
            echo json_encode($return_result);
        } else if ($_POST['operation'] == 'make_reservation') {
            // var_dump($_POST);
            $time_to_slots_hash = array(
                '05:00PM - 06:00PM' => 'slot_5_6',
                '06:00PM - 07:00PM' => 'slot_6_7',
                '07:00PM - 08:00PM' => 'slot_7_8',
                '08:00PM - 09:00PM' => 'slot_8_9',
                '09:00PM - 10:00PM' => 'slot_9_10'
            );
            $time_to_24_hours = array(
                '05:00PM - 06:00PM' => 17,
                '06:00PM - 07:00PM' => 18,
                '07:00PM - 08:00PM' => 19,
                '08:00PM - 09:00PM' => 20,
                '09:00PM - 10:00PM' => 21
            );
            $user_email = $_POST['user_email'];
            $user_phone = $_POST['user_phone'];
            if (isset($_POST['guest_reservation']) && $_POST['guest_reservation'] == 'true') {
                $fields = array(
                    'restaurant_id' => $_POST['restaurant_id'],
                    'people_size' => $_POST['people_size'],
                    'date_of_reservation' => $_POST['date'],
                    'time_of_reservation' => $time_to_24_hours[$_POST['time']],
                    'effective' => 1,
                    'guest_info' => $_POST['guest_info']
                );
            } else {
                $fields = array(
                    'user_id' => $_POST['user_id'],
                    'restaurant_id' => $_POST['restaurant_id'],
                    'people_size' => $_POST['people_size'],
                    'date_of_reservation' => $_POST['date'],
                    'time_of_reservation' => $time_to_24_hours[$_POST['time']],
                    'effective' => 1
                );
            }

            try {
                $query_tool = new DataAccessLayer();
                $sql = 'select '.$time_to_slots_hash[$_POST['time']].', date, restaurant_id from time_slots_capacity where date = "'.$fields['date_of_reservation'].'" and restaurant_id = "'.$fields['restaurant_id'].'"';
                $results = $query_tool->query($sql);
                if ($results->num_rows > 0) {
                    $row = $results->fetch_row();
                    $previous_capacity = $row[0];
                    if ($previous_capacity >= $fields['people_size']) {
                        $new_reservation = new Reservation();
                        $new_reservation->create($fields);
                        $next_capacity = $previous_capacity - $fields['people_size'];
                        $sql = 'update time_slots_capacity set '.$time_to_slots_hash[$_POST['time']].' = '.$next_capacity.' where date = "'.$fields['date_of_reservation'].'" and restaurant_id = "'.$fields['restaurant_id'].'"';
                        if ($query_tool->query($sql)) {
                            // send email to user to confirm reservation
                            $restaurant = new Restaurant();
                            $restaurant->find($fields['restaurant_id']);
                            $mail = Mail::getInstance();
                            $mailto = $_POST['user_email'];
                            $subject = 'FineTable - Restaurant Reservation Confirmation!';
                            if (isset($_POST['guest_reservation']) && $_POST['guest_reservation'] == 'true') {
                                $body = '
                                <div class="mail-body" style="padding: 20px; border: 5px solid rgb(254, 127, 65); border-radius: 4px; margin: 0 auto; width: 90%;">
                                    <p>Hello '. $_POST['guest_fullname'] . ',</p>
                                    <p>Congratulations!</p>
                                    <p>You have made a reservation with restaurant: '. $restaurant->get_restaurant_name() .'.</p>
                                    <p>'.$restaurant->get_full_address().'</p>
                                    <p><b>Date: </b>'.$_POST['date'].'</p>
                                    <p><b>Time: </b>'.$_POST['time'].'</p>
                                    <p>If you need to make a change with the reservation, please contact the restaurant at: '.$restaurant->get_phone().'</p>
                                    <p>Thank you!</p>
                                    <p>FineTable team</p>
                                </div>
                                ';
                            } else {
                                $body = '
                                <div class="mail-body" style="padding: 20px; border: 5px solid rgb(254, 127, 65); border-radius: 4px; margin: 0 auto; width: 90%;">
                                    <p>Hello '. $_COOKIE["login_user_firstname"] . ',</p>
                                    <p>Congratulations!</p>
                                    <p>You have made a reservation with restaurant: '. $restaurant->get_restaurant_name() .'.</p>
                                    <p>'.$restaurant->get_full_address().'</p>
                                    <p><b>Date: </b>'.$_POST['date'].'</p>
                                    <p><b>Time: </b>'.$_POST['time'].'</p>
                                    <p>If you need to make a change with the reservation, please contact the restaurant at: '.$restaurant->get_phone().'</p>
                                    <p>Thank you!</p>
                                    <p>FineTable team</p>
                                </div>
                                ';
                            }

                            $mail->add_mailto($mailto);
                            $mail->add_subject($subject);
                            $mail->add_body($body);
                            $mail->send();
                            $success = true;
                            $message = "Congratulations! You have made reservation!";
                        } else {
                            throw new Exception("Database update failed. Please report to the administrators!");
                        }
                    } else {
                        throw new Exception("Sorry, the restaurant does not an available table at the time slot you chose.");
                    }
                } else {
                    throw new Exception("Database error! Please report to the administrators!");
                }
            } catch (Exception $e) {
                $success = false;
                $message = $e->getMessage();
            }
            $return_result = array(
                'success' => $success,
                'message' => $message
            );
            if ($success) {
                $return_result['restaurant_info'] = array(
                    'name' => $restaurant->get_restaurant_name(),
                    'date' => $fields['date_of_reservation'],
                    'time' => $_POST['time'],
                    'address' => $restaurant->get_full_address()
                );
            }
            echo json_encode($return_result);
        }
    }
?>
