<?php
if (!empty($meta_title) && $meta_title != null)
    $site_title = $meta_title;
else
    $site_title = "Welcome to Luvr";
$user_data = $this->session->userdata('user');
if (!empty($user_data) && uri_string() != "home") {
    redirect("match/nearby");
}

$fb_login_url = '';
if (empty($user_data)) {
    $fb_login_url = $this->facebook->get_login_url();
}
?>
<html lang="en">
    <head>
        <meta charset="utf-8"/>
        <meta name="viewport" content="width=device-width, initial-scale=1, maximum-scale=1.0, user-scalable=0"/>
        <link rel="icon" href="<?php echo base_url('/favicon.png'); ?>" type="image/x-icon"/>
        <title><?php echo $site_title; ?></title>
        <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:400,500" rel="stylesheet"/>
        <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/bootstrap.min.css'; ?>"/>
        <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/icomoon.css'; ?>"/>
        <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/jquery.bxslider.css'; ?>"/>
        <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/style.css'; ?>"/>
        <link rel="stylesheet" href="<?php echo base_url() . 'assets/css/responsive.css'; ?>"/>
        <?php
        // add js files
        $this->minify->js(array('jquery.min.js', 'bootstrap.min.js'));
        echo $this->minify->deploy_js(FALSE, 'combined.min.js');
        ?>
        <?php if ($_SERVER['HTTP_HOST'] == 'dev.luvr.me' || $_SERVER['HTTP_HOST'] == 'luvr.me') { ?>
            <script>
                (function (i, s, o, g, r, a, m) {
                    i['GoogleAnalyticsObject'] = r;
                    i[r] = i[r] || function () {
                        (i[r].q = i[r].q || []).push(arguments)
                    }, i[r].l = 1 * new Date();
                    a = s.createElement(o),
                            m = s.getElementsByTagName(o)[0];
                    a.async = 1;
                    a.src = g;
                    m.parentNode.insertBefore(a, m)
                })(window, document, 'script', 'https://www.google-analytics.com/analytics.js', 'ga');
                ga('create', 'UA-87679442-2', 'auto');
                ga('send', 'pageview');
            </script>
        <?php } ?>
    </head> 
    <body>
        <header id="header">
            <div class="container">
                <div class="row">
                    <div class="col-md-12 col-sm-12">
                        <div class="header">
                            <div class="logo">
                                <a href="<?php echo base_url('home'); ?>"><img src="<?php echo base_url(); ?>assets/images/luvr-logo.png" alt="Luvr" title="Luvr"/></a>
                            </div>
                            <?php
                            if (!empty($user_data)) {
                                $user_media = $this->Users_model->getUserMediaByCol('id', $user_data['profile_media_id']);
                                $username = (!empty($user_data['user_name'])) ? $user_data['user_name'] : $user_data['instagram_username'];
                                ?>
                                <div class="user-dropdown dropdown">
                                    <a href="" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                        <span class="user-pic">
                                            <?php if ($user_media['media_type'] == '1' || $user_media['media_type'] == '2') { ?>
                                                <img src="<?php echo base_url() . 'bio/show_img/' . $user_media['media_thumb'] . '/1'; ?>" alt="<?php echo $username; ?>" 
                                                     title="<?php echo $username; ?>" 
                                                     onerror="this.src='<?php echo base_url(); ?>assets/images/default_avatar.jpg'"/>
                                                 <?php } else if ($user_media['media_type'] == '3' || $user_media['media_type'] == '4') { ?>
                                                <img src="<?php echo $user_media['media_thumb']; ?>" alt="<?php echo $username; ?>" title="<?php echo $username; ?>" onerror="this.src='<?php echo base_url(); ?>assets/images/default_avatar.jpg'"/>
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
                            <?php } else { ?>

                                <div class="header-r">
                                    <a href="<?php echo $fb_login_url; ?>" class="login-facebook">
                                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"viewBox="0 0 512 512" style="enable-background:new 0 0 512 512;" xml:space="preserve"> <path style="fill:#CEE8FA;" d="M365.075,307.969L365.075,307.969c-0.209-21.573-17.756-38.95-39.33-38.95h-36.052v-54.677 c0-15.267,12.377-27.643,27.643-27.643h13.209c19.534,0,35.37-15.836,35.37-35.37l0,0c0-19.534-15.836-35.37-35.37-35.37h-32.525 c-48.044,0-86.991,38.947-86.991,86.991v66.067h-25.613c-21.722,0-39.332,17.609-39.332,39.332l0,0 c0,21.722,17.609,39.332,39.332,39.332h25.613v149.794h78.665V347.682h36.052C347.617,347.682,365.287,329.839,365.075,307.969z"/> <g> <path style="fill:#2D527C;" d="M289.693,512h-78.665c-8.021,0-14.523-6.502-14.523-14.523V362.206h-11.09 c-29.695,0-53.855-24.159-53.855-53.855s24.159-53.856,53.855-53.856h11.09v-51.544c0-55.975,45.539-101.514,101.514-101.514 h32.525c27.511,0,49.893,22.382,49.893,49.893c0,27.51-22.382,49.893-49.893,49.893h-13.209c-7.234,0-13.12,5.886-13.12,13.12 v40.154h21.529c29.409,0,53.567,23.925,53.853,53.332c0.141,14.484-5.395,28.128-15.588,38.419s-23.782,15.958-38.266,15.958 h-21.528v135.271C304.216,505.498,297.714,512,289.693,512z M225.553,482.954h49.618V347.682c0-8.021,6.502-14.523,14.523-14.523 h36.052c6.672,0,12.933-2.611,17.627-7.352c4.695-4.74,7.246-11.026,7.18-17.698c-0.131-13.547-11.26-24.569-24.808-24.569h-36.052 c-8.021,0-14.523-6.502-14.523-14.523v-54.677c0-23.25,18.915-42.166,42.166-42.166h13.209c11.495,0,20.847-9.351,20.847-20.847 c0-11.495-9.351-20.847-20.847-20.847h-32.525c-39.959,0-72.468,32.509-72.468,72.468v66.067c0,8.021-6.502,14.523-14.523,14.523 h-25.613c-13.679,0-24.808,11.129-24.808,24.81c0,13.679,11.129,24.808,24.808,24.808h25.613c8.021,0,14.523,6.502,14.523,14.523 V482.954z"/> <path style="fill:#2D527C;" d="M432.464,512h-37.766c-8.021,0-14.523-6.502-14.523-14.523s6.502-14.523,14.523-14.523h37.766 c27.839,0,50.49-22.65,50.49-50.49V79.536c0-27.839-22.65-50.49-50.49-50.49H214.367c-8.021,0-14.523-6.502-14.523-14.523 S206.346,0,214.367,0h218.097C476.321,0,512,35.679,512,79.536v352.928C512,476.321,476.321,512,432.464,512z"/> <path style="fill:#2D527C;" d="M289.693,512H79.536C35.679,512,0,476.321,0,432.464V79.536C0,35.679,35.679,0,79.536,0h32.684 c8.021,0,14.523,6.502,14.523,14.523s-6.502,14.523-14.523,14.523H79.536c-27.839,0-50.49,22.65-50.49,50.49v352.928 c0,27.839,22.65,50.49,50.49,50.49h210.157c8.021,0,14.523,6.502,14.523,14.523S297.714,512,289.693,512z"/> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> </svg>
                                        <span>Login With <br/>Facebook</span>
                                    </a>
                                    <a href="https://api.instagram.com/oauth/authorize/?client_id=<?php echo INSTA_CLIENT_ID; ?>&redirect_uri=<?php echo base_url() . 'register/return_url'; ?>&response_type=code&scope=likes+comments+follower_list+relationships+public_content" class="login-instagram">
                                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"viewBox="0 0 512.001 512.001" style="enable-background:new 0 0 512.001 512.001;" xml:space="preserve"> <g> <path style="fill:#B3404A;" d="M432.464,512.001h-37.767c-8.02,0-14.523-6.502-14.523-14.523s6.503-14.523,14.523-14.523h37.767 c27.839,0,50.49-22.65,50.49-50.49V79.537c0-27.839-22.65-50.49-50.49-50.49H214.367c-8.02,0-14.523-6.502-14.523-14.523 s6.503-14.523,14.523-14.523h218.098c43.855,0,79.536,35.679,79.536,79.536v352.928C512,476.322,476.321,512.001,432.464,512.001z"/> <path style="fill:#B3404A;" d="M289.694,512.001H79.536C35.679,512.001,0,476.322,0,432.465V79.537 C0,35.68,35.679,0.001,79.536,0.001h32.684c8.02,0,14.523,6.502,14.523,14.523s-6.503,14.523-14.523,14.523H79.536 c-27.839,0-50.49,22.65-50.49,50.49v352.928c0,27.839,22.65,50.49,50.49,50.49h210.158c8.02,0,14.523,6.502,14.523,14.523 C304.218,505.499,297.714,512.001,289.694,512.001z"/> </g> <path style="fill:#F4B2B0;" d="M359.599,102.056H152.401c-27.806,0-50.347,22.541-50.347,50.347v207.197 c0,27.806,22.541,50.347,50.347,50.347h207.197c27.806,0,50.347-22.541,50.347-50.347V152.402 C409.946,124.596,387.405,102.056,359.599,102.056z M256.001,330.781c-41.299,0-74.78-33.48-74.78-74.78s33.48-74.78,74.78-74.78 s74.78,33.48,74.78,74.78S297.3,330.781,256.001,330.781z"/> <g> <path style="fill:#B3404A;" d="M359.599,424.47H152.403c-35.77,0-64.87-29.1-64.87-64.87V152.402c0-35.769,29.1-64.87,64.87-64.87 h207.196c35.77,0,64.87,29.1,64.87,64.87v207.197C424.469,395.368,395.368,424.47,359.599,424.47z M152.403,116.579 c-19.754,0-35.824,16.07-35.824,35.824v207.197c0,19.753,16.07,35.824,35.824,35.824h207.196c19.754,0,35.824-16.07,35.824-35.824 V152.402c0-19.753-16.07-35.824-35.824-35.824H152.403V116.579z M256.001,345.304c-49.242,0-89.303-40.061-89.303-89.303 s40.061-89.303,89.303-89.303s89.303,40.061,89.303,89.303S305.243,345.304,256.001,345.304z M256.001,195.744 c-33.226,0-60.256,27.03-60.256,60.257s27.03,60.257,60.256,60.257s60.257-27.03,60.257-60.257S289.225,195.744,256.001,195.744z"/> <circle style="fill:#B3404A;" cx="346.406" cy="164.504" r="20.449"/> </g> <g></g> <g></g> <g></g> <g></g> <g></g> <g></g> <g></g> <g></g> <g></g> <g></g> <g></g> <g></g> <g></g> <g></g> <g></g> </svg>
                                        <span>Login With <br/>instagram</span>
                                    </a>    

                                </div>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </header>