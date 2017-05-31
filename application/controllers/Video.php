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
        if (is_numeric($id)) {
            if ($param2 == 2) {
                $this->db->select('*');
                $this->db->where('userid', $id);
                $this->db->where_in('media_type', array(2, 4));
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
            } else {
                $user_media = $this->Users_model->getUserMediaByCol('id', $id);
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
                "" . $_SERVER['REQUEST_SCHEME'] . "://ssp.streamrail.net/ssp/vpaid/5715f70d2ed89a0002000242/5924041c932f1a00024b75b9?cb=" . uniqid() . "&width=1024&height=768&ip=" . $_SERVER['REMOTE_ADDR'] . "&ua=" . urlencode($_SERVER['HTTP_USER_AGENT']) . "&page_url=" . base_url(uri_string()) . "",
                "" . $_SERVER['REQUEST_SCHEME'] . "://www.objectdisplay.com/a/display.php?r=1593023&acp=pre&acw=1024&ach=768&vast=3"
            );
            $data['ad_url'] = $ads[array_rand($ads)];
            if (!empty($_GET['p'])) {
                if ($_GET['p'] == "op") {
                    $data['ad_url'] = $ads[0];
                } else if ($_GET['p'] == "opk") {
                    $data['ad_url'] = $ads[1];
                } else if ($_GET['p'] == "ha") {
                    $data['ad_url'] = $ads[2];
                } else if ($_GET['p'] == "ac") {
                    $data['ad_url'] = $ads[3];
                }
            }
        }
        /*if (isset($data['ad_url']) && !empty($data['ad_url'])) {
            if (strpos($data['ad_url'], 'optimatic') !== false) {
                redirect(str_replace('https', 'http', base_url(uri_string())));
            }
        }*/
        $data['sub_view'] = 'bio/video';
        $data['show_header_footer'] = 1;
        $next_random = $this->Videos_model->getRandomVideoOwner($id);
        $data['next_random'] = $next_random['userid'];
        if ($param2 == 0 && $param2 != null)
            $data['show_header_footer'] = 0;
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

}
