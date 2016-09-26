<?php

class DataAccessLayer {
    # Information for team database. When testing on your own,
    # change usename, password, and database to your own information

    private $_host = 'sfsuswe.com',
            $_username = 'f15g06',
            $_password = 'MT06Team',
            $_database = 'student_f15g06',
            $_conn;

    /**
     * __construct constructor
     */
    function __construct() {
        $this->_conn = new mysqli($this->_host, $this->_username, $this->_password, $this->_database);

        if (mysqli_connect_errno()) {
            echo 'Connect to database failed: ' . mysqli_connect_errno() . '\n';
            exit();
        }
    }

    /**
     * __destruct destructor
     */
    function __destruct() {
        $this->_conn->close();
    }

    #
    /**
     * query to execute a sql statement
     * @param  string $sql SQL statement
     * @return object      SQL results
     */

    public function query($sql) {
        return $this->_conn->query($sql);
    }

    /**
     * select_columns to select all columns in a table
     * @param  string $table the name of table
     * @return object        SQL results
     */
    public function select_columns($table) {
        $sql = 'select * from ' . $table;
        return $this->query($sql);
    }

    /**
     * select_columns_by_id to select columns in a table by id
     * @param  string $table    name of table
     * @param  string $id_name  name of id column
     * @param  string $id_value value of id
     * @return object           SQL results
     */
    public function select_columns_by_id($table, $id_name, $id_value) {
        $sql = 'select * from ' . $table
                . ' where '
                . $id_name . ' = "' . $id_value . '"';
        return $this->query($sql);
    }

    /**
     * [select_columns_by_id_order description]
     * @param  [type] $table    [description]
     * @param  [type] $id_name  [description]
     * @param  [type] $id_value [description]
     * @param  [type] $order    [description]
     * @return [type]           [description]
     */
    public function select_columns_by_id_order($table, $id_name, $id_value, $order) {
        $sql = 'select * from ' . $table
                . ' where '
                . $id_name . ' = "' . $id_value . '"'
                . ' order by ' . $order;
        return $this->query($sql);
    }

    /**
     * select columns by where constraint
     * @param  string $table table name
     * @param  string $where constraint column_name = 'column_value'
     * @return mysql object
     */
    public function select_columns_by_where($table, $where) {
        $sql = 'select * from ' . $table
                . ' where ' . $where;
        // echo $sql . "<br>";
        return $this->query($sql);
    }

    /**
     * [select_columns_by_where_order description]
     * @param  [type] $table [description]
     * @param  [type] $where [description]
     * @param  [type] $order [description]
     * @return [type]        [description]
     */
    public function select_columns_by_where_order($table, $where, $order) {
        $sql = 'select * from ' . $table
                . ' where ' . $where
                . ' order by ' . $order;
        return $this->query($sql);
    }

    /**
     * [select_fields description]
     * @param  [type] $table  [description]
     * @param  [type] $params [description]
     * @return [type]         [description]
     */
    public function select_fields($table, $params) {
        $sql = 'select ' . $this->parse_array_to_values_without_quote($params)
                . ' from ' . $table;
        return $this->query($sql);
    }

    /**
     * [select_fields_by_id description]
     * @param  [type] $table    [description]
     * @param  [type] $params   [description]
     * @param  [type] $id_name  [description]
     * @param  [type] $id_value [description]
     * @return [type]           [description]
     */
    public function select_fields_by_id($table, $params, $id_name, $id_value) {
        $sql = 'select ' . $this->parse_array_to_values_without_quote($params)
                . ' from ' . $table
                . ' where '
                . $id_name . ' = "' . $id_value . '"';
        // echo $sql . '<br>';
        return $this->query($sql);
    }

    /**
     * [select_fields_by_id_order description]
     * @param  [type] $table    [description]
     * @param  [type] $params   [description]
     * @param  [type] $id_name  [description]
     * @param  [type] $id_value [description]
     * @param  [type] $order    [description]
     * @return [type]           [description]
     */
    public function select_fields_by_id_order($table, $params, $id_name, $id_value, $order) {
        $sql = 'select ' . $this->parse_array_to_values_without_quote($params)
                . ' from ' . $table
                . ' where '
                . $id_name . ' = "' . $id_value . '"'
                . ' order by ' + $order;
        return $this->query($sql);
    }

    /**
     * [select_fields_by_where description]
     * @param  [type] $table  [description]
     * @param  [type] $params [description]
     * @param  [type] $where  [description]
     * @return [type]         [description]
     */
    public function select_fields_by_where($table, $params, $where) {
        $sql = 'select ' . $this->parse_array_to_values_without_quote($params)
                . ' from ' . $table
                . ' where ' . $where;
        // echo $sql . '<br>';
        return $this->query($sql);
    }

    /**
     * [select_fields_by_where_order description]
     * @param  [type] $table  [description]
     * @param  [type] $params [description]
     * @param  [type] $where  [description]
     * @param  [type] $order  [description]
     * @return [type]         [description]
     */
    public function select_fields_by_where_order($table, $params, $where, $order) {
        $sql = 'select ' . $this->parse_array_to_values_without_quote($params)
                . ' from ' . $table
                . ' where ' . $where
                . ' order by ' . $order;
        return $this->query($sql);
    }

    /**
     * [search_columns description]
     * @param  [type] $table            [description]
     * @param  [type] $fields_to_search [description]
     * @param  [type] $search_key       [description]
     * @return [type]                   [description]
     */
    public function search_columns($table, $fields_to_search, $search_key) {
        $sql = 'select * from ' . $table . ' where ';
        foreach ($fields_to_search as $wfield => $field) {
            foreach ($search_key as $wkey => $key) {
                $sql .= $field . ' like \'%' . $key . '%\'';
                if ($wkey != end(array_keys($search_key)))
                    $sql .= ' or ';
            }
            if ($wfield != end(array_keys($fields_to_search)))
                $sql .= ' or ';
        }
        return $this->query($sql);
    }

    /**
     * [search_columns_key_constrain description]
     * @param  [type] $table            [description]
     * @param  [type] $fields_to_search [description]
     * @param  [type] $search_key       [description]
     * @param  [type] $key_id           [description]
     * @param  [type] $key_value        [description]
     * @return [type]                   [description]
     */
    public function search_columns_key_constrain($table, $fields_to_search, $search_key, $key_id, $key_value) {
        // add key matching to the sql statement
    }

    /**
     * [insert description]
     * @param  [type] $table  [description]
     * @param  [type] $params [description]
     * @return [type]         [description]
     */
    public function insert($table, $params) {
        $sql = "insert into " . $table . ' ( ';
        $fields = $this->parse_array_to_keys($params);
        $values = $this->parse_array_to_values($params);
        $sql .= $fields . ' ) values ( ' . $values . ' )';
        //   echo $sql . '<br>';
        $this->query($sql);
        return $this->_conn->insert_id;
    }

    /**
     * [insert_query description]
     * @param  [type] $sql [description]
     * @return [type]      [description]
     */
    public function insert_query($sql) {
        $this->query($sql);
        return $this->_conn->insert_id;
    }

    /**
     * [update_by_id description]
     * @param  [type] $table    [description]
     * @param  [type] $params   [description]
     * @param  [type] $id_name  [description]
     * @param  [type] $id_value [description]
     * @return [type]           [description]
     */
    public function update_by_id($table, $params, $id_name, $id_value) {
        $sql = 'update ' . $table . ' set '
                . $this->parse_array_to_kv_pair($params)
                . ' where '
                . $id_name . ' = ' . $id_value;
        $this->query($sql);
        return $this->_conn->affected_rows;
    }

    /**
     * [update_by_where description]
     * @param  [type] $table          [description]
     * @param  [type] $params         [description]
     * @param  [type] $where_sentence [description]
     * @return [type]                 [description]
     */
    public function update_by_where($table, $params, $where_sentence) {
        $sql = 'update ' . $table . ' set '
                . $this->parse_array_to_kv_pair($params)
                . ' where ' . $where_sentence;

        $this->query($sql);
        return $this->_conn->affected_rows;
    }

    /**
     * [delete_by_id description]
     * @param  [type] $table    [description]
     * @param  [type] $id_name  [description]
     * @param  [type] $id_value [description]
     * @return [type]           [description]
     */
    public function delete_by_id($table, $id_name, $id_value) {
        $sql = 'delete from ' . $table . ' where '
                . $id_name . ' = "' . $id_value . '"';
        $this->query($sql);
        return $this->_conn->affected_rows;
    }

    /**
     * [delete_by_where description]
     * @param  [type] $table [description]
     * @param  [type] $where [description]
     * @return [type]        [description]
     */
    public function delete_by_where($table, $where) {
        $sql = 'delete from ' . $table . ' where '
                . $where;
        $this->query($sql);
        return $this->_conn->affected_rows;
    }

    /**
     * [parse_array_to_values description]
     * @param  [type] $params [description]
     * @return [type]         [description]
     */
    private function parse_array_to_values($params) {
        $str = '';
        foreach ($params as $key => $value) {
            $str .= "'" . $value . "'";
            if ($key != end(array_keys($params))) {
                $str .= ', ';
            }
        }
        return $str;
    }

    /**
     * [parse_array_to_values description]
     * @param  [type] $params [description]
     * @return [type]         [description]
     */
    private function parse_array_to_values_without_quote($params) {
        $str = '';
        foreach ($params as $key => $value) {
            $str .= $value;
            if ($key != end(array_keys($params))) {
                $str .= ', ';
            }
        }
        return $str;
    }

    /**
     * [parse_array_to_keys description]
     * @param  [type] $params [description]
     * @return [type]         [description]
     */
    private function parse_array_to_keys($params) {
        $str = '';
        $keys = array_keys($params);
        foreach ($keys as $key) {
            $str .= $key;
            if ($key != end($keys)) {
                $str .= ', ';
            }
        }
        return $str;
    }

    /**
     * [parse_array_to_kv_pair description]
     * @param  [type] $params [description]
     * @return [type]         [description]
     */
    private function parse_array_to_kv_pair($params) {
        $str = '';
        foreach ($params as $key => $value) {
            $str .= $key . ' = "' . $value . '"';
            if ($key != end(array_keys($params))) {
                $str .= ', ';
            }
        }
        return $str;
    }

}
