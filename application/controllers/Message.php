<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Message extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(['Users_model', 'Messages_model', 'Bio_model']);
    }

    public function index() {
        
    }

    public function insert_data() {

        $message = trim($this->input->post('message'));
        $encode_message = base64_encode($message);

        $sender_id = $this->input->post('session_id');
        $receiver_id = $this->input->post('chat_user_id');

        if ($_FILES['msg_file']['error'] != '4') {

            $ret_data = $this->upload_message_data($_FILES, $sender_id);

            $unique_id = $ret_data['raw_name'];

            $arr = array(
                'message_type' => $ret_data['message_type'],
                'message' => $ret_data['message'],
                'media_name' => $ret_data['raw_name'] . '.' . $ret_data['ext'],
                'unique_id' => $unique_id,
                'sender_id' => $sender_id,
                'receiver_id' => $receiver_id,
                'created_date' => date('Y-m-d H:i:s'),
                'is_encrypted' => $ret_data['is_encrypted'], // dynamic
                'encrypted_message' => $encode_message
            );
        } else {

            $unique_id = $sender_id . '_' . random_string('alnum', 8);

            $arr = array(
                'message_type' => '1',
                'message' => $message,
                'media_name' => '',
                'unique_id' => $unique_id,
                'sender_id' => $sender_id,
                'receiver_id' => $receiver_id,
                'created_date' => date('Y-m-d H:i:s'),
                'is_encrypted' => '1', // dynamic
                'encrypted_message' => $encode_message
            );
        }

        echo json_encode($arr);
    }

    public function upload_message_data($file_data, $sender_id) {

        $u_data = $this->session->userdata('user');
        $user_id = $u_data['id'];

        $path = $file_data['msg_file']['name'];
        $ext = pathinfo($path, PATHINFO_EXTENSION);

        if ($ext == 'mp4') {
            $ret['ext'] = 'mp4';
            $upload_path = UPLOADPATH_VIDEO;
        } else {
            $ret['ext'] = 'png';
            $upload_path = UPLOADPATH_IMAGE;
        }

        $new_file_name = $sender_id . '_' . random_name_generate(); // Generate random file name of 8 characters only - Look into Site helper for reference

        $config['file_name'] = $new_file_name;
        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = 'jpg|png|jpeg|mp4';
        $config['max_size'] = '30000';
        $config['detect_mime'] = TRUE;
        $config['file_ext_tolower'] = TRUE;

        $this->upload->initialize($config);

        if (!$this->upload->do_upload('msg_file')) {
            $error = array('error' => $this->upload->display_errors());
            pr($error);
        } else {

            $data = array('upload_data' => $this->upload->data());

            $full_path = $data['upload_data']['full_path'];
            $file_name = $data['upload_data']['file_name'];
            $raw_name = $data['upload_data']['raw_name'];

            if ($data['upload_data']['is_image'] == '1') {
                $file_name = replace_extension($file_name, "png");
                $new_name = $data['upload_data']['file_path'] . $file_name;
                rename($full_path, $new_name);
                $full_path = $new_name;
            }

            $thumb_name = $raw_name . '.png';
            $ret['raw_name'] = $raw_name; // v! Return data
            $thumb_path = UPLOADPATH_THUMB . '/' . $thumb_name;

            // IF image then create thumb using GD library otherwise use ffmpeg for create image
            if ($data['upload_data']['is_image'] == '1') {
                _createThumbnail($full_path, $thumb_path);
                $media_type = '1';
                $ret['message_type'] = '2'; // v! Return data ( 2 is for image )
                $ret['message'] = 'Image';
                $ret['is_encrypted'] = '0';
            } else {
                exec(FFMPEG_PATH . ' -i ' . $full_path . ' -ss 00:00:01.000 -vframes 1 ' . $thumb_path);
                $media_type = '2';
                $ret['message_type'] = '3'; // v! Return data ( 3 is for video )
                $ret['message'] = 'Video'; // v! Return data
                $ret['is_encrypted'] = '1';
            }

            if ($ext == 'mp4') {
                $thumb_name = $raw_name . '.mp4';
            } else {
                $thumb_name = $raw_name . '.png';
            }

            $ins_data = array(
                'userid' => $u_data['id'],
                'media_name' => $file_name,
                'media_thumb' => $thumb_name,
                'media_type' => $media_type,
                'created_date' => date('Y-m-d H:i:s'),
                'is_bios' => '0'
            );
            $this->Bio_model->insert_media($ins_data);

            return $ret;
        }
    }

    public function all_chats() {

        $u_data = $this->session->userdata('user');
        $user_id = $u_data['id'];
        $data['db_user_data'] = $this->Users_model->fetch_userdata(['id' => $user_id], true);

        $data['all_matches'] = $this->Messages_model->get_new_matches($user_id, $data['db_user_data']['lastseen_date']);
        $data['all_messages'] = $this->Messages_model->all_chat_messages($user_id);
        $data['all_messages_user_ids'] = array_column($data['all_messages'], 'sender_id');

        $data['sub_view'] = 'message/all_messages';
        $data['meta_title'] = "Chat Messages";

        $this->load->view('main', $data);
    }

    public function chat($chat_user_id) {

        $u_data = $this->session->userdata('user');
        $user_id = $u_data['id'];

        $data['chat_user_id'] = $chat_user_id;

        $data['db_user_data'] = $this->Users_model->fetch_userdata(['id' => $user_id], true);
        $data['db_user_media'] = $this->Bio_model->fetch_mediadata(['id' => $data['db_user_data']['profile_media_id']], true);

        $data['chat_user_data'] = $this->Users_model->fetch_userdata(['id' => $chat_user_id], true);
        $data['chat_user_media'] = $this->Bio_model->fetch_mediadata(['id' => $data['chat_user_data']['profile_media_id']], true);

        $data['last_message_id'] = (int) $this->Messages_model->fetch_all_messages_from_user($user_id, $chat_user_id);

        $data['sub_view'] = 'message/message_list';
        $data['meta_title'] = "Chat Messages";

        $this->load->view('main', $data);
    }

    public function get_video_id() {
        $image_name = $this->input->post('image_name');
        $res_data = $this->Bio_model->fetch_mediadata(['media_thumb' => $image_name], true, 'id');
        echo $res_data['id'];
    }

    public function delete_chat() {
        $msg_id = $this->input->post('msg_id');
        $this->Messages_model->update_message($msg_id, ['is_delete' => '1']);
    }

    function videocall($id = null, $calling_id = null, $msg_id = null, $room_id = null) {
        if (is_numeric($id) && $id != null) {
            $u_data = $this->session->userdata('user');
            $user_id = $u_data['id'];
            $data['db_user_data'] = $this->Users_model->fetch_userdata(['id' => $user_id], true);
            $data['chat_user_data'] = $this->Users_model->fetch_userdata(['id' => $id], true);
            $data['chat_user_media'] = $this->Bio_model->fetch_mediadata(['id' => $data['chat_user_data']['profile_media_id']], true);
            $data['sub_view'] = 'message/videocall';
            $data['meta_title'] = "Video Call";
            $data['direct'] = ($room_id != null && !empty($room_id)) ? false : true;
            if ($room_id != null && !empty($room_id))
                $data['room_id'] = $room_id;
            else
                $data['room_id'] = $user_id . "_" . random_string('alnum', 8);
            $data['msg_id'] = $msg_id;
            $data['caller_id'] = $calling_id;
            $this->load->view('main', $data);
        } else {
            show_404();
        }
    }

    public function getUserDetail() {
        $select = $this->input->post('select');
        $id = $this->input->post('id');
        $this->db->select($select);
        $this->db->from('users');
        $this->db->where('id', $id);
        $rs = $this->db->get()->row_array();
        echo json_encode($rs);
        exit;
    }

}

/* End of file Message.php */
/* Location: ./application/controllers/Message.php */