<?php
require_once 'DataAccessLayer.class.php';
class Restaurant extends DataAccessLayer
{
    private $_restaurant_id,
            $_restaurant_name,
            $_address,
            $_city,
            $_state,
            $_zipcode,
            $_phone_number,
            $_description,
            $_rating,
            $_cuisine_type,
            $_max_capacity,
            $_current_capacity,
            $_owner_id,
            $_approved,
            $_number_of_reviews,
            $_restaurant_raw_data;

    public function __construct() {
        parent::__construct();
        $this->_table_name = 'restaurants';
    }

    public function __destruct() {
        parent::__destruct();
        //$this->_conn.close();
    }

    public function create($fields = null) {
        if ($fields) {
            $where = 'phone_number = "'. $fields['phone_number'] . '"';
            if ($this->any_duplicates($where)) {
                throw new Exception('Phone number has already been registered!');
            }
            if (($this->_restaurant_id = $this->insert($this->_table_name, $fields)) <= 0) {
                throw new Exception('Error occurred when communicating with database, please try again!');
            }
        } else {
            throw new Exception('Argument is incorrect');
        }
    }

    public function find($restaurant = null) {
        $select_fields = array('restaurant_id', 'name', 'address', 'city', 'state', 'phone_number', 'description', 'rating', 'cuisine_type', 'max_capacity', 'current_capacity', 'owner_user_id', 'approved', 'number_of_reviews', 'zipcode');
        if ($restaurant) {
            if (is_numeric($restaurant)) {
                $restaurant_raw_data = $this->select_fields_by_id($this->_table_name, $select_fields, 'restaurant_id', $restaurant);
            } else {
                $restaurant_raw_data = $this->select_fields_by_where($this->_table_name, $select_fields, 'name = "'.$restaurant.'"');
            }
            if ($restaurant_raw_data->num_rows > 0) {
                $this->_restaurant_raw_data = $restaurant_raw_data->fetch_row();
                $this->parse_raw_data($this->_restaurant_raw_data);
                return true;
            }
        }
        return false;
    }

    public static function query_all_restaurants() {
        $sql = 'select * from restaurants';
        $query_tool = new DataAccessLayer();
        $results = $query_tool->query($sql);
        if ($results->num_rows > 0) {
            return $results;
        } else {
            throw new Exception("No registered restaurant found or database error!");
        }
    }

    public function approve_or_deny($restaurant, $approved) {
        $change_arr = array("approved" => $approved);
        if ($restaurant) {
            if (is_numeric($restaurant)) {
                $restaurant_raw_data = $this->update_by_id($this->_table_name, $change_arr, 'restaurant_id', $restaurant);
            }
            return true;
        }
        return false;
    }

    public function get_restaurant_info() {
        return array(
              'restaurant_id' => $this->_restaurant_id,
              'name' => $this->_restaurant_name,
              'address' => $this->_address,
              'city' => $this->_city,
              'state' => $this->_state,
              'zipcode' => $this->_zipcode,
              'cuisine_type' => $this->_cuisine_type,
              'rating' => $this->_rating,
              'description' => $this->_description,
              'phone_number' => $this->_phone_number,
              'max_capacity' => $this->_max_capacity,
              'current_capacity' => $this->_current_capacity,
              'owner_user_id' => $this->_owner_id,
              'approved' => $this->_approved,
              'number_of_reviews' => $this->_number_of_reviews
        );
    }

    public function get_restaurant_name() {
        if ($this->_restaurant_name) {
            return $this->_restaurant_name;
        }
        return '';
    }

    public function get_full_address() {
        return $this->_address.' '.$this->_city.' '.$this->_state;
    }

    public function get_phone() {
        if ($this->_phone_number) {
            return $this->_phone_number;
        }
    }

    public function get_restaurant_info_json() {
        $result = $this->get_restaurant_info();
        return json_encode($result);
    }

    public function delete($restaurant = null) {
        if ($restaurant) {
            if (is_numeric($restaurant)) {
                $result = $this->delete_by_id($this->_table_name, 'restaurant_id', $restaurant);
            }
            if ($result) {
                return true;
            }
        }

        return false;
    }

    public function update_rating($rate = null) {
        if ($rate) {
            $sql = 'update restaurants set rating = '. $rate .' where restaurant_id = '.$this->_restaurant_id;
            if (!$this->query($sql)) {
                throw new Exception('Update rate to database failed!');
            }
        }
    }

    public function get_restaurant_id() {
        if ($this->_restaurant_id) {
            return $this->_restaurant_id;
        }
    }

    public function get_max_capacity() {
        if ($this->_max_capacity) {
            return $this->_max_capacity;
        }
    }

    public function get_rating() {
        if ($this->_rating) {
          return $this->_rating;
        }
    }

    public function get_raw_data() {
        if ($this->_restaurant_raw_data) {
            return $this->_restaurant_raw_data;
        }
    }

    private function any_duplicates($where) {
        $result = $this->select_columns_by_where('restaurants', $where);
        if ($result->num_rows > 0) {
            return true;
        }
        return false;
    }

    private function parse_raw_data($row) {
        $this->_restaurant_id = $row[0];
        $this->_restaurant_name = $row[1];
        $this->_address = $row[2];
        $this->_city = $row[3];
        $this->_state = $row[4];
        $this->_phone_number = $row[5];
        $this->_description = $row[6];
        $this->_rating = $row[7];
        $this->_cuisine_type = $row[8];
        $this->_max_capacity = $row[9];
        $this->_current_capacity = $row[10];
        $this->_owner_id = $row[11];
        $this->_approved = $row[12];
        $this->_number_of_reviews = $row[13];
        $this->_zipcode = $row[14];
    }


}
?>
