<?php

class Users_model extends CI_Model {

    function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    function getUserByCol($column, $value) {
        return $this->db->get_where('users', array($column => $value));
    }

    public function fetch_userdata($where,$is_single= false,$select = '*'){
    	$this->db->select($select);
    	$this->db->where($where);
    	$res = $this->db->get('users');
    	$return_data = $res->result_array;    	
    	if($is_single){ $return_data = $res->row_array(); }
    	return $return_data;
    }

    public function insert_record($data){
    	$this->db->insert('users',$data);
    	$ins_id = $this->db->insert_id();
    	return $ins_id;	
    }

}

?>