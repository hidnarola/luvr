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
        $this->load->model(array('Users_model', 'Filters_model', 'Matches_model'));
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
        $user_filters = $this->Filters_model->getUserSubFilterByCol('userid', $user_id);
        $user_info = $this->Users_model->getUserByCol('id', $user_id);
        if ($user_settings['is_premium_member'] == 0) {
            $data['user_swipes_per_day'] = $this->Users_model->getTotalUsersSwipesByCol('requestby_id', $user_id, true, array("relation_status" => 1));
        } else {
            $data['user_swipes_per_day'] = "n/a";
        }
        $data['user_powerluvs_per_day'] = $this->Users_model->getTotalUsersSwipesByCol('requestby_id', $user_id, true, array("relation_status" => 3));
        $u_data['latlong'] = $user_info['latlong'];
        $u_data['radius'] = $user_info['radius'];
        $u_data['user_settings'] = $user_settings;
        $u_data['user_filters'] = $user_filters;
        $near_by = $this->Matches_model->getUserNearBy($user_id, $u_data);
        $data['sub_view'] = 'match/nearByMatches';
        $data['meta_title'] = "Nearby Matches";
        $data['nearByUsers'] = $near_by['result'];
        $data['is_user_premium_member'] = $user_settings['is_premium_member'];
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
        /* $offset = $this->input->post('offset'); */
        $user_settings = $this->Users_model->getUserSetings('userid', $user_id);
        $user_filters = $this->Filters_model->getUserSubFilterByCol('userid', $user_id);
        $u_data['user_settings'] = $user_settings;
        $u_data['user_filters'] = $user_filters;
        $near_by = $this->Matches_model->getUserNearBy($user_id, $u_data);
        if (!empty($near_by['result']) && $near_by['result'] != null) {
            $response = true;
            $i = 0;
            $html = "";
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

                $path = "";
                if ($user['media_type'] == 0 && !empty($user['media_thumb'])) {
                    $path = $user['media_thumb'];
                } else if ($user['media_type'] == 1 || $user['media_type'] == 2) {
                    $path = base_url() . "assets/images/users/" . $user['media_thumb'];
                    if (!file_exists(PHYSICALUPLOADPATH . "/images/users/" . $user['media_thumb']))
                        $path = base_url() . "assets/images/big_avatar.jpg";
                } else if ($user['media_type'] == 3 || $user['media_type'] == 4) {
                    $path = $user['media_thumb'];
                }
                $html .= '<li class="panel" data-id="' . $user['id'] . '">
                                        <div class="user-list-pic-wrapper">
                                            <div class="user-list-pic-bg">
                                                <div style="background:url(\'' . $path . '\') no-repeat scroll center center;" class="img"></div>
                                            </div>
                                        <div class="user-list-pic-close">
                                        <a class="for_pointer" onclick="$(\'#tinderslide\').jTinder(\'dislike\');">
                                            <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                 viewBox="0 0 371.23 371.23" style="enable-background:new 0 0 371.23 371.23;" xml:space="preserve">
                                            <polygon points="371.23,21.213 350.018,0 185.615,164.402 21.213,0 0,21.213 164.402,185.615 0,350.018 21.213,371.23 
                                                     185.615,206.828 350.018,371.23 371.23,350.018 206.828,185.615 "/>
                                            <g>
                                            </g>
                                            <g>
                                            </g>
                                            <g>
                                            </g>
                                            <g>
                                            </g>
                                            <g>
                                            </g>
                                            <g>
                                            </g>
                                            <g>
                                            </g>
                                            <g>
                                            </g>
                                            <g>
                                            </g>
                                            <g>
                                            </g>
                                            <g>
                                            </g>
                                            <g>
                                            </g>
                                            <g>
                                            </g>
                                            <g>
                                            </g>
                                            <g>
                                            </g>
                                            </svg>
                                        </a>
                                    </div>
                                    <div class="user-next-prev">
                                        <a class="for_pointer" onclick="prevMatch(' . $user['id'] . ')">
                                            <svg version="1.0" id="Layer_1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;"
                                                 xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="27.08px"
                                                 height="17.699px" viewBox="0 0 27.08 17.699" enable-background="new 0 0 27.08 17.699" xml:space="preserve">
                                            <switch>
                                            <foreignObject requiredExtensions="&ns_ai;" x="0" y="0" width="1" height="1">
                                            <i:pgfRef  xlink:href="#adobe_illustrator_pgf">
                                            </i:pgfRef>
                                            </foreignObject>
                                            <g i:extraneous="self">
                                            <path d="M26.54,9.917H10.858v5.865c0,0.391-0.225,0.75-0.586,0.938s-0.802,0.172-1.148-0.04L1.056,9.747
                                                  C0.734,9.551,0.54,9.212,0.54,8.85c0-0.363,0.194-0.701,0.516-0.898l8.068-6.933c0.346-0.212,0.787-0.227,1.148-0.04
                                                  c0.361,0.188,0.586,0.547,0.586,0.938v5.865h15.67 M3.743,8.85l4.865,4.975V3.875L3.743,8.85z"/>
                                            </g>
                                            </switch>
                                            </svg>
                                        </a>
                                        <a href="#">
                                            <svg version="1.0" id="Layer_1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;"
                                                 xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="27.08px"
                                                 height="17.699px" viewBox="0 0 27.08 17.699" enable-background="new 0 0 27.08 17.699" xml:space="preserve">
                                            <switch>
                                            <foreignObject requiredExtensions="&ns_ai;" x="0" y="0" width="1" height="1">
                                            <i:pgfRef  xlink:href="#adobe_illustrator_pgf">
                                            </i:pgfRef>
                                            </foreignObject>
                                            <g i:extraneous="self">
                                            <path d="M0.54,7.782h15.682V1.917c0-0.391,0.225-0.75,0.586-0.938s0.802-0.172,1.148,0.04l8.068,6.933
                                                  c0.321,0.196,0.516,0.535,0.516,0.897c0,0.363-0.194,0.701-0.516,0.898l-8.068,6.933c-0.346,0.212-0.787,0.227-1.148,0.04
                                                  c-0.361-0.188-0.586-0.547-0.586-0.938V9.918H0.552 M23.337,8.85l-4.865-4.975v9.949L23.337,8.85z"/>
                                            </g>
                                            </switch>
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