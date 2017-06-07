<link href="<?php echo base_url(); ?>assets/css/bootstrap-select.min.css" rel="stylesheet" />
<div class="my-account">
    <?php
    $sess_user_data = $this->session->userdata('user');
    $message = $this->session->flashdata('message');
    if (!empty($message)) {
        echo '<div class="' . $message['class'] . '">' . $message['message'] . '</div>';
    }
    $this->load->view('side_bar_account');
    $user_data = $this->session->userdata('user');
    $username = (!empty($user_data['user_name'])) ? ucfirst($user_data['user_name']) : $user_data['instagram_username'];
    $allow_more_location = false;
    if ($is_subscriber == true) {
        $allow_more_location = true;
    }
    ?>
    <link href="<?php echo base_url(); ?>assets/jquery-ui/jquery-ui-1.10.1.custom.min.css" rel="stylesheet"/>
    <?php
    if (!empty($notificationSettings['age_range'])) {
        $agerange = str_replace(' ', '', $notificationSettings['age_range']);
        $age_range = explode("-", $agerange);
    }
    ?>
    <style type="text/css">
        #slider-range1{background:#51ca94;}
        .ui-widget-header{background:#fff;}
        #slider-range2{background:#fff;}
        #slider-range2 .ui-widget-header{background:#51ca94;}
    </style>
    <div class="col-md-8 col-sm-8 col-xs-12 account-r">
        <div class="account-r-head"><h2><big><?php echo $username; ?></big></h2></div>
        <div class="account-r-body">
            <?php
            if ($this->session->flashdata('error')) {
                echo '<div class="alert alert-danger">' . $this->session->flashdata('error') . '</div>';
            }
            if ($this->session->flashdata('success')) {
                echo '<div class="alert alert-success">' . $this->session->flashdata('success') . '</div>';
            }
            $all_errors = validation_errors();
            if ($all_errors != '') {
                echo '<div class="alert alert-danger">';
                echo $all_errors;
                echo '</div>';
            }
            if ($this->session->flashdata('error1'))
                echo '<div class="alert alert-danger">' . $this->session->flashdata('error1') . '</div>';
            if ($this->session->flashdata('error2'))
                echo '<div class="alert alert-danger">' . $this->session->flashdata('error2') . '</div>';
            ?>
            <div class="account-body-head">
                <h2 class="account-title">Settings</h2>
                <p>&nbsp;</p>
            </div>

            <?php
            $instagram_id = $sess_user_data['userid'];
            $facebook_id = $sess_user_data['facebook_id'];
            $loginwith = $sess_user_data['loginwith'];

            if (!empty($instagram_id) && !empty($facebook_id)) {
                $link_insta = base_url() . 'user/unlink_account/instagram';
                $link_fb = base_url() . 'user/unlink_account/facebook';

                $link_insta_text = 'Unlink';
                $link_fb_text = 'Unlink';
            } else if (!empty($facebook_id)) {

                $link_insta = $insta_login_url;
                $link_fb = '#';

                $link_insta_text = 'Link';
                $link_fb_text = 'Connected';
            } else if ($instagram_id) {

                $link_insta = '#';
                $link_fb = $fb_login_url;

                $link_insta_text = 'Connected';
                $link_fb_text = 'Link';
            }
            ?>


            <div class="col-md-12 col-sm-12 col-xs-12 mar-btm-20 social-connect">
                <div class="facebook-connet">
                    <span>
                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"viewBox="0 0 490 490" style="enable-background:new 0 0 490 490;" xml:space="preserve"> <g id="XMLID_21_"> <g> <g> <path d="M460,0H30C13.458,0,0,13.458,0,30v430c0,16.542,13.458,30,30,30h430c16.542,0,30-13.458,30-30V30 C490,13.458,476.542,0,460,0z M470,460c0,5.514-4.486,10-10,10H30c-5.514,0-10-4.486-10-10V30c0-5.514,4.486-10,10-10h430 c5.514,0,10,4.486,10,10V460z"/> <path d="M345,180h-50v-49.965c0.076-0.015,0.151-0.027,0.219-0.035H335c5.523,0,10-4.477,10-10V50c0-5.522-4.477-10-10-10h-60 c-41.268,0-60.95,22.039-70.194,40.528C195.111,99.916,195,119.189,195,120v60h-50c-5.523,0-10,4.477-10,10v80 c0,5.523,4.477,10,10,10h40v155c0,5.523,4.477,10,10,10h90c5.523,0,10-4.477,10-10V270h40c4.977,0,9.196-3.659,9.899-8.586l10-70 c0.41-2.87-0.445-5.776-2.345-7.966C350.655,181.258,347.899,180,345,180z M326.327,250H285c-5.523,0-10,4.477-10,10v165h-70V270 c0-5.522-4.477-10-10-10h-40v-60h50c5.523,0,10-4.477,10-10v-69.969c0.001-0.158,0.189-15.92,7.977-31.119 C232.81,69.728,250.313,60,275,60h50v50h-30c-9.695,0-20,7.009-20,20v60c0,5.523,4.477,10,10,10h48.47L326.327,250z"/> </g> </g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> </svg>
                    </span>
                    <p>Connect With<br/>Facebook</p>
                    <a href="<?php echo $link_fb; ?>"><?php echo $link_fb_text; ?></a>
                </div>
                <div class="instagram-connet">
                    <span>
                        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"width="30px" height="30px" viewBox="0 0 48 48" style="enable-background:new 0 0 48 48;" xml:space="preserve"> <g> <g id="Instagram"> <path d="M5.583,13c-0.553,0-1-0.447-1-1V3c0-0.553,0.447-1,1-1s1,0.447,1,1v9C6.583,12.553,6.136,13,5.583,13z"/> <path d="M8.958,13c-0.553,0-1-0.447-1-1V2.25c0-0.553,0.447-1,1-1s1,0.447,1,1V12C9.958,12.553,9.511,13,8.958,13z"/> <path d="M12.333,13c-0.553,0-1-0.447-1-1V2.25c0-0.553,0.447-1,1-1s1,0.447,1,1V12C13.333,12.553,12.886,13,12.333,13z"/> <path d="M37.292,48H11.917C4.344,48,0,44.014,0,37.062V12.125C0,4.873,4.27,0,10.625,0h27.584C42.918,0,48,4.524,48,11.834v25.083 C48,43.132,43.297,48,37.292,48z M10.625,2C4.259,2,2,7.454,2,12.125v24.938C2,44.449,7.393,46,11.917,46h25.375 C42.257,46,46,42.095,46,36.917V11.834C46,5.76,41.956,2,38.209,2H10.625z"/> <path d="M23.999,15.25c-4.234,0-7.667,3.434-7.667,7.668c0,4.234,3.433,7.666,7.667,7.666c4.233,0,7.667-3.432,7.667-7.666 C31.666,18.684,28.232,15.25,23.999,15.25z M23.999,26.75c-2.117,0-3.834-1.716-3.834-3.833s1.717-3.834,3.834-3.834 s3.834,1.717,3.834,3.834S26.116,26.75,23.999,26.75z"/> <path d="M23.999,35.084c-6.709,0-12.167-5.458-12.167-12.167S17.29,10.75,23.999,10.75s12.167,5.458,12.167,12.167 S30.708,35.084,23.999,35.084z M23.999,12.75c-5.606,0-10.167,4.561-10.167,10.167c0,5.606,4.561,10.167,10.167,10.167 c5.606,0,10.167-4.561,10.167-10.167C34.166,17.311,29.605,12.75,23.999,12.75z"/> <rect x="32.999" y="14.92" width="14" height="2.16"/> <rect x="1.499" y="14.949" width="13.25" height="2.102"/> <path d="M41.333,10.133c0,1.215-0.985,2.201-2.202,2.201h-3.264c-1.217,0-2.202-0.986-2.202-2.201V6.867 c0-1.215,0.985-2.201,2.202-2.201h3.264c1.217,0,2.202,0.986,2.202,2.201V10.133z"/> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> <g> </g> </svg>
                    </span>
                    <p>Connect With<br/>Instagram</p>
                    <a href="<?php echo $link_insta; ?>"><?php echo $link_insta_text; ?></a>
                </div>
            </div>            

            <div class="account-body-body my-profile">
                <form id="updatenotificationsettingsform" method="post" class="form-horizontal" action="<?php echo base_url(); ?>user/user_settings">
                    <ul class="preferences-ul">
                        <li>
                            <span><label for="is_universal_profile">Global Profile</label></span>
                            <label class="switch">
                                <input type="checkbox" id="is_universal_profile" name="is_universal_profile" <?php echo ($notificationSettings['is_universal_profile'] == 1) ? "checked" : ""; ?> value="<?php echo $notificationSettings['is_universal_profile']; ?>" onchange="setMyValue(this);"/><div class="slider round"></div>
                            </label>
                        </li>
                        <li>
                            <span><label for="is_visibility">Show me on Luvr</label></span>
                            <label class="switch">
                                <input type="checkbox" id="is_visibility" name="is_visibility" <?php echo ($notificationSettings['is_visibility'] == 1) ? "checked" : ""; ?> value="<?php echo $notificationSettings['is_visibility']; ?>" onchange="setMyValue(this);"/><div class="slider round"></div>
                            </label>
                        </li>
                        <li>
                            <span><label for="is_new_match">New matches</label></span>
                            <label class="switch">
                                <input type="checkbox" id="is_new_match" name="is_new_match" <?php echo ($notificationSettings['is_new_match'] == 1) ? "checked" : ""; ?> value="<?php echo $notificationSettings['is_new_match']; ?>" onchange="setMyValue(this);"/><div class="slider round"></div>
                            </label>
                        </li>
                        <li>
                            <span><label for="is_allow_messages">Messages</label></span>
                            <label class="switch">
                                <input type="checkbox" id="is_allow_messages" name="is_allow_messages" <?php echo ($notificationSettings['is_allow_messages'] == 1) ? "checked" : ""; ?> value="<?php echo $notificationSettings['is_allow_messages']; ?>" onchange="setMyValue(this);"/><div class="slider round"></div>
                            </label>
                        </li>
                        <li>
                            <span><label for="is_allow_power_likes">Power Likes</label></span>
                            <label class="switch">
                                <input type="checkbox" id="is_allow_power_likes" name="is_allow_power_likes" <?php echo ($notificationSettings['is_allow_power_likes'] == 1) ? "checked" : ""; ?> value="<?php echo $notificationSettings['is_allow_power_likes']; ?>" onchange="setMyValue(this);"/><div class="slider round"></div>
                            </label>
                        </li>
                    </ul>
                    <ul class="preferences-ul">                        
                        <li>
                            <div class="clearfix">
                                <span><label>Max. Distance</label></span>
                                <span id="slider-range1-amount" class="pull-right"><?php echo $userInfo['radius']; ?> Miles</span>
                            </div>
                            <div class="clearfix">
                                <div id="slider-range1" class="slider bg-blue"></div>
                                <div class="slider-value">
                                    <input type="hidden" id="hdn_radius" name="hdn_radius" value="<?php echo $userInfo['radius']; ?>"/>
                                </div>
                            </div>
                        </li>                        
                        <li>
                            <div class="clearfix">
                                <span><label for="age_range">Age Range</label></span>
                                <span id="slider-range2-amount" class="pull-right"><?php echo $notificationSettings['age_range']; ?></span>
                            </div>
                            <div class="clearfix">
                                <div id="slider-range2" class="slider bg-blue"></div>
                                <div class="slider-value">
                                    <input type="hidden" id="hdn_age_range" name="hdn_age_range" value="<?php echo $notificationSettings['age_range']; ?>"/>
                                </div>
                            </div>
                        </li>
                    </ul>

                    <div class="col-md-6 col-sm-6 col-xs-12 mar-btm-20">
                        <div class="input-wrapper">
                            <?php echo form_label('Gender:', 'gender'); ?>
                            <div class="clearfix"></div>
                            <select class="selectpicker" name="interest" id="interest">
                                <option value="male" <?php echo ($notificationSettings['interest'] == "male") ? "selected" : ""; ?>>Male</option>
                                <option value="female" <?php echo ($notificationSettings['interest'] == "female") ? "selected" : ""; ?>>Female</option>
                                <option value="male,female" <?php echo ($notificationSettings['interest'] == "male,female") ? "selected" : ""; ?>>Both</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-6 col-xs-12 mar-btm-20">
                        <div class="input-wrapper input-addres-div">
                            <?php echo form_label('Address:', 'address'); ?>
                            <?php if (count($userAddresses) < 5 && !empty($userAddresses)) { ?>
                                <button type="button" title="Add more locations" class="btn btn-success" onclick="cloneElement(this);">+</button>
                            <?php } ?>
                            <div class="clearfix"></div>
                            <?php if ($allow_more_location == true && empty($userAddresses)) { ?>
                                <button type="button" title="Add more locations" class="btn btn-success" onclick="cloneElement(this);">+</button>
                            <?php } ?>
                            <?php if ($allow_more_location == true) { ?>
                                <div id="locations">
                                    <div class="locations-wrap">
                                        <?php if (!empty($userAddresses)) { ?>
                                            <?php foreach ($userAddresses as $add_block) { ?>
                                                <?php if ($userInfo['location_id'] == $add_block['id']) { ?>
                                                    <input type="hidden" id="hdn_default_address" name="hdn_default_address" value="<?php echo $add_block['location_name']; ?>"/>
                                                <?php } ?>
                                                <div class="location-block">
                                                    <input type="radio" name="same" onchange="setThisValue(this);" <?php echo ($userInfo['location_id'] == $add_block['id']) ? "checked" : ""; ?>/>
                                                    <input type="hidden" name="latlongs[]" value="<?php echo $add_block['latlong']; ?>"/>
                                                    <input type="text" id="address" name="address[]" class="locationboxes" value="<?php echo $add_block['location_name']; ?>"/>
                                                </div>
                                            <?php } ?>
                                        <?php } else { ?>
                                            <input type="hidden" id="hdn_default_address" name="hdn_default_address" value="<?php echo $userInfo['address']; ?>"/>
                                            <div class="location-block">
                                                <input type="radio" name="same" onchange="setThisValue(this);"/>
                                                <input type="hidden" name="latlongs[]" value="<?php echo $userInfo['latlong']; ?>"/>
                                                <input type="text" id="address" name="address[]" class="locationboxes" value="<?php echo $userInfo['address']; ?>"/>
                                            </div>
                                        <?php } ?>
                                    </div>
                                </div>
                            <?php } else { ?>
                                <input type="text" id="address" name="address[]" value="<?php echo $userInfo['address']; ?>"/>
                            <?php } ?>
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12 text-center mar-btm-20">
                        <button type="submit" class="color-btn">Save</button>
                        <button type="reset" class="dark-btn">Reset</button>
                    </div>
                </form>
            </div>
        </div>
        <div class="ad-video-h">
            <div class="video-box-add"><div id="spplayer1"></div></div>
            <div class="video-box-add"><div id="spplayer2"></div></div>
            <div class="video-box-add"><div id="spplayer3"></div></div>
        </div>
    </div>
</div>
<div id="map"></div>
<script src="<?php echo base_url(); ?>assets/js/bootstrap-select.min.js"></script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAP_API; ?>&libraries=places&callback=initMap" async defer></script>
<script src="<?php echo base_url(); ?>assets/jquery-ui/jquery-ui-1.10.1.custom.min.js" type="text/javascript"></script>
<script type="text/javascript">
                                                    $(document).ready(function () {
                                                        $("#slider-range1").slider({
                                                            range: "max",
                                                            min: 1,
                                                            max: 100,
                                                            value: <?php echo $userInfo['radius']; ?>,
                                                            slide: function (event, ui) {
                                                                $("#slider-range1-amount").html("&nbsp;" + ui.value + " Miles");
                                                                $("#hdn_radius").val(ui.value);
                                                            }
                                                        });
                                                        $("#slider-range2").slider({
                                                            range: true,
                                                            min: 18,
                                                            max: 100,
                                                            values: [<?php echo $age_range[0]; ?>, <?php echo $age_range[1]; ?>],
                                                            slide: function (event, ui) {
                                                                $("#slider-range2-amount").html("&nbsp;" + ui.values[0] + " - " + ui.values[1]);
                                                                $("#hdn_age_range").val(ui.values[0] + " - " + ui.values[1]);
                                                            }
                                                        });
                                                    });
                                                    function initMap() {
                                                        var map = new google.maps.Map(document.getElementById('map'), {center: {lat: -33.8688, lng: 151.2195}, zoom: 13});
                                                        var input = document.getElementById('address');
                                                        var autocomplete = new google.maps.places.Autocomplete(input);
                                                        // Bind the map's bounds (viewport) property to the autocomplete object,
                                                        // so that the autocomplete requests use the current map bounds for the
                                                        // bounds option in the request.
                                                        autocomplete.bindTo('bounds', map);
                                                        google.maps.event.addListener(autocomplete, 'place_changed', function () {
                                                            var place = autocomplete.getPlace();
                                                            $('#address').parent().find("input[type='hidden']").val(place.geometry.location.lat() + "," + place.geometry.location.lng());
                                                        });
                                                    }
                                                    function setMyValue(obj) {
                                                        if ($(obj).is(":checked")) {
                                                            $(obj).val(1);
                                                        } else {
                                                            $(obj).val(0);
                                                        }
                                                    }
                                                    function cloneElement(obj) {
                                                        if ($(".locationboxes").length < 5)
                                                        {
                                                            var map = new google.maps.Map(document.getElementById('map'));
                                                            $("#locations").append('<div class="location-block"><input type="radio" name="same" onchange="setThisValue(this);"/><input type="hidden" name="latlongs[]"/><input type="text" id="address" class="locationboxes" name="address[]" placeholder="Enter a location"/></div>');
                                                            var i = 2;
                                                            $(".locationboxes").each(function () {
                                                                $(this).attr("id", "address_" + i);
                                                                var obj = document.getElementById('address_' + i);
                                                                var tmp = new google.maps.places.Autocomplete(obj);
                                                                tmp.bindTo('bounds', map);
                                                                google.maps.event.addListener(tmp, 'place_changed', function () {
                                                                    var place = tmp.getPlace();
                                                                    /*console.log(place.name);*/
                                                                    $(obj).parent().find("input[type='hidden']").val(place.geometry.location.lat() + "," + place.geometry.location.lng());
                                                                });
                                                                i++;
                                                            });
                                                        }
                                                    }
                                                    function setThisValue(obj)
                                                    {
                                                        if ($.trim($(obj).parent().find("input[type='text']").val()).length > 0)
                                                            $('#hdn_default_address').val($(obj).parent().find("input[type='text']").val());
                                                    }
</script>