<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bio_model extends CI_Model {


	/* This function will add media in db. */    
    public function insert_media($data) {
        $this->db->insert('media', $data);
        $ins_id = $this->db->insert_id();
        return $ins_id;
    }
	
	public function update_media($id,$data){
		if(is_array($id)){
			$this->db->where($id);
		}else{
			$this->db->where('id',$id);
		}
		$this->db->update('media',$data);
		$ret = $this->db->affected_rows();
		return $ret;
	}

	/* This function will fetch user related data based on where clauses provided. */
    public function fetch_mediadata($where, $is_single = false, $select = '*') {

        // $this->db->select($select);
        $this->db->where($where);
        $res = $this->db->get('media');
        $return_data = $res->result_array();

        if ($is_single) {
            $return_data = $res->row_array();
        }
        return $return_data;
    }

    public function fetch_total_feed_cnt(){
        $u_data = $this->session->userdata('user');   
        return $this->db->get_where('media',['userid'=>$u_data['id'],'is_active'=>'1'])->num_rows();
    }
}

/* End of file Bio_model.php */
/* Location: ./application/models/Bio_model.php */