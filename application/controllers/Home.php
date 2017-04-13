<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {

        parent::__construct();
        /* $this->load->model(array('Users_model', 'Filters_model'));
          $u_data = $this->session->userdata('user');
          if (empty($u_data)) {
          redirect('register');
          } */
        $this->load->library('minify');
    }

    public function index() {
        $data['sub_view'] = 'Homepage';
        $data['header'] = 'home_header';
        $data['meta_title'] = "Welcome to Luvr";
        $this->load->view('main', $data);
    }

}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */
