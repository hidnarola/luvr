<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class User extends CI_Controller {

    public function __construct() {
        // Call the Model constructor
        parent::__construct();
        $this->load->model(array('Users_model', 'Filters_model', 'Matches_model', 'Bio_model','Messages_model'));
        $this->load->library(['unirest', 'facebook']);
        $u_data = $this->session->userdata('user');

        if (empty($u_data) && uri_string() != 'user/manage_subscription' && uri_string() != "user/login_callback" && uri_string() != "user/webcam" && uri_string() != "user/saverecordedvideo") {
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
        $this->form_validation->set_rules('age', 'Age', 'greater_than_equal_to[18]');
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

            $user_data['encrypted_username'] = base64_encode($user_data['user_name']);
            $user_data['encrypted_one_liner'] = base64_encode($user_data['one_liner']);
            $user_data['encrypted_bio'] = base64_encode($user_data['bio']);


            $res_address = $this->validate_zipcode($user_data['address'], true); // fetch latlong using google api
            // pr($res_address,1);
            $user_data['latlong'] = implode(',', $res_address['results'][0]['geometry']['location']); // implode into single string

            $success = $this->Users_model->manageUser($user_data);

            $country_short_code = '';
            $all_address = $res_address['results'][0]['address_components'];
            if (!empty($all_address)) {
                foreach ($all_address as $a_address) {
                    $map_type = $a_address['types'][0];
                    if ($map_type == 'country') {
                        $country_short_code = $a_address['short_name'];
                    }
                }
            }
            $session_u_data = (array) $this->Users_model->fetch_userdata(['id' => $user_data['id']], true);
            $session_u_data['access_token'] = $u_data['access_token'];
            $session_u_data['country_short_code'] = $country_short_code;
            $session_u_data['loginwith'] = $u_data['loginwith'];
            $session_u_data['fb_access_token'] = $u_data['fb_access_token'];

            $this->session->set_userdata('user', $session_u_data); // Set session key - "user" for all userdata

            if ($success == true) {

                if ($mode != null && $mode == "edit") {
                    $this->session->set_flashdata('success', 'User profile updated successfully.');
                    redirect('user/setup_userprofile');
                } else {
                    $result = $this->Users_model->checkUserPreferencesSet($user_id);
                    if (empty($result) || $result == false) {
                        redirect('user/setup_userfilters');
                    } else {
                        redirect('match/nearby');
                    }
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

        $user_data = $this->session->userdata('user');
        $fb_access_token = $user_data['fb_access_token'];

        $this->session->unset_userdata(['user', 'fb_token', 'FBRLH_state', 'login_callback']);

        $data['fb_url'] = 'https://www.facebook.com/logout.php?next=' . base_url() . '&access_token=' . $fb_access_token;
        $data['loginwith'] = $user_data['loginwith'];
        $this->load->view('user/logout', $data);
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
        $address = $this->input->post('address');
        if (!empty($address)) {
            foreach ($address as $ind => $val) {
                $this->form_validation->set_rules("address[" . $ind . "]", "Address", 'required|callback_validate_zipcode|trim', ['validate_zipcode' => 'Please enter valid location.']);
            }
        }
        $is_subscriber = false;
        if (isUserActiveSubscriber($user_id) == 1) {
            $is_subscriber = true;
        }
        if ($this->form_validation->run() == FALSE) {
            $data['fb_login_url'] = $this->facebook->get_login_url();
            $data['insta_login_url'] = 'https://api.instagram.com/oauth/authorize/?client_id=' . INSTA_CLIENT_ID . '&redirect_uri=' . base_url() . 'register/return_url' . '&response_type=code&scope=likes+comments+follower_list+relationships+public_content';

            $data['sub_view'] = 'user/settings';
            $data['meta_title'] = "Edit User Settings";
            $notification_settings = $this->Users_model->getUserSetings('userid', $user_id);
            $user_info = $this->Users_model->getUserByCol('id', $user_id);
            $data['notificationSettings'] = $notification_settings;
            $data['userAddresses'] = array();
            if ($is_subscriber == true) {
                $this->db->select("*");
                $this->db->from("location");
                $this->db->where("userid", $user_id);
                $rs = $this->db->get()->result_array();
                $data['userAddresses'] = $rs;
            }
            $data['userInfo'] = $user_info;
            $data['is_subscriber'] = $is_subscriber;
            $this->load->view('main', $data);
        } else {
            $user_data['id'] = $settings['userid'] = $user_id;
            $add = $this->input->post('address');
            if ($is_subscriber == false) {
                $user_data['address'] = $add[0];
            } else {
                $default_address = $this->input->post('hdn_default_address');
                $i = 0;
                if (!empty($default_address) && $default_address != null && !empty($add) && $add != null) {
                    $this->db->delete('location', array('userid' => $user_id));
                    $default_location_id = null;
                    foreach ($add as $ad) {
                        $latlong = $_POST['latlongs'][$i];
                        $this->db->insert("location", array('userid' => $user_id, "location_name" => $ad, "latlong" => $latlong, "updated_date" => date("Y-m-d H:i:s")));
                        if ($default_address == $ad) {
                            $default_location_id = $this->db->insert_id();
                        }
                        $i++;
                    }
                    $user_data['location_id'] = $default_location_id;
                }
            }
            $settings['is_universal_profile'] = (int) $this->input->post('is_universal_profile');
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
        $u_data = $this->session->userdata('user');
        $user_id = $u_data['id'];

        $request_id = $this->input->post('request_id');
        $status = $this->input->post('mode');
        $updated_date = date("Y-m-d H:i:s");
        $res = $this->db->get_where('videosnaps',['requestto_id'=>$user_id,'requestby_id'=>$request_id])->row_array();
        $this->db->update('videosnaps',['status'=>$status],['id'=>$res['id']]);
        $success = true;        
        echo json_encode(array("success" => $success,'last_id'=>$request_id));
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

    /* -------------------------------------------------------------------------
      This function will fetch and display user's subscription information.
      -------------------------------------------------------------------------- */

    public function subscription() {
        $u_data = $this->session->userdata('user');
        $user_id = $u_data['id'];
        $user_settings = $this->Users_model->getUserSetings('userid', $user_id);
        $data['sub_view'] = 'user/subscription';
        $data['meta_title'] = "Subscription";
        $data['user_settings'] = $user_settings;
        $data['is_user_premium_member'] = $user_settings['is_premium_member'];
        if ((strtotime($user_settings['premium_expiry_date']) > strtotime(date("Y-m-d H:i:s", time()))) && $user_settings['is_premium_member'] == 1) {
            $data['is_user_premium_member'] = 1;
        } else {
            $data['is_user_premium_member'] = 0;
        }
        $this->load->view('main', $data);
    }

    /* -------------------------------------------------------------------------
      This function will catch postback data after purchasing subscription.
      -------------------------------------------------------------------------- */

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
            $subscription_plan = $_POST['subplan'];

            try {
                $charge = Stripe_Charge::create(array(
                            "amount" => $_POST['amt'],
                            "currency" => "usd",
                            "source" => $_POST['stripeToken'])
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
                } else if ($subscription_plan == "2years") {
                    $data['premium_expiry_date'] = date("Y-m-d H:i:s", strtotime("+2 years", time()));
                } else if ($subscription_plan == "5years") {
                    $data['premium_expiry_date'] = date("Y-m-d H:i:s", strtotime("+5 years", time()));
                }
                /* $user_settings = $this->db->get_where('user_settings', array('userid' => $user_id))->row_array();
                  if (!empty($user_settings)) { */
                $this->Users_model->updateUserSettings($data);
                /* } else {
                  $this->Users_model->insertUserSettings($data);
                  } */
                $this->session->set_flashdata('success', 'Your purchase was successful.');
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

    public function unlink_account($account) {
        $sess_u_data = $this->session->userdata('user');


        if ($account == 'facebook') {
            $upd_data = ['facebook_id' => '', 'id' => $sess_u_data['id']];
            $this->Users_model->manageUser($upd_data); // Update the user's updated instagram ID            
        } else {
            $upd_data = ['userid' => '', 'id' => $sess_u_data['id']];
            $this->Users_model->manageUser($upd_data); // Update the user's updated instagram ID        
        }

        $session_u_data = (array) $this->Users_model->fetch_userdata(['id' => $sess_u_data['id']], true);

        if ($account == 'facebook') {
            $session_u_data['fb_access_token'] = '';
            $session_u_data['access_token'] = $response_arr['access_token'];
        } else {
            $session_u_data['fb_access_token'] = $sess_u_data['fb_access_token'];
            $session_u_data['access_token'] = '';
        }
        $session_u_data['country_short_code'] = $sess_u_data['country_short_code'];
        $session_u_data['loginwith'] = $sess_u_data['loginwith'];

        $this->session->set_userdata('user', $session_u_data); // Set session key - "user" for all userdata
        redirect('user/user_settings');
    }

    public function send_video_snap($chat_user_id) {

        $u_data = $this->session->userdata('user');
        $user_id = $u_data['id'];

        $data['video_snap_data'] = $this->Messages_model->fetch_videosnap_request($user_id,$chat_user_id);
        if(empty($data['video_snap_data'])){ show_404(); }
        if($data['video_snap_data']['status'] != 2){ show_404(); }

        $data['chat_user_id'] = $chat_user_id;
        $data['sub_view'] = 'user/webcam';
        $data['meta_title'] = "Snap request";
        $this->load->view('main', $data);
    }

    public function saverecordedvideo() {

        $u_data = $this->session->userdata('user');
        $user_id = $u_data['id'];

        $data = array();
        $path = "";
        $filename = '';
        $receiver_id = $this->input->post('receiver_id');        
        // pr($_FILES);

        if (isset($_FILES['file']) && !$_FILES['file']['error']) {
            if ($_FILES['file']['type'] == "video/webm") {
                $random = $user_id.'_'.random_name_generate();
                $fname = $random . ".webm";
                $fpathw = UPLOADPATH_VIDEO . '/' . $fname;
            }
            if (move_uploaded_file($_FILES['file']['tmp_name'], $fpathw)) {
                $mp4name = $random . ".mp4";
                $fpath = UPLOADPATH_VIDEO . '/' . $mp4name;
                if ($_FILES['file']['type'] == "video/webm") {
                    exec(FFMPEG_PATH.' -i ' . $fpathw . ' ' . $fpath);
                    @unlink($fpathw);
                }
                $thumb_name = $random . '.png';
                $thumb_path = UPLOADPATH_THUMB . '/' . $thumb_name;
                exec(FFMPEG_PATH . ' -i ' . $fpath . ' -ss 00:00:01.000 -vframes 1 ' . $thumb_path);                

                $success = true;
                $data['message'] = "";
                $path = $fpath;
                $filename = $random;
            } else {
                $success = false;
                $data['message'] = "Could not move file to destination folder!";
            }
        } else {
            $success = false;
            switch ($_FILES['file']['error']) {
                case '1': $data['message'] = "The uploaded file exceeds the upload_max_filesize directive in php.ini."; break;
                case '2': $data['message'] = "The uploaded file exceeds the MAX_FILE_SIZE directive that was specified in the HTML form."; break;
                case '3': $data['message'] = "The uploaded file was only partially uploaded."; break;                
                case '4': $data['message'] = "No file was uploaded."; break;                
                case '6': $data['message'] = "Missing /tmp folder."; break;
                case '7': $data['message'] = "Failed to write file to disk."; break;
                case '8': $data['message'] = "A PHP extension stopped the file upload."; break;
            }            
        }

        $this->Bio_model->insert_media([
                                        'userid'=>$user_id,
                                        'media_id'=>'0',
                                        'media_name'=>$filename.'.mp4',
                                        'media_thumb'=>$filename.'.mp4',
                                        'media_type'=>'2',
                                        'insta_datetime'=>date('Y-m-d H:i:s'),
                                        'created_date'=>date('Y-m-d H:i:s'),
                                        'is_bios'=>'0',
                                        'is_active'=>'1'
                                    ]);

        // sender_id
        // receiver_id
        // created_date
        echo json_encode(
                array(
                        "success" => $success,
                        "message" => $data['message'],
                        "path" => $path,
                        'filename'=>$filename,
                        'sender_id'=>$user_id,
                        'receiver_id'=>$receiver_id,
                        'created_date'=>date('Y-m-d H:i:s')
                    )
                );
    }

    public function manage_powerluv_subscription() {
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
            $subscription_plan = $_POST['subplan'];

            try {
                $charge = Stripe_Charge::create(array(
                            "amount" => $_POST['amt'],
                            "currency" => "usd",
                            "source" => $_POST['stripeToken'])
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
                $data['userid'] = $user_id;
                $data['ipn_log'] = $charge->__toJSON();
                $data['price'] = number_format($_POST['amt'] / 100, 2);
                /* $user_settings = $this->db->get_where('user_settings', array('userid' => $user_id))->row_array();
                  if (!empty($user_settings)) { */
                $this->Users_model->manageUserPowerLuvPackage($data);
                /* } else {
                  $this->Users_model->insertUserSettings($data);
                  } */
                $this->session->set_flashdata('success', 'Your purchase was successful.');
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
