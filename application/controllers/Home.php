<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Home extends CI_Controller {

    public function __construct() {

        parent::__construct();
        $this->load->model(array('Users_model', 'Videos_model', 'Matches_model'));
        /* $u_data = $this->session->userdata('user');
          if (empty($u_data)) {
          redirect('register');
          } */
        $this->load->library(['unirest', 'facebook']);
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

    public function adcashtest($id = null, $param2 = null) {
        $data['single_video'] = false;
        if (isset($_GET['s']) && !empty($_GET['s'])) {
            $playlist = array();
            $j = 1;
            for ($i = 0; $i < 100; $i++) {
                $playlist[$i]['file'] = ASSETS_URL . '/Videos/' . $j . '.mp4';
                $playlist[$i]['image'] = ASSETS_URL . '/Videos/Thumbs/' . $j . '.jpg';
                $j++;
            }
            $data['playlist'] = $playlist;
        } else {
            if (is_numeric($id)) {
                if ($param2 == 2) {
                    $this->db->select('*');
                    $this->db->where('userid', $id);
                    $this->db->where_in('media_type', array(4));
                    $user_media = $this->db->get('media')->result_array();
                    if (!empty($user_media)) {
                        $i = 0;
                        $playlist = array();
                        foreach ($user_media as $value) {
                            $playlist[$i]['file'] = $value['media_name'];
                            $playlist[$i]['image'] = $value['media_thumb'];
                            $i++;
                        }
                        $data['playlist'] = $playlist;
                    } else {
                        $random_video = $this->Videos_model->getRandomVideo();
                        $data['playlist'][0]['file'] = $random_video['media_name'];
                    }
                } else if ($param2 == 3) {
                    $u_data = $this->session->userdata('user');
                    $s_user_id = $u_data['id'];
                    $sql = "
                            SELECT * 
                                FROM messages
                            WHERE
                                ((sender_id = $s_user_id AND receiver_id = $id) OR (receiver_id = $s_user_id AND sender_id = $id))
                            AND 
                                message_type = 3 
                            AND 
                                is_delete = 0 
                            AND 
                                media_name IS NOT NULL 
                            AND 
                                media_name != ''
                            ORDER BY id DESC";
                    $q = $this->db->query($sql);
                    $user_media = $q->result_array();
                    if (!empty($user_media)) {
                        $i = 0;
                        $playlist = array();
                        foreach ($user_media as $value) {
                            $playlist[$i]['file'] = base_url() . "video/show_video/" . $value['media_name'];
                            $thumb_name = str_replace(".mp4", ".png", $value['media_name']);
                            $playlist[$i]['image'] = base_url() . "bio/show_img/" . $thumb_name . "/1";
                            $i++;
                        }
                        $data['playlist'] = $playlist;
                    } else {
                        echo "We could not find any video(s) associated with the requested conversation!";
                        die;
                    }
                } else if ($param2 != 2 && !is_numeric($param2) && !empty($param2)) {
                    $this->db->select('*');
                    $this->db->where('userid', $id);
                    $this->db->where_in('media_type', array(4));
                    $user_media = $this->db->get('media')->row_array();
                    if (!empty($user_media)) {
                        if ($user_media['media_type'] == 2) {
                            $data['playlist'][0]['file'] = base_url() . "video/show_video/" . $user_media['media_name'];
                        } else if ($user_media['media_type'] == 4) {
                            $data['playlist'][0]['file'] = $user_media['media_name'];
                        } else
                            show_404();
                    }else {
                        show_404();
                    }
                } else {
                    $user_media = $this->Users_model->getUserMediaByCol('id', $id);
                    $data['single_video'] = true;
                    if (!empty($user_media)) {
                        if ($user_media['media_type'] == 2) {
                            $data['playlist'][0]['file'] = base_url() . "video/show_video/" . $user_media['media_name'];
                        } else if ($user_media['media_type'] == 4) {
                            $data['playlist'][0]['file'] = $user_media['media_name'];
                        } else
                            show_404();
                    }else {
                        show_404();
                    }
                }
            } else {
                if (!empty($_GET['url']) && isset($_GET['url'])) {
                    $data['playlist'][0]['file'] = urldecode($_GET['url']);
                } else {
                    $random_video = $this->Videos_model->getRandomVideo();
                    $data['playlist'][0]['file'] = $random_video['media_name'];
                }
            }
        }
        if (detect_browser() == 'mobile') {
            $mob_user_agent = $_SERVER['HTTP_USER_AGENT'];
            $response = $this->Videos_model->manageUserAgent();
        } else {
            /* $ua_data = $this->Videos_model->getRandomUserAgent();
              $mob_user_agent = $ua_data['user_agent']; */
        }
        if ($this->input->get('d') == 1) {
            pr(urlencode($mob_user_agent));
            pr($_SERVER['HTTP_USER_AGENT']);
        }
        if ($_SERVER['HTTP_HOST'] == 'luvr.me') {
            $data['ad_url'] = "" . $_SERVER['REQUEST_SCHEME'] . "://go.aniview.com/api/adserver6/vast/?AV_PUBLISHERID=59394e4828a06156ac564965&AV_CHANNELID=59395c9728a06118183e72cf&cb=" . time() . "&AV_WIDTH=1024&AV_HEIGHT=768";
        }
        $data['sub_view'] = 'video/adcash';
        $data['show_header_footer'] = 1;
        /* $next_random = $this->Videos_model->getRandomVideoOwner($id);
          $data['next_random'] = $next_random['userid']; */
        if ($param2 == 0 && $param2 != null)
            $data['show_header_footer'] = 0;
        if (isset($_GET['hf'])) {
            if ($_GET['hf'] == 0)
                $data['show_header_footer'] = 0;
        }
        $data['meta_title'] = "Luvr";
        $data['is_video'] = true;
        $this->load->view('main', $data);
    }

    public function aniplayer() {
        $data['meta_title'] = "Aniplayer";
        $data['sub_view'] = 'video/aniplayer';
        $this->load->view('main', $data);
    }

    public function drluvr($video = null) {
        $data['meta_title'] = "Dr. Luvr";
        if (!empty($video) && $video != null)
            $data['sub_view'] = 'video/video';
        else
            $data['sub_view'] = 'drluvr';
        $data['video'] = (!empty($video) && $video != null) ? $video : null;
        /* $data['playlist'] = (!empty($video) && $video != null) ? array("file" => S3_URL . "/Videos/Dating/$video.mp4", "image" => S3_URL . "/Videos/Dating/thumbs/$video.jpg") : null; */
        if (!empty($video) && $video != null) {
            $indexes = array(1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12);
            $vid_index = str_replace("video", "", $video);
            if (($key = array_search($vid_index, $indexes)) !== false) {
                unset($indexes[$key]);
            }
            array_unshift($indexes, $vid_index);
            $j = 0;
            foreach ($indexes as $idx) {
                $data['playlist'][$j] = array("file" => ASSETS_URL . "/Videos/Dating/video$idx.mp4", "image" => ASSETS_URL . "/Videos/Dating/thumbs/video$idx.jpg");
                $j++;
            }
        } else {
            $data['playlist'] = null;
        }
        $this->load->view('main', $data);
    }

    function adcashtestwithjwplayer() {
        $data['meta_title'] = "Adcash Test";
        $data['sub_view'] = 'ads/adcash';
        $this->load->view('main', $data);
    }

    function speed($gender = null) {
        $u_data = $this->session->userdata('user');
        if ($u_data) {
            $user_id = $u_data['id'];
            $user_info = $this->Users_model->getUserByCol('id', $user_id);
            if ($user_info['location_id'] > 0) {
                $rs = $this->db->get_where('location', array('id' => $user_info['location_id']))->row_array();
                $u_data['latlong'] = $rs['latlong'];
            } else {
                $u_data['latlong'] = $user_info['latlong'];
            }
            $u_data['radius'] = $user_info['radius'];
            $allusers = $this->Users_model->getRandomUsers($gender, $user_id);
            $data['randomUsers'] = $allusers;
            $data['latlong'] = $u_data['latlong'];
            $data['radius'] = $user_info['radius'];
            $data['meta_title'] = "Luvr Lightning Round";
            if ($gender == "a" || $gender == "m" || $gender == "f") {
                $data['sub_view'] = 'speeddating/speeddating';
            } else {
                $data['sub_view'] = 'speeddating/preference';
            }
            $data['pref'] = $gender;
            $this->load->view('main', $data);
        } else {
            redirect('register');
        }
    }

}

/* End of file Home.php */
/* Location: ./application/controllers/Home.php */