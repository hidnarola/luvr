<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Register extends CI_Controller {

	public function __construct(){
		parent::__construct();
		$this->load->library(['unirest']);
		$this->load->model(['Users_model','Bio_model']);
	}

	public function index(){
		$data['sub_view'] = 'register/register_view';
    	$data['meta_title'] = "Setup User Profile";
    	$this->load->view('main', $data);
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
				
				$curl2 = "https://api.instagram.com/v1/users/self?access_token=" . $response_arr['access_token'];
				$response = $this->unirest->get($curl2, $headers = array());
				$row_data = json_decode($response->raw_body,true);

				if(!empty($row_data['data'])){
					$insta_id = $row_data['data']['id'];
					$insta_username = $row_data['data']['username'];					
					$insta_full_name = $row_data['data']['full_name'];
					$insta_profile = $row_data['data']['profile_picture'];
				}

				$u_data = $this->Users_model->fetch_userdata(['userid'=>$insta_id],true);

				if(!empty($u_data)){
					$u_data['access_token'] = $response_arr['access_token'];
					$this->session->set_userdata('user',$u_data);
					$upd_data = ['lastseen_date'=>date('Y-m-d H:i:s'),'id'=>$u_data['id']];				
					$this->Users_model->manageUser($upd_data);
					redirect('user/setup_userprofile');
				}else{

					$media_data = array(
										'userid'=>'0',
										'media_id'=>'',
										'media_name'=>$insta_profile,
										'media_thumb'=>'',
										'media_type'=>'3',
										'created_date'=>date('Y-m-d H:i:s'),
										'is_bios'=>'0',
										'is_active'=>'1'
									);

					$last_media_id = $this->Bio_model->insert_media($media_data);

					$ins_data = array(
										'userid'=>$insta_id,
										'instagram_username'=>$insta_username,
										'full_name'=>$insta_full_name,
										'profile_media_id'=>$last_media_id,
										'created_date'=>date('Y-m-d H:i:s'),
										'gender'=>'male',
										'lastseen_date'=>date('Y-m-d H:i:s')
									);
					
					$last_id = $this->Users_model->insert_record($ins_data);
					$ins_data['id'] = $last_id;
					$ins_data['access_token'] = $response_arr['access_token'];

					$this->session->set_userdata('user',$ins_data);
					redirect('user/setup_userprofile');
				}
			} // END of IF condition for ACCESS TOKEN
		}
	}

	public function logout(){
		$this->session->unset_userdata('user');
		redirect('register');
	}

}

/* End of file Register.php */
/* Location: ./application/controllers/Register.php */