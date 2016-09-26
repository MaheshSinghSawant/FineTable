<?php
require_once('DataAccessLayer.class.php');
require_once('Reservation.class.php');

class Review extends DataAccessLayer {
    private $_review_id,
            $_restaurant_id,
            $_title,
            $_content,
            $_rating,
            $_user_id,
            $_date,
            $_reviews_raw_data;

    function __construct() {
        parent::__construct();
        $this->_table_name = 'reviews';
    }

    function __destruct() {
      parent::__destruct();
    }

    /**
     * create and insert a new review into database
     * @param array $fields review info from form post
     */
    function create($fields = array()) {
        // check input
        if (empty($fields)) {
            throw new Exception("Argument is incorrect");
        }
        // if (empty($fields['content'])) {
        //     $fields['message'] = "";
        // }
        // if (empty($fields['restaurant_id']) ||
        //     empty($fields['title']) ||
        //     empty($fields['content']) ||
        //     empty($fields['rating']) ||
        //     empty($fields['user_id']) ) {
        //     throw new Exception("Error Input!");
        // }

        date_default_timezone_set('America/Los_Angeles');
        $fields['date'] = date("Y-m-d H:i:s", time());

        // check if user has made reservation already
        $restaurant_id = intval($fields['restaurant_id']);
        $user_id = intval($fields['user_id']);
        $rsv = new Reservation();
        $ret = $rsv->if_rsved_restaurant($restaurant_id, $user_id);

        if (!$ret) {
            throw new Exception("Error occured when creating review, user has not made reservation yet!");
        };

        // check if user has reviewed already
        $where = 'user_id = "' . $fields['user_id'] . '" AND ' .
                 'restaurant_id = "'. $fields['restaurant_id'] . '"';
        if ($this->any_duplicates($where)) {
            throw new Exception("User has already reviewed this restaurant!");
        }

        // insert to db
        $ret = $this->insert($this->_table_name, $fields);

        if ( $ret <= 0) {
            throw new Exception("Error occurred when communicating with database, please try again!");
        }

        return $ret;
    }

    /**
     * find review
     */
    function find($review_id) {
        $select_fields = array('review_id', 'restaurant_id', 'title', 'content', 'rating', 'user_id', 'date');
        if ($review_id) {
            $rsv_raw_data = $this->select_fields_by_id($this->_table_name, $select_fields, 'review_id', $review_id);
        }

        if ($rsv_raw_data->num_rows > 0) {
            // $this->parse_raw_data($rsv_raw_data->fetch_row());
            $this->_reviews_raw_data = $rsv_raw_data;
            return true;
        }
        return false;
    }

    function find_reviews_by_restaurant_id($id) {
        if (is_numeric($id)) {
            $sql = 'select * from reviews where restaurant_id = "'.$id.'"';
            $results = $this->query($sql);
            if ($results->num_rows > 0) {
                $this->_reviews_raw_data = $results;
                return true;
            }
        } else {
            throw new Exception("Can only use restaurant id to find reviews!");
        }
        return false;
    }

    function cancel($review_id) {
        if (empty($review_id)) {
            throw new Exception("Argument is incorrect");
        }
        $result = $this->delete_by_id($this->_table_name, 'review_id', $review_id);

        if ($result) {
            return true;
        }
        return false;
    }

    /**
     * wrap review information to a hash array
     * @return arry
     */
    public function get_review_info() {
        return array(
            'review_id' => $this->_review_id,
            'restaurant_id' => $this->_restaurant_id,
            'title' => $this->_title,
            'content' => $this->_content,
            'rating' => $this->_rating,
            'user_id' => $this->_user_id,
            'date' => $this->_date
        );
    }
    /**
     * get review info in json format
     * @return json object
     */
    public function get_review_info_json() {
        $result = $this->get_review_info();
        return json_encode($result);
    }

    public function get_reviews_raw_data() {
        if ($this->_reviews_raw_data) {
            return $this->_reviews_raw_data;
        }
    }

    public static function any_review_before($restaurant_id, $user_id) {
        if ($restaurant_id && $user_id) {
            $sql = 'select * from reviews where restaurant_id = '.$restaurant_id.' and user_id = '.$user_id;
            $query_tool = new DataAccessLayer();
            $results = $query_tool->query($sql);
            if ($results->num_rows > 0) {
                return true;
            }
        }
        return false;
    }

    public static function get_all_reviews_by_restaurant($restaurant_id) {
        if ($restaurant_id) {
            $sql = 'select * from reviews where restaurant_id = '.$restaurant_id;
            // echo $sql;
            $query_tool = new DataAccessLayer();
            $results = $query_tool->query($sql);
            if ($results->num_rows > 0) {
                return $results;
            } else {
                return null;
            }
        }
    }

    /**
     * find if thers is any duplicates in the database by the where constraint
     * @param  string $where column_name = 'column_value'
     * @return boolean
     */
    private function any_duplicates($where) {
        $result = $this->select_columns_by_where('reviews', $where);
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
        $this->_review_id = $row[0];
        $this->_restaurant_id = $row[1];
        $this->_title = $row[2];
        $this->_content = $row[3];
        $this->_rating = $row[4];
        $this->_user_id = $row[5];
        $this->_date = $row[6];
    }
}
?>
