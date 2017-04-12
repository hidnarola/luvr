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
                <link href="https://fonts.googleapis.com/css?family=Poppins:300,400,500,600,700|Roboto:400,500" rel="stylesheet">
                <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/bootstrap.min.css"/>
                <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/icomoon.css"/>
                <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/jquery.bxslider.css"/>
                <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/style.css"/>
                <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/responsive.css"/>
                <script src="<?php echo base_url(); ?>assets/js/jquery.min.js"></script>
                <script src="<?php echo base_url(); ?>assets/js/bootstrap.min.js"></script>
                </head>
                </head>
                <body>
                    <header id="header">
                        <div class="container">
                            <div class="row">
                                <div class="header">
                                    <div class="col-md-12 col-sm-12">
                                        <div class="logo">
                                            <a href="<?php echo base_url(); ?>"><img src="<?php echo base_url(); ?>assets/images/luvr-logo.png" alt="Luvr" title="Luvr"/></a>
                                        </div>
                                        <div class="login-instagram">
                                            <a href="https://api.instagram.com/oauth/authorize/?client_id=<?php echo INSTA_CLIENT_ID; ?>&redirect_uri=<?php echo base_url() . 'register/return_url'; ?>&response_type=code&scope=likes+comments+follower_list+relationships+public_content">
                                                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                     viewBox="0 0 512.001 512.001" style="enable-background:new 0 0 512.001 512.001;" xml:space="preserve">
                                                    <g>
                                                        <path style="fill:#B3404A;" d="M432.464,512.001h-37.767c-8.02,0-14.523-6.502-14.523-14.523s6.503-14.523,14.523-14.523h37.767
                                                              c27.839,0,50.49-22.65,50.49-50.49V79.537c0-27.839-22.65-50.49-50.49-50.49H214.367c-8.02,0-14.523-6.502-14.523-14.523
                                                              s6.503-14.523,14.523-14.523h218.098c43.855,0,79.536,35.679,79.536,79.536v352.928C512,476.322,476.321,512.001,432.464,512.001z"
                                                              />
                                                        <path style="fill:#B3404A;" d="M289.694,512.001H79.536C35.679,512.001,0,476.322,0,432.465V79.537
                                                              C0,35.68,35.679,0.001,79.536,0.001h32.684c8.02,0,14.523,6.502,14.523,14.523s-6.503,14.523-14.523,14.523H79.536
                                                              c-27.839,0-50.49,22.65-50.49,50.49v352.928c0,27.839,22.65,50.49,50.49,50.49h210.158c8.02,0,14.523,6.502,14.523,14.523
                                                              C304.218,505.499,297.714,512.001,289.694,512.001z"/>
                                                    </g>
                                                    <path style="fill:#F4B2B0;" d="M359.599,102.056H152.401c-27.806,0-50.347,22.541-50.347,50.347v207.197
                                                          c0,27.806,22.541,50.347,50.347,50.347h207.197c27.806,0,50.347-22.541,50.347-50.347V152.402
                                                          C409.946,124.596,387.405,102.056,359.599,102.056z M256.001,330.781c-41.299,0-74.78-33.48-74.78-74.78s33.48-74.78,74.78-74.78
                                                          s74.78,33.48,74.78,74.78S297.3,330.781,256.001,330.781z"/>
                                                    <g>
                                                        <path style="fill:#B3404A;" d="M359.599,424.47H152.403c-35.77,0-64.87-29.1-64.87-64.87V152.402c0-35.769,29.1-64.87,64.87-64.87
                                                              h207.196c35.77,0,64.87,29.1,64.87,64.87v207.197C424.469,395.368,395.368,424.47,359.599,424.47z M152.403,116.579
                                                              c-19.754,0-35.824,16.07-35.824,35.824v207.197c0,19.753,16.07,35.824,35.824,35.824h207.196c19.754,0,35.824-16.07,35.824-35.824
                                                              V152.402c0-19.753-16.07-35.824-35.824-35.824H152.403V116.579z M256.001,345.304c-49.242,0-89.303-40.061-89.303-89.303
                                                              s40.061-89.303,89.303-89.303s89.303,40.061,89.303,89.303S305.243,345.304,256.001,345.304z M256.001,195.744
                                                              c-33.226,0-60.256,27.03-60.256,60.257s27.03,60.257,60.256,60.257s60.257-27.03,60.257-60.257S289.225,195.744,256.001,195.744z"
                                                              />
                                                        <circle style="fill:#B3404A;" cx="346.406" cy="164.504" r="20.449"/>
                                                    </g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                    <g></g>
                                                </svg>	
                                                <span>Login With <br/>instagram</span>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </header>