<div class="my-account">
    <?php
    $this->load->view('side_bar_account');
    $user_data = $this->session->userdata('user');
    $username = (!empty($user_data['user_name'])) ? ucfirst($user_data['user_name']) : $user_data['instagram_username'];
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
            <div class="account-body-head">
                <h2 class="account-title">Settings</h2>
                <p>&nbsp;</p>
            </div>
            <?php
            $all_errors = validation_errors();
            if ($all_errors != '') {
                echo '<div class="alert alert-danger">';
                echo $all_errors;
                echo '</div>';
            }
            if ($this->session->flashdata('success'))
                echo '<div class="alert alert-success">' . $this->session->flashdata('success') . '</div>';
            if ($this->session->flashdata('error1'))
                echo '<div class="alert alert-danger">' . $this->session->flashdata('error1') . '</div>';
            if ($this->session->flashdata('error2'))
                echo '<div class="alert alert-danger">' . $this->session->flashdata('error2') . '</div>';
            ?>
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
                            <span><label for="address">Location</label></span>
                            <div class="input-wrapper">
                                <input type="text" id="address" name="address" value="<?php echo $userInfo['address']; ?>"/>
                            </div>
                        </li>
                        <li>
                            <div class="clearfix">
                                <span><label>Maximum Distance</label></span>
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
                            <span><label for="interest">Gender</label></span>
                            <div class="input-wrapper">
                                <select class="form-control" name="interest" id="interest">
                                    <option value="male" <?php echo ($notificationSettings['interest'] == "male") ? "selected" : ""; ?>>Male</option>
                                    <option value="female" <?php echo ($notificationSettings['interest'] == "female") ? "selected" : ""; ?>>Female</option>
                                    <option value="male,female" <?php echo ($notificationSettings['interest'] == "male,female") ? "selected" : ""; ?>>Both</option>
                                </select>
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
                    <div class="col-md-12 col-sm-12 col-xs-12 text-center mar-btm-20">
                        <button type="submit" class="color-btn">Save</button>
                        <button type="reset" class="dark-btn">Reset</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="map"></div>
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
                                    }
                                    function setMyValue(obj) {
                                        if ($(obj).is(":checked")) {
                                            $(obj).val(1);
                                        } else {
                                            $(obj).val(0);
                                        }
                                    }
</script>