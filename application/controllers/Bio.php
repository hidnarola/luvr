<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Bio extends CI_Controller {

	public function __construct(){
		parent::__construct();
        
        $this->load->library('unirest');
		$this->load->model(array('Users_model', 'Filters_model','Bio_model'));
        $u_data = $this->session->userdata('user');

        if(empty($u_data)){
            redirect('register');
        }
	}

	public function index(){
		
	}	

    public function change_profile(){
        $data['sub_view'] = 'bio/change_profile';
        $data['meta_title'] = "Change Profile";
        $data['userData'] = $user_info;
        $data['all_images'] = $row_data['data'];

        $this->load->view('main', $data);
    }

    public function saved_feed(){
        $u_data = $this->session->userdata('user');

        $user_info = $this->Users_model->getUserByCol('id', $u_data['id']);
        $all_saved_media = $this->Bio_model->fetch_mediadata(['userid'=>$u_data['id'],'is_active'=>'1'],false,'id,media_id');
        $data['all_saved_media'] = array_column($all_saved_media,'media_id');

        $images = $this->Bio_model->fetch_mediadata('media');

        $data['sub_view'] = 'bio/saved_feed';
        $data['meta_title'] = "Save instagram bio";
        $data['userData'] = $user_info;
        $data['all_images'] = $row_data['data'];

        $this->load->view('main', $data);
    }

    public function instagram_feed(){
        
        $u_data = $this->session->userdata('user');
        $insta_id = $u_data['userid'];
        $access_token = $u_data['access_token'];

        $curl1 = "https://api.instagram.com/v1/users/".$insta_id."/media/recent/?access_token=" . $access_token.'&count=30';
        $response = $this->unirest->get($curl1, $headers = array());
        $row_data = json_decode($response->raw_body,true);

        // pr($row_data);
        $user_info = $this->Users_model->getUserByCol('id', $u_data['id']);            
        $all_saved_media = $this->Bio_model->fetch_mediadata(['userid'=>$u_data['id'],'is_active'=>'1'],false,'id,media_id');
        $data['all_saved_media'] = array_column($all_saved_media,'media_id');

        $next_link = '';
        if(isset($row_data['pagination']['next_url'])){
            $next_link = $row_data['pagination']['next_url'];
        }

        $data['sub_view'] = 'bio/index';
        $data['meta_title'] = "Save instagram bio";
        $data['userData'] = $user_info;
        $data['all_images'] = $row_data['data'];        
        $data['next_link'] = $next_link;
        $this->load->view('main', $data);        
    }

    public function fetch_insta_bio(){

        $next_url = $this->input->post('next_url');
        $all_saved_media = $this->input->post('all_saved_media');

        if(!empty($all_saved_media)){
            $all_saved_media = explode(',',$all_saved_media);
        }else{
            $all_saved_media = [];
        }
        $curl1 = $next_url;
        // die;
        $response = $this->unirest->get($curl1, $headers = array());
        $row_data = json_decode($response->raw_body,true);
        
        $all_images = $row_data['data'];
        $next_link = (!empty($row_data['pagination']['next_url'])) ? $row_data['pagination']['next_url']:'';

        $new_str = '';
        
        if(!empty($all_images)){
            foreach($all_images as $image){

                $type = $image['type'];
                $link = $image['images']['standard_resolution']['url'];
                $thumb = $image['images']['thumbnail']['url'];
                
                $is_delete = 'no';
                $save_icon = 'ok';
                $save_link_class = 'success';
                if(!empty($all_saved_media) && in_array($image['id'], $all_saved_media) == true){
                    $is_delete = 'yes';
                    $save_link_class = 'danger';
                    $save_icon = 'remove';
                }
                
                if($is_delete == 'no'){
                    $new_str .= '<div class="col-sm-3" style="margin-bottom:10px;">';
                    $new_str .= '<img src="'.$image['images']['standard_resolution']['url'].'" class="img-responsive" style="width:100%" alt="Image">';
                    
                    $new_str .= ' <a style="margin-top:10px" href="'.$image['link'].'" target="_blank" class="btn btn-primary"> <span class="glyphicon glyphicon-link"></span> </a>';

                    $new_str .= ' <a style="margin-top:10px"  data-type="'.$type.'" data-val="'.$link.'" class="btn btn-'.$save_link_class.'" ';
                    $new_str .= ' onclick="ajax_save_bio(this)" data-thumb="'.$thumb.'" data-is-delete="'.$is_delete.'" ';
                    $new_str .= ' data-insta-id="'.$image['id'].'" data-insta-time="'.$image['created_time'].'"> <span class="glyphicon glyphicon-'.$save_icon.'"></span> </a>';

                    $new_str .= ' <a style="margin-top:10px"  data-type="'.$type.'" data-val="'.$link.'" class="btn btn-warning"> <span class="glyphicon glyphicon-picture"></span> </a></div>';
                }
            }
        }

        $ret_data['all_images'] = $new_str;
        $ret_data['next_link'] = $next_link;
        $ret_data['return_data'] = $row_data;
        $ret_data['curl1'] = $curl1;

        echo json_encode($ret_data);
    }

    // ------------------------------------------------------------------------
    // Save Insta Feed into BIO MAximum upto 50 only
    // ------------------------------------------------------------------------

    public function ajax_save_bio(){

        $u_data = $this->session->userdata('user');
        $user_id = $u_data['id'];

    	$img_name = $this->input->post('img_name');
        $type = $this->input->post('type');
        $thumb = $this->input->post('thumb');
        $media_type = ($type == 'image') ? '3': '4';
        $insta_id = $this->input->post('insta_id');
        $is_delete = $this->input->post('is_delete');

        $insta_time = date('Y-m-d H:i:s',$this->input->post('insta_time'));

        $ret['total_feeds_cnt'] = $this->Bio_model->fetch_total_feed_cnt();

        if($ret['total_feeds_cnt'] == 50){
            echo json_encode(['status'=>'error','query'=>'Limit on total feed validation']); // User can't save more than 50 images
            die;
        }

        $ins_data = array(
                        'userid'=>$user_id,
                        'media_id'=>$insta_id,
                        'media_name'=>$img_name,
                        'media_thumb'=>$thumb,
                        'media_type'=>$media_type,
                        'insta_datetime'=>$insta_time,
                        'created_date'=>date('Y-m-d H:i:s'),
                        'is_bios'=>'1'
                    );

        if($is_delete == 'yes'){
            $ret['query'] = 'update_to_0';
            $this->Bio_model->update_media(['media_id'=>$insta_id], ['is_active'=>'0']);
        }else{

            $media_data = $this->Bio_model->fetch_mediadata(['media_id'=>$insta_id],true);

            if(!empty($media_data)){
                $ret['query'] = 'update_to_1';
                $this->Bio_model->update_media(['media_id'=>$insta_id], ['is_active'=>'1']);
            }else{                
                $ret['query'] = 'insert';
        	    $this->db->insert('media',$ins_data);
            }
        }
        $ret['status'] = 'success';
        echo json_encode($ret);
    }
    
    // ------------------------------------------------------------------------
    // Save insta feed picture as profile picture
    // ------------------------------------------------------------------------

    public function ajax_picture_set_profile(){
        
        $u_data = $this->session->userdata('user');
        $profile_media_id = $u_data['profile_media_id'];
        $access_token = $u_data['access_token'];
        
        $img_name = $this->input->post('img_name');
        $type = $this->input->post('type');
        $thumb = $this->input->post('thumb');
        $media_type = ($type == 'image') ? '3': '4';
        $insta_id = $this->input->post('insta_id');
        $is_delete = $this->input->post('is_delete');
        $insta_time = date('Y-m-d H:i:s',$this->input->post('insta_time'));

        // Step-1) Set existing profile image to is_active -> 0
        // Step-2) Insert new data into media with userid - 0 which indiacte it's for profile
        // Step-3) Update last media id into users table

        $ins_data = array();
        $this->Bio_model->update_media(['id'=>$profile_media_id],['is_active'=>'0']); // Step-1)

        $ins_data = array(
                            'userid'=>'0',
                            'media_id'=>$insta_id,
                            'media_name'=>$img_name,
                            'media_thumb'=>$thumb,
                            'media_type'=>$media_type,
                            'insta_datetime'=>$insta_time,
                            'created_date'=>date('Y-m-d H:i:s')
                        );

        $last_id = $this->Bio_model->insert_media($ins_data); // Step-2)

        $this->Users_model->update_record(['id'=>$u_data['id']],['profile_media_id'=>$last_id]); // Step-3)
        
        $latest_u_data = $this->Users_model->fetch_userdata(['id'=>$u_data['id']],true);
        $latest_u_data['access_token'] = $access_token;
        $this->session->set_userdata( 'user', $latest_u_data); 
        
        echo json_encode(['success'=>'success']);
    }

    // ------------------------------------------------------------------------
}

/* End of file Bio.php */
/* Location: ./application/controllers/Bio.php */