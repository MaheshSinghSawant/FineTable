<?php
require_once('DataAccessLayer.class.php');

class User extends DataAccessLayer {
    private $_user_id,
            $_username,
            $_password,
            $_firstname,
            $_lastname,
            $_email,
            $_phone_number,
            $_user_group,
            $_host_restaurant_id,
            $_table_name,
            $_user_raw_data;

    /**
     * constructor
     */
    function __construct() {
        parent::__construct();
        $this->_table_name = 'users';
    }

    /**
     * destructor
     */
    function __destruct() {
      parent::__destruct();
    }

    /**
     * create and insert a new user into database
     * @param  array $fields user info from form post
     */
    public function create($fields = array()) {
        if ($fields) {
            // in users table, username, email and phone number have to be unique
            // need to check if there is any duplicates in database
            // for other tables please check the structure
            // if find any duplicates, throw a related exception
            // check username
            $where = 'username = "' . $fields['username'] . '"';
            if ($this->any_duplicates($where)) {
                throw new Exception("Username has already been registered!");
            }
            $where = 'email = "' . $fields['email'] . '"';
            if ($this->any_duplicates($where)) {
                throw new Exception("Email has already been registered!");
            }
            $where = 'phone_number = "' . $fields['phone_number'] . '"';
            if ($this->any_duplicates($where)) {
                throw new Exception("Phone number has already been registered!");
            }
            // all checks are passed, then insert new user into database
            // if insert succed, will return the new row's id to $result
            // if failed, will return 0
            // by default, user group is: user
            $fields['user_group'] = 'user';
            if ($this->insert($this->_table_name, $fields) <= 0) {
                throw new Exception("Error occurred when communicating with database, please try again!");
            }
        } else {
            throw new Exception("Argument is incorrect");
        }
    }

    /**
     * update exsit user information
     * @param  array  $fields user information need to be updated, including a user_id for constraint
     */
    public function update($fields = array()) {
        if ($fields) {
            if (array_key_exists('email', $fields)) {
                $where = 'email = "' . $fields['email'] . '"';
                if ($this->any_duplicates($where)) {
                    throw new Exception("Email has already been registered!");
                }
            }
            if (array_key_exists('phone_number', $fields)) {
                $where = 'phone_number = "' . $fields['phone_number'] . '"';
                if ($this->any_duplicates($where)) {
                    throw new Exception("Phone number has already been registered!");
                }
            }
            $user_id = $fields['user_id'];
            unset($fields['user_id']); // before update, remove user_id from $fields
            $results = $this->update_by_id($this->_table_name, $fields, 'user_id', $user_id);
        } else {
            throw new Exception('Argument is incorrect!');
        }
    }

    /**
     * delete a user from database by user_id or username
     * @param  int || string $user user_id or username
     * @return boolean
     */
    public function delete($user = null) {
        if ($user) {
            if (is_numeric($user)) {
                $result = $this->delete_by_id($this->_table_name, 'user_id', $user);
            } else {
                $where = 'username = "' . $user . '"';
                $result = $this->delete_by_where($this->_table_name, $where);
            }
            if ($result) {
                return true;
            }
        }
        return false;
    }

    /**
     * find is there is a user in database by user_id or user_name
     * if the user exists, extrac user infomation to local
     * @param  int or string $user user_id or username
     * @return boolean
     */
    public function find($user = null) {
        $select_fields = array('user_id', 'username', 'firstname', 'lastname', 'email', 'phone_number', 'user_group', 'password', 'host_restaurant_id');
        if ($user) {
            // password shouldn't be retrieved
            if (is_numeric($user)) {
                $user_raw_data = $this->select_fields_by_id($this->_table_name, $select_fields, 'user_id', $user);
            } else {
                $user_raw_data = $this->select_fields_by_where($this->_table_name, $select_fields, 'username = "' . $user . '"');
            }
            if ($user_raw_data->num_rows > 0) {
                $this->parse_raw_data($user_raw_data->fetch_row());
                return true;
            }
        }
        return false;
    }

    /**
     * [login description]
     * @param  string $password password to be checked
     * @return boolean
     */
    public function login($password = null) {
        if (strcmp($password, $this->_password)) {
            return true;
        } else {
            return false;
        }
    }

    /**
     * wrap user information to a hash array
     * @return arry
     */
    public function get_user_info() {
        return array(
            'user_id' => $this->_user_id,
            'username' => $this->_username,
            'firstname' => $this->_firstname,
            'lastname' => $this->_lastname,
            'email' => $this->_email,
            'phone_number' => $this->_phone_number,
            'user_group' => $this->_user_group
        );
    }

    /**
     * return current user id
     * @return user_id
     */
    public function get_user_id() {
        if ($this->_user_id) {
            return $this->_user_id;
        }
        return 0;
    }

    public function get_user_name() {
        if ($this->_username) {
            return $this->_username;
        }
        return '';
    }

    public function get_user_firstname() {
        if ($this->_firstname) {
            return $this->_firstname;
        }
        return '';
    }

    public function get_user_lastname() {
        if ($this->_lastname) {
            return $this->_lastname;
        }
        return '';
    }

    public function get_user_email() {
        if ($this->_email) {
            return $this->_email;
        }
    }

    public function get_user_phone() {
        if ($this->_phone_number) {
            return $this->_phone_number;
        }
    }

    public function get_host_restaurant_id() {
        if ($this->_host_restaurant_id) {
            return $this->_host_restaurant_id;
        }
    }

    public function switche_user_group_to($group = null) {
        $user_groups = array('admin', 'owner', 'host', 'user');
        if (in_array($group, $user_groups)) {
            if ($this->_user_id) {
                $sql = 'update users set user_group = "'.$group.'" where user_id = '.$this->_user_id;
                $this->query($sql);
            } else {
                throw new Exception('Please use find first to find the use!');
            }
        }
    }

    /**
     * get user info in json format
     * @return json object
     */
    public function get_user_info_json() {
        $result = $this->get_user_info();
        return json_encode($result);
    }

    public static function query_all_users() {
        $sql = 'select * from users';
        $query_tool = new DataAccessLayer();
        $results = $query_tool->query($sql);
        if ($results->num_rows > 0) {
            return $results;
        } else {
            throw new Exception("No registered user found or database error!");
        }
    }

    /**
     * find if thers is any duplicates in the database by the where constraint
     * @param  string $where column_name = 'column_value'
     * @return boolean
     */
    private function any_duplicates($where) {
        $result = $this->select_columns_by_where('users', $where);
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
        $this->_user_id = $row[0];
        $this->_username = $row[1];
        $this->_firstname = $row[2];
        $this->_lastname = $row[3];
        $this->_email = $row[4];
        $this->_phone_number = $row[5];
        $this->_user_group = $row[6];
        $this->_password = $row[7];
        $this->_host_restaurant_id = $row[8];
    }

    private function is_correct_password($password) {

    }
}
?>
