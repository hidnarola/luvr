<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->library(['unirest']);
		$this->load->model(['Users_model']);
	}

	public function index(){
		$this->load->view('register/register_view');
	}

	public function return_url(){

		$code = $this->input->get('code');

		if(!empty($code)){

			$insta_params = array('client_id' => INSTA_CLIENT_ID, 'client_secret' => INSTA_CLIENT_SECRET,'grant_type' => "authorization_code",
                			      'redirect_uri' => base_url().'register/return_url', 'code' => $code);
            $curl1 = "https://api.instagram.com/oauth/access_token";
			$response = $this->unirest->post($curl1, $headers = array(), $insta_params);
			$response_raw_body = $response->raw_body;
			$response_arr = json_decode($response_raw_body,true);

			if(!empty($response_arr['access_token'])){
				
				$curl2 = "https://api.instagram.com/v1/users/self/media/recent?access_token=" . $response_arr['access_token'];
				$response = $this->unirest->get($curl2, $headers = array());
				$row_data = json_decode($response->raw_body,true);
				
				if(!empty($row_data['data'][0]['user'])){
					$insta_id = $row_data['data'][0]['user']['id'];
					$insta_username = $row_data['data'][0]['user']['username'];
					$insta_full_name = $row_data['data'][0]['user']['full_name'];
					$insta_profile = $row_data['data'][0]['user']['profile_picture'];
				}

				$u_data = $this->Users_model->fetch_userdata(['userid'=>$instagram_id],true);
				
				pr($row_data,1);
				if(!empty($u_data)){

				}else{
					$ins_data = ['userid'];

				}

			}

		}
	}

}

/* End of file Register.php */
/* Location: ./application/controllers/Register.php */