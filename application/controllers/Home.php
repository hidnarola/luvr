<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {
        
        parent::__construct();
        $this->load->model(array('Users_model', 'Filters_model'));
        $this->load->library('unirest');
        $u_data = $this->session->userdata('user');
        if(empty($u_data)){
            redirect('register');
        }
    }

    public function index() {   
        echo "Welcome Home page";
    }

    // ------------------------------------------------------------------------
    // BIO START
    // ------------------------------------------------------------------------

    public function save_bio(){
        
        $u_data = $this->session->userdata('user');
        $insta_id = $u_data['userid'];
        $access_token = $u_data['access_token'];

        $curl1 = "https://api.instagram.com/v1/users/".$insta_id."/media/recent/?access_token=" . $access_token;
        $response = $this->unirest->get($curl1, $headers = array());
        $row_data = json_decode($response->raw_body,true);
        
        // pr($row_data);

        $user_info = $this->Users_model->getUserByCol('id', $u_data['id']);

        // pr($row_data,1);

        $data['sub_view'] = 'bio/index';
        $data['meta_title'] = "Save instagram bio";
        $data['userData'] = $user_info;
        $data['all_images'] = $row_data['data'];
        $data['next_link'] = $row_data['pagination']['next_url'];
        $this->load->view('main', $data);

        // pr($u_data,1);

    }

    public function test_fetch(){
        // $curl1 = 'https://api.instagram.com/v1/users/145281153/media/recent?max_id=988968134181522074_145281153&access_token=145281153.17fd6de.dda034d5718c4ea19a1ec798be53dc9f';
        $curl1 = 'https://api.instagram.com/v1/users/145281153/media/recent?access_token=145281153.17fd6de.dda034d5718c4ea19a1ec798be53dc9f&max_id=685858156971165999_145281153';
        $response = $this->unirest->get($curl1, $headers = array());
        $row_data = json_decode($response->raw_body,true);
        pr($row_data);
    }

    public function fetch_insta_bio(){
        
        $next_url = $this->input->post('next_url');

        $curl1 = $next_url;
        $response = $this->unirest->get($curl1, $headers = array());
        $row_data = json_decode($response->raw_body,true);
        
        $all_images = $row_data['data'];
        $next_link = $row_data['pagination']['next_url'];

        $new_str = '';
        
            if(!empty($all_images)){
                foreach($all_images as $image){                        
                    $new_str .= '<div class="col-sm-3">';
                    $new_str .= '<img src="'.$image['images']['standard_resolution']['url'].'" class="img-responsive" style="width:100%" alt="Image">';
                    $new_str .= '<a href="'.$image['link'].'" class="btn btn-primary"> Insta Link </a></div>';
                }
            }
        
        $ret_data['all_images'] = $new_str;
        $ret_data['next_link'] = $next_link;
        $ret_data['return_data'] = $row_data;
        echo json_encode($ret_data);
    }

    // ------------------------------------------------------------------------
    // BIO ENDS
    // ------------------------------------------------------------------------

    public function setup_userprofile() {

        $u_data = $this->session->userdata('user');

        $user_id = $u_data['id'];
        $user_info = $this->Users_model->getUserByCol('id', $user_id);

        $this->form_validation->set_rules('id', 'UserID', 'required|trim');
        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
        $this->form_validation->set_rules('address', 'Location', 'required|callback_validate_zipcode|trim',['validate_zipcode'=>'Please enter valid address.']);
        $this->form_validation->set_rules('gender', 'Gender', 'required|trim');
        $this->form_validation->set_rules('bio', 'About Me', 'required|trim');
        
        if ($this->form_validation->run() == FALSE) {
            $data['sub_view'] = 'userProfileSettings';
            $data['meta_title'] = "Setup User Profile";
            $data['userData'] = $user_info;
            $this->load->view('main', $data);
        } else {
            
            $user_data['id'] = $this->input->post('id');
            $user_data['user_name'] = $this->input->post('username');
            $user_data['email'] = $this->input->post('email');
            $user_data['gender'] = $this->input->post('gender');
            $user_data['age'] =  $this->input->post('age');
            $user_data['one_liner'] = $this->input->post('one_liner');
            $user_data['work'] = $this->input->post('work');
            $user_data['school'] = $this->input->post('school');
            $user_data['address'] = $this->input->post('address');
            $user_data['bio'] = $this->input->post('bio');

            $res_address = $this->validate_zipcode($user_data['address'],true); // fetch latlong using google api
            $user_data['latlong'] = implode(',',$res_address['results'][0]['geometry']['location']); // implode into single string

            $success = $this->Users_model->manageUser($user_data);
            if ($success == true) {
                $result = $this->Users_model->checkUserPreferencesSet($user_id);
                if (empty($result) || $result == false)
                    redirect('home/setup_userfilters');
                else
                    redirect('match');
            } else {
                $this->session->set_flashdata('error', 'Something went wrong!');
                redirect('home/setup_userprofile');
            }
        }
    }

    public function setup_userfilters() {
        $u_data = $this->session->userdata('user');
        $user_id = $u_data['id'];

        $all_filters = $this->Filters_model->getMainFilterByCol('filter_id', 1);
        $user_filters = $this->Filters_model->getUserSubFilterByCol('userid', $user_id);
        $total_filters = $this->db->count_all_results('main_filters');
        $data['sub_view'] = 'userFilterSettings';
        $data['meta_title'] = "Setup User Preferences";
        $data['filtersData'] = $all_filters;
        $data['userFilters'] = $user_filters;
        $data['totalFilters'] = $total_filters;
        $this->load->view('main', $data);
    }

    public function savestep() {
        $u_data = $this->session->userdata('user');
        $user_id = $u_data['id'];

        $success = false;
        $next_filter_id = null;
        $next_pref_html = "";
        if (!empty($_POST['sub_filters']) && $_POST['sub_filters'] != null && is_numeric($_POST['filter_id'])) {
                    
            $sub_filters_post = $_POST['sub_filters'];
            $sub_filters = $this->Filters_model->getSubFilterByCol('filter_id', $_POST['filter_id']);
            if (!empty($sub_filters)) {
                $sub_filter_ids = extractIDS('sub_filter_id', $sub_filters);
                $this->Filters_model->deleteUserSubFiltersByCol('userid', $user_id, $sub_filter_ids);
                $sub_filter_data = array();
                foreach ($sub_filters as $sf) {
                    unset($sf['sub_filter_name'], $sf['filter_id']);
                    $sf['userid'] = $user_id;
                    $sf['sub_filter_id'] = $sf['sub_filter_id'];
                    $sf['is_filter_on'] = 0;
                    if (in_array($sf['sub_filter_id'], $sub_filters_post)) {
                        $sf['is_filter_on'] = 1;
                    }
                    $sf['created_date'] = $sf['updated_date'] = date("Y-m-d H:i:s");
                    $sub_filter_data[] = $sf;
                }
                $this->db->insert_batch('user_filter', $sub_filter_data);
                $next_filter_info = $this->Filters_model->getNextFilterInfo($_POST['filter_id']);
                $next_filter_id = $next_filter_info['filter_id'];
                $next_filter_name = $next_filter_info['filter_name'];
                $next_filter_detailed_info = $this->Filters_model->getMainFilterByCol('filter_id', $next_filter_id);
                $user_filters = $this->Filters_model->getUserSubFilterByCol('userid', $user_id);
                $users_filters = array();
                if (!empty($user_filters)) {
                    foreach ($user_filters as $uf) {
                        if ($uf['is_filter_on'] == 1) {
                            $users_filters[] = $uf['sub_filter_id'];
                        }
                    }
                }
                foreach ($next_filter_detailed_info as $fdata) {
                    $is_checked = (in_array($fdata['sub_filter_id'], $users_filters)) ? "checked" : "";
                    $i_dont_care = (strtolower($fdata['sub_filter_name']) == strtolower('I don\'t care')) ? "onclick='ignoreOther()' id='idontcare' class='subfilters_ignoreme'" : "onclick='ignoreLast()' class='subfilters'";
                    $next_pref_html .= '<tr><td>' . $fdata['sub_filter_name'] . '</td><td><label class="switch"><input type="checkbox" name="sub_filters[]" value="' . $fdata['sub_filter_id'] . '" ' . $is_checked . ' ' . $i_dont_care . '/><div class="slider round"></div></label></td></tr>';
                }
                $success = true;
            }
        }
        echo json_encode(array("success" => $success, "next_filter_id" => $next_filter_id, "next_filter_name" => $next_filter_name, "next_filter_html" => $next_pref_html));
    }

    // --------------------------------------------------------------------------------------
    // function will validate whether user enter valid address so we can enter valid lat,long
    // --------------------------------------------------------------------------------------
    public function validate_zipcode($address,$data=''){
        if($address != ''){
            $str = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$address.'&key='.GOOGLE_MAP_API;
            $res = $this->unirest->get($str);
            $res_arr = json_decode($res->raw_body,true);
            
            // If $data is not null means return a longitude and latitude array ohter wise only status True/False
            // pr($res_arr,1);
            if($data){
                return $res_arr;
            } else {
                if($res_arr['status'] != 'OK' && !empty($address)){
                    return FALSE;
                }else if($res_arr['status'] == 'OK' && !empty($address)) {
                    return TRUE;
                }
            }    
        }        
    } // END of function validate_zipcode
}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */
