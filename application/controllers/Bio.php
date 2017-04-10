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
        $all_saved_media = $this->db->select('media_id')->get_where('media',['userid'=>$u_data['id']])->result_array();
        $data['all_saved_media'] = array_column($all_saved_media,'media_id');        

        $data['sub_view'] = 'bio/index';
        $data['meta_title'] = "Save instagram bio";
        $data['userData'] = $user_info;
        $data['all_images'] = $row_data['data'];
        $data['next_link'] = $row_data['pagination']['next_url'];
        $this->load->view('main', $data);

        // pr($u_data,1);
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
        $next_link = $row_data['pagination']['next_url'];

        $new_str = '';
        
        if(!empty($all_images)){
            foreach($all_images as $image){

                $type = $image['type'];
                $thumb = $image['images']['thumbnail']['url'];
                
                $is_delete = 'no';
                $save_icon = 'ok';
                $save_link_class = 'success';
                if(!empty($all_saved_media) && in_array($image['id'], $all_saved_media) == true){
                    $is_delete = 'yes';
                    $save_link_class = 'danger';
                    $save_icon = 'remove';
                }

                if($type == 'image'){
                    $link = $image['images']['standard_resolution']['url'];
                }else{
                    $link = $image['videos']['standard_resolution']['url'];
                }

                $new_str .= '<div class="col-sm-3" style="margin-bottom:10px;">';
                $new_str .= '<img src="'.$image['images']['standard_resolution']['url'].'" class="img-responsive" style="width:100%" alt="Image">';
                
                $new_str .= ' <a style="margin-top:10px" href="'.$image['link'].'" target="_blank" class="btn btn-primary"> <span class="glyphicon glyphicon-link"></span> </a>';

                $new_str .= ' <a style="margin-top:10px"  data-type="'.$type.'" data-val="'.$link.'" class="btn btn-'.$save_link_class.'" ';
                $new_str .= ' onclick="ajax_save_bio(this)" data-thumb="'.$thumb.'" data-is-delete="'.$is_delete.'" ';
                $new_str .= ' data-insta-id="'.$image['id'].'" data-insta-time="'.$image['created_time'].'"> <span class="glyphicon glyphicon-'.$save_icon.'"></span> </a>';

                $new_str .= ' <a style="margin-top:10px"  data-type="'.$type.'" data-val="'.$link.'" class="btn btn-warning"> <span class="glyphicon glyphicon-picture"></span> </a></div>';

            }
        }
        
        $ret_data['all_images'] = $new_str;
        $ret_data['next_link'] = $next_link;
        $ret_data['return_data'] = $row_data;
        $ret_data['curl1'] = $curl1;

        echo json_encode($ret_data);
    }

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
            $ret['query'] = 'delete';
            $this->db->delete('media',['media_id'=>$insta_id]);
        }else{
            $ret['query'] = 'insert';
    	    $this->db->insert('media',$ins_data);
        }


        echo json_encode($ret);
    }
    
    public function ajax_picture_set_profile(){
        $img_name = $this->input->post('img_name');        
        $u_data = $this->session->userdata('user');

    }

    // ------------------------------------------------------------------------
}

/* End of file Bio.php */
/* Location: ./application/controllers/Bio.php */