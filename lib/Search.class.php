<?php
require_once('DataAccessLayer.class.php');
class Search extends DataAccessLayer {
    private $_raw_data;

    function __construct() {
        parent::__construct();
    }

    function __destruct() {
        parent::__destruct();
    }

    function accurate_search($search_key = null) {

    }

    function blur_search($search_key = null, $start = null, $rows = null) {
        if ($search_key) {
            $restaurant_fields = array('restaurant_id', 'name', 'address', 'city', 'state', 'phone_number','description', 'rating', 'cuisine_type', 'approved');
            $image_fields = array('thumb_medium_path', 'restaurant_id');
            $tag_fields = array('tag_content', 'restaurant_id');
            $search_keys = explode(' ', $search_key);
            $search_restaurants_where = 'approved = 1 and (';

            foreach($search_keys as $value) {
                $search_restaurants_where .= "name like '%" . $value . "%'";
                if ($value != end($search_keys)) {
                    $search_restaurants_where .= " or ";
                }
            }
            $search_restaurants_where .= " or ";
            foreach($search_keys as $value) {
                $search_restaurants_where .= "address like '%" . $value . "%'";
                if ($value != end($search_keys)) {
                    $search_restaurants_where .= " or ";
                }
            }
            $search_restaurants_where .= " or ";
            foreach($search_keys as $value) {
                $search_restaurants_where .= "city like '%" . $value . "%'";
                if ($value != end($search_keys)) {
                    $search_restaurants_where .= " or ";
                }
            }
            $search_restaurants_where .= " or ";
            foreach($search_keys as $value) {
                $search_restaurants_where .= "state like '%" . $value . "%'";
                if ($value != end($search_keys)) {
                    $search_restaurants_where .= " or ";
                }
            }
            $search_restaurants_where .= " or ";
            foreach($search_keys as $value) {
                $search_restaurants_where .= "cuisine_type like '%" . $value     . "%'";
                if ($value != end($search_keys)) {
                    $search_restaurants_where .= " or ";
                }
            }
            $search_restaurants_where .= ')';

            // $search_restaurants_where .= " or ";
            // $search_restaurants_where = "name like '%" . $search_key . "%'"
            //                             . " or address like '%" . $search_key . "%'"
            //                             . " or city like '%" . $search_key . "%'"
            //                             . " or state like '%" . $search_key . "%'"
            //                             . " or cuisine_type like '%" . $search_key . "%'";
            if ($start >= 0 && $rows >= 1) {
                $search_restaurants_where .= (" limit " . $start . ", " . $rows);
            }
            $this->_raw_data = $this->select_fields_by_where('restaurants', $restaurant_fields, $search_restaurants_where);
            return $this->_raw_data;
        } else {
            throw new Exception("Argument is in correct!");
        }
    }
}
?>
