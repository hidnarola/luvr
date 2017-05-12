<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Messages_model extends CI_Model {

	public function insert_message($data){
		$last_id = $this->db->insert('messages',$data);
		return $last_id;
	}

	public function fetch_all_messages_from_user($other_user_id){

		$u_data = $this->session->userdata('user');		
		$user_id = $u_data['id'];

		// $user_id = '99';
		// $other_user_id = '120';

		$this->db->select('msg.*,usr1.profile_media_id as meedia1,usr2.profile_media_id as meedia2,
						   med1.media_thumb as med1_name,med1.media_type as med1_type,
						   med2.media_thumb as med2_name,med2.media_type as med2_type');
		
		$this->db->where(['msg.sender_id'=>$user_id,'msg.receiver_id'=>$other_user_id]);
		$this->db->or_where(['msg.sender_id'=>$other_user_id,'msg.receiver_id'=>$user_id]);

		$this->db->join('users usr1','usr1.id = '.$user_id);
		$this->db->join('users usr2','usr2.id = '.$other_user_id);

		$this->db->join('media med1','med1.id = usr1.profile_media_id');
		$this->db->join('media med2','med2.id = usr2.profile_media_id');

		$ret_data = $this->db->order_by('msg.id','desc')->limit(1)->get('messages msg')->row_array();

		return $ret_data['id'];
	}

	public function all_chat_messages($user_id){
		
		$query = "" . "Select * from (select `id`, `message_type`, `message`, `status`, `media_name`, `unique_id`, `sender_id`, `receiver_id`,is_encrypted, encrypted_message,
            CONCAT(DATE_FORMAT( created_date,'%Y-%m-%d %H:%i:%s'), ' +0000') as created_date,
            CONCAT(DATE_FORMAT( updated_date,'%Y-%m-%d %H:%i:%s'), ' +0000') as updated_date
            ,( CASE WHEN (sender_id != ".$user_id." ) THEN sender_id ELSE receiver_id END ) AS userid
            from messages where  message_type!=5 and is_delete =0 and (`sender_id` = ".$user_id." or `receiver_id` = ".$user_id.")   ORDER BY `created_date` desc) s GROUP BY s.userid";
				
		$res_query = $this->db->query($query);
		$res_array = $res_query->result_array();

		return $res_array;
	}

	public function get_user_message_data($user_id){
		$this->db->select('u.id,u.user_name,u.encrypted_username,m.media_name,m.media_thumb,m.media_type');
		$this->db->join('media m','u.profile_media_id = m.id');
		$this->db->where(['u.id'=>$user_id]);
		$ret = $this->db->get('users u')->row_array();
		return $ret;
	}

	// ------------------------------------------------------------------------

	public function get_new_matches($userid,$lastseen_date){
		
		$query = "". "select * from (select count(ur.id) as count, u.id,u.userid as insta_id,user_name,is_encrypted,encrypted_username,encrypted_bio,encrypted_one_liner,email,one_liner,full_name,age,bio,address,birthdate,u.latlong,gender,work,school
             ,m.media_id,m.id as profile_media_id,IFNULL (m.media_type, 0) as media_type, m.`media_name` as user_profile,m.media_thumb,2 as relation_status,us.is_timestamps_on,
            (CASE WHEN ( vs.requestto_id != $userid ) THEN 1 ELSE 0 END ) as isSender,IFNULL(vs.status,0) as videosnap_request_status ,
              (select is_blocked from users_relation WHERE requestby_id = $userid and requestto_id = u.id) as is_blocked,
            DATE_FORMAT(insta_datetime,'%Y-%m-%d %H:%i:%s') as insta_datetime
            from users_relation as ur
            left join users as u ON ((ur.`requestto_id` = u.id and ur.requestby_id= $userid) or (ur.`requestto_id` = $userid and ur.requestby_id= u.id))
            left join user_settings as us ON us.userid = u.id left join media as m ON m.`id` = `profile_media_id`
            left join videosnaps AS vs ON ((vs.`requestto_id` = u.id and vs.requestby_id= $userid) or (vs.`requestto_id` = $userid and vs.requestby_id= u.id))
            where (relation_status= 2 or relation_status= 3) and u.id!= $userid and  u.is_delete =0 and ur.updated_date  > '$lastseen_date'
            group by u.id ORDER BY u.id) as s where s.count > 1";

		$ret = $this->db->query($query)->result_array();
		return $ret;
	}


}

/* End of file Messages_model.php */
/* Location: ./application/models/Messages_model.php */