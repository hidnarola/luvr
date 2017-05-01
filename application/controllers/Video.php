<?php

defined('BASEPATH') OR exit('No direct script access allowed');

class Video extends CI_Controller {

    public function __construct() {
        parent::__construct();

        $this->load->library('unirest');
        $this->load->model(array('Users_model', 'Filters_model', 'Bio_model'));
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
        $mobile_user_agents = array(
            'Mozilla/5.0 (Linux; Android 6.0.1; SM-G920V Build/MMB29K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.98 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 5.1.1; SM-G928X Build/LMY47X) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.83 Mobile Safari/537.36',
            'Mozilla/5.0 (Windows Phone 10.0; Android 4.2.1; Microsoft; Lumia 950) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2486.0 Mobile Safari/537.36 Edge/13.10586',
            'Mozilla/5.0 (Linux; Android 6.0.1; Nexus 6P Build/MMB29P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.83 Mobile Safari/537.36',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 10_3 like Mac OS X) AppleWebKit/602.1.50 (KHTML, like Gecko) CriOS/56.0.2924.75 Mobile/14E5239e Safari/602.1',
            'Mozilla/5.0 (iPhone; CPU iPhone OS 10_3 like Mac OS X) AppleWebKit/603.1.23 (KHTML, like Gecko) Version/10.0 Mobile/14E5239e Safari/602.1',
            'Mozilla/5.0 (Linux; U; Android 4.1.1; en-gb; Build/KLP) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Safari/534.30',
            'Mozilla/5.0 (Linux; Android 4.4; Nexus 5 Build/_BuildID_) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/30.0.0.0 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 5.1.1; Nexus 5 Build/LMY48B; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/43.0.2357.65 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 4.0.4; Galaxy Nexus Build/IMM76B) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.133 Mobile Safari/535.19',
            'Mozilla/5.0 (Linux; Android 5.0.2; SAMSUNG SM-A500FU Build/LRX22G) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/3.3 Chrome/38.0.2125.102 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 6.0.1; SAMSUNG SM-G930T1 Build/MMB29M) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/4.0 Chrome/44.0.2403.133 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 5.1.1; SAMSUNG SM-A9000 Build/LMY47V) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/4.0 Chrome/44.0.2403.133 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 5.0; SAMSUNG SM-N900V 4G Build/LRX21V) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/2.1 Chrome/34.0.1847.76 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 6.0.1; SM-N910V Build/MMB29M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.98 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 6.0.1; SAMSUNG SM-N920T Build/MMB29K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/4.0 Chrome/44.0.2403.133 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 5.0.1; SAMSUNG SCH-I545 4G Build/LRX22C) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/2.1 Chrome/34.0.1847.76 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 6.0.1; SM-G900V Build/MMB29M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.98 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 6.0.1; SAMSUNG SM-G925F Build/MMB29K) AppleWebKit/537.36 (KHTML, like Gecko) SamsungBrowser/4.0 Chrome/44.0.2403.133 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 6.0.1; E6653 Build/32.2.A.0.253) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.98 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 6.0; HTC One M9 Build/MRA58K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.98 Mobile Safari/537.36',
            'Mozilla/5.0 (BlackBerry; U; BlackBerry 9900; en) AppleWebKit/534.11+ (KHTML, like Gecko) Version/7.1.0.346 Mobile Safari/534.11+',
            'Mozilla/5.0 (BlackBerry; U; BlackBerry 9860; en-US) AppleWebKit/534.11+ (KHTML, like Gecko) Version/7.0.0.254 Mobile Safari/534.11+',
            'Mozilla/5.0 (BlackBerry; U; BlackBerry 9850; en-US) AppleWebKit/534.11+ (KHTML, like Gecko) Version/7.0.0.254 Mobile Safari/534.11+',
            'Mozilla/5.0 (BlackBerry; U; BlackBerry 9850; en-US) AppleWebKit/534.11+ (KHTML, like Gecko) Version/7.0.0.115 Mobile Safari/534.11+',
            'Mozilla/5.0 (BlackBerry; U; BlackBerry 9850; en) AppleWebKit/534.11+ (KHTML, like Gecko) Version/7.0.0.254 Mobile Safari/534.11+',
            'Mozilla/5.0 (BlackBerry; U; BlackBerry 9800; zh-TW) AppleWebKit/534.8+ (KHTML, like Gecko) Version/6.0.0.448 Mobile Safari/534.8+',
            'Mozilla/5.0 (BlackBerry; U; BlackBerry 9800; zh-TW) AppleWebKit/534.1+ (KHTML, like Gecko) Version/6.0.0.246 Mobile Safari/534.1+',
            'Mozilla/5.0 (BlackBerry; U; BlackBerry 9800; tr) AppleWebKit/534.1+ (KHTML, like Gecko) Version/6.0.0.246 Mobile Safari/534.1+',
            'Mozilla/5.0 (BlackBerry; U; BlackBerry 9800; it) AppleWebKit/534.8+ (KHTML, like Gecko) Version/6.0.0.668 Mobile Safari/534.8+',
            'Mozilla/5.0 (BlackBerry; U; BlackBerry 9800; fr) AppleWebKit/534.1+ (KHTML, like Gecko) Version/6.0.0.246 Mobile Safari/534.1+',
            'Mozilla/5.0 (BlackBerry; U; BlackBerry 9800; en-US) AppleWebKit/534.8+ (KHTML, like Gecko) Version/6.0.0.701 Mobile Safari/534.8+',
            'Mozilla/5.0 (BlackBerry; U; BlackBerry 9800; en-US) AppleWebKit/534.8+ (KHTML, like Gecko) Version/6.0.0.466 Mobile Safari/534.8+',
            'Mozilla/5.0 (BlackBerry; U; BlackBerry 9800; en-US) AppleWebKit/534.8+ (KHTML, like Gecko) Version/6.0.0.450 Mobile Safari/534.8+',
            'Mozilla/5.0 (BlackBerry; U; BlackBerry 9800; en-US) AppleWebKit/534.8+ (KHTML, like Gecko) Version/6.0.0.448 Mobile Safari/534.8+',
            'Mozilla/5.0 (BlackBerry; U; BlackBerry 9800; en-US) AppleWebKit/534.8+ (KHTML, like Gecko) Version/6.0.0.446 Mobile Safari/534.8+',
            'Mozilla/5.0 (BlackBerry; U; BlackBerry 9800; en-US) AppleWebKit/534.1+ (KHTML, like Gecko) Version/6.0.0.201 Mobile Safari/534.1+',
            'Mozilla/5.0 (BlackBerry; U; BlackBerry 9800; en-US) AppleWebKit/534.1+ (KHTML, like Gecko)',
            'Mozilla/5.0 (BlackBerry; U; BlackBerry 9800; en-GB) AppleWebKit/534.1+ (KHTML, like Gecko) Version/6.0.0.337 Mobile Safari/534.1+',
            'Mozilla/5.0 (BlackBerry; U; BlackBerry 9800; en) AppleWebKit/534.8+ (KHTML, like Gecko) Version/6.0.0.448 Mobile Safari/534.8+',
            'Mozilla/5.0 (BlackBerry; U; BlackBerry 9700; pt) AppleWebKit/534.8+ (KHTML, like Gecko) Version/6.0.0.546 Mobile Safari/534.8+',
            'Mozilla/5.0 (BB10; Kbd) AppleWebKit/537.10+ (KHTML, like Gecko) Version/10.1.0.4633 Mobile Safari/537.10+',
            'Mozilla/5.0 (BB10; Touch) AppleWebKit/537.35+ (KHTML, like Gecko) Version/10.2.0.1791 Mobile Safari/537.35+',
            'Mozilla/5.0 (BB10; Kbd) AppleWebKit/537.35_ (KHTML,like Gecko) Version/10.2.1.1925 Mobile Safari/537.35+',
            'Mozilla/5.0 (BB10; Touch) AppleWebKit/537.35+ (KHTML, like Gecko) Version/10.2.1.2141 Mobile Safari/537.35+',
            'Mozilla/5.0 (BB10; Touch) AppleWebKit/537.35+ (KHTML, like Gecko) Version/10.3.0.140 Mobile Safari/537.35+',
            'Mozilla/5.0 (Linux; U; Android 4.0.3; ko-kr; LG-L160L Build/IML74K) AppleWebkit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
            'Mozilla/5.0 (Linux; U; Android 4.0.3; de-ch; HTC Sensation Build/IML74K) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
            'Mozilla/5.0 (Linux; U; Linux Ventana; en-us; Transformer TF101 Build/HTK75) AppleWebKit/534.13 (KHTML, like Gecko) Chrome/8.0 Safari/534.13',
            'Mozilla/5.0 (Linux; U; Android 4.2.1; it-it; Nexus 7 Build/JOP40D) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Safari/534.30',
            'Mozilla/5.0 (Linux; U; en-us; EBRD1201; EXT) AppleWebKit/533.1 (KHTML, like Gecko) Version/4.0 Mobile Safari/533.1',
            'Mozilla/5.0 (Linux; Android 4.1.2; GT-I9300 Build/JZO54K) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.166 Mobile Safari/535.19',
            'Mozilla/5.0 (Linux; Android 4.1.2; GT-N7105 Build/JZO54K) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.166 Mobile Safari/535.19',
            'Mozilla/5.0 (Linux; Android 4.2.1; Nexus 7 Build/JOP40D) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.166 Safari/535.19',
            'Mozilla/5.0 (Linux; Android 4.2.2; GT-I9500 Build/JDQ39) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/37.0.2062.117 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 4.4.2; SAMSUNG-SM-T337A Build/KOT49H) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/44.0.2403.133 Safari/537.36',
            'Mozilla/5.0 (Linux; Android 4.4.2; SM-T310 Build/KOT49H) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Safari/537.36',
            'Mozilla/5.0 (Linux; Android 4.4.4; 2014821 Build/KTU84P) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/35.0.1916.138 Mobile Safari/537.36 T7/7.5 baidubrowser/7.5.22.0 (Baidu; P1 4.4.4)',
            'Mozilla/5.0 (Linux; Android 4.4.4; GT-P5100 Build/KTU84Q) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/33.0.0.0 Safari/537.36',
            'Mozilla/5.0 (Linux; Android 6.0; Lenovo K50-t5 Build/MRA58K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 4.0.4; DROID RAZR Build/6.7.2-180_DHD-16_M4-31) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.166 Mobile Safari/535.19',
            'Mozilla/5.0 (Linux; U; Android 4.0.3; ru-ru; Transformer TF101 Build/IML74K) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Safari/534.30',
            'Mozilla/5.0 (Linux; U; Android 4.0.3; sv-se; DEM752HCF Build/IML74K) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
            'Mozilla/5.0 (Linux; U; Android 4.0.4; da-dk; GT-I9100 Build/IMM76D) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
            'Mozilla/5.0 (Linux; Android 4.4.4; MI 3W Build/KTU84P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/42.0.2311.111 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; U; Android 4.2; xx-xx; XT1058 Build/13.9.0Q2.X-70-GHOST-ATT_LE-2) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
            'Mozilla/5.0 (Linux; Android 4.4.4; MI 3W Build/KTU84P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/45.0.2454.94 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; U; Android 4.4.4; en-US; MI 3W Build/KTU84P) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 UCBrowser/10.8.0.718 U3/0.8.0 Mobile Safari/534.30',
            'Mozilla/5.0 (Linux; Android 4.4.4; MI 3W Build/KTU84P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.83 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 4.4.4; HM NOTE 1LTE Build/KTU84P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/36.0.1985.135 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; U; Android 4.4.4; en-US; HM NOTE 1LTE Build/KTU84P) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 UCBrowser/10.5.2.582 U3/0.8.0 Mobile Safari/534.30',
            'Mozilla/5.0 (Linux; U; Android 4.4.2; en-US; HM NOTE 1W Build/KOT49H) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 UCBrowser/10.4.1.565 U3/0.8.0 Mobile Safari/534.30',
            'Mozilla/5.0 (Linux; Android 4.0.3; HTC One X Build/IML74K) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.166 Mobile Safari/535.19',
            'Mozilla/5.0 (Linux; Android 4.0.3; HTC One X Build/IML74K) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.166 Mobile Safari/535.19',
            'Mozilla/5.0 (iPhone; U; CPU iPhone OS 4_0 like Mac OS X; xx-xx) AppleWebKit/532.9 (KHTML, like Gecko) Version/4.0.5 Mobile/8A293 Safari/6531.22.7',
            'Mozilla/5.0 (Linux; U; Android 4.2; xx-xx; GT-I9500 Build/JDQ39) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
            'Mozilla/5.0 (Linux; U; Android 4.2; xx-xx; GT-I9295 Build/JDQ39) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
            'Mozilla/5.0 (Linux; U; Android 4.0; xx-xx; GT-I9300 Build/IMM76D) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
            'Mozilla/5.0 (Linux; U; Android 4.0; xx-xx; GT-I9305 Build/IMM76D) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
            'Mozilla/5.0 (Linux; U; Android 4.1; xx-xx; LG-F240L Build/JZO54K) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
            'Mozilla/5.0 (Linux; U; Android 4.1; xx-xx; LG-E980/E98010g Build/JZO54K) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
            'Mozilla/5.0 (compatible; MSIE 10.0; Windows Phone 8.0; Trident/6.0; IEMobile/10.0; ARM; Touch; NOKIA; Lumia 625)',
            'Mozilla/5.0 (Linux; U; Android 4.3; xx-xx; SM-N900T Build/JSS15J) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
            'Mozilla/5.0 (Linux; U; Android 4.1; xx-xx; SonyEricssonC6603 Build/10.1.A.0.182) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
            'Xiaomi_2014216_TD-LTE/V1 Linux/3.4.0 Android/4.4.4 Release/20.10.2014 Browser/AppleWebKit537.36 Mobile Safari/537.36 System/Android 4.4.4 XiaoMi/MiuiBrowser/2.0.1',
            'Mozilla/5.0 (Linux; U; Android 4.4.4; en-gb; MI 3W Build/KTU84P) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/39.0.0.0 Mobile Safari/537.36 XiaoMi/MiuiBrowser/2.1.1',
            'Mozilla/5.0 (Linux; U; Android 5.0.2; en-gb; Mi 4i Build/LRX22G) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/39.0.0.0 Mobile Safari/537.36 XiaoMi/MiuiBrowser/2.1.1',
            'Mozilla/5.0 (Linux; U; Android 4.4.4; en-us; HM 1S Build/KTU84P) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/39.0.0.0 Mobile Safari/537.36 XiaoMi/MiuiBrowser/2.1.1',
            'Mozilla/5.0 (Linux; U; Android 4.4.4; en-us; 2014818 Build/KTU84P) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/42.0.0.0 Mobile Safari/537.36 XiaoMi/MiuiBrowser/2.1.1',
            'Mozilla/5.0 (Linux; U; Android 5.1.1; en-us; Xperia Z Ultra Build/LVY48F) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/39.0.0.0 Mobile Safari/537.36 XiaoMi/MiuiBrowser/2.1.1',
            'Mozilla/5.0 (Linux; U; Android 4.4.4; zh-cn; MI 4LTE Build/KTU84P) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/42.0.0.0 Mobile Safari/537.36 XiaoMi/MiuiBrowser/2.1.1',
            'Mozilla/5.0 (Linux; U; Android 4.4.4; id-id; 2014817 Build/KTU84P) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/42.0.0.0 Mobile Safari/537.36 XiaoMi/MiuiBrowser/2.1.1',
            'Mozilla/5.0 (Linux; U; Android 4.1.1; nl-nl; MI 2S Build/JRO03L) AppleWebKit/534.30 (KHTML, zoals Gecko) Versie/4.0 MobielSafari/534.30 XiaoMi/MiuiBrowser/1.0',
            'Mozilla/5.0 (Linux; U; Android 4.1.1; zh-cn; MI Build/JRO03L) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30 XiaoMi/MiuiBrowser/1.0',
            'Mozilla/5.0 (Linux; U; Android 4.3; xx-xx; A0001 Build/JLS36C) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
            'Mozilla/5.0 (Linux; Android 6.0; RAIN A3000 Build/MMB29M) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/50.0.2661.89 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 4.0.3; MI-ONE Plus Build/IML74K) AppleWebKit/535.19 (KHTML, like Gecko) Chrome/18.0.1025.166 Mobile Safari/535.19',
            'Mozilla/5.0 (Linux; U; Android 5.1; en-US; PULP Build/LMY47I) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 UCBrowser/10.10.5.809 U3/0.8.0 Mobile Safari/534.30',
            'Mozilla/5.0 (Linux; Android 4.1.2; B1-710 Build/JZO54K) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/39.0.0.0 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; U; Android 6.0.1; pt-BR; MI 3W Build/MMB29M) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 UCBrowser/10.7.5.658 U3/0.8.0 Mobile Safari/534.30',
            'Mozilla/5.0 (Windows Phone 10.0; Android 6.0.1; Microsoft; Lumia 650 Dual SIM) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.79 Mobile Safari/537.36 Edge/14.14393',
            'Mozilla/5.0 (Linux; Android 4.1.2; A510 Build/JZO54K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.91 Safari/537.36',
            'UCWEB/2.0 (Linux; U; Opera Mini/7.1.32052/30.3697; en-US; Micromax Q334 Build/LMY47I) U2/1.0.0 UCBrowser/10.7.5.785 Mobile',
            'Mozilla/5.0 (Linux; Android 5.1.1; SGP611 Build/23.4.A.1.264) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.91 Safari/537.36',
            'Mozilla/5.0 (Linux; Android 4.1.2; LG-E980 Build/JZO54K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/51.0.2704.81 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 5.1.1; Lenovo TB2-X30F Build/LenovoTB2-X30F; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/51.0.2704.81 Safari/537.36 SznProhlizec/3.2.7a',
            'Mozilla/5.0 (Linux; Android 6.0; MotoE2(4G-LTE) Build/MPI24.65-39; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/51.0.2704.81 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 6.0.1; SM-T550 Build/MMB29M; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/51.0.2704.81 Safari/537.36 GSA/6.1.28.21.arm',
            'Mozilla/5.0 (Linux; Android 5.1; M5 Build/LMY47D) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.93 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; U; Android 5.1; xx-xx; GN8001 Build/LMY47D) AppleWebKit/537.36 (KHTML, like Gecko)Version/4.0 Chrome/37.0.0.0 MQQBrowser/6.3 Mobile Safari/537.36',
            'MozillaP2S/5.0 (Linux; U; Android 4.2; xx-xx; P2S Build/JDQ39) AppleWebKit/534.30 (KHTML, like Gecko) Version/4.0 Mobile Safari/534.30',
            'Mozilla/5.0 (Linux; Android 5.1; M3mini Build/LMY47D) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.93 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 4.4.2; Lenovo B8000-F Build/KOT49H) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.83 Safari/537.36',
            'Mozilla/5.0 (Linux; Android 4.4.2; LenovoA3300-H Build/KOT49H) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/47.0.2526.83 Safari/537.36',
            'Mozilla/5.0 (Linux; Android 5.0.1; HTC One_M8 Build/LRX22C) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.65 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 5.1.1; Nexus 5 Build/LMY48B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/43.0.2357.78 Mobile Safari/537.36',
            'Opera/9.80 (Android; Opera Mini/7.5.33361/34.1697; U; en) Presto/2.8.119 Version/11.108.119 Version/Nexus 5',
            'Mozilla/5.0 (Linux; Android 5.0; Nexus 5 Build/LPX13D) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/38.0.2125.102 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 4.4.2; Nexus 5 Build/KOT49H) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/31.0.1650.59 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 6.0; Nexus 5X Build/MDB08I) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/46.0.2490.43 Mobile Safari/537.36',
            'mozilla/5.0 (Linux; Android 6.0.1; Nexus 5x build/mtc19t applewebkit/537.36 (KHTML, like Gecko) Chrome/51.0.2702.81 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1.1; Pixel Build/NMF26U) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.91 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1.1; Pixel Build/NOF26V) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1.1; Pixel Build/NOF27B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1.1; Pixel Build/NMF26O) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.91 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1.2; Pixel Build/N2G47E) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.132 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1.1; Pixel Build/NOF27B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.132 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1.1; Pixel Build/NOF26V) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.91 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1; Pixel Build/NDE63V) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.85 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1.1; Pixel Build/NMF26V) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.91 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1.1; Pixel Build/NMF26U) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1; Pixel Build/NDE63X) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.85 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1; Pixel Build/NDE63U) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.85 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1.1; Pixel Build/NMF26Q) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.91 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1; Pixel Build/NDE63H) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.68 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1.2; Pixel Build/NHG47K) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.132 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1.1; Pixel Build/NOF27B; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/56.0.2924.87 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1; Pixel Build/NDE63H) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.99 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1.1; Pixel Build/NMF26U; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/55.0.2883.91 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1; Pixel Build/NDE63H) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.85 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1.1; Pixel Build/NOF26V; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/56.0.2924.87 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1; Pixel Build/NDE63H) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.91 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1.1; Pixel Build/NMF26O) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.135 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1.1; Pixel Build/NOF26W) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1.1; Pixel Build/NOF27B) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.91 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1; Pixel Build/NDE63X) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.91 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1.1; Pixel Build/NMF26O; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/55.0.2883.91 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1; Pixel Build/NDE63V) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.91 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1; Pixel Build/NDE63H) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.134 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1; Pixel Build/NDE63P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.68 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1; Pixel Build/NDE63H) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1.1; Pixel Build/NMF26U) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/53.0.2785.135 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1; Pixel Build/NDE63L) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.91 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1.2; Pixel Build/NPG05E) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1; Pixel Build/NDE63L) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.85 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1; Pixel Build/NDE63V; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/54.0.2840.85 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1; Pixel Build/NDE63P) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.85 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1; Pixel Build/NPF26J) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.85 Mobile Safari/537.36',
            'mozilla/5.0 (linux; android 7.1.1; pixel build/nmf26u) applewebkit/537.36 (khtml, like gecko) chrome/55.0.2883.91 mobile safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1.2; Pixel Build/N2G47E; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/57.0.2987.132 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1; Pixel Build/NDE63L) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/54.0.2840.68 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1.1; Pixel Build/NMF26V) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/56.0.2924.87 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1.1; Pixel Build/NOF26V) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.132 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1; Pixel Build/NDE63X; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/54.0.2840.85 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1.1; Pixel Build/NMF26F) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/55.0.2883.91 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1; Pixel Build/NDE63L; wv) AppleWebKit/537.36 (KHTML, like Gecko) Version/4.0 Chrome/54.0.2840.85 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1.1; Pixel Build/NOF27C) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/57.0.2987.132 Mobile Safari/537.36',
            'Mozilla/5.0 (Linux; Android 7.1; Pixel Build/NDE63N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/52.0.2743.99 Mobile Safari/537.36',
            'Mozilla/5.0 (compatible; MSIE 10.0; Windows Phone 8.0; Trident/6.0; IEMobile/10.0; ARM; Touch; HTC; Windows Phone 8X by HTC)',
            'Mozilla/5.0 (compatible; MSIE 10.0; Windows Phone 8.0; Trident/6.0; IEMobile/10.0; ARM; Touch; HUAWEI; W1-U00)',
            'Mozilla/5.0 (compatible; MSIE 10.0; Windows Phone 8.0; Trident/6.0; IEMobile/10.0; ARM; Touch; NOKIA; Lumia 925)',
            'Mozilla/5.0 (compatible; MSIE 10.0; Windows Phone 8.0; Trident/6.0; IEMobile/10.0; ARM; Touch; NOKIA; Lumia 625H)',
            'Mozilla/5.0 (compatible; MSIE 10.0; Windows Phone 8.0; Trident/6.0; IEMobile/10.0; ARM; Touch; NOKIA; Lumia 520)',
            'Mozilla/5.0 (compatible; MSIE 9.0; Windows Phone OS 7.5; Trident/5.0; IEMobile/9.0; NOKIA; Lumia 610)',
            'Mozilla/5.0 (compatible; MSIE 10.0; Windows Phone 8.0; Trident/6.0; IEMobile/10.0; ARM; Touch; HUAWEI; W1-C00)',
        );
        if ($_SERVER['HTTP_HOST'] == 'dev.luvr.me') {
            $ads = array(
                "http://my.mobfox.com/request.php?rt=" . MOBFOX_APIKEY . "&r_type=video&r_resp=vast30&s=" . MOBFOX_INVHASH_DEV . "&i=" . $_SERVER['REMOTE_ADDR'] . "&u=" . urlencode($mobile_user_agents[array_rand($mobile_user_agents)]) . "",
                "http://soma.smaato.net/oapi/reqAd.jsp?adspace=130268026&apiver=502&format=video&formatstrict=true&height=768&pub=1100031417&response=XML&vastver=2&videotype=interstitial&width=1024",
                "http://ads.nexage.com/adServe?dcn=2c9d2b4f015b5b87d1dea3a7a1ae016f&pos=interstitial&ua=" . urlencode($mobile_user_agents[array_rand($mobile_user_agents)]) . "&ip=" . $_SERVER['REMOTE_ADDR'] . "&u(id)=" . uniqid() . "&req(url)=" . base_url(uri_string()) . "" //AOL1
            );
            $data['ad_url'] = $ads[array_rand($ads)];
        } else if ($_SERVER['HTTP_HOST'] == 'luvr.me') {
            $ads = array(
                "http://my.mobfox.com/request.php?rt=" . MOBFOX_APIKEY . "&r_type=video&r_resp=vast30&s=" . MOBFOX_INVHASH_LIVE . "&i=" . $_SERVER['REMOTE_ADDR'] . "&u=" . urlencode($mobile_user_agents[array_rand($mobile_user_agents)]) . "",
                "http://soma.smaato.net/oapi/reqAd.jsp?adspace=130269290&apiver=502&format=video&formatstrict=true&height=768&pub=1100031417&response=XML&vastver=2&videotype=interstitial&width=1024",
                "http://ads.nexage.com/adServe?dcn=2c9d2b50015b5bb0aaaab3d2d9960047&pos=interstitial&ua=" . urlencode($mobile_user_agents[array_rand($mobile_user_agents)]) . "&ip=" . $_SERVER['REMOTE_ADDR'] . "&u(id)=" . uniqid() . "&req(url)=" . base_url(uri_string()) . "" //AOL1
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
