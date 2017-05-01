<?php

class Videos_model extends CI_Model {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
    }

    public function manageUserAgent() {
        $result = $this->db->get_where('user_agents', array('user_agent' => $_SERVER['HTTP_USER_AGENT']))->row_array();
        if (empty($result) || $result == null) {
            $data = array(
                'ip' => $this->input->ip_address(),
                'user_agent' => $_SERVER['HTTP_USER_AGENT']
            );
            $this->db->insert('user_agents', $data);
            return true;
        }
        return false;
    }

    public function getRandomUserAgent() {
        $this->db->select('ip,user_agent');
        $this->db->from('user_agents');
        $this->db->limit(1);
        $this->db->order_by("rand()", "");
        $res = $this->db->get('users')->row_array();
        return $res;
    }

}

?>