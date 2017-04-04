<?php

class Users_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function getUserByCol($column, $value) {
        return $this->db->get_where('users', array($column => $value));
    }

}

?>