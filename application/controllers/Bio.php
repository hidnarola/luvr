<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bio extends CI_Controller {

	public function __construct(){
		parent::__construct();
		
		$this->load->model(array('Users_model', 'Filters_model'));
        $this->load->library('unirest');
        $u_data = $this->session->userdata('user');

        if(empty($u_data)){
            redirect('register');
        }
	}
	public function index(){
		
	}	

    public function save_bio(){
        
        $u_data = $this->session->userdata('user');
        $insta_id = $u_data['userid'];
        $access_token = $u_data['access_token'];

        $curl1 = "https://api.instagram.com/v1/users/".$insta_id."/media/recent/?access_token=" . $access_token;
        $response = $this->unirest->get($curl1, $headers = array());
        $row_data = json_decode($response->raw_body,true);

        $user_info = $this->Users_model->getUserByCol('id', $u_data['id']);

        $data['sub_view'] = 'bio/index';
        $data['meta_title'] = "Save instagram bio";
        $data['userData'] = $user_info;
        $data['all_images'] = $row_data['data'];
        $data['next_link'] = $row_data['pagination']['next_url'];
        $this->load->view('main', $data);

        // pr($u_data,1);
    }

    public function test_fetch(){
        // $curl1 = 'https://api.instagram.com/v1/users/145281153/media/recent?access_token=145281153.17fd6de.dda034d5718c4ea19a1ec798be53dc9f&max_id=1068802229350304458_145281153';
        // $curl1 = 'https://api.instagram.com/v1/users/145281153/media/recent?access_token=145281153.17fd6de.dda034d5718c4ea19a1ec798be53dc9f&max_id=988968134181522074_145281153';
        $curl1 = 'https://api.instagram.com/v1/users/145281153/media/recent?access_token=145281153.17fd6de.dda034d5718c4ea19a1ec798be53dc9f&max_id=685858156971165999_145281153';

        $response = $this->unirest->get($curl1, $headers = array());
        $row_data = json_decode($response->raw_body,true);

        if(!empty($row_data['data'])){
        	foreach($row_data['data'] as $image_data){
        		echo "<img src='".$image_data["images"]["thumbnail"]["url"]."' >";
        	}
        }

        pr($row_data);
    }

    public function fetch_insta_bio(){

        $next_url = $this->input->post('next_url');
        $curl1 = $next_url;
        // die;
        $response = $this->unirest->get($curl1, $headers = array());
        $row_data = json_decode($response->raw_body,true);
        
        $all_images = $row_data['data'];
        $next_link = $row_data['pagination']['next_url'];

        $new_str = '';
        
        if(!empty($all_images)){
            foreach($all_images as $image){
                $new_str .= '<div class="col-sm-3">';
                $new_str .= '<img src="'.$image['images']['standard_resolution']['url'].'" class="img-responsive" style="width:100%" alt="Image">';
                $new_str .= '<a href="'.$image['link'].'" class="btn btn-primary"> Insta Link </a></div>';
            }
        }
        
        $ret_data['all_images'] = $new_str;
        $ret_data['next_link'] = $next_link;
        $ret_data['return_data'] = $row_data;
        $ret_data['curl1'] = $curl1;

        echo json_encode($ret_data);
    }


    public function ajax_save_bio(){

    	$img_name = $this->input->post('img_name');
    	$res_data = $this->db->get_where('media',['media_name'=>$img_name])->row_array();

    	// if update then else Insert
    	if(!empty($res_data)){

    	}else{
    		
    	}
    }
    

}

/* End of file Bio.php */
/* Location: ./application/controllers/Bio.php */