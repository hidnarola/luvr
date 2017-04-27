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

        $this->db->select($select);
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

    public function fetch_media_for_sidebar($user_id){

        $this->db->from('media');
        $this->db->where(['userid'=>$user_id,'is_active'=>'1']);
        $num_results = $this->db->count_all_results();
        
        $this->db->limit(4);

        if($num_results >= 12){
            $this->db->limit(12);
        }
        if($num_results >= 9 && $num_results < 12){
            $this->db->limit(8);
        }
        if($num_results >= 5 && $num_results < 9){
            $this->db->limit(4);
        }

        $this->db->select('id,media_type,media_name,media_thumb');
        $this->db->where(['userid'=>$user_id,'is_active'=>'1']);
        $res = $this->db->get('media')->result_array();
        return $res;
    }
}

/* End of file Bio_model.php */
/* Location: ./application/models/Bio_model.php */