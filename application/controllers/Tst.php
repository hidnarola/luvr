<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Tst extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model(array('Users_model', 'Filters_model', 'Bio_model', 'Videos_model'));
    }

    public function index() {
        
    }

    public function test() {
        pr($_SERVER);
        echo getUserIP();
    }

}

?>