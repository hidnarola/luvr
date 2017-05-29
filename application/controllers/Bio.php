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
            
            $new_file_name = $u_data['id'].'_'.random_name_generate(); // Generate random file name of 8 characters only - Look into Site helper for reference

            $config['file_name'] = $new_file_name;
            $config['upload_path'] = $upload_path;
            $config['allowed_types'] = 'jpg|png|jpeg|mp4';
            $config['max_size'] = '30000';            
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
                $user_media = $this->Bio_model->fetch_mediadata(['id' => $profile_media_id], true);

                // Unlink existing file
                if($user_media['media_type'] == '1'){
                    $path_image = UPLOADPATH_IMAGE.'/'.$user_media['media_name'];                    
                    $path_image_thumb = UPLOADPATH_THUMB.'/'.$user_media['media_thumb'];
                    if(file_exists($path_image)){ unlink($path_image); }
                    if(file_exists($path_image_thumb)){ unlink($path_image_thumb); }
                }                

                // Unlink existing file
                if($user_media['media_type'] == '2'){
                    $path_video = UPLOADPATH_VIDEO.'/'.$user_media['media_name'];
                    $user_media['media_thumb'] = str_replace('.mp4', '.png', $user_media['media_thumb']);
                    $path_image_thumb = UPLOADPATH_THUMB.'/'.$user_media['media_thumb'];
                    if(file_exists($path_video)){ unlink($path_video); }
                    if(file_exists($path_image_thumb)){ unlink($path_image_thumb); }
                }

                $full_path = $data['upload_data']['full_path'];
                $file_name = $data['upload_data']['file_name'];
                if ($data['upload_data']['is_image'] == '1') {
                    $file_name = replace_extension($file_name, "png");
                    $new_name = $data['upload_data']['file_path'] . $file_name;
                    rename($full_path, $new_name);
                    $full_path = $new_name;
                    $data['upload_data']['file_name'] = $file_name;
                }

                $raw_name = $data['upload_data']['raw_name'];
                $thumb_name = $raw_name . '.png';
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

    public function upload_feed(){
        $u_data = $this->session->userdata('user');
        $path = $_FILES['feed']['name'];
        $ext = pathinfo($path, PATHINFO_EXTENSION);
        
        if ($ext == 'mp4') {
            $upload_path = UPLOADPATH_VIDEO;
        } else {
            $upload_path = UPLOADPATH_IMAGE;
        }

        $total_feeds_cnt = $this->Bio_model->fetch_total_feed_cnt();

        if ($total_feeds_cnt == 50) {
            $this->session->set_flashdata('message', ['message'=>'Can Not Save More Than 50 Images or Videos in Bio.','class'=>'alert alert-danger']);
            redirect('bio/saved_feed');
        }


        $new_file_name = $u_data['id'].'_'.random_name_generate(); // Generate random file name of 8 characters only - Look into Site helper for reference

        $config['file_name'] = $new_file_name;
        $config['upload_path'] = $upload_path;
        $config['allowed_types'] = 'jpg|png|jpeg|mp4';
        $config['max_size']  = '1000000000';
        $config['detect_mime'] = TRUE;
        $config['file_ext_tolower'] = TRUE;
        
        $this->upload->initialize($config);

        if ( ! $this->upload->do_upload('feed')){
            $error = array('error' => $this->upload->display_errors());
            $this->session->set_flashdata('message', ['message'=>$error['error'],'class'=>'alert alert-danger']);
            redirect('bio/saved_feed');
        } else {
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

            $ins_data = [];

            $thumb_name = $raw_name . '.png';
            $thumb_path = UPLOADPATH_THUMB . '/' . $thumb_name;

            // IF image then create thumb using GD library otherwise use ffmpeg for create image
            if ($data['upload_data']['is_image'] == '1') {
                _createThumbnail($full_path, $thumb_path);
                $media_type = '1';
            } else {
                // Count the length of the video for the validation
                ob_start();
                passthru(FFMPEG_PATH." -i ".$full_path."  2>&1");
                $duration = ob_get_contents();
                $full = ob_get_contents();
                ob_end_clean();

                $search='/Duration: (.*?),/';
                $duration = preg_match($search, $duration, $matches, PREG_OFFSET_CAPTURE, 3);

                if(isset($matches[0][0])){
                    
                    $explode_arr = explode(':',$matches[0][0]);

                    $hour_vid = (int)trim($explode_arr[count($explode_arr) - 3]);
                    $min_vid = (int)trim($explode_arr[count($explode_arr) - 2]);
                    $sec_vid = (int)trim($explode_arr[count($explode_arr) - 1]);
                    
                    if($hour_vid > 0 || $min_vid > 0 || $sec_vid > 30){
                        $this->session->set_flashdata('message', ['message'=>'Video can not be longer than 30 seconds.','class'=>'alert alert-danger']);
                        redirect('bio/saved_feed');
                    } // v! end of IF condition for house,min,sec limit
                }
                exec(FFMPEG_PATH . ' -i ' . $full_path . ' -ss 00:00:01.000 -vframes 1 ' . $thumb_path);
                $media_type = '2';        
            }            

            if ($ext == 'mp4') {
                $thumb_name = str_replace('.png', '.mp4', $thumb_name);
            }

            $ins_data = array(
                                'userid'=>$u_data['id'],
                                'media_name'=>$file_name,
                                'media_thumb'=>$thumb_name,
                                'media_type'=>$media_type,
                                'created_date'=>date('Y-m-d H:i:s'),
                                'is_bios'=>'1'
                            );
            $this->Bio_model->insert_media($ins_data);
            $this->session->set_flashdata('message', ['message'=>'Video can not be longer than 30 seconds.','class'=>'alert alert-danger']);
            redirect('bio/saved_feed');
        }
    }    

    // ------------------------------------------------------------------------
    // Fetch facebook feed landing page and next page on AJAX
    // ------------------------------------------------------------------------    
    public function facebook_feed(){
        
        $u_data = $this->session->userdata('user');
        $user_info = $this->Users_model->getUserByCol('id', $u_data['id']);

        $fb_id = $user_info['facebook_id'];        
        $fb_access_token = $u_data['fb_access_token'];
        
        // pr($_SESSION,1);
        $fb_feed = 'https://graph.facebook.com/'.$fb_id.'/feed?fields=full_picture,source,type,created_time&limit=100&access_token='.$fb_access_token;
        $response = $this->unirest->get($fb_feed, $headers = array());
        $raw_data = json_decode($response->raw_body, true);
        $next_link = $raw_data['paging']['next'];

        // pr($response,1);

        $all_saved_media = $this->Bio_model->fetch_mediadata(['userid' => $u_data['id'], 'is_active' => '1'], false, 'id,media_id');
        $data['all_saved_media'] = array_column($all_saved_media, 'media_id');
        $data['sub_view'] = 'bio/facebook_feed';
        $data['meta_title'] = "Save facebook feed into bio";
        $data['userData'] = $user_info;
        $data['all_images'] = $raw_data['data'];
        $data['next_link'] = $next_link;
        // echo $data['next_link'];
        $this->load->view('main', $data);
    }

    public function fetch_facebook_bio(){
        $u_data = $this->session->userdata('user');
        $user_info = $this->Users_model->getUserByCol('id', $u_data['id']);

        $next_url = $this->input->post('next_url');
        $all_saved_media = $this->input->post('all_saved_media');

        if (!empty($all_saved_media)) {
            $all_saved_media = explode(',', $all_saved_media);
        } else {
            $all_saved_media = [];
        }

        $fb_feed = $next_url;
        $response = $this->unirest->get($fb_feed, $headers = array());
        $raw_data = json_decode($response->raw_body, true);

        $all_images = $raw_data['data'];
        
        $next_link = '';
        $new_str = '';

        if(!empty($raw_data['data'])){
            $next_link = $raw_data['paging']['next'];
        }
        //pr($all_images);
        if (!empty($all_images)) {
            foreach ($all_images as $image) {
                
                if($image['type'] == 'video' || $image['type'] == 'photo'){

                    $type = '3';  // Online Image link
                    $thumb = $image['full_picture'];
                    $image_link = $link = $data_val = $image['full_picture'];
                    $fancybox_str = 'data-fancybox="gallery"';
                    $anchor_target = '';
                    $dynamic_id = random_string();
                    $is_video_class = '';
                                        
                    if ($image['type'] == 'video') {
                        $type = '4'; // Online Video link
                        if(strpos($image['source'],"video.xx.fbcdn.net") == FALSE){ continue; }
                        $fancybox_str = '';
                        $anchor_target = '_blank';
                        $image_link = base_url() . "video/play?url=".urlencode($image['source']);
                        $data_val =  $image['source'];
                        $is_video_class = 'video-tag';
                    }

                    
                    $is_delete = 'no';
                    if (in_array($image['id'], $all_saved_media)) { continue; }

                    $new_str .= '<li id="'.$dynamic_id.'"> <div class="my-picture-box"> <a class="'.$is_video_class.'"> <img src="'.$link.'" alt="" /> </a>';
                    $new_str .= '<div class="picture-action"> <div class="picture-action-inr">';
                    $new_str .= '<a data-type="'.$type.'" data-insta-id="'.$image['id'].'" data-insta-time="'.$image['created_time'].'"';
                    $new_str .= ' data-val="'.urlencode($link).'" class="for_pointer icon-picture js-mytooltip type-inline-block style-block style-block-one"';
                    $new_str .= ' data-thumb="'.urlencode($thumb).'" onclick="ajax_set_profile(this)" data-is-delete="<?= $is_delete ?>"';
                    $new_str .= ' data-mytooltip-custom-class="align-center" data-mytooltip-content="Set as a profile pic"> </a>';
                    
                    $new_str .= '<a '.$fancybox_str.' href="'.$image_link.'" target="'.$anchor_target.'"';
                    $new_str .= ' class="icon-full-screen image-link js-mytooltip type-inline-block style-block style-block-one"';
                    $new_str .= ' data-mytooltip-custom-class="align-center" data-mytooltip-content="Full screen"></a>';
                    $new_str .= '<a data-type="'.$type.'" data-insta-id="'.$image['id'].'" data-insta-time="'.$image['created_time'].'"';
                    $new_str .= ' data-val="'.$data_val.'" data-thumb="'.$thumb.'" class="for_pointer icon-tick-inside-circle js-mytooltip type-inline-block style-block style-block-one"';
                    $new_str .= ' onclick="ajax_save_bio(this)" data-is-delete="'.$is_delete.'" data-dynamic-id="'.$dynamic_id.'"';
                    $new_str .= ' data-mytooltip-custom-class="align-center" data-mytooltip-content="Save into Bio"> </a> </div> </div> </div> </li>';
                }                
            }
        }        

        $ret_data['all_images'] = $new_str;
        $ret_data['next_link'] = $next_link;
        echo json_encode($ret_data);
    }

    // ------------------------------------------------------------------------
    // Fetch instagram feed landing page and next page on AJAX
    // ------------------------------------------------------------------------
    public function instagram_feed() {

        $u_data = $this->session->userdata('user');
        $insta_id = $u_data['userid'];
        $access_token = $u_data['access_token'];

        $curl1 = "https://api.instagram.com/v1/users/" . $insta_id . "/media/recent/?access_token=" . $access_token . '&count=9';
        $response = $this->unirest->get($curl1, $headers = array());
        $row_data = json_decode($response->raw_body, true);

        // pr($row_data,1);

        $user_info = $this->Users_model->getUserByCol('id', $u_data['id']);
        $all_saved_media = $this->Bio_model->fetch_mediadata(['userid' => $u_data['id'], 'is_active' => '1'], false, 'id,media_id');
        $data['all_saved_media'] = array_column($all_saved_media, 'media_id');

        $next_link = '';
        if (isset($row_data['pagination']['next_url'])) {
            $next_link = $row_data['pagination']['next_url'];
        }

        $data['sub_view'] = 'bio/instagram_feed';
        $data['meta_title'] = "Save instagram bio";
        $data['userData'] = $user_info;
        $data['all_images'] = $row_data['data'];
        $data['next_link'] = $next_link;
        $this->load->view('main', $data);
    }
    
    // AJAX call for this function to get next page data 
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

                $type = '3'; // For online image link
                $link = $image['images']['standard_resolution']['url'];
                $thumb = $image['images']['thumbnail']['url'];
                $image_link = $link = $data_val =  $image['images']['standard_resolution']['url'];
                $fancybox_str = 'data-fancybox="gallery"';
                $anchor_target = '';
                $dynamic_id = random_string();
                $is_video_class = '';

                if ($image['type'] == 'video') {
                    $type = '4'; // For online video link
                    $fancybox_str = '';
                    $anchor_target = '_blank';
                    $vid_url = urlencode($image['videos']['standard_resolution']['url']);
                    $image_link = base_url() . "video/play?url=".$vid_url;                    
                    $data_val = $image['videos']['standard_resolution']['url'];
                    $is_video_class = 'video-tag';
                }

                $is_delete = 'no';
                if (!empty($all_saved_media) && in_array($image['id'], $all_saved_media) == true) {
                    $is_delete = 'yes';
                }

                if ($is_delete == 'no') {
                    $new_str .= '<li id="' . $dynamic_id . '"> <div class="my-picture-box"> <a class="'.$is_video_class.'"> <img src="' . $link . '" alt="" /> </a>';
                    $new_str .= '<div class="picture-action"> <div class="picture-action-inr">';
                    $new_str .= '<a data-type="' . $type . '" data-insta-id="' . $image['id'] . '" data-insta-time="' . $image['created_time'] . '"';
                    $new_str .= ' data-val="' . urlencode($link) . '" class="for_pointer icon-picture js-mytooltip type-inline-block style-block style-block-one"';
                    $new_str .= ' data-mytooltip-custom-class="align-center" data-mytooltip-content="Set as a profile pic"';
                    $new_str .= ' data-thumb="' . urlencode($thumb) . '" onclick="ajax_set_profile(this)"></a>';

                    $new_str .= ' <a '.$fancybox_str.' href="' . $image_link . '" target="'.$anchor_target.'" class="icon-full-screen image-link js-mytooltip type-inline-block style-block style-block-one"';
                    $new_str .= ' data-mytooltip-custom-class="align-center" data-mytooltip-content="Full screen"></a>';

                    $new_str .= ' <a data-type="' . $type . '" data-insta-id="' . $image['id'] . '" data-insta-time="' . $image['created_time'] . '"';
                    $new_str .= ' data-val="' . $data_val . '" class="for_pointer icon-tick-inside-circle js-mytooltip type-inline-block style-block style-block-one" data-thumb="' . $thumb . '" ';
                    $new_str .= 'data-mytooltip-custom-class="align-center" data-mytooltip-content="Save into Bio"';
                    $new_str .= ' onclick="ajax_save_bio(this)" data-is-delete="' . $is_delete . '" data-dynamic-id="' . $dynamic_id . '" > </a> </div> </div> </div> </li>';
                }
            }
        }

        $ret_data['all_images'] = $new_str;
        $ret_data['next_link'] = $next_link;
        echo json_encode($ret_data);
    }
    
    // Save Insta Feed into BIO Maximum upto 50 only
    public function ajax_save_bio() {

        $u_data = $this->session->userdata('user');
        $user_id = $u_data['id'];

        $img_name = $this->input->post('img_name');
        $type = $this->input->post('type');
        $thumb = $this->input->post('thumb');

        //$media_type = ($type == 'video') ? '4' : '3';

        $insta_id = $this->input->post('insta_id');
        $is_delete = $this->input->post('is_delete');

        $insta_time = date('Y-m-d H:i:s');

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
            'media_type' => $type,
            'insta_datetime' => $insta_time,
            'created_date' => date('Y-m-d H:i:s'),
            'is_bios' => '1'
        );

        if ($is_delete == 'yes') {
            $ret['query'] = 'update_to_0';
            $this->Bio_model->update_media(['media_id' => $insta_id], ['is_active' => '0']);
        } else {

            $media_data = $this->Bio_model->fetch_mediadata(['media_id' => $insta_id,'userid !='=>'0'], true);

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
    
    // Save insta feed picture as profile picture
    public function ajax_picture_set_profile() {

        $u_data = $this->session->userdata('user');
        $profile_media_id = $u_data['profile_media_id'];
        $access_token = $u_data['access_token'];

        $type = $this->input->get('type');
        $img_name = urldecode($this->input->get('img_name'));
        $thumb = urldecode($this->input->get('thumb'));        

        $insta_id = $this->input->get('insta_id');
        $is_delete = $this->input->get('is_delete');
        $insta_time = date('Y-m-d H:i:s');

        $upd_data = array(
            'userid' => '0',
            'media_id' => $insta_id,
            'media_name' => $img_name,
            'media_thumb' => $thumb,
            'media_type' => $type,
            'insta_datetime' => $insta_time,
            'updated_date' => date('Y-m-d H:i:s')
        );        

        // Update new data into media with userid - 0 which indiacte it's for profile
        $this->Bio_model->update_media(['id' => $profile_media_id], $upd_data);        
        redirect('user/view_profile');
    }

    // ----------------------------------------------------------------------------------------------------------------------------------------

    public function show_img($file, $is_thumb = '0') {

        if ($is_thumb == '1') {
            $path = UPLOADPATH_THUMB . '/' . $file;
        } else {
            $path = UPLOADPATH_IMAGE . '/' . $file;
        }
        header('Content-type: image/jpeg');
        readfile($path);
    }

}

/* End of file Bio.php */
/* Location: ./application/controllers/Bio.php */