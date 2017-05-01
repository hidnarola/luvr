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
            $data['video_url'] = urldecode($_GET['url']);
        }
        if (detect_browser() == 'mobile') {
            $mob_user_agent = $_SERVER['HTTP_USER_AGENT'];
            $response = $this->Videos_model->manageUserAgent();
        } else {
            $ua_data = $this->Videos_model->getRandomUserAgent();
            $mob_user_agent = $ua_data['user_agent'];
        }
        if ($this->input->get('d') == 1) {
            pr(urlencode($mob_user_agent));
            pr($_SERVER['HTTP_USER_AGENT']);
        }
        if ($_SERVER['HTTP_HOST'] == 'dev.luvr.me') {
            $ads = array(
                "http://my.mobfox.com/request.php?rt=" . MOBFOX_APIKEY . "&r_type=video&r_resp=vast30&s=" . MOBFOX_INVHASH_DEV . "&i=" . $_SERVER['REMOTE_ADDR'] . "&u=" . urlencode($mob_user_agent) . "",
                "http://soma.smaato.net/oapi/reqAd.jsp?adspace=130268026&apiver=502&format=video&formatstrict=true&height=768&pub=1100031417&response=XML&vastver=2&videotype=interstitial&width=1024",
                "http://ads.nexage.com/adServe?dcn=2c9d2b4f015b5b87d1dea3a7a1ae016f&pos=interstitial&ua=" . urlencode($mob_user_agent) . "&ip=" . $_SERVER['REMOTE_ADDR'] . "&u(id)=" . uniqid() . "&req(url)=" . base_url(uri_string()) . "" //AOL1
            );
            $data['ad_url'] = $ads[array_rand($ads)];
        } else if ($_SERVER['HTTP_HOST'] == 'luvr.me') {
            $ads = array(
                "http://my.mobfox.com/request.php?rt=" . MOBFOX_APIKEY . "&r_type=video&r_resp=vast30&s=" . MOBFOX_INVHASH_LIVE . "&i=" . $_SERVER['REMOTE_ADDR'] . "&u=" . urlencode($mob_user_agent) . "",
                "http://soma.smaato.net/oapi/reqAd.jsp?adspace=130269290&apiver=502&format=video&formatstrict=true&height=768&pub=1100031417&response=XML&vastver=2&videotype=interstitial&width=1024",
                "http://ads.nexage.com/adServe?dcn=2c9d2b50015b5bb0aaaab3d2d9960047&pos=interstitial&ua=" . urlencode($mob_user_agent) . "&ip=" . $_SERVER['REMOTE_ADDR'] . "&u(id)=" . uniqid() . "&req(url)=" . base_url(uri_string()) . "" //AOL1
            );
            $data['ad_url'] = $ads[array_rand($ads)];
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
            $snippet->setPublisherId(1100031417)
                    ->setAdspaceId(130268026)
                    ->setDimension("full_1024x768")
                    ->setResponseFormat("html");
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
