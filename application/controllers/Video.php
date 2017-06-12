<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Video extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->library('unirest');
        $this->load->model(array('Users_model', 'Filters_model', 'Bio_model', 'Videos_model'));
    }

    public function index() {
        
    }

    public function show_video($file) {
        $path = UPLOADPATH_VIDEO . '/' . $file;
        header("Content-Type: video/mp4");
        header("Content-Length: " . filesize($path));
        readfile($path);
    }

    public function play($id = null, $param2 = null) {
        /* if (empty($id)) {
          show_404();
          } */
        /* set_time_limit(3600);
          for ($i = 1; $i <= 100; $i++) {
          $full_path = "C:/wamp/www/Luvr/assets/uploads/Video/Sample/$i.mp4";
          $dest_path = "C:/wamp/www/Luvr/assets/uploads/Video/processed/$i.mp4";
          $thumb_path = "C:/wamp/www/Luvr/assets/uploads/Video/processed/thumbs/$i.jpg";
          exec(FFMPEG_PATH . " -i $full_path -vf delogo=x=160:y=150:w=320:h=60 -c:a copy $dest_path");
          exec(FFMPEG_PATH . ' -i ' . $dest_path . ' -ss 00:00:01.000 -vframes 1 ' . $thumb_path);
          exec(FFMPEG_PATH . ' -i new.mp4 -c:v libx264 -crf 24 -b:v 1M -c:a aac new1.mp4');
          }
          die; */
        $data['single_video'] = false;
        if (isset($_GET['s']) && !empty($_GET['s'])) {
            $playlist = array();
            $j = 1;
            for ($i = 0; $i < 100; $i++) {
                $playlist[$i]['file'] = '' . $_SERVER['REQUEST_SCHEME'] . '://s3.ap-south-1.amazonaws.com/luvr/Videos/' . $j . '.mp4';
                $playlist[$i]['image'] = '' . $_SERVER['REQUEST_SCHEME'] . '://s3.ap-south-1.amazonaws.com/luvr/Videos/Thumbs/' . $j . '.jpg';
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
        if ($_SERVER['HTTP_HOST'] == 'dev.luvr.me') {
            $ads = array(
                "http://ads.nexage.com/adServe?dcn=2c9d2b4f015b5b87d1dea3a7a1ae016f&pos=interstitial&ua=" . urlencode($_SERVER['HTTP_USER_AGENT']) . "&ip=" . $_SERVER['REMOTE_ADDR'] . "&u(id)=" . uniqid() . "&req(url)=" . base_url(uri_string()) . "", //AOL1
            );
            $data['ad_url'] = $ads[array_rand($ads)];
            if (!empty($_GET['p'])) {
                if ($_GET['p'] == "ao") {
                    $data['ad_url'] = $ads[0];
                }
            }
        } else if ($_SERVER['HTTP_HOST'] == 'luvr.me') {
            $ads = array(
                "https://vast.optimatic.com/vast/getVast.aspx?id=tI8OelBpLoQd&o=3&zone=default&pageURL=" . base_url(uri_string()) . "&pageTitle=BioVideo&cb=" . uniqid() . "", // US traffic
                "https://vast.optimatic.com/vast/getVast.aspx?id=TKEjzowTy68F&o=3&zone=default&pageURL=" . base_url(uri_string()) . "&pageTitle=BioVideo&cb=" . uniqid() . "", // UK traffic
                "http://ssp.streamrail.net/ssp/vpaid/5715f70d2ed89a0002000242/5924041c932f1a00024b75b9?cb=" . uniqid() . "&width=1024&height=768&ip=" . $_SERVER['REMOTE_ADDR'] . "&ua=" . urlencode($_SERVER['HTTP_USER_AGENT']) . "&page_url=" . base_url(uri_string()) . "",
                "" . $_SERVER['REQUEST_SCHEME'] . "://www.objectdisplay.com/a/display.php?r=1593023&acp=pre&acw=1024&ach=768&vast=3"
            );
            $data['ad_url'] = $ads[array_rand($ads)];
            if (!empty($_GET['p'])) {
                if ($_GET['p'] == "op") {
                    $data['ad_url'] = $ads[0];
                } else if ($_GET['p'] == "opk") {
                    $data['ad_url'] = $ads[1];
                } else if ($_GET['p'] == "ha") {
                    $data['ad_url'] = $ads[0];
                } else if ($_GET['p'] == "ac") {
                    $data['ad_url'] = $ads[0];
                }
            }
        }
        if (isset($data['ad_url']) && !empty($data['ad_url'])) {
            if ($_SERVER['REQUEST_SCHEME'] == "https") {
                if (strpos($data['ad_url'], 'streamrail') !== false) {
                    redirect(str_replace('https', 'http', _current_url()));
                }
            }
        }
        $data['sub_view'] = 'bio/video';
        $data['show_header_footer'] = 1;
        $next_random = $this->Videos_model->getRandomVideoOwner($id);
        $data['next_random'] = $next_random['userid'];
        if ($param2 == 0 && $param2 != null)
            $data['show_header_footer'] = 0;
        if (isset($_GET['hf'])) {
            if ($_GET['hf'] == 0)
                $data['show_header_footer'] = 0;
        }
        $data['meta_title'] = "Play Video";
        $data['is_video'] = true;
        $this->load->view('main', $data);
    }

    function testSmaato() {
        require APPPATH . 'third_party/SmaatoSnippet.php';
        $snippet = new SmaatoSnippet();
        try {
            if ($_SERVER['HTTP_HOST'] == 'dev.luvr.me') {
                $snippet->setPublisherId(1100031417)
                        ->setAdspaceId(130268026)
                        ->setDimension("full_1024x768")
                        ->setResponseFormat("html");
            } else if ($_SERVER['HTTP_HOST'] == 'luvr.me') {
                $snippet->setPublisherId(1100031417)
                        ->setAdspaceId(130269290)
                        ->setDimension("full_1024x768")
                        ->setResponseFormat("html");
            }
            $snippet->requestAd();
            if ($snippet->isAdAvailable()) {
                $banner = $snippet->getAd();
            } else {
                echo "Currently no ad is available.";
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
    }

    function aol1() {
        $this->load->view('ads/aol1');
    }

    function adcash() {
        $this->load->view('ads/adcash');
    }

    function checkad() {
        $data['sub_view'] = 'ads/testad';
        $data['meta_title'] = "Ad Checkup";
        $this->load->view('main', $data);
    }

    function testproxy() {
        $proxies = array(); // Declaring an array to store the proxy list
        /* Adding list of proxies to the $proxies array */
        /* $proxies[] = 'user:password@173.234.11.134:54253';  // Some proxies require user, password, IP and port number
          $proxies[] = 'user:password@173.234.120.69:54253';
          $proxies[] = 'user:password@173.234.46.176:54253'; */
        $proxies[] = '104.198.63.245:8888';  // Some proxies only require IP
        $proxies[] = '93.188.164.193:3128';
        $proxies[] = '64.140.159.209:80'; // Some proxies require IP and port number
        $proxies[] = '54.219.138.73:8083';
        $proxies[] = '173.75.39.21:3128';


        if (isset($proxies)) {  // If the $proxies array contains items, then
            $proxy = $proxies[array_rand($proxies)];    // Select a random proxy from the array and assign to $proxy variable
        }
        pr($proxy);
        pr(_current_url());
        
        $ch = curl_init();  // Initialise a cURL handle
        /* Setting proxy option for cURL */
        if (isset($proxy)) {    // If the $proxy variable is set, then
            curl_setopt($ch, CURLOPT_PROXY, $proxy);    // Set CURLOPT_PROXY with proxy in $proxy variable
        }
        
        /* Set any other cURL options that are required */
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);
        curl_setopt($ch, CURLOPT_COOKIESESSION, TRUE);
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_URL, _current_url());

        $results = curl_exec($ch);  // Execute a cURL request
        curl_close($ch);

        $data['sub_view'] = 'ads/testproxy';
        $data['meta_title'] = "Test";
        $this->load->view('main', $data);
    }

}
