<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Message extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->model(['Users_model','Messages_model']);
	}

	public function index(){
		
	}

	public function insert_data(){
		
		$message = $this->input->post('message');
		$encode_message = base64_encode($message);

		$sender_id = $this->input->post('session_id');
		$receiver_id = $this->input->post('chat_user_id');

		$unique_id = $sender_id.'_'.random_string('alnum',8);

		$db_user_img = $this->input->post('db_user_img');
		$chat_user_img = $this->input->post('chat_user_img');		

		if($_FILES['msg_file']['error'] != '4'){
			$this->upload_message_data($_FILES);

			$arr = array(
							'message_type'=>'1',
							'message'=>$message,
							'media_name'=>'',
							'unique_id'=>$unique_id,
							'sender_id'=>$sender_id,
							'receiver_id'=>$receiver_id,
							'is_encrypted'=>'1',
							'encrypted_message'=>$encode_message
						);
		}else{

			$arr = array(
							'message_type'=>'1',
							'message'=>$message,
							'media_name'=>'',
							'unique_id'=>$unique_id,
							'sender_id'=>$sender_id,
							'receiver_id'=>$receiver_id,
							'is_encrypted'=>'1',
							'encrypted_message'=>$encode_message
						);
		}

		echo json_encode($arr);
	}

	public function upload_message_data($file_data){

		$path = $file_data['msg_file']['name'];
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        
        if ($ext == 'mp4') {
            $upload_path = UPLOADPATH_VIDEO;
        } else {
            $upload_path = UPLOADPATH_IMAGE;
        }

        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = 'jpg|png|jpeg|mp4';
        $config['max_size'] = '30000';
        $config['encrypt_name'] = TRUE;
        $config['detect_mime'] = TRUE;
        $config['file_ext_tolower'] = TRUE;
		
		$this->upload->initialize($config);
		
		if ( ! $this->upload->do_upload('msg_file')){
			$error = array('error' => $this->upload->display_errors());
			pr($error);
		} else{
			$data = array('upload_data' => $this->upload->data());

			$full_path = $data['upload_data']['full_path'];
            $file_name = $data['upload_data']['file_name'];
            $raw_name  = $data['upload_data']['raw_name'];

            if ($data['upload_data']['is_image'] == '1') {
                $file_name = replace_extension($file_name, "png");
                $new_name = $data['upload_data']['file_path'] . $file_name;
                rename($full_path, $new_name);
                $full_path = $new_name;                
            }

            $thumb_name = $raw_name . '.png';
            $thumb_path = UPLOADPATH_THUMB . '/' . $thumb_name;

            // IF image then create thumb using GD library otherwise use ffmpeg for create image
            if ($data['upload_data']['is_image'] == '1') {
                _createThumbnail($full_path, $thumb_path);
            	$media_type = '1';
            } else {
                exec(FFMPEG_PATH . ' -i ' . $full_path . ' -ss 00:00:01.000 -vframes 1 ' . $thumb_path);
                $media_type = '2';
            }

            $ins_data = array(
                               	'userid'=>$u_data['id'],
                                'media_name'=>$file_name,
                                'media_thumb'=>$thumb_name,
                                'media_type'=>$media_type,
                                'created_date'=>date('Y-m-d H:i:s'),
                                'is_bios'=>'1'
                            );
            // $this->Bio_model->insert_media($ins_data);

			pr($data);
		}		
	}

	public function all_chats(){

		$u_data = $this->session->userdata('user');
		$user_id = $u_data['id'];
		$data['db_user_data'] = $this->Users_model->fetch_userdata(['id' => $user_id], true);
		$data['all_messages'] = $this->Messages_model->all_chat_messages($user_id);
		$data['all_matches'] = $this->Messages_model->get_new_matches($user_id,$data['db_user_data']['lastseen_date']);

		$data['sub_view'] = 'message/all_messages';
        $data['meta_title'] = "Chat Messages";

		$this->load->view('main', $data);
	}

	public function chat($chat_user_id){

		$u_data = $this->session->userdata('user');
		$user_id = $u_data['id'];

		$data['chat_user_id'] = $chat_user_id;		

        $data['db_user_data'] = $this->Users_model->fetch_userdata(['id' => $user_id], true);
        $data['db_user_media'] = $this->Bio_model->fetch_mediadata(['id'=>$data['db_user_data']['profile_media_id']],true);

        $data['chat_user_data'] = $this->Users_model->fetch_userdata(['id' => $chat_user_id], true);
        $data['chat_user_media'] = $this->Bio_model->fetch_mediadata(['id'=>$data['chat_user_data']['profile_media_id']],true);		

		$data['last_message_id'] = (int)$this->Messages_model->fetch_all_messages_from_user($user_id,$chat_user_id);		

        $data['sub_view'] = 'message/message_list';
        $data['meta_title'] = "Chat Messages";

		$this->load->view('main', $data);
	}
	

}

/* End of file Message.php */
/* Location: ./application/controllers/Message.php */