<?php
if (!empty($meta_title) && $meta_title != null)
    $site_title = $meta_title;
else
    $site_title = "Welcome to Luvr";
?>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <title><?php echo $site_title; ?></title>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=0"/>
        <link rel="icon" href="<?php echo base_url('/favicon.png'); ?>" type="image/x-icon" />
        <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:400,500" rel="stylesheet"/>
        <?php
        /* Load css files */
        $css = array('bootstrap.min.css', 'style.css', 'responsive.css', 'icomoon.css', 'jquery.bxslider.css');
        $js = array('jquery.min.js', 'bootstrap.min.js');
        $this->minify->css($css);
        echo $this->minify->deploy_css();

        /* Load js files */
        $this->minify->js($js);
        echo $this->minify->deploy_js(FALSE, 'combined.min.js');
        ?>
    </head>
    <body class="with-login">
        <header id="header">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="header">    
                            <div class="logo">
                                <a href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>assets/images/luvr-logo.png" alt="Luvr" title="Luvr"/></a>
                            </div>
                            <?php
                            $user_data = $this->session->userdata('user');
                            if (!empty($user_data)) {
                                $user_media = $this->Users_model->getUserMediaByCol('id', $user_data['profile_media_id']);
                                $username = (!empty($user_data['user_name'])) ? $user_data['user_name'] : $user_data['instagram_username'];
                                ?>
                                <div class="user-dropdown dropdown">
                                    <a href="" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        <span class="user-pic">                                                        
                                            <?php if ($user_media['media_type'] == '1' || $user_media['media_type'] == '2' || $user_media['media_type'] == '3' || $user_media['media_type'] == '4') { ?>
                                                <img src="<?php echo base_url() . 'bio/show_img/' . $user_media['media_thumb'] . '/1'; ?>" alt="<?php echo $username; ?>" 
                                                     title="<?php echo $username; ?>" 
                                                     onerror="this.src='<?php echo base_url(); ?>assets/images/default_avatar.jpg'"/>
                                                 <?php } else { ?>
                                                <img alt="User Pic" src="<?php echo base_url(); ?>assets/images/default_avatar.jpg" class="img-circle img-responsive"/>
                                            <?php } ?>                                             
                                        </span>
                                        <big><?php echo $username; ?></big>
                                        <?php
                                        if (!empty($user_data['country_short_code'])) {
                                            echo '<small>[' . $user_data['country_short_code'] . ']</small>';
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
                                        <li><a href="<?php echo base_url() . 'user/view_profile'; ?>">My Account</a></li>
                                        <li><a href="<?php echo base_url() . 'match/nearby'; ?>">Nearby Matches</a></li>
                                        <li><a href="<?php echo base_url() . 'user/logout'; ?>">Logout</a></li>
                                    </ul>
                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </header>

        <section id="inner-content" class="inner-content">
            <div class="container">
                <div class="row">
                    <div id="msg_txt" style="display:none;"></div>
