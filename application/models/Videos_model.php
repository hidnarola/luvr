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
        $res = $this->db->get()->row_array();
        return $res;
    }

    public function getRandomVideo() {
        $this->db->select('media_name,media_thumb');
        $this->db->from('media');
        $this->db->where('media_type', 4);
        $this->db->like('media_name', ".mp4");
        $this->db->limit(1);
        $this->db->order_by("rand()", "");
        $res = $this->db->get()->row_array();
        return $res;
    }

    public function getRandomVideoOwner($id) {
        $this->db->select('userid,media_name,media_thumb');
        $this->db->from('media');
        $this->db->where('media_type', 4);
        if ($id != null && is_numeric($id)) {
            $this->db->where_not_in('userid', array($id));
        }
        $this->db->like('media_name', ".mp4");
        $this->db->limit(1);
        $this->db->order_by("rand()", "");
        $res = $this->db->get()->row_array();
        return $res;
    }

}

?>