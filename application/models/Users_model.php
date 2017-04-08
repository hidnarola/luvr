<?php

class Users_model extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    public function getUserByCol($column, $value) {
        return $this->db->get_where('users', array($column => $value))->row_array();
    }

    public function manageUser($data) {
        if (!empty($data['id'])) {
            $this->db->where('id', $data['id']);
            $this->db->update('users', $data);
            return true;
        } else {
            $this->db->insert('users', $data);
        }
    }

    public function checkUserPreferencesSet($user_id) {
        if (!empty($user_id) && is_numeric($user_id)) {
            return $this->db->get_where('user_filter', array('userid' => $user_id))->result_array();
        }
        return false;
    }

    public function fetch_userdata($where,$is_single= false,$select = '*'){
    	$this->db->select($select);
    	$this->db->where($where);
    	$res = $this->db->get('users');
    	$return_data = $res->result_array;    	
    	if($is_single){ $return_data = $res->row_array(); }
    	return $return_data;
    }

    // insert into users table
    public function insert_record($data){
    	$this->db->insert('users',$data);
        $ins_id = $this->db->insert_id();
    	return $ins_id;	
    }

    public function insert_media($data){
        $this->db->insert('media',$data);
        $ins_id = $this->db->insert_id();
        return $ins_id;    
    }

}

?>