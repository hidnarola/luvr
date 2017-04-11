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
        } else {
            $this->db->insert('users', $data);
        }
        return true;
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
        $return_data = $res->result_array;
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

    /* This function will add media in db. */

    public function insert_media($data) {
        $this->db->insert('media', $data);
        $ins_id = $this->db->insert_id();
        return $ins_id;
    }

    /* This function will return user settings based on column and value provided. */

    public function getUserSetings($column, $value) {
        if (!empty($column) && !empty($value)) {
            return $this->db->get_where('user_settings', array($column => $value))->row_array();
        }
        return false;
    }

    /* This function will update user's settings. */

    public function updateUserSettings($data) {
        if (!empty($data['userid'])) {
            $this->db->where('userid', $data['userid']);
            $this->db->update('user_settings', $data);
            return true;
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

    public function getBlockedUsers($user_id, $offset = null, $fetch_counts = false) {
        if (!empty($user_id) && is_numeric($user_id)) {
            if ($fetch_counts == false) {
                $this->db->select('users_relation.*,users.userid,users.user_name,users.email,users.full_name,users.age,media.*');
                $this->db->from('users_relation');
            }
            $this->db->join('users', 'users.id = users_relation.requestto_id');
            $this->db->join('media', 'media.id = users.profile_media_id', 'left');
            $this->db->where('users_relation.requestby_id', $user_id);
            $this->db->where('users_relation.is_blocked', 1);
            if ($fetch_counts == false) {
                if (!empty($offset) && $offset != null)
                    $this->db->limit(10, $offset);
                else
                    $this->db->limit(10);
                return $this->db->get()->result_array();
            } else
                return $this->db->count_all_results('users_relation');
        }
        return false;
    }

}

?>