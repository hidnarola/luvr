<?php
$user_data = $this->session->userdata('user');
$user_media = $this->Users_model->getUserMediaByCol('id', $user_data['profile_media_id']);
$all_side_medias = $this->Bio_model->fetch_media_for_sidebar($user_data['id']);
$IsPowerLuvsAllowed = IsPowerLuvsAllowed($user_data['id']);
$UserPowerLuvsPerDay = GetUserPowerLuvsPerDay($user_data['id']);
$max_powerluvs = MAX_POWERLUVS_PER_DAY;

$current_class = $this->router->fetch_class();
$current_method = $this->router->fetch_method();

if (isUserActiveSubscriber($user_data['id']) == 1) {
    $max_powerluvs = MAX_POWERLUVS_PER_DAY_P;
}
if ($IsPowerLuvsAllowed == 1) {
    $max_powerluvs = $max_powerluvs + 5;
}
if ($UserPowerLuvsPerDay >= $max_powerluvs) {
    $IsPowerLuvsAllowed = 0;
}
?>
<div class="col-md-4 col-sm-4 col-xs-12 account-l">
    <div class="account-l-head">
        <span class="user-pic">
            <?php if ($user_media['media_type'] == '1' || $user_media['media_type'] == '2') { ?>
                <img alt="User Pic" src="<?php echo base_url() . 'bio/show_img/' . $user_media['media_thumb'] . '/1'; ?>" onerror="this.src='<?php echo base_url(); ?>assets/images/default_avatar.jpg'" class="img-responsive"/>
            <?php } else { ?>
                <img alt="User Pic" src="<?php echo $user_media['media_thumb']; ?>" class="img-circle img-responsive" onerror="this.src='<?php echo base_url(); ?>assets/images/default_avatar.jpg'"/>
            <?php } ?>
        </span>
        <?php if ($IsPowerLuvsAllowed == 0 && $UserPowerLuvsPerDay >= $max_powerluvs) { ?>
            <form action="<?php echo base_url() . "user/manage_powerluv_subscription"; ?>" class="pull-right" method="post" id='frm_powerluvs'>
                <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                        data-key="<?php echo ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') ? PK_TEST : PK_LIVE; ?>"
                        data-description="Power Luvs (5 extra power luvs for a day)"
                        data-amount="99"
                        data-image='<?php echo base_url() . "assets/images/luvrlogo.png" ?>'
                data-locale="auto"></script>
                <input name="amt" value="99" type="hidden"/>
            </form>
        <?php } ?>
        <a class="white-btn user-edit for_pointer" onclick="$('#profile_picture').click();">
            <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"viewBox="0 0 55.25 55.25" style="enable-background:new 0 0 55.25 55.25;" xml:space="preserve"> <path d="M52.618,2.631c-3.51-3.508-9.219-3.508-12.729,0L3.827,38.693C3.81,38.71,3.8,38.731,3.785,38.749 c-0.021,0.024-0.039,0.05-0.058,0.076c-0.053,0.074-0.094,0.153-0.125,0.239c-0.009,0.026-0.022,0.049-0.029,0.075 c-0.003,0.01-0.009,0.02-0.012,0.03l-3.535,14.85c-0.016,0.067-0.02,0.135-0.022,0.202C0.004,54.234,0,54.246,0,54.259 c0.001,0.114,0.026,0.225,0.065,0.332c0.009,0.025,0.019,0.047,0.03,0.071c0.049,0.107,0.11,0.21,0.196,0.296 c0.095,0.095,0.207,0.168,0.328,0.218c0.121,0.05,0.25,0.075,0.379,0.075c0.077,0,0.155-0.009,0.231-0.027l14.85-3.535 c0.027-0.006,0.051-0.021,0.077-0.03c0.034-0.011,0.066-0.024,0.099-0.039c0.072-0.033,0.139-0.074,0.201-0.123 c0.024-0.019,0.049-0.033,0.072-0.054c0.008-0.008,0.018-0.012,0.026-0.02l36.063-36.063C56.127,11.85,56.127,6.14,52.618,2.631z M51.204,4.045c2.488,2.489,2.7,6.397,0.65,9.137l-9.787-9.787C44.808,1.345,48.716,1.557,51.204,4.045z M46.254,18.895l-9.9-9.9 l1.414-1.414l9.9,9.9L46.254,18.895z M4.961,50.288c-0.391-0.391-1.023-0.391-1.414,0L2.79,51.045l2.554-10.728l4.422-0.491 l-0.569,5.122c-0.004,0.038,0.01,0.073,0.01,0.11c0,0.038-0.014,0.072-0.01,0.11c0.004,0.033,0.021,0.06,0.028,0.092 c0.012,0.058,0.029,0.111,0.05,0.165c0.026,0.065,0.057,0.124,0.095,0.181c0.031,0.046,0.062,0.087,0.1,0.127 c0.048,0.051,0.1,0.094,0.157,0.134c0.045,0.031,0.088,0.06,0.138,0.084C9.831,45.982,9.9,46,9.972,46.017 c0.038,0.009,0.069,0.03,0.108,0.035c0.036,0.004,0.072,0.006,0.109,0.006c0,0,0.001,0,0.001,0c0,0,0.001,0,0.001,0h0.001 c0,0,0.001,0,0.001,0c0.036,0,0.073-0.002,0.109-0.006l5.122-0.569l-0.491,4.422L4.204,52.459l0.757-0.757 C5.351,51.312,5.351,50.679,4.961,50.288z M17.511,44.809L39.889,22.43c0.391-0.391,0.391-1.023,0-1.414s-1.023-0.391-1.414,0 L16.097,43.395l-4.773,0.53l0.53-4.773l22.38-22.378c0.391-0.391,0.391-1.023,0-1.414s-1.023-0.391-1.414,0L10.44,37.738 l-3.183,0.354L34.94,10.409l9.9,9.9L17.157,47.992L17.511,44.809z M49.082,16.067l-9.9-9.9l1.415-1.415l9.9,9.9L49.082,16.067z"/> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> </svg>
        </a>
        <form action="<?php echo base_url('/bio/change_profile'); ?>" method="POST" role="form" enctype="multipart/form-data" id="frm_change_profile_pic">
            <input type="file" name="profile_picture" id="profile_picture" style="padding: 0 !important;margin: 0 !important;position: absolute !important;height: 0 !important;width: 0 !important;"/>
            <input type="hidden" name="sub_view" value="<?php echo uri_string(); ?>"/>
        </form>
    </div>
    <div class="account-l-btm">
        <div class="account-l-link">
            <h3 class="left-title">
                <span>
                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"viewBox="0 0 52.974 52.974" style="enable-background:new 0 0 52.974 52.974;" xml:space="preserve"> <g> <path d="M49.467,3.51c-4.677-4.679-12.291-4.681-16.97,0l-9.192,9.192c-0.391,0.391-0.391,1.023,0,1.414s1.023,0.391,1.414,0 l9.192-9.192c1.88-1.88,4.391-2.915,7.07-2.915c2.681,0,5.191,1.036,7.071,2.916s2.916,4.391,2.916,7.071 c0,2.68-1.036,5.19-2.916,7.07L36.033,31.088c-3.898,3.898-10.244,3.898-14.143,0c-0.391-0.391-1.023-0.391-1.414,0 s-0.391,1.023,0,1.414c2.34,2.339,5.412,3.509,8.485,3.509s6.146-1.17,8.485-3.509L49.467,20.48 c2.258-2.258,3.502-5.271,3.502-8.485C52.969,8.781,51.725,5.768,49.467,3.51z"/> <path d="M26.84,40.279l-7.778,7.778c-1.88,1.88-4.391,2.916-7.071,2.916c-2.68,0-5.19-1.036-7.071-2.916 c-3.898-3.898-3.898-10.243,0-14.142l11.314-11.314c3.899-3.898,10.244-3.896,14.142,0c0.391,0.391,1.023,0.391,1.414,0 s0.391-1.023,0-1.414c-4.677-4.679-12.291-4.681-16.97,0L3.505,32.502c-2.258,2.258-3.501,5.271-3.501,8.485 c0,3.214,1.244,6.227,3.502,8.484s5.271,3.502,8.484,3.502c3.215,0,6.228-1.244,8.485-3.502l7.778-7.778 c0.391-0.391,0.391-1.023,0-1.414S27.231,39.889,26.84,40.279z"/> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> </svg>
                </span>
                <big>My Account</big>
            </h3>
            <ul>
                <li class="<?php echo ($sub_view == "user/userProfileSettings") ? "active" : ""; ?>"><a href="<?php echo base_url('/user/setup_userprofile/edit'); ?>">Edit Profile</a></li>
                <li class="<?php echo ($sub_view == "user/settings") ? "active" : ""; ?>"><a href="<?php echo base_url('/user/user_settings'); ?>">Edit Settings</a></li>

                <li class="<?php echo ($current_class == "message") ? "active" : ""; ?> ">
                    <a href="<?php echo base_url('/message/all_chats'); ?>" class="sidebar_message">
                        Messages
                        <?php
                        $unread_counts = GetUserUnreadNotificationCounts($user_data['id']);
                        if ($unread_counts > 0) {
                            ?>
                            <span class="badge"><?php echo $unread_counts; ?></span>
                        <?php } ?>
                    </a>
                </li>

                <li class="<?php echo ($sub_view == "user/userProfile") ? "active" : ""; ?>"><a href="<?php echo base_url('/user/view_profile'); ?>">View Profile </a></li>
                <li class="<?php echo ($sub_view == "user/userFilterSettings") ? "active" : ""; ?>"><a href="<?php echo base_url('/user/edit_filters'); ?>">Edit Filters </a></li>
                <li class="<?php echo ($sub_view == "user/videoRequests") ? "active" : ""; ?>"><a href="<?php echo base_url('/user/video_requests'); ?>">Video Snap Requests </a></li>
                <li class="<?php echo ($sub_view == "user/blockedList") ? "active" : ""; ?>"><a href="<?php echo base_url('/user/blocked_list'); ?>">Blocked List </a></li>
                <?php
                $CI = & get_instance();
                $CI->load->library('facebook');
                $fb_login_url = $CI->facebook->get_login_url();
                $insta_login_url = 'https://api.instagram.com/oauth/authorize/?client_id=' . INSTA_CLIENT_ID . '&redirect_uri=' . base_url() . 'register/return_url' . '&response_type=code&scope=likes+comments+follower_list+relationships+public_content';

                $anchor_href_fb = base_url('bio/facebook_feed');
                $anchor_href_insta = base_url('bio/instagram_feed');

                if (!empty($user_data['facebook_id']) && !empty($user_data['userid'])) {

                    $fb_token = $user_data['fb_access_token'];
                    $insta_token = $user_data['access_token'];

                    if (empty($fb_token)) {
                        $anchor_href_fb = $fb_login_url;
                    }

                    if (empty($insta_token)) {
                        $anchor_href_insta = $insta_login_url;
                    }
                }
                ?>

                <?php if (!empty($user_data['facebook_id'])) { ?>
                    <li class="<?php echo ($sub_view == "bio/facebook_feed") ? "active" : ""; ?>">
                        <a href="<?php echo $anchor_href_fb; ?>">
                            Facebook Feeds
                        </a>
                    </li>
                <?php } ?>

                <?php if (!empty($user_data['userid'])) { ?>
                    <li class="<?php echo ($sub_view == "bio/instagram_feed") ? "active" : ""; ?>">
                        <a href="<?php echo $anchor_href_insta; ?>">
                            Instagram Feeds
                        </a>
                    </li>
                <?php } ?>

                <li class="<?php echo ($sub_view == "bio/saved_feed") ? "active" : ""; ?>"><a href="<?php echo base_url('/bio/saved_feed'); ?>">My Media</a></li>
                <li class="<?php echo ($sub_view == "user/subscription") ? "active" : ""; ?>"><a href="<?php echo base_url('/user/subscription'); ?>">Subscription</a></li>
            </ul>
        </div>
        <div class="back-btn-div"><a onclick="window.history.back();" class="for_pointer"></a></div>
        <div class="left-my-picture">
            <h3 class="left-title">
                <span>
                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"viewBox="0 0 60 60" style="enable-background:new 0 0 60 60;" xml:space="preserve"> <g> <path d="M55.201,15.5h-8.524l-4-10H17.323l-4,10H12v-5H6v5H4.799C2.152,15.5,0,17.652,0,20.299v29.368 C0,52.332,2.168,54.5,4.833,54.5h50.334c2.665,0,4.833-2.168,4.833-4.833V20.299C60,17.652,57.848,15.5,55.201,15.5z M8,12.5h2v3H8 V12.5z M58,49.667c0,1.563-1.271,2.833-2.833,2.833H4.833C3.271,52.5,2,51.229,2,49.667V20.299C2,18.756,3.256,17.5,4.799,17.5H6h6 h2.677l4-10h22.646l4,10h9.878c1.543,0,2.799,1.256,2.799,2.799V49.667z"/> <path d="M30,14.5c-9.925,0-18,8.075-18,18s8.075,18,18,18s18-8.075,18-18S39.925,14.5,30,14.5z M30,48.5c-8.822,0-16-7.178-16-16 s7.178-16,16-16s16,7.178,16,16S38.822,48.5,30,48.5z"/> <path d="M30,20.5c-6.617,0-12,5.383-12,12s5.383,12,12,12s12-5.383,12-12S36.617,20.5,30,20.5z M30,42.5c-5.514,0-10-4.486-10-10 s4.486-10,10-10s10,4.486,10,10S35.514,42.5,30,42.5z"/> <path d="M52,19.5c-2.206,0-4,1.794-4,4s1.794,4,4,4s4-1.794,4-4S54.206,19.5,52,19.5z M52,25.5c-1.103,0-2-0.897-2-2s0.897-2,2-2 s2,0.897,2,2S53.103,25.5,52,25.5z"/> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> </svg>
                </span>
                <big>My Images</big>
            </h3>
            <ul>
                <?php
                if (!empty($all_side_medias)) {
                    foreach ($all_side_medias as $a_img) {
                        $m_type = $a_img['media_type'];

                        $fancybox_str = 'data-fancybox="gallerynew"';
                        $anchor_target = '';

                        if ($m_type == '4') {
                            $anchor_target = '_blank';
                            $fancybox_str = '';
                            $img_link = $a_img['media_thumb'];
                            $link = base_url() . "video/play/" . $a_img['id'];
                        }

                        if ($m_type == '3') {
                            $img_link = $a_img['media_thumb'];
                            $link = $a_img['media_name'];
                        }

                        if ($m_type == '2') {
                            $anchor_target = '_blank';
                            $fancybox_str = '';
                            $a_img['media_thumb'] = str_replace('.mp4', '.png', $a_img['media_thumb']);
                            $img_link = base_url() . 'bio/show_img/' . $a_img['media_thumb'] . '/1';
                            $link = base_url() . "video/play/" . $a_img['id'];
                        }

                        if ($m_type == '1') {
                            $link = base_url() . 'bio/show_img/' . $a_img['media_name'];
                            $img_link = base_url() . 'bio/show_img/' . $a_img['media_thumb'] . '/1';
                        }
                        ?>
                        <!-- <li>
                            <a <?php echo $fancybox_str; ?> class="image-link" href="<?php echo $link; ?>"  target="<?php echo $anchor_target; ?>">
                                <img src="<?php echo $img_link; ?>" alt="" />
                            </a>
                        </li> -->
                        <?php
                    }
                }
                ?>
            </ul>
        </div>
    </div>
</div>
<script type="text/javascript">
    $("#frm_powerluvs .stripe-button-el").html('Buy powerluvs');
    $("#frm_powerluvs .stripe-button-el").attr('class', 'color-btn');
    $(document).on('change', '#profile_picture', function () {
        $("#frm_change_profile_pic").submit();
    });
</script>