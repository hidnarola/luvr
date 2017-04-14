<?php
if (!empty($meta_title) && $meta_title != null)
    $site_title = $meta_title;
else
    $site_title = "Welcome to Luvr";
?>
<html lang="en">
    <head>
        <meta charset="utf-8">
            <title><?php echo $site_title; ?></title>
            <meta charset="utf-8">
                <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=0"/>
                <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:400,500" rel="stylesheet"/>
                <?php
                /* Load css files */
                $css = array('style.css', 'bootstrap.min.css', 'responsive.css');
                $js = array('jquery.min.js', 'bootstrap.min.js');
                if ($sub_view == "match/nearByMatches") {
                    array_push($css, "jTinder.css");
                    array_push($js, "jquery.transform2d.js", "jquery.jTinder.js");
                }
                $this->minify->css($css);
                echo $this->minify->deploy_css(TRUE, 'styles.min.css');

                /* Load js files */
                $this->minify->js($js);
                echo $this->minify->deploy_js(FALSE, 'combined.min.js');
                ?>
                </head>
                <body class="with-login">
                    <header id="header">
                        <div class="container">
                            <div class="row">
                                <div class="header">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="logo">
                                            <a href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>assets/images/luvr-logo.png" alt="Luvr" title="Luvr"/></a>
                                        </div>
                                        <?php
                                        $user_data = $this->session->userdata('user');
                                        if (!empty($user_data)) {
                                            $user_media = $this->Users_model->getUserMediaByCol('id', $user_data['profile_media_id']);
                                            ?>
                                            <div class="user-dropdown dropdown">
                                                <a href="" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                                    <span class="user-pic"><img src="<?php echo $user_media['media_name']; ?>" alt="<?php echo $user_data['user_name']; ?>" title="<?php echo $user_data['user_name']; ?>" onerror="this.src='<?php echo base_url(); ?>assets/images/default_avatar.jpg'"/></span>
                                                    <big><?php echo $user_data['user_name']; ?></big>
                                                    <?php
                                                    $address = $user_data['address'];
                                                    if ($address != '' && !empty($address)) {
                                                        $str = 'https://maps.googleapis.com/maps/api/geocode/json?address=' . $address . '&key=' . GOOGLE_MAP_API;
                                                        $res = $this->unirest->get($str);
                                                        $res_arr = json_decode($res->raw_body, true);
                                                        if ($res_arr) {
                                                            echo '<small>[' . $res_arr['results'][0]['address_components'][2]['short_name'] . ']</small>';
                                                        }
                                                    }
                                                    ?>
                                                    <span class="down-caret">
                                                        <svg version="1.1" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 129 129" xmlns:xlink="http://www.w3.org/1999/xlink" enable-background="new 0 0 129 129">
                                                            <g>
                                                                <path d="m121.3,34.6c-1.6-1.6-4.2-1.6-5.8,0l-51,51.1-51.1-51.1c-1.6-1.6-4.2-1.6-5.8,0-1.6,1.6-1.6,4.2 0,5.8l53.9,53.9c0.8,0.8 1.8,1.2 2.9,1.2 1,0 2.1-0.4 2.9-1.2l53.9-53.9c1.7-1.6 1.7-4.2 0.1-5.8z"/>
                                                            </g>
                                                        </svg>
                                                    </span>
                                                </a>
                                                <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
                                                    <li><a href="<?php echo base_url() . 'user/setup_userprofile/edit'; ?>">Edit Profile</a></li>
                                                    <li><a href="<?php echo base_url() . 'user/user_settings'; ?>">Edit Settings</a></li>
                                                    <li><a href="<?php echo base_url() . 'user/edit_filters'; ?>">Edit Filters</a></li>
                                                    <li><a href="<?php echo base_url() . 'user/video_requests'; ?>">Video Requests</a></li>
                                                    <li><a href="<?php echo base_url() . 'user/blocked_list'; ?>">Blocked List</a></li>
                                                    <li><a href="<?php echo base_url() . 'match/nearby'; ?>">Nearby Matches</a></li>
                                                    <li><a href="<?php echo base_url() . 'bio/instagram_feed'; ?>">Instagram Feeds</a></li>
                                                    <li><a href="<?php echo base_url() . 'bio/saved_feed'; ?>">My Media</a></li>
                                                    <li><a href="<?php echo base_url() . 'user / logout'; ?>">Logout</a></li>
                                                </ul>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                                <!--                                        <div class="breadcrumb-div">
                                                                            <ul>
                                                                                <li><a href="<?php echo base_url(); ?>">Home</a></li>
                                                                                <li>Nearby Matches</li>
                                                                            </ul>
                                                                        </div>-->
                            </div>
                        </div>
                    </header>

                    <section id="inner-content" class="inner-content">
                        <div class="container">
                            <div class="row">
                                <div id="msg_txt" style="display:none;"></div>
