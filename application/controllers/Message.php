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

        if (!empty($message)) {
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
                'encrypted_message' => $encode_message,
            );

            $status = 'text';
            $arr_str = json_encode($arr);
        } else {

            $arr = array();
            $is_error = 0;
            $my_all_files = array();

            for ($i = 1; $i <= count($_FILES); $i++) {
                if ($_FILES['msg_file_' . $i]['error'] != '4') {
                    $ret_data = $this->upload_message_data($_FILES['msg_file_' . $i], $sender_id, 'msg_file_' . $i);
                    if ($ret_data['status'] == 'upload') {
                        $arr_new = array('message_type' => $ret_data['message_type'], 'message' => $ret_data['message'], 'media_name' => $ret_data['raw_name'] . '.' . $ret_data['ext'], 'unique_id' => $ret_data['raw_name'], 'sender_id' => $sender_id, 'receiver_id' => $receiver_id, 'created_date' => date('Y-m-d H:i:s'), 'is_encrypted' => $ret_data['is_encrypted'], 'encrypted_message' => $encode_message);
                        array_push($my_all_files, $arr_new);
                    }
                } // End condition for the if() $_FILES['msg_file_'.$i]['error'] != '4'
            } // End of For loop            

            $arr = $my_all_files;
            $arr_str = json_encode($arr);
            $status = 'upload';
        }

        echo json_encode(['str' => $arr_str, 'status' => $status]);
    }

    public function upload_message_data($file_data, $sender_id, $upload_file_name) {

        $u_data = $this->session->userdata('user');
        $user_id = $u_data['id'];

        $path = $file_data['name'];
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
        $config['max_size'] = '30000000';
        $config['detect_mime'] = TRUE;
        $config['file_ext_tolower'] = TRUE;

        $this->upload->initialize($config);

        if (!$this->upload->do_upload($upload_file_name)) {
            $error = array('error' => $this->upload->display_errors());
            $ret['status'] = 'error';
            $ret['error_msg'] = $this->upload->display_errors();
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

            $ret['status'] = 'upload';
        }

        return $ret;
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
        $data['video_snap_data'] = $this->Messages_model->fetch_videosnap_request($user_id, $chat_user_id);

        $data['unread_video_snaps_str'] = $this->Messages_model->unread_video_snaps($user_id, $chat_user_id);

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

    public function change_message_status() {

        $u_data = $this->session->userdata('user');
        $user_id = $u_data['id'];

        $all_unread_msg_ids = $this->input->post('all_unread_msg_ids');
        $msg_status = $this->input->post('msg_status');

        if (!empty($all_unread_msg_ids)) {
            foreach ($all_unread_msg_ids as $msg) {
                $this->Messages_model->update_message($msg, ['status' => '2']);
            }
        }

        echo json_encode(['status' => 'success', 'total_unread' => GetUserUnreadNotificationCounts($user_id)]);
    }

    public function get_msg_unread_cnt() {
        $u_data = $this->session->userdata('user');
        $user_id = $u_data['id'];
        echo (int) GetUserUnreadNotificationCounts($user_id);
    }

    public function videocall($id = null, $calling_id = null, $msg_id = null, $room_id = null) {
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

    public function update_message_status() {
        $vid_url = $this->input->post('vid_url');
        $msg_id = $this->input->post('msg_id');
        $this->Messages_model->update_message($msg_id, ['status' => '2']);
        $res = $this->db->get_where('media', ['media_name' => $vid_url])->row_array();
        echo json_encode(['status' => 'success', 'media_id' => $res['id']]);
    }

    public function getconversationmedia() {
        $u_data = $this->session->userdata('user');
        $s_user_id = $u_data['id'];
        $user_id = $_GET['user_id'];
        /* $s_user_id = 115;
          $user_id = 140; */
        $query = "SELECT `id`, `message_type`, `message`, `status`, `media_name`, `unique_id`, `sender_id`, `receiver_id`, is_encrypted, encrypted_message," .
                "CONCAT(DATE_FORMAT( created_date,'%Y-%m-%d %H:%i:%s'), ' +0000') as created_date," .
                "CONCAT(DATE_FORMAT( updated_date,'%Y-%m-%d %H:%i:%s'), ' +0000') as updated_date " .
                "FROM `messages` WHERE message_type IN (2,3,5) AND is_delete = 0 AND ((receiver_id = $s_user_id AND sender_id = $user_id) OR (receiver_id = $user_id AND sender_id = $s_user_id)) ORDER BY id DESC";
        $q = $this->db->query($query);
        $rs = $q->result_array();
        $r_arr = array();
        if (!empty($rs)) {
            foreach ($rs as $r) {
                $r_arr[] = array("href" => ($r['message_type'] == 2) ? base_url() . "bio/show_img/" . $r['media_name'] : base_url() . "video/show_video/" . $r['media_name'], "type" => ($r['message_type'] == 2) ? "image" : "video", "title" => "", "isDom" => false);
            }
        }
        echo json_encode($r_arr);
    }

}

/* End of file Message.php */
/* Location: ./application/controllers/Message.php */