<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Match extends CI_Controller {

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
        $this->load->model(array('Users_model', 'Filters_model', 'Matches_model', 'Bio_model'));
        $u_data = $this->session->userdata('user');
        if (empty($u_data)) {
            redirect('register');
        }
    }

    function index() {
        $data['sub_view'] = 'match/findMatch';
        $data['meta_title'] = "Find Match";
        $this->load->view('main', $data);
    }

    /* --------------------------------------------------------------------------------------
      This function will find nearby users based on the logged in users location.
      -------------------------------------------------------------------------------------- */

    function nearby() {
        $this->load->library('unirest');
        $u_data = $this->session->userdata('user');
        $user_id = $u_data['id'];
        $user_settings = $this->Users_model->getUserSetings('userid', $user_id);
        /* $user_filters = $this->Filters_model->getUserSubFilterByCol('userid', $user_id); */
        $user_info = $this->Users_model->getUserByCol('id', $user_id);
        if ($user_settings['is_premium_member'] == 0) {
            $data['user_swipes_per_day'] = $this->Users_model->getTotalUsersSwipesByCol('requestby_id', $user_id, true, array("relation_status" => 1));
        } else {
            $data['user_swipes_per_day'] = 0;
        }
        $data['user_powerluvs_per_day'] = $this->Users_model->getTotalUsersSwipesByCol('requestby_id', $user_id, true, array("relation_status" => 3));
        $u_data['latlong'] = $user_info['latlong'];
        $u_data['radius'] = $user_info['radius'];
        $u_data['user_settings'] = $user_settings;
        /* $u_data['user_filters'] = $user_filters; */
        $near_by = $this->Matches_model->getUserNearBy($user_id, $u_data);
        $data['sub_view'] = 'match/nearByMatches';
        $data['meta_title'] = "Nearby Matches";
        $data['nearByUsers'] = $near_by['result'];
        $data['latlong'] = $user_info['latlong'];
        $data['radius'] = $user_info['radius'];
        $data['is_user_premium_member'] = $user_settings['is_premium_member'];
        if ((strtotime($user_settings['premium_expiry_date']) > strtotime(date("Y-m-d H:i:s", time()))) && $user_settings['is_premium_member'] == 1) {
            $data['is_user_premium_member'] = 1;
        } else {
            $data['is_user_premium_member'] = 0;
        }
        $this->load->view('main', $data);
    }

    /* --------------------------------------------------------------------------------------
      This function will bring user to next level after like/luv.
      -------------------------------------------------------------------------------------- */

    function level2($user_id = '', $view_card = 0, $mode = null) {
        $this->load->library('unirest');
        $u_data = $this->session->userdata('user');
        if (empty($user_id)) {
            $user_id = $u_data['id'];
        }
        $user_settings = $this->Users_model->getUserSetings('userid', $u_data['id']);
        $user_info = $this->Users_model->getUserByCol('id', $u_data['id']);
        if ($user_settings['is_premium_member'] == 0) {
            $data['user_swipes_per_day'] = $this->Users_model->getTotalUsersSwipesByCol('requestby_id', $u_data['id'], true, array("relation_status" => 1));
        } else {
            $data['user_swipes_per_day'] = 0;
        }
        $data['user_powerluvs_per_day'] = $this->Users_model->getTotalUsersSwipesByCol('requestby_id', $u_data['id'], true, array("relation_status" => 3));
        $u_data['latlong'] = $user_info['latlong'];
        $u_data['radius'] = $user_info['radius'];
        $u_data['user_settings'] = $user_settings;
        $data['sub_view'] = 'match/level2';
        $data['db_user_data'] = $this->Users_model->fetch_userdata(['id' => $user_id], true);
        $data['user_profile'] = $this->Bio_model->fetch_mediadata(['userid' => $user_id]);
        $data['meta_title'] = "User info : " . $data['db_user_data']['user_name'];
        $data['latlong'] = $user_info['latlong'];
        $data['radius'] = $user_info['radius'];
        $data['is_user_premium_member'] = $user_settings['is_premium_member'];
        if ((strtotime($user_settings['premium_expiry_date']) > strtotime(date("Y-m-d H:i:s"))) && $user_settings['is_premium_member'] == 1) {
            $data['is_user_premium_member'] = 1;
        } else {
            $data['is_user_premium_member'] = 0;
        }
        $data['view_card'] = $view_card;
        $data['mode'] = $mode;
        $this->load->view('main', $data);
    }

    /* --------------------------------------------------------------------------------------
      This function will set like/dislike status based on swipe left/right operation.
      -------------------------------------------------------------------------------------- */

    function likedislike() {
        $totallikesreached = (int) $this->input->post('totallikesreached');
        $response = false;
        $user_swipes_per_day = $user_powerluvs_per_day = 0;
        $u_data = $this->session->userdata('user');
        $logged_in_user_id = $u_data['id'];
        $user_settings = $this->Users_model->getUserSetings('userid', $logged_in_user_id);
        $user_id = $this->input->post('user_id');
        $status = $this->input->post('status');
        $data['requestby_id'] = $logged_in_user_id;
        $data['requestto_id'] = $user_id;
        if ($status == "dislike")
            $data['relation_status'] = 0;
        else if ($status == "like")
            $data['relation_status'] = 1;
        else if ($status == "luv")
            $data['relation_status'] = 2;
        else if ($status == "powerluv")
            $data['relation_status'] = 3;
        $data['created_date'] = $data['updated_date'] = date("Y-m-d H:i:s");
        if ($totallikesreached == 0) {
            $response = $this->Users_model->likeDislikeUser($data);
        } else if ($totallikesreached == 1 && $status != "like") {
            $response = $this->Users_model->likeDislikeUser($data);
        }
        if ($user_settings['is_premium_member'] == 0) {
            $user_swipes_per_day = $this->Users_model->getTotalUsersSwipesByCol('requestby_id', $logged_in_user_id, true, array("relation_status" => 1));
        }
        $user_powerluvs_per_day = $this->Users_model->getTotalUsersSwipesByCol('requestby_id', $logged_in_user_id, true, array("relation_status" => 3));
        if (is_numeric($response) && $response != false)
            $response = true;
        echo json_encode(array("success" => $response, "user_swipes_per_day" => $user_swipes_per_day, "user_powerluvs_per_day" => $user_powerluvs_per_day));
    }

    /* --------------------------------------------------------------------------------------
      This function will fetch more users after reaching swipe of 10 users.
      -------------------------------------------------------------------------------------- */

    function loadMoreNearBys() {
        $response = false;
        $u_data = $this->session->userdata('user');
        $user_id = $u_data['id'];
        $user_settings = $this->Users_model->getUserSetings('userid', $user_id);
        $user_filters = $this->Filters_model->getUserSubFilterByCol('userid', $user_id);
        $u_data['user_settings'] = $user_settings;
        $u_data['user_filters'] = $user_filters;
        $near_by = $this->Matches_model->getUserNearBy($user_id, $u_data);
        $html = "";
        if ((strtotime($user_settings['premium_expiry_date']) > strtotime(date("Y-m-d H:i:s"))) && $user_settings['is_premium_member'] == 1) {
            $is_user_premium_member = 1;
        } else {
            $is_user_premium_member = 0;
        }
        if (!empty($near_by['result']) && $near_by['result'] != null) {
            $response = true;
            $i = 0;
            foreach ($near_by['result'] as $user) {
                $distance = null;
                if (!empty($user['latlong'])) {
                    $loc1 = explode(",", $u_data['latlong']);
                    $lat1 = (double) $loc1[0];
                    $lon1 = (double) $loc1[1];
                    $loc2 = explode(",", $user['latlong']);
                    $lat2 = (double) $loc2[0];
                    $lon2 = (double) $loc2[1];
                    if (!empty($loc1) && !empty($loc2)) {
                        $distance = distance($lat1, $lon1, $lat2, $lon2, "K");
                        $distance = number_format($distance, 2);
                    }
                }
                $near_by['result'][$i]['distance'] = $distance;

                $path = $href = "";
                if ($user['media_type'] == 0 && !empty($user['media_thumb'])) {
                    $path = $user['media_thumb'];
                    $href = $user['user_profile'];
                } else if ($user['media_type'] == 1 || $user['media_type'] == 2) {
                    /* $path = base_url() . "assets/images/users/" . $user['media_thumb'];
                      if (!file_exists(PHYSICALUPLOADPATH . "/images/users/" . $user['media_thumb']))
                      $path = base_url() . "assets/images/big_avatar.jpg";
                      $href = base_url() . "assets/images/users/" . $user['user_profile']; */
                    if ($user['media_type'] == 1) {
                        $path = base_url() . 'bio/show_img/' . $user['media_thumb'] . "/1";
                        $href = base_url() . "bio/show_img/" . $user['user_profile'];
                    }
                    if ($user['media_type'] == 2) {
                        $fname = replace_extension($user['media_thumb'], "png");
                        $path = base_url() . 'bio/show_img/' . $fname . "/1";
                        $href = base_url() . "bio/show_video/" . $user['user_profile'];
                    }
                } else if ($user['media_type'] == 3 || $user['media_type'] == 4) {
                    $path = $user['media_thumb'];
                    $href = $user['user_profile'];
                }
                $timestamp_html = "";
                if ($is_user_premium_member == 1) {
                    $timestamp_html = '<span class="_timestamp">' . date("m/d/y", strtotime($user['insta_datetime'])) . '<br/>' . date("h:s a", strtotime($user['insta_datetime'])) . '</span>';
                }
                $html .= '<li class="panel" data-id="' . $user['id'] . '">
                                        <div class="user-list-pic-wrapper">
                                            ' . $timestamp_html . '
                                            <div class="user-list-pic-bg">
                                                <a style="background:url(\'' . $path . '\') no-repeat scroll center center;" class="img"></a>';
                if ($user['media_type'] == 2 || $user['media_type'] == 4) {
                    $html .= '<a class="play-btn-large icon-play-button" data-fancybox href="' . $href . '"></a>';
                }
                $html .= '</div>
                                        <div class="user-list-pic-close">
                                        <a class="for_pointer" onclick="$(\'#tinderslide\').jTinder(\'dislike\');">
                                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                 viewBox="0 0 371.23 371.23" style="enable-background:new 0 0 371.23 371.23;" xml:space="preserve">
                                            <polygon points="371.23,21.213 350.018,0 185.615,164.402 21.213,0 0,21.213 164.402,185.615 0,350.018 21.213,371.23 
                                                     185.615,206.828 350.018,371.23 371.23,350.018 206.828,185.615 "/>
                                            <g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g>
                                            </svg>
                                        </a>
                                    </div>
                                    </div>
                                    </li>';

                $i++;
            }
        }
        echo json_encode(array("success" => $response, "data" => $near_by['result'], "html" => $html));
    }

}

?>