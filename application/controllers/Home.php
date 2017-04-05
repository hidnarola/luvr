<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    /**
     * Index Page for this controller.
     *
     * Maps to the following URL
     * 		http://example.com/index.php/welcome
     * 	- or -
     * 		http://example.com/index.php/welcome/index
     * 	- or -
     * Since this controller is set as the default controller in
     * config/routes.php, it's displayed at http://example.com/
     *
     * So any other public methods not prefixed with an underscore will
     * map to /index.php/welcome/<method_name>
     * @see https://codeigniter.com/user_guide/general/urls.html
     */
    function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->model(array('Users_model', 'Filters_model'));
    }

    public function index() {
        if ($this->input->get('code')) {
            $insta_params = array(
                'client_id' => INSTA_CLIENT_ID,
                'client_secret' => INSTA_CLIENT_SECRET,
                'grant_type' => "authorization_code",
                'redirect_uri' => base_url(),
                'code' => $this->input->get('code')
            );

            $curl1 = curl_init("https://api.instagram.com/oauth/access_token");
            curl_setopt($curl1, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($curl1, CURLOPT_POSTFIELDS, $insta_params);
            curl_setopt($curl1, CURLOPT_SSL_VERIFYPEER, false);
            $result1 = curl_exec($curl1);
            curl_close($curl1);
            $result1 = json_decode($result1, true);
            pr($result1);

            if (!empty($result1['access_token'])) {
                $curl2 = curl_init("https://api.instagram.com/v1/users/self/media/recent?access_token=" . $result1['access_token']);
                curl_setopt($curl2, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($curl2, CURLOPT_SSL_VERIFYPEER, false);
                $result2 = curl_exec($curl2);
                curl_close($curl2);
                $result2 = json_decode($result2);
                pr($result2);
            }
        } else {
            $data['sub_view'] = 'Homepage';
            $data['meta_title'] = "Welcome to Luvr";
            $this->load->view('main', $data);
        }
    }

    public function setup_userprofile() {
        $user_id = 111;
        $user_info = $this->Users_model->getUserByCol('id', $user_id);

        $this->form_validation->set_rules('id', 'UserID', 'required');
        $this->form_validation->set_rules('username', 'Username', 'required');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('address', 'Location', 'required');
        $this->form_validation->set_rules('gender', 'Gender', 'required');
        $this->form_validation->set_rules('bio', 'About Me', 'required');

        if ($this->form_validation->run() == FALSE) {
            $data['sub_view'] = 'userProfileSettings';
            $data['meta_title'] = "Setup User Profile";
            $data['userData'] = $user_info;
            $this->load->view('main', $data);
        } else {
            $user_data['id'] = $this->input->post('id');
            $user_data['user_name'] = (isset($_POST['username']) && !empty($_POST['username'])) ? trim($this->input->post('username')) : null;
            $user_data['email'] = (isset($_POST['email']) && !empty($_POST['email'])) ? trim($this->input->post('email')) : null;
            $user_data['gender'] = (isset($_POST['gender']) && !empty($_POST['gender'])) ? ucfirst(trim($this->input->post('gender'))) : null;
            $user_data['age'] = (isset($_POST['age']) && !empty($_POST['age'])) ? trim($this->input->post('age')) : null;
            $user_data['one_liner'] = (isset($_POST['one_liner']) && !empty($_POST['one_liner'])) ? trim($this->input->post('one_liner')) : null;
            $user_data['work'] = (isset($_POST['work']) && !empty($_POST['work'])) ? trim($this->input->post('work')) : null;
            $user_data['school'] = (isset($_POST['school']) && !empty($_POST['school'])) ? trim($this->input->post('school')) : null;
            $user_data['address'] = (isset($_POST['address']) && !empty($_POST['address'])) ? trim($this->input->post('address')) : null;
            $user_data['bio'] = (isset($_POST['bio']) && !empty($_POST['bio'])) ? trim($this->input->post('bio')) : null;
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
        $user_id = 111;
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
        $success = false;
        $next_filter_id = null;
        $next_pref_html = "";
        if (!empty($_POST['sub_filters']) && $_POST['sub_filters'] != null && is_numeric($_POST['filter_id'])) {
            $user_id = 111;
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
        
}
