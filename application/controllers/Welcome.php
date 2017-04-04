<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

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
        $this->load->model(array('Users_model'));
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
            $this->load->view('main');
        }
    }

    public function setup_userprofile() {
        echo "in";
        die;
        $user_info = $this->Users_model->getUserByCol('userid', '1572217132');
        $data['sub_view'] = 'userProfileSettings';
        $data['meta_title'] = "Setup User Profile";
        $this->load->view('main', $data);
    }

    function test() {
        echo "here";
    }

}
