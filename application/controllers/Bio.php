<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Bio extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->library('unirest');
        $this->load->model(array('Users_model', 'Filters_model', 'Bio_model'));

        $u_data = $this->session->userdata('user');
        if (empty($u_data)) {
            redirect('');
        }
    }

    public function index() {
        
    }

    public function change_profile() {
        $u_data = $this->session->userdata('user');

        if ($_POST) {
            $path = $_FILES['profile_picture']['name'];
            $ext = pathinfo($path, PATHINFO_EXTENSION);

            if ($ext == 'mp4') {
                $upload_path = UPLOADPATH_VIDEO;
            } else {
                $upload_path = UPLOADPATH_IMAGE;
            }

            $config['upload_path'] = $upload_path;
            $config['allowed_types'] = 'jpg|png|jpeg|mp4|JPG|JPEG|PNG|MP4|jpe|jpeg';
            $config['max_size'] = '30000';
            $config['encrypt_name'] = TRUE;
            $config['detect_mime'] = TRUE;
            $config['file_ext_tolower'] = TRUE;

            $this->upload->initialize($config);

            if (!$this->upload->do_upload('profile_picture')) {
                $error = array('error' => $this->upload->display_errors());
                $this->session->set_flashdata('message', ['message' => $error['error'], 'class' => 'alert alert-danger']);
                redirect($_POST['sub_view']);
            } else {
                $data = array('upload_data' => $this->upload->data());
                $profile_media_id = $u_data['profile_media_id'];

                /* $user_media = $this->Bio_model->fetch_mediadata(['id' => $profile_media_id], true); */

                $full_path = $data['upload_data']['full_path'];
                $file_name = $data['upload_data']['file_name'];
                $file_name = replace_extension($file_name, "png");
                $new_name = $data['upload_data']['file_path'] . $file_name;
                rename($full_path, $new_name);
                $full_path = $new_name;
                $data['upload_data']['file_name'] = $file_name;
                $raw_name = $data['upload_data']['raw_name'];

                $thumb_name = 'thumb_' . $raw_name . '.png';

                $thumb_path = UPLOADPATH_THUMB . '/' . $thumb_name;

                $upd_data = [];

                // IF image then create thumb using GD library otherwise use ffmpeg for create image
                if ($data['upload_data']['is_image'] == '1') {
                    _createThumbnail($full_path, $thumb_path);
                    $upd_data['media_type'] = '1';
                } else {
                    exec(FFMPEG_PATH . ' -i ' . $full_path . ' -ss 00:00:01.000 -vframes 1 ' . $thumb_path);
                    $upd_data['media_type'] = '2';
                }

                $upd_data['media_name'] = $file_name;
                $upd_data['media_thumb'] = $thumb_name;
                $upd_data['insta_datetime'] = '0000-00-00 00:00:00';
                $upd_data['updated_date'] = date('Y-m-d H:i:s');

                $this->Bio_model->update_media(['id' => $profile_media_id], $upd_data);
                redirect('user/view_profile');
            }
        }

        $data['sub_view'] = 'bio/change_profile';
        $data['meta_title'] = "Change Profile";
        $data['userData'] = [];
        $this->load->view('main', $data);
    }

    public function saved_feed() {

        $u_data = $this->session->userdata('user');

        $user_info = $this->Users_model->getUserByCol('id', $u_data['id']);
        $all_saved_media = $this->Bio_model->fetch_mediadata(['userid' => $u_data['id'], 'is_active' => '1'], false, 'id,media_id');
        $data['all_saved_media'] = array_column($all_saved_media, 'media_id');

        $all_images = $this->Bio_model->fetch_mediadata(['userid' => $u_data['id'], 'is_active' => '1']);

        $data['sub_view'] = 'bio/saved_feed';
        $data['meta_title'] = "Save instagram bio";
        $data['userData'] = $user_info;
        $data['all_images'] = $all_images;

        $this->load->view('main', $data);
    }

    public function instagram_feed() {

        $u_data = $this->session->userdata('user');
        $insta_id = $u_data['userid'];
        $access_token = $u_data['access_token'];

        $curl1 = "https://api.instagram.com/v1/users/" . $insta_id . "/media/recent/?access_token=" . $access_token . '&count=9';
        $response = $this->unirest->get($curl1, $headers = array());
        $row_data = json_decode($response->raw_body, true);

        // pr($row_data);
        $user_info = $this->Users_model->getUserByCol('id', $u_data['id']);
        $all_saved_media = $this->Bio_model->fetch_mediadata(['userid' => $u_data['id'], 'is_active' => '1'], false, 'id,media_id');
        $data['all_saved_media'] = array_column($all_saved_media, 'media_id');

        $next_link = '';
        if (isset($row_data['pagination']['next_url'])) {
            $next_link = $row_data['pagination']['next_url'];
        }

        $data['sub_view'] = 'bio/index';
        $data['meta_title'] = "Save instagram bio";
        $data['userData'] = $user_info;
        $data['all_images'] = $row_data['data'];
        $data['next_link'] = $next_link;
        $this->load->view('main', $data);
    }

    public function fetch_insta_bio() {

        $next_url = $this->input->post('next_url');
        $all_saved_media = $this->input->post('all_saved_media');

        if (!empty($all_saved_media)) {
            $all_saved_media = explode(',', $all_saved_media);
        } else {
            $all_saved_media = [];
        }
        $curl1 = $next_url;

        $response = $this->unirest->get($curl1, $headers = array());
        $row_data = json_decode($response->raw_body, true);

        $all_images = $row_data['data'];
        $next_link = (!empty($row_data['pagination']['next_url'])) ? $row_data['pagination']['next_url'] : '';

        $new_str = '';

        if (!empty($all_images)) {
            foreach ($all_images as $image) {

                $type = $image['type'];
                $link = $image['images']['standard_resolution']['url'];
                $thumb = $image['images']['thumbnail']['url'];
                $image_link = $link = $image['images']['standard_resolution']['url'];
                $dynamic_id = random_string();

                if ($type == 'video') {
                    $image_link = $image['videos']['standard_resolution']['url'];
                }

                $is_delete = 'no';
                if (!empty($all_saved_media) && in_array($image['id'], $all_saved_media) == true) {
                    $is_delete = 'yes';
                }

                if ($is_delete == 'no') {
                    $new_str .= '<li id="' . $dynamic_id . '"> <div class="my-picture-box"> <a> <img src="' . $link . '" alt="" /> </a>';
                    $new_str .= '<div class="picture-action"> <div class="picture-action-inr">';
                    $new_str .= '<a data-type="' . $type . '" data-insta-id="' . $image['id'] . '" data-insta-time="' . $image['created_time'] . '"';
                    $new_str .= ' data-val="' . $link . '" class="for_pointer icon-picture js-mytooltip type-inline-block style-block style-block-one"';
                    $new_str .= ' data-mytooltip-custom-class="align-center" data-mytooltip-content="Set as a profile pic"';
                    $new_str .= ' data-thumb="' . $thumb . '" onclick="ajax_set_profile(this)"></a>';

                    $new_str .= ' <a data-fancybox="gallery" href="' . $image_link . '" class="icon-full-screen image-link js-mytooltip type-inline-block style-block style-block-one"';
                    $new_str .= ' data-mytooltip-custom-class="align-center" data-mytooltip-content="Full screen"></a>';

                    $new_str .= ' <a data-type="' . $type . '" data-insta-id="' . $image['id'] . '" data-insta-time="' . $image['created_time'] . '"';
                    $new_str .= ' data-val="' . $link . '" class="for_pointer icon-tick-inside-circle js-mytooltip type-inline-block style-block style-block-one" data-thumb="' . $thumb . '" ';
                    $new_str .= 'data-mytooltip-custom-class="align-center" data-mytooltip-content="Save into Bio"';
                    $new_str .= ' onclick="ajax_save_bio(this)" data-is-delete="' . $is_delete . '" data-dynamic-id="' . $dynamic_id . '" > </a> </div> </div> </div> </li>';
                }
            }
        }

        $ret_data['all_images'] = $new_str;
        $ret_data['next_link'] = $next_link;
        $ret_data['return_data'] = $row_data;
        $ret_data['curl1'] = $curl1;

        echo json_encode($ret_data);
    }

    // ------------------------------------------------------------------------
    // Save Insta Feed into BIO MAximum upto 50 only
    // ------------------------------------------------------------------------

    public function ajax_save_bio() {

        $u_data = $this->session->userdata('user');
        $user_id = $u_data['id'];

        $img_name = $this->input->post('img_name');
        $type = $this->input->post('type');
        $thumb = $this->input->post('thumb');
        $media_type = ($type == 'image') ? '3' : '4';
        $insta_id = $this->input->post('insta_id');
        $is_delete = $this->input->post('is_delete');

        $insta_time = date('Y-m-d H:i:s', $this->input->post('insta_time'));

        $ret['total_feeds_cnt'] = $this->Bio_model->fetch_total_feed_cnt();

        if ($ret['total_feeds_cnt'] == 50) {
            echo json_encode(['status' => 'error', 'query' => 'Limit on total feed validation']); // User can't save more than 50 images
            die;
        }

        $ins_data = array(
            'userid' => $user_id,
            'media_id' => $insta_id,
            'media_name' => $img_name,
            'media_thumb' => $thumb,
            'media_type' => $media_type,
            'insta_datetime' => $insta_time,
            'created_date' => date('Y-m-d H:i:s'),
            'is_bios' => '1'
        );

        if ($is_delete == 'yes') {
            $ret['query'] = 'update_to_0';
            $this->Bio_model->update_media(['media_id' => $insta_id], ['is_active' => '0']);
        } else {

            $media_data = $this->Bio_model->fetch_mediadata(['media_id' => $insta_id], true);

            if (!empty($media_data)) {
                $ret['query'] = 'update_to_1';
                $this->Bio_model->update_media(['media_id' => $insta_id], ['is_active' => '1']);
            } else {
                $ret['query'] = 'insert';
                $this->db->insert('media', $ins_data);
            }
        }
        $ret['status'] = 'success';
        echo json_encode($ret);
    }

    // ------------------------------------------------------------------------
    // Save insta feed picture as profile picture
    // ------------------------------------------------------------------------

    public function ajax_picture_set_profile() {

        $u_data = $this->session->userdata('user');
        $profile_media_id = $u_data['profile_media_id'];
        $access_token = $u_data['access_token'];

        $img_name = $this->input->get('img_name');
        $type = $this->input->get('type');
        $thumb = $this->input->get('thumb');
        $media_type = ($type == 'image') ? '3' : '4';
        $insta_id = $this->input->get('insta_id');
        $is_delete = $this->input->get('is_delete');
        $insta_time = date('Y-m-d H:i:s', $this->input->get('insta_time'));

        $upd_data = array(
            'userid' => '0',
            'media_id' => $insta_id,
            'media_name' => $img_name,
            'media_thumb' => $thumb,
            'media_type' => $media_type,
            'insta_datetime' => $insta_time,
            'updated_date' => date('Y-m-d H:i:s')
        );

        // Update new data into media with userid - 0 which indiacte it's for profile
        $this->Bio_model->update_media(['id' => $profile_media_id], $upd_data);
        redirect('user/view_profile');
    }

    // ------------------------------------------------------------------------

    public function show_img($file, $is_thumb = '0') {

        if ($is_thumb == '1') {
            $path = UPLOADPATH_THUMB . '/' . $file;
        } else {
            $path = UPLOADPATH_IMAGE . '/' . $file;
        }
        header('Content-type: image/jpeg');
        readfile($path);
    }

    public function show_video($file) {
        $path = UPLOADPATH_VIDEO . '/' . $file;
        header("Content-Type: video/mp4");
        header("Content-Length: " . filesize($path));
        readfile($path);
    }

}

/* End of file Bio.php */
/* Location: ./application/controllers/Bio.php */