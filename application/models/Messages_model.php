<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Messages_model extends CI_Model {

	public function insert_message($data){
		$last_id = $this->db->insert('messages',$data);
		return $last_id;
	}

	public function update_message($id,$data){
		if(is_array($id)){
			$this->db->where($id);
		}else{
			$this->db->where('id',$id);
		}
		$this->db->update('messages',$data);
		$ret = $this->db->affected_rows();
		return $ret;
	}

	public function fetch_all_messages_from_user($other_user_id){

		$u_data = $this->session->userdata('user');		
		$user_id = $u_data['id'];		

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

	public function fetch_last_message($sender_id,$receiver_id){
		$this->db->where('is_delete','0');
		//$this->db->where(['sender_id'=>$sender_id,'receiver_id'=>$receiver_id]);

		$this->db->where('(sender_id = '.$sender_id.' and receiver_id ='.$receiver_id.')', null, false);
		$this->db->or_where('(sender_id = '.$receiver_id.' and receiver_id ='.$sender_id.')', null, false);

		// $this->db->or_where(['sender_id'=>$receiver_id,'receiver_id'=>$sender_id]);
		$res = $this->db->order_by('id','desc')->limit(1)->get('messages')->row_array();
		return $res;
	}

	public function unread_cnt_message_indivisual($user_id,$db_user_id){
		$res = $this->db->get_where('messages',['receiver_id'=>$db_user_id,'sender_id'=>$user_id,'is_delete'=>'0','status'=>'0'])
						->num_rows();
		return $res;
	}

	public function fetch_videosnap_request($user1,$user2){
		$data_fetch_1 = $this->db->get_where('videosnaps',['requestby_id'=>$user1,'requestto_id'=>$user2])->row_array();
		$data_fetch_2 = $this->db->get_where('videosnaps',['requestby_id'=>$user2,'requestto_id'=>$user1])->row_array();
		if(!empty($data_fetch_1)){ return $data_fetch_1; }
		if(!empty($data_fetch_2)){ return $data_fetch_2; }
		return false;
	}

	public function unread_video_snaps($sender_id,$receiver_id){		
		$u_data = $this->session->userdata('user');
        $user_id = $u_data['id'];

		$this->db->where('(sender_id = '.$sender_id.' and receiver_id ='.$receiver_id.' and is_delete = 0 and status = 0 and message_type = 5)', null, false);
		$this->db->or_where('(sender_id = '.$receiver_id.' and receiver_id ='.$sender_id.' and is_delete = 0 and status = 0 and message_type = 5)', null, false);
		$res = $this->db->order_by('id','desc')->get('messages')->result_array();

		$new_str = '';
		if(!empty($res)){
			foreach($res as $r){
				if($r['sender_id'] == $user_id){
                    // Login User                    
                    $u_data = $this->db->select('profile_media_id')->get_where('users',['id'=>$r['sender_id']])->row_array();                    
                    $u_media_data = $this->db->get_where('media',['id'=>$u_data['profile_media_id']])->row_array();          
                    $cls = 'rider-talk';                    
                    $img_url = my_img_url($u_media_data['media_type'],$u_media_data['media_name']);
                }else{
                    // Chat User                    
                    $u_data = $this->db->select('profile_media_id')->get_where('users',['id'=>$r['sender_id']])->row_array();
                    $u_media_data = $this->db->get_where('media',['id'=>$u_data['profile_media_id']])->row_array();                    
                    $cls = 'user-talk';
                    $img_url = my_img_url($u_media_data['media_type'],$u_media_data['media_name']);
                }

				$new_str .='<li id="li_'.$r['id'].'" class="'.$cls.'">';
				$new_str .='<div class="pic-01">';
				$new_str .='<img src="'.$img_url.'" />';
				$new_str .='</div><p>';
				
				$msg_date = date('h:i a',strtotime($r['created_date']));
				$new_str .='<a data-cls="'.$cls.'" data-msg-id="'.$r['id'].'" onclick="delete_snap(this)" data-url="'.$r['media_name'].'" target="_blank" class="chat_video for_pointer">';
				$media_thumb = str_replace('.mp4', '.png', $r['media_name']);
				$new_str .='<img width="50px" height="50px" src="'.base_url().'bio/show_img/'.$media_thumb.'/1" /></a>';
				$new_str .='<span>'.$msg_date.'</span></p></li>';				
			}
		}
		return $new_str;
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