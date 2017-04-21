<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->model(array('Users_model', 'Filters_model', 'Matches_model', 'Bio_model'));
        $this->load->library('unirest');
        $u_data = $this->session->userdata('user');
        if (empty($u_data) && uri_string() != 'user/manage_subscription' && uri_string() != "user/login_callback") {
            redirect('register');
        }
    }

    public function index() {
        $data['sub_view'] = 'viewProfile';
        $data['meta_title'] = "User Profile";
        $this->load->view('main', $data);
    }

    /* --------------------------------------------------------------------------------------
      This function will save user profile's data.
      -------------------------------------------------------------------------------------- */

    public function setup_userprofile($mode = null) {
        $u_data = $this->session->userdata('user');

        $user_id = $u_data['id'];
        $user_info = $this->Users_model->getUserByCol('id', $user_id);

        $this->form_validation->set_rules('id', 'UserID', 'required|trim');
        $this->form_validation->set_rules('username', 'Username', 'required|trim');
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email|trim');
        $this->form_validation->set_rules('age', 'Age', 'min[1]');
        $this->form_validation->set_rules('address', 'Location', 'required|callback_validate_zipcode|trim', ['validate_zipcode' => 'Please enter valid address.']);
        $this->form_validation->set_rules('gender', 'Gender', 'required|trim');
        $this->form_validation->set_rules('bio', 'About Me', 'required|trim');

        if ($this->form_validation->run() == FALSE) {
            $data['sub_view'] = 'user/userProfileSettings';
            $data['meta_title'] = "Setup User Profile";
            $data['userData'] = $user_info;
            $data['mode'] = $mode;
            $this->load->view('main', $data);
        } else {
            $user_data['id'] = $this->input->post('id');
            $user_data['user_name'] = $this->input->post('username');
            $user_data['email'] = $this->input->post('email');
            $user_data['gender'] = $this->input->post('gender');
            $user_data['age'] = $this->input->post('age');
            $user_data['one_liner'] = $this->input->post('one_liner');
            $user_data['work'] = $this->input->post('work');
            $user_data['school'] = $this->input->post('school');
            $user_data['address'] = $this->input->post('address');
            $user_data['bio'] = $this->input->post('bio');

            $res_address = $this->validate_zipcode($user_data['address'], true); // fetch latlong using google api
            $user_data['latlong'] = implode(',', $res_address['results'][0]['geometry']['location']); // implode into single string

            $success = $this->Users_model->manageUser($user_data);
            if ($success == true) {
                if ($mode != null && $mode == "edit") {
                    $this->session->set_flashdata('success', 'User profile updated successfully.');
                    redirect('user/setup_userprofile');
                } else {
                    $result = $this->Users_model->checkUserPreferencesSet($user_id);
                    if (empty($result) || $result == false)
                        redirect('user/setup_userfilters');
                    else
                        redirect('match/nearby');
                }
            } else {
                $this->session->set_flashdata('error', 'Something went wrong!');
                redirect('user/setup_userprofile');
            }
        }
    }

    /* --------------------------------------------------------------------------------------
      This function will save bring logged in user on setup user filters page from where
      logged in user can setup his/her search preferences.
      -------------------------------------------------------------------------------------- */

    public function setup_userfilters() {
        $u_data = $this->session->userdata('user');
        $user_id = $u_data['id'];
        $result = $this->Users_model->checkUserPreferencesSet($user_id);
        if (empty($result) || $result == false) {
            $all_filters = $this->Filters_model->getMainFilterByCol('filter_id', 1);
            $user_filters = $this->Filters_model->getUserSubFilterByCol('userid', $user_id);
            $total_filters = $this->db->count_all_results('main_filters');
            $data['sub_view'] = 'user/userFilterSettings';
            $data['meta_title'] = "Setup User Preferences";
            $data['redirect'] = "match/nearby";
            $data['filtersData'] = $all_filters;
            $data['userFilters'] = $user_filters;
            $data['totalFilters'] = $total_filters;
            $this->load->view('main', $data);
        } else
            redirect('match/nearby');
    }

    /* --------------------------------------------------------------------------------------
      This function will save filters and sub filters data for logged in user.
      -------------------------------------------------------------------------------------- */

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
                    $i_dont_care = (strtolower($fdata['sub_filter_name']) == strtolower('I don\'t care')) ? "onchange='ignoreOther()' id='idontcare' class='subfilters_ignoreme'" : "onchange='ignoreLast()' id='chk_" . $fdata['sub_filter_id'] . "' class='subfilters'";
                    $id = "chk_" . $fdata['sub_filter_id'] . "";
                    if (strtolower($fdata['sub_filter_name']) == strtolower('I don\'t care'))
                        $id = "idontcare";
                    $next_pref_html .= '<li><span><label for="' . $id . '">' . $fdata['sub_filter_name'] . '</label></span><label class="switch"><input type="checkbox" name="sub_filters[]" value="' . $fdata['sub_filter_id'] . '" ' . $is_checked . ' ' . $i_dont_care . '/><div class="slider round"></div></label></li>';
                }
                $success = true;
            }
        }
        echo json_encode(array("success" => $success, "next_filter_id" => $next_filter_id, "next_filter_name" => $next_filter_name, "next_filter_html" => $next_pref_html));
    }

    // --------------------------------------------------------------------------------------
    // function will validate whether user enter valid address so we can enter valid lat,long
    // --------------------------------------------------------------------------------------
    public function validate_zipcode($address, $data = '') {
        if ($address != '') {
            $str = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . $address . '&key=' . GOOGLE_MAP_API;
            $res = $this->unirest->get($str);
            $res_arr = json_decode($res->raw_body, true);

            // If $data is not null means return a longitude and latitude array ohter wise only status True/False
            if ($data) {
                return $res_arr;
            } else {
                if ($res_arr['status'] != 'OK' && !empty($address)) {
                    return FALSE;
                } else if ($res_arr['status'] == 'OK' && !empty($address)) {
                    return TRUE;
                }
            }
        }
    }

    // END of function validate_zipcode

    public function login_callback() {
        $this->session->set_userdata('login_callback', base_url('home/#packages'));
        redirect("https://api.instagram.com/oauth/authorize/?client_id=" . INSTA_CLIENT_ID . "&redirect_uri=" . base_url() . "register/return_url&response_type=code&scope=likes+comments+follower_list+relationships+public_content");
    }

    /* This function will logout user. */

    public function logout() {
        $this->session->unset_userdata('user');
        $this->load->view('user/logout');
    }

    /* -----------------------------------------------------------------------------------------
      This function will bring user to page which will allow user to change preferences filters.
      ------------------------------------------------------------------------------------------ */

    public function edit_filters() {
        $u_data = $this->session->userdata('user');
        $user_id = $u_data['id'];
        $all_filters = $this->Filters_model->getMainFilterByCol('filter_id', 1);
        $user_filters = $this->Filters_model->getUserSubFilterByCol('userid', $user_id);
        $total_filters = $this->db->count_all_results('main_filters');
        $data['sub_view'] = 'user/userFilterSettings';
        $data['meta_title'] = "Edit User Preferences";
        $data['redirect'] = "match/nearby";
        $data['filtersData'] = $all_filters;
        $data['userFilters'] = $user_filters;
        $data['totalFilters'] = $total_filters;
        $this->load->view('main', $data);
    }

    /* -----------------------------------------------------------------------------------------
      This function will is to fetch users who were blocked by logged in user.
      ------------------------------------------------------------------------------------------ */

    public function blocked_list($offset = null) {
        $this->load->library('pagination');
        $u_data = $this->session->userdata('user');
        $user_id = $u_data['id'];
        $blocked_users = $this->Users_model->getBlockedUsers($user_id, $offset);
        $data['sub_view'] = 'user/blockedList';
        $data['meta_title'] = "Blocked List";
        $data['blockedUsers'] = $blocked_users;
        $config['base_url'] = base_url() . 'user/blocked_list/';
        $config['total_rows'] = $this->Users_model->getBlockedUsers($user_id, null, true);
        $config['per_page'] = 10;
        $config = array_merge($config, pagination_config());
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('main', $data);
    }

    public function unblockUser() {
        $u_data = $this->session->userdata('user');
        $data['requestby_id'] = $u_data['id'];
        $data['requestto_id'] = $this->input->post('user_id');
        $data['is_blocked'] = 0;
        $data['updated_date'] = date("Y-m-d H:i:s");
        $success = false;
        if (!empty($data)) {
            $success = $this->Users_model->updateUserRelation($data);
        }
        echo json_encode(array("success" => $success));
    }

    /* -----------------------------------------------------------------------------------------
      This function will bring user on user settings page.
      ------------------------------------------------------------------------------------------ */

    public function user_settings() {
        $u_data = $this->session->userdata('user');
        $user_id = $u_data['id'];

        $this->form_validation->set_rules('address', 'Location', 'required|callback_validate_zipcode|trim', ['validate_zipcode' => 'Please enter valid location.']);

        if ($this->form_validation->run() == FALSE) {
            $data['sub_view'] = 'user/settings';
            $data['meta_title'] = "Edit User Settings";
            $notification_settings = $this->Users_model->getUserSetings('userid', $user_id);
            $user_info = $this->Users_model->getUserByCol('id', $user_id);
            $data['notificationSettings'] = $notification_settings;
            $data['userInfo'] = $user_info;
            $this->load->view('main', $data);
        } else {
            $user_data['id'] = $settings['userid'] = $user_id;
            $settings['is_universal_profile'] = (int) $this->input->post('is_universal_profile');
            $user_data['address'] = $this->input->post('address');
            $res_address = $this->validate_zipcode($user_data['address'], true); // fetch latlong using google api
            $user_data['latlong'] = implode(',', $res_address['results'][0]['geometry']['location']); // implode into single string
            $user_data['radius'] = $this->input->post('hdn_radius');
            $settings['interest'] = $this->input->post('interest');
            $settings['age_range'] = $this->input->post('hdn_age_range');
            $settings['is_visibility'] = (int) $this->input->post('is_visibility');
            $settings['is_new_match'] = (int) $this->input->post('is_new_match');
            $settings['is_allow_messages'] = (int) $this->input->post('is_allow_messages');
            $settings['is_allow_power_likes'] = (int) $this->input->post('is_allow_power_likes');
            $success_user = $this->Users_model->manageUser($user_data);
            $success_settings = $this->Users_model->updateUserSettings($settings);
            $this->session->set_flashdata('success', 'User settings saved successfully.');
            redirect('user/user_settings');
        }
    }

    /* -----------------------------------------------------------------------------------------
      This function will display all video requests.
      ------------------------------------------------------------------------------------------ */

    public function video_requests($offset = null) {
        $this->load->library('pagination');
        $u_data = $this->session->userdata('user');
        $user_id = $u_data['id'];
        $video_requests = $this->Users_model->getUsersVideoRequests($user_id, $offset);
        $data['sub_view'] = 'user/videoRequests';
        $data['meta_title'] = "Video Requests";
        $data['videoRequests'] = $video_requests;
        $config['base_url'] = base_url() . 'user/video_requests/';
        $config['total_rows'] = $this->Users_model->getUsersVideoRequests($user_id, null, true);
        $config['per_page'] = 10;
        $config = array_merge($config, pagination_config());
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('main', $data);
    }

    /* -----------------------------------------------------------------------------------------
      This function will manage status of video requests.
      ------------------------------------------------------------------------------------------ */

    public function manageVideoRequest() {
        $data['id'] = $this->input->post('request_id');
        $data['status'] = $this->input->post('mode');
        $data['updated_date'] = date("Y-m-d H:i:s");
        $success = false;
        if (!empty($data)) {
            $success = $this->Users_model->manageVideoRequest($data);
        }
        echo json_encode(array("success" => $success));
    }

    /* --------------------------------------------------------------------------------------------------
      This function will display profile of logged in user or of the user with the id passed as parameter.
      --------------------------------------------------------------------------------------------------- */

    public function view_profile($user_id = '', $view_card = 0, $mode = null) {
        $u_data = $this->session->userdata('user');

        if (empty($user_id)) {
            $user_id = $u_data['id'];
        }

        $data['sub_view'] = 'user/userProfile';
        $data['view_card'] = $view_card;
        $data['mode'] = $mode;
        $data['meta_title'] = "View User Profile";
        $data['db_user_data'] = $this->Users_model->fetch_userdata(['id' => $user_id], true);
        $data['user_profile'] = $this->Bio_model->fetch_mediadata(['id' => $data['db_user_data']['profile_media_id']], true);
        $data['user_swipes_per_day'] = 0;
        if ($view_card == 1) {
            $user_settings = $this->Users_model->getUserSetings('userid', $user_id);
            if ($user_settings['is_premium_member'] == 0) {
                $data['user_swipes_per_day'] = $this->Users_model->getTotalUsersSwipesByCol('requestby_id', $user_id, true, array("relation_status" => 1));
            }
        }
        if (empty($data['db_user_data'])) {
            show_404();
        }

        $this->load->view('main', $data);
    }

    /* -------------------------------------------------------------------------
      This function will fetch all power luv requests of logged in user.
      -------------------------------------------------------------------------- */

    public function pl_requests($offset = null) {
        $this->load->library('pagination');
        $u_data = $this->session->userdata('user');
        $user_id = $u_data['id'];
        $pl_requests = $this->Matches_model->getUsersPLRequests($user_id, $offset);
        $data['sub_view'] = 'user/powerLuvRequests';
        $data['meta_title'] = "Powerluv Requests";
        $data['powerLuvRequests'] = $pl_requests;
        $config['base_url'] = base_url() . 'user/pl_requests/';
        $config['total_rows'] = $this->Matches_model->getUsersPLRequests($user_id, null, true);
        $config['per_page'] = 10;
        $config = array_merge($config, pagination_config());
        $this->pagination->initialize($config);
        $data['pagination'] = $this->pagination->create_links();
        $this->load->view('main', $data);
    }

    public function manage_subscription() {
        require APPPATH . 'third_party/stripe/Stripe.php';
        $u_data = $this->session->userdata('user');
        $user_id = $u_data['id'];
        $params = array(
            "testmode" => "off",
            "private_live_key" => SK_LIVE,
            "public_live_key" => PK_LIVE,
            "private_test_key" => SK_TEST,
            "public_test_key" => PK_TEST
        );

        if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
            $params['testmode'] = "on";
        }

        if ($params['testmode'] == "on") {
            Stripe::setApiKey($params['private_test_key']);
            $pubkey = $params['public_test_key'];
        } else {
            Stripe::setApiKey($params['private_live_key']);
            $pubkey = $params['public_live_key'];
        }

        if (isset($_POST['stripeToken'])) {
            $amount_cents = str_replace(".", "", "10.52");  // Chargeble amount
            $invoiceid = "14526321";                      // Invoice ID
            $description = "Invoice #" . $invoiceid . " - " . $invoiceid;
            $subscription_plan = $_POST['subplan'];

            try {
                $charge = Stripe_Charge::create(array(
                            "amount" => $amount_cents,
                            "currency" => "usd",
                            "source" => $_POST['stripeToken'],
                            "description" => $description)
                );

                if (@$charge->card->address_zip_check == "fail") {
                    throw new Exception("zip_check_invalid");
                } else if (@$charge->card->address_line1_check == "fail") {
                    throw new Exception("address_check_invalid");
                } else if (@$charge->card->cvc_check == "fail") {
                    throw new Exception("cvc_check_invalid");
                }
                // Payment has succeeded, no exceptions were thrown or otherwise caught				

                $result = "success";
            } catch (Stripe_CardError $e) {
                $error = $e->getMessage();
                $result = "declined";
            } catch (Stripe_InvalidRequestError $e) {
                $error = $e->getMessage();
                $result = "declined";
            } catch (Stripe_AuthenticationError $e) {
                $error = $e->getMessage();
                $result = "declined";
            } catch (Stripe_ApiConnectionError $e) {
                $error = $e->getMessage();
                $result = "declined";
            } catch (Stripe_Error $e) {
                $error = $e->getMessage();
                $result = "declined";
            } catch (Exception $e) {
                $error = $e->getMessage();
                if ($e->getMessage() == "zip_check_invalid") {
                    $result = "declined";
                } else if ($e->getMessage() == "address_check_invalid") {
                    $result = "declined";
                } else if ($e->getMessage() == "cvc_check_invalid") {
                    $result = "declined";
                } else {
                    $result = "declined";
                }
            }
            /* echo "<BR>Stripe Payment Status : " . $result; */
            if ($result == "success") {
                $data['is_premium_member'] = 1;
                $data['userid'] = $user_id;
                $data['main_receipe_token'] = $charge->__toJSON();
                if ($subscription_plan == "monthly") {
                    $data['premium_expiry_date'] = date("Y-m-d H:i:s", strtotime("+1 month", time()));
                } else if ($subscription_plan == "6monthly") {
                    $data['premium_expiry_date'] = date("Y-m-d H:i:s", strtotime("+6 months", time()));
                } else if ($subscription_plan == "yearly") {
                    $data['premium_expiry_date'] = date("Y-m-d H:i:s", strtotime("+1 year", time()));
                }
                /* $user_settings = $this->db->get_where('user_settings', array('userid' => $user_id))->row_array();
                  if (!empty($user_settings)) { */
                $this->Users_model->updateUserSettings($data);
                /* } else {
                  $this->Users_model->insertUserSettings($data);
                  } */
                redirect('match/nearby');
            } else {
                $this->session->set_flashdata('error', 'Error occured while purchase!');
                redirect('/');
            }
            /* if ($result == "declined")
              echo "<BR>Stripe Payment Error : " . $error;
              echo "<BR>Stripe Response : ";
              pr($charge); */
        }
    }

}
