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
        $u_data = $this->session->userdata('user');
        $user_id = $u_data['id'];
        $user_settings = $this->Users_model->getUserSetings('userid', $user_id);
        $user_filters = $this->Filters_model->getUserSubFilterByCol('userid', $user_id);
        $user_info = $this->Users_model->getUserByCol('id', $user_id);
        if ($user_settings['is_premium_member'] == 0) {
            $data['user_swipes_per_day'] = $this->Users_model->getTotalUsersSwipesByCol('requestby_id', $user_id, true);
        } else {
            $data['user_swipes_per_day'] = "n/a";
        }
        $u_data['latlong'] = $user_info['latlong'];
        $u_data['radius'] = $user_info['radius'];
        $u_data['user_settings'] = $user_settings;
        $u_data['user_filters'] = $user_filters;
        $near_by = $this->Matches_model->getUserNearBy($user_id, $u_data);
        $data['sub_view'] = 'match/nearByMatches';
        $data['meta_title'] = "Nearby Matches";
        $data['nearByUsers'] = $near_by['result'];
        $this->load->view('main', $data);
    }

    /* --------------------------------------------------------------------------------------
      This function will set like/dislike status based on swipe left/right operation.
      -------------------------------------------------------------------------------------- */

    function likedislike() {
        $u_data = $this->session->userdata('user');
        $logged_in_user_id = $u_data['id'];
        $user_settings = $this->Users_model->getUserSetings('userid', $logged_in_user_id);
        $user_id = $this->input->post('user_id');
        $status = $this->input->post('status');
        $data['requestby_id'] = $logged_in_user_id;
        $data['requestto_id'] = $user_id;
        if ($status == "like")
            $data['relation_status'] = 1;
        else if ($status == "dislike")
            $data['relation_status'] = 0;
        $data['created_date'] = $data['updated_date'] = date("Y-m-d H:i:s");
        $user_swipes_per_day = 0;
        $response = $this->Users_model->likeDislikeUser($data);
        if ($user_settings['is_premium_member'] == 0) {
            $user_swipes_per_day = $this->Users_model->getTotalUsersSwipesByCol('requestby_id', $logged_in_user_id, true);
        }
        if (is_numeric($response) && $response != false)
            $response = true;
        echo json_encode(array("success" => $response, "user_swipes_per_day" => $user_swipes_per_day));
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
        if (!empty($near_by) && $near_by != null)
            $response = true;
        echo json_encode(array("success" => $response, "data" => $near_by['result']));
    }

}

?>