<?php

class Users_model extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    /* This function will fetch user information based on column and value provided. */

    public function getUserByCol($column, $value) {
        return $this->db->get_where('users', array($column => $value))->row_array();
    }

    /* This function will add new user or update existing user if id will be provided along with $data. */

    public function manageUser($data) {
        if (!empty($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('users', $data);
            return true;
        } else {
            $this->db->insert('users', $data);
        }
    }

    /* This function will check and return whether user's preferences already exist in db. */

    public function checkUserPreferencesSet($user_id) {
        if (!empty($user_id) && is_numeric($user_id)) {
            return $this->db->get_where('user_filter', array('userid' => $user_id))->result_array();
        }
        return false;
    }

    /* This function will fetch user related data based on where clauses provided. */
    public function fetch_userdata($where, $is_single = false, $select = '*') {
        $this->db->select($select);
        $this->db->where($where);
        $res = $this->db->get('users');
        $return_data = $res->result_array();
        if ($is_single) {
            $return_data = $res->row_array();
        }
        return $return_data;
    }

    // insert into users table
    public function insert_record($data) {
        $this->db->insert('users', $data);
        $ins_id = $this->db->insert_id();
        return $ins_id;
    }

    public function update_record($where,$data){
        $this->db->where($where);
        $this->db->update('users',$data);
    }
    

    /* This function will return user settings based on column and value provided. */

    public function getUserSetings($column, $value) {
        if (!empty($column) && !empty($value)) {
            return $this->db->get_where('user_settings', array($column => $value))->row_array();
        }
        return false;
    }

    /* This function will mark particular user as liked or disliked in db. */

    public function likeDislikeUser($data) {
        if (!empty($data)) {
            $this->db->insert('users_relation', $data);
            return $this->db->insert_id();
        }
        return false;
    }

    /* This function will return list of users who were blocked by user with provided user_id. */

    public function getBlockedUsers($user_id) {
        if (!empty($user_id) && is_numeric($user_id)) {
            return $this->db->get_where('users_relation', array('requestby_id' => $user_id, 'is_blocked' => 1))->result_array();
        }
        return false;
    }

}

?>