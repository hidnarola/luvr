<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->library(['unirest','facebook']);
        $this->load->model(['Users_model', 'Bio_model']);
    }

    public function index() {
                
        $data['fb_login_url'] = $this->facebook->get_login_url();
        $data['sub_view'] = 'register/register_view';
        $data['meta_title'] = "Setup User Profile";
        $this->load->view('main', $data);
    }

    public function return_url() {
        $code = $this->input->get('code');

        if (!empty($code)) {

            $sess_u_data = $this->session->userdata('user');
            $insta_params = array('client_id' => INSTA_CLIENT_ID, 'client_secret' => INSTA_CLIENT_SECRET, 'grant_type' => "authorization_code",
                'redirect_uri' => base_url() . 'register/return_url', 'code' => $code);
            $curl1 = "https://api.instagram.com/oauth/access_token";
            $response = $this->unirest->post($curl1, $headers = array(), $insta_params);
            $response_raw_body = $response->raw_body;
            $response_arr = json_decode($response_raw_body, true);

            if (!empty($response_arr['access_token'])) {

                $curl2 = "https://api.instagram.com/v1/users/self?access_token=" . $response_arr['access_token'];
                $response = $this->unirest->get($curl2, $headers = array());
                $row_data = json_decode($response->raw_body, true);

                if (!empty($row_data['data'])) {
                    $insta_id = $row_data['data']['id'];
                    $insta_username = $row_data['data']['username'];
                    $insta_full_name = $row_data['data']['full_name'];
                    $insta_profile = $row_data['data']['profile_picture'];                                    
                }

                $u_data = $this->Users_model->fetch_userdata(['userid' => $insta_id], true);

                // If session is already logged in when visit this page
                if(!empty($sess_u_data)){
                    if(!empty($u_data)){

                        if($u_data['id'] == $sess_u_data['id']){

                            $session_u_data = (array)$this->Users_model->fetch_userdata(['id' => $sess_u_data['id']], true);                        
                            $session_u_data['access_token'] = $response_arr['access_token'];
                            $session_u_data['country_short_code'] = $sess_u_data['country_short_code'];
                            $session_u_data['loginwith'] = $sess_u_data['loginwith'];
                            $session_u_data['fb_access_token'] = $sess_u_data['fb_access_token'];
                            
                            $this->session->set_userdata('user', $session_u_data); // Set session key - "user" for all userdata
                            redirect('bio/instagram_feed');

                        }else{                            
                            $this->session->set_flashdata('error', 'This account is already connected with other user.');
                            redirect('user/user_settings');
                        }

                    }else{
                        $upd_data = ['userid' => $insta_id, 'id' => $sess_u_data['id']];
                        $this->Users_model->manageUser($upd_data); // Update the user's updated instagram ID

                        $session_u_data = (array)$this->Users_model->fetch_userdata(['id' => $sess_u_data['id']], true);                        
                        $session_u_data['access_token'] = $response_arr['access_token'];
                        $session_u_data['country_short_code'] = $sess_u_data['country_short_code'];
                        $session_u_data['loginwith'] = $sess_u_data['loginwith'];
                        $session_u_data['fb_access_token'] = $sess_u_data['fb_access_token'];
                        
                        $this->session->set_userdata('user', $session_u_data); // Set session key - "user" for all userdata
                        redirect('user/user_settings');
                    }
                }

                // If User is already registered with instagram.
                if (!empty($u_data)) {
                    
                    $google_map_data = $this->get_google_map_data($u_data['address']);

                    $u_sess_data = (array)$u_data;
                    $u_sess_data['access_token'] = $response_arr['access_token'];
                    $u_sess_data['country_short_code'] = $google_map_data['country_short_code'];
                    $u_sess_data['loginwith'] = 'instagram';
                    $u_sess_data['fb_access_token'] = '';
                    
                    $this->session->set_userdata('user', $u_sess_data); // Set session key - "user" for all userdata

                    $upd_data = ['lastseen_date' => date('Y-m-d H:i:s'), 'id' => $u_data['id']];
                    if ($u_data['latlong'] == null || empty($u_data['latlong'])) { $upd_data['latlong'] = implode(',', $google_map_data['location']); }
                    $this->Users_model->manageUser($upd_data); // Update the user's last seen and latlong if empty

                    $this->login_callback(); // If login_callback session var set then redirect to that page

                    // If profile not saved by user then redirect to set user profile page otherwise redirect to set filters
                    if (empty($u_data['email'])){
                        redirect('user/setup_userprofile');
                    }else {
                        redirect('user/setup_userfilters');
                    }

                } else {

                    // If User login with instagram for the firsttime

                    $media_data = array('userid' => '0', 'media_id' => '', 'media_name' => $insta_profile, 'media_thumb' => $insta_profile,
                                        'media_type' => '3', 'created_date' => date('Y-m-d H:i:s'), 'is_bios' => '0', 'is_active' => '1');
                    $last_media_id = $this->Bio_model->insert_media($media_data);

                    $ins_data = array('userid' => $insta_id, 'instagram_username' => $insta_username, 'full_name' => $insta_full_name,'profile_media_id' => $last_media_id, 
                                     'created_date' => date('Y-m-d H:i:s'), 'gender' => 'male', 'radius' => 100,'lastseen_date' => date('Y-m-d H:i:s') );

                    $last_id = $this->Users_model->insert_record($ins_data);
                    
                    $u_sess_data = (array)$this->Users_model->fetch_userdata(['id' => $last_id], true);
                    $u_sess_data['access_token'] = $response_arr['access_token'];
                    $u_sess_data['country_short_code'] = '';
                    $u_sess_data['loginwith'] = 'instagram';
                    $u_sess_data['fb_access_token'] = '';

                    $this->session->set_userdata('user', $u_sess_data); // Set session key - "user" for all userdata

                    $this->login_callback(); // If login_callback session var set then redirect to that page

                    redirect('user/setup_userprofile');
                }
            } // END of IF condition for ACCESS TOKEN
        }
    }

    public function return_url_fb(){
        
        $sess_u_data = $this->session->userdata('user');

        $error =  $this->input->get('error_code');
        if(!empty($error)){ redirect(''); }

        $user_detail = $this->facebook->get_user();        

        if(empty($user_detail)){ show_404(); }
        
        $fb_access_token = $this->session->userdata('fb_token'); // Access token of facebook

        $fb_id = $user_detail['id']; // Facebook ID
        $fb_name = $user_detail['name'];
        $fb_email = $user_detail['email'];
        $fb_first_name = $user_detail['first_name'];
        $fb_last_name = $user_detail['last_name'];
        $fb_full_name = $fb_first_name.' '.$fb_last_name;
        $fb_birthday = $user_detail['birthday'];
        $fb_gender = $user_detail['gender'];        
                        
        $fb_profile_pic = 'https://graph.facebook.com/'.$fb_id.'/picture?type=large';

        $u_data = $this->Users_model->fetch_userdata(['facebook_id' => $fb_id], true);        

        // If session is already logged in when visit this page
        if(!empty($sess_u_data)){
            if(!empty($u_data)){

                if($u_data['id'] == $sess_u_data['id']){
                    $session_u_data = (array)$this->Users_model->fetch_userdata(['id' => $sess_u_data['id']], true);
                    $session_u_data['access_token'] = $sess_u_data['access_token'];
                    $session_u_data['country_short_code'] = $sess_u_data['country_short_code'];
                    $session_u_data['loginwith'] = $sess_u_data['loginwith'];
                    $session_u_data['fb_access_token'] = $fb_access_token;
                    
                    $this->session->set_userdata('user', $session_u_data); // Set session key - "user" for all userdata
                    redirect('bio/facebook_feed');
                }else{                            
                    $this->session->set_flashdata('error', 'This account is already connected with other user.');
                    redirect('user/user_settings');
                }
            }else{

                $upd_data = ['facebook_id' => $fb_id, 'id' => $sess_u_data['id']];
                $this->Users_model->manageUser($upd_data); // Update the user's updated instagram ID

                $session_u_data = (array)$this->Users_model->fetch_userdata(['id' => $sess_u_data['id']], true);                        
                $session_u_data['access_token'] = $sess_u_data['access_token'];
                $session_u_data['country_short_code'] = $sess_u_data['country_short_code'];
                $session_u_data['loginwith'] = $sess_u_data['loginwith'];
                $session_u_data['fb_access_token'] = $fb_access_token;
                
                $this->session->set_userdata('user', $session_u_data); // Set session key - "user" for all userdata
                redirect('user/user_settings');
            }
        }


        if(!empty($u_data)){

            $google_map_data = $this->get_google_map_data($u_data['address']);

            $u_sess_data = (array)$u_data;
            $u_sess_data['access_token'] = '';
            $u_sess_data['country_short_code'] = $google_map_data['country_short_code'];
            $u_sess_data['loginwith'] = 'facebook';
            $u_sess_data['fb_access_token'] = $fb_access_token;
            
            $this->session->set_userdata('user', $u_sess_data); // Set session key - "user" for all userdata

            $upd_data = ['lastseen_date' => date('Y-m-d H:i:s'), 'id' => $u_data['id']];
            if ($u_data['latlong'] == null || empty($u_data['latlong'])) { $upd_data['latlong'] = implode(',', $google_map_data['location']); }
            $this->Users_model->manageUser($upd_data); // Update the user's last seen and latlong if empty

            $this->login_callback(); // If login_callback session var set then redirect to that page

            // If profile not saved by user then redirect to set user profile page otherwise redirect to set filters
            if (empty($u_data['email'])){
                redirect('user/setup_userprofile');
            }else {
                redirect('user/setup_userfilters');
            }

        }else{

            // If user login with facebook for the first time
            $media_data = array('userid' => '0', 'media_id' => '', 'media_name' => $fb_profile_pic, 'media_thumb' => $fb_profile_pic,
                                'media_type' => '3', 'created_date' => date('Y-m-d H:i:s'), 'is_bios' => '0', 'is_active' => '1');
            $last_media_id = $this->Bio_model->insert_media($media_data);

            $ins_data = array('facebook_id'=>$fb_id,'userid' => '','user_name'=>$fb_name,'facebook_username' => $fb_name, 'full_name' => $fb_full_name,
                             'profile_media_id' => $last_media_id,'created_date' => date('Y-m-d H:i:s'), 'gender' => 'male', 'radius' => 100,
                             'lastseen_date' => date('Y-m-d H:i:s'));
            $last_id = $this->Users_model->insert_record($ins_data);

            $u_sess_data = (array)$this->Users_model->fetch_userdata(['id' => $last_id], true);
            $u_sess_data['access_token'] = '';
            $u_sess_data['country_short_code'] = '';
            $u_sess_data['loginwith'] = 'facebook';
            $u_sess_data['fb_access_token'] = $fb_access_token;

            $this->session->set_userdata('user', $u_sess_data); // Set session key - "user" for all userdata            

            $this->login_callback(); // If login_callback session var set then redirect to that page

            redirect('user/setup_userprofile');
        }
    }

    // ------------------------------------------------------------------------

    // function use for get coutnry short code and show into Header and when it requried
    // use this on return_url(instagram) and return_url_fb(facebook)
    public function get_google_map_data($address){
        
        $ret['country_short_code'] = '';
        $ret['location'] = '';

        if ($address != '') {
            $str = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . $address . '&key=' . GOOGLE_MAP_API;
            $res = $this->unirest->get($str);
            $res_arr = json_decode($res->raw_body, true);
            $ret['location'] = $res_arr['results'][0]['geometry']['location'];

            $all_address = $res_arr['results'][0]['address_components'];
            if (!empty($all_address)) {
                foreach ($all_address as $a_address) {
                    $map_type = $a_address['types'][0];                                
                    if ($map_type == 'country') {
                        $ret['country_short_code'] = $a_address['short_name'];
                    }
                }
            }
        }
        return $ret;
    }

    public function login_callback(){
        if ($this->session->userdata('login_callback')) {
            $custom_callback = $this->session->userdata('login_callback');
            $this->session->unset_userdata('login_callback');
            redirect($custom_callback);
        }
    }

    public function test_player(){
        $this->load->view('test');
    }
}

/* End of file Register.php */
/* Location: ./application/controllers/Register.php */