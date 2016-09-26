<?php
require_once('DataAccessLayer.class.php');

class Reservation extends DataAccessLayer {
    private $_reservation_id,
            $_user_id,
            $_restaurant_id,
            $_people_size,
            $_effective,
            $_time_of_reservation,
            $_date_of_reservation,
            $_time_made_reservation;

    public function __construct() {
        parent::__construct();
        $this->_table_name = 'reservations';
    }

    public function __destruct() {
      parent::__destruct();
    }

    /**
     * create and insert a new reservation into database
     * @param array $fields reservation info from form post
     */
    public function create($fields = array()) {
        if ($fields) {
            if (isset($fields['user_id']) && $fields['user_id']) {
                $where = 'user_id = "'.$fields['user_id'].'" and date_of_reservation = "'.$fields['date_of_reservation'].'" and time_of_reservation = "'.$fields['time_of_reservation'].'"';
                if ($this->any_duplicates($where)) {
                    throw new Exception('You have already made a reservation at that time. :)');
                }
            }
            if ($this->insert($this->_table_name, $fields) <= 0) {
                throw new Exception("Error occurred when communicating with database, please try again!");
            }
        } else {
            throw new Exception("Argument is incorrect");
        }
        // // check input
        // if (empty($fields)) {
        //     throw new Exception("Argument is incorrect");
        // }
        // // if (empty($fields['user_id']) ||
        // //     empty($fields['restaurant_id']) ||
        // //     empty($fields['people_size']) ||
        // //     empty($fields['time_of_reservation']) ||
        // //     empty($fields['time_made_reservation']) ) {
        // //     throw new Exception("Error Input!");
        // // }
        //
        // $fields['effective'] = true;
        // date_default_timezone_set('UTC');
        // $tm = strtotime($fields['time_of_reservation']);
        // $fields['time_of_reservation'] = date("Y-m-d H:i:s", $tm);
        // $fields['date_of_reservation'] = $fields['time_of_reservation'];
        //
        // $tm = strtotime($fields['time_made_reservation']);
        // $fields['time_made_reservation'] = date("Y-m-d H:i:s", $tm);
        // // check if user has made reservation already
        // $where = 'user_id = "' . $fields['user_id'] . '" AND ' .
        //          'restaurant_id = "'. $fields['restaurant_id'] . '"';
        // if ($this->any_duplicates($where)) {
        //     throw new Exception("User has already reserved this restaurant!");
        // }
        //
        // // insert to db
        // $ret = $this->insert($this->_table_name, $fields);
        //
        // if ( $ret <= 0) {
        //     throw new Exception("Error occured when communicate with database, please try again!");
        // }
        //
        // return $ret;
    }

    /**
     * find reservation
     */
    public function find($reservation_id) {
        $select_fields = array('reservation_id', 'user_id', 'restaurant_id', 'people_size', 'effective', 'time_of_reservation', 'date_of_reservation', 'time_made_reservation');
        if ($reservation_id) {
            $rsv_raw_data = $this->select_fields_by_id($this->_table_name, $select_fields, 'reservation_id', $reservation_id);
        }

        if ($rsv_raw_data->num_rows > 0) {
            $this->parse_raw_data($rsv_raw_data->fetch_row());
            return true;
        }
        return false;
    }

    public static function find_all_by_date($date) {
        $query_tool = new DataAccessLayer();
        date_default_timezone_set('UTC');
        $tm = strtotime($date);
        $date = date('Y-m-d', $tm);
        $sql = 'select * from reservations where date_of_reservation = "' . $date . '"';
        $results = $query_tool->query($sql);
        if ($results->num_rows > 0) {
            return $results;
        } else {
            throw new Exception("No reservations found in database error!");
        }
    }

    /**
     * for review use: check wheher the user have reserved
     */
    public function if_rsved_restaurant($restaurant_id, $user_id) {
        $where = 'restaurant_id = "' . $restaurant_id . '" AND user_id = "' . $user_id . '"';
        // $select_fields = array('reservation_id', 'user_id', 'restaurant_id', 'people_size', 'message', 'effective', 'time_of_reservation', 'date_of_reservation', 'time_made_reservation');

        if ($restaurant_id && $user_id) {
            $rsv = $this->any_duplicates($where);
        }
        if ($rsv) {
            return true;
        }
        return false;
    }

    /**
     * find reservation by date, restaurant and user
     */
    public function find_by_restaurant_and_user($restaurant_id, $user_id, $date) {
        date_default_timezone_set('UTC');
        $tm = strtotime($date);
        $date = date("Y-m-d", $tm);

        $where = 'restaurant_id = "' . $restaurant_id . '" AND user_id = "' . $user_id .
                    '"AND date_of_reservation="'. $date . '"';
        $select_fields = array('reservation_id', 'user_id', 'restaurant_id', 'people_size', 'effective', 'time_of_reservation', 'date_of_reservation', 'time_made_reservation');

        if ($restaurant_id && $user_id) {
            $rsv_raw_data = $this->select_fields_by_where($this->_table_name, $select_fields, 'reservation_id', $where);
        }

        if ($rsv_raw_data->num_rows > 0) {
            $this->parse_raw_data($rsv_raw_data->fetch_row());
            return true;
        }
        return false;
    }

    public static function any_effective_reservation($restaurant_id = null, $user_id = null) {
        if ($restaurant_id && $user_id) {
            $sql = 'select * from reservations where restaurant_id = '.$restaurant_id.' and user_id = '.$user_id.' and effective = 1';
            $query_tool = new DataAccessLayer();
            $results = $query_tool->query($sql);
            // echo $sql;
            if ($results->num_rows > 0) {
                return true;
            }
        }
        return false;
    }

    public static function find_by_restaurant_id($restaurant_id) {
        if ($restaurant_id) {
            $sql = 'select * from reservations where restaurant_id = '.$restaurant_id;
            $query_tool = new DataAccessLayer();
            $results = $query_tool->query($sql);
            if ($results->num_rows > 0) {
                return $results;
            } else {
                return null;
            }
        }
    }

    public function cancel($reservation_id) {
        if (empty($reservation_id)) {
            throw new Exception("Argument is incorrect");
        }
        $fields = array();
        $fields['effective'] = false;
        $result = $this->update_by_id($this->_table_name, $fields, 'reservation_id', $reservation_id);

        if ($result) {
            return true;
        }
        return false;
    }

    /**
     * wrap reservation information to a hash array
     * @return arry
     */
    public function get_reservation_info() {
        return array(
            'reservation_id' => $this->_reservation_id,
            'user_id' => $this->_user_id,
            'restaurant_id' => $this->_restaurant_id,
            'people_size' => $this->_people_size,
            'effective' => $this->_effective,
            'time_of_reservation' => $this->_time_of_reservation,
            'date_of_reservation' => $this->_date_of_reservation,
            'time_made_reservation' => $this->_time_made_reservation
        );
    }
    /**
     * get reservation info in json format
     * @return json object
     */
    public function get_reservation_info_json() {
        $result = $this->get_reservation_info();
        return json_encode($result);
    }

    /**
     * find if thers is any duplicates in the database by the where constraint
     * @param  string $where column_name = 'column_value'
     * @return boolean
     */
    private function any_duplicates($where) {
        $result = $this->select_columns_by_where('reservations', $where);

        if ($result->num_rows > 0) {
            return true;
        }
        return false;
    }

    /**
     * parse a row into local data fields
     * @param  array $row A row from database
     */
    private function parse_raw_data($row) {
        $this->_reservation_id = $row[0];
        $this->_user_id = $row[1];
        $this->_restaurant_id = $row[2];
        $this->_people_size = $row[3];
        $this->_effective = $row[4];
        $this->_time_of_reservation = $row[5];
        $this->_date_of_reservation = $row[6];
        $this->_time_made_reservation = $row[7];
    }
}
?>
