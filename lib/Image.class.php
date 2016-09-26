<?php
require_once('DataAccessLayer.class.php');
class Image extends DataAccessLayer {
    private $_image_id,
            $_restaurant_id,
            $_original_path,
            $_thumb_medium_path,
            $_thumb_small_path,
            $_raw_data;

    function __construct() {
        parent::__construct();
    }

    function __destruct() {
        parent::__destruct();
    }

    public function add_image_to_restaurant($fields = null) {
        if ($fields) {
            if ($insert_id = $this->insert('images', $fields) <= 0) {
                    throw new Exception("Adding image to database failed! Please report to admin.");
            }
        }
    }

    public function find($restaurant_id = null) {
        if ($restaurant_id) {
            $where = 'restaurant_id = "' . $restaurant_id . '"';
            $this->_raw_data = $this->select_columns_by_where('images', $where);
            if ($this->_raw_data->num_rows > 0) {
                $this->parse_raw_data($this->_raw_data->fetch_row());
                return true;
            }
        }
        return false;
    }

    public function get_original() {
        return $this->_original_path;
    }

    public function get_medium() {
        return $this->_thumb_medium_path;
    }

    public function get_small() {
        return $this->_thumb_small_path;
    }

    private function parse_raw_data($row) {
        $this->_image_id = $row[0];
        $this->_original_path = $row[1];
        $this->_thumb_medium_path = $row[2];
        $this->_thumb_small_path = $row[3];
        $this->_restaurant_id = $row[4];
    }
}
?>
