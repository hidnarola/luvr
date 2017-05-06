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

    public function play($id = null) {
        /* if (empty($id)) {
          show_404();
          } */
        if (is_numeric($id)) {
            $user_media = $this->Users_model->getUserMediaByCol('id', $id);
            if (!empty($user_media)) {
                if ($user_media['media_type'] == 2)
                    $data['video_url'] = base_url() . "video/show_video/" . $user_media['media_name'];
                else if ($user_media['media_type'] == 4)
                    $data['video_url'] = $user_media['media_name'];
                else
                    show_404();
            }else {
                show_404();
            }
        } else {
            if (!empty($_GET['url']) && isset($_GET['url']))
                $data['video_url'] = urldecode($_GET['url']);
            else {
                $random_video = $this->Videos_model->getRandomVideo();
                $data['video_url'] = $random_video['media_name'];
            }
        }
        if (detect_browser() == 'mobile') {
            $mob_user_agent = $_SERVER['HTTP_USER_AGENT'];
            $response = $this->Videos_model->manageUserAgent();
        } else {
            /*$ua_data = $this->Videos_model->getRandomUserAgent();
            $mob_user_agent = $ua_data['user_agent'];*/
        }
        if ($this->input->get('d') == 1) {
            pr(urlencode($mob_user_agent));
            pr($_SERVER['HTTP_USER_AGENT']);
        }
        if ($_SERVER['HTTP_HOST'] == 'dev.luvr.me') {
            $ads = array(
                "http://ads.nexage.com/adServe?dcn=2c9d2b4f015b5b87d1dea3a7a1ae016f&pos=interstitial&ua=" . urlencode($_SERVER['HTTP_USER_AGENT']) . "&ip=" . $_SERVER['REMOTE_ADDR'] . "&u(id)=" . uniqid() . "&req(url)=" . base_url(uri_string()) . "" //AOL1
            );
            $data['ad_url'] = $ads[array_rand($ads)];
            if (!empty($_GET['p'])) {
                if ($_GET['p'] == "ao") {
                    $data['ad_url'] = $ads[0];
                }
            }
        } else if ($_SERVER['HTTP_HOST'] == 'luvr.me') {
            $ads = array(
                "http://ads.nexage.com/adServe?dcn=2c9d2b50015b5bb0aaaab3d2d9960047&pos=interstitial&ua=" . urlencode($_SERVER['HTTP_USER_AGENT']) . "&ip=" . $_SERVER['REMOTE_ADDR'] . "&u(id)=" . uniqid() . "&req(url)=" . base_url(uri_string()) . "" //AOL1
            );
            $data['ad_url'] = $ads[array_rand($ads)];
            if (!empty($_GET['p'])) {
                if ($_GET['p'] == "ao") {
                    $data['ad_url'] = $ads[0];
                }
            }
        }
        $data['sub_view'] = 'bio/video';
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

}
