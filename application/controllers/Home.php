<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->model(array('Users_model'));
        /* $u_data = $this->session->userdata('user');
          if (empty($u_data)) {
          redirect('register');
          } */
        $this->load->library('unirest');
    }

    public function index() {
        $u_data = $this->session->userdata('user');
        $data['sub_view'] = 'Homepage';
        $data['header'] = 'home_header';
        $data['meta_title'] = "Welcome to Luvr";
        $data['is_user_premium_member'] = 0;
        if (!empty($u_data)) {
            $user_id = $u_data['id'];
            $user_settings = $this->Users_model->getUserSetings('userid', $user_id);
            if ((strtotime($user_settings['premium_expiry_date']) > strtotime(date("Y-m-d H:i:s", time()))) && $user_settings['is_premium_member'] == 1) {
                $data['is_user_premium_member'] = 1;
            }
        }
        $this->load->view('main', $data);
    }

}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */
