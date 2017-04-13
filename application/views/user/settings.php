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
<link href="<?php echo base_url(); ?>assets/jquery-ui/jquery-ui-1.10.1.custom.min.css" rel="stylesheet"/>
<h2>Settings</h2>
<hr>
<?php
if (!empty($notificationSettings['age_range'])) {
    $agerange = str_replace(' ', '', $notificationSettings['age_range']);
    $age_range = explode("-", $agerange);
}
?>
<form id="updatenotificationsettingsform" method="post" class="form-horizontal" action="<?php echo base_url(); ?>user/user_settings">
    <table class="table-responsive table-condensed">
        <tr>
            <td><label for="is_universal_profile">Global Profile</label></td>
            <td>
                <label class="switch">
                    <input type="checkbox" id="is_universal_profile" name="is_universal_profile" <?php echo ($notificationSettings['is_universal_profile'] == 1) ? "checked" : ""; ?> value="<?php echo $notificationSettings['is_universal_profile']; ?>" onchange="setMyValue(this);"/><div class="slider round"></div>
                </label>
            </td>
        </tr>
        <tr>
            <td><label for="address">Location</label></td>
            <td>
                <input type="text" id="address" name="address" class="form-control" value="<?php echo $userInfo['address']; ?>"/>
            </td>
        </tr>
        <tr>
            <td><label for="age_range">Maximum Distance</label></td>
            <td>
                <div id="slider-range1" class="slider bg-blue"></div>
            </td>
            <td>
                <div class="slider-value">
                    <span id="slider-range1-amount"><?php echo $userInfo['radius']; ?> Miles</span>
                    <input type="hidden" id="hdn_radius" name="hdn_radius" value="<?php echo $userInfo['radius']; ?>"/>
                </div>
            </td>
        </tr>
        <tr>
            <td><label for="interest">Gender</label></td>
            <td>
                <select class="form-control" name="interest" id="interest">
                    <option value="male" <?php echo ($notificationSettings['interest'] == "male") ? "selected" : ""; ?>>Male</option>
                    <option value="female" <?php echo ($notificationSettings['interest'] == "female") ? "selected" : ""; ?>>Female</option>
                    <option value="male,female" <?php echo ($notificationSettings['interest'] == "male,female") ? "selected" : ""; ?>>Both</option>
                </select>
            </td>
        </tr>
        <tr>
            <td><label for="age_range">Age Range</label></td>
            <td>
                <div id="slider-range2" class="slider bg-blue"></div>
            </td>
            <td>
                <div class="slider-value">
                    <span id="slider-range2-amount"><?php echo $notificationSettings['age_range']; ?></span>
                    <input type="hidden" id="hdn_age_range" name="hdn_age_range" value="<?php echo $notificationSettings['age_range']; ?>"/>
                </div>
            </td>
        </tr>
        <tr>
            <td><label for="is_visibility">Show me on Luvr</label></td>
            <td>
                <label class="switch">
                    <input type="checkbox" id="is_visibility" name="is_visibility" <?php echo ($notificationSettings['is_visibility'] == 1) ? "checked" : ""; ?> value="<?php echo $notificationSettings['is_visibility']; ?>" onchange="setMyValue(this);"/><div class="slider round"></div>
                </label>
            </td>
        </tr>
        <tr>
            <td><label for="is_new_match">New matches</label></td>
            <td>
                <label class="switch">
                    <input type="checkbox" id="is_new_match" name="is_new_match" <?php echo ($notificationSettings['is_new_match'] == 1) ? "checked" : ""; ?> value="<?php echo $notificationSettings['is_new_match']; ?>" onchange="setMyValue(this);"/><div class="slider round"></div>
                </label>
            </td>
        </tr>
        <tr>
            <td><label for="is_allow_messages">Messages</label></td>
            <td>
                <label class="switch">
                    <input type="checkbox" id="is_allow_messages" name="is_allow_messages" <?php echo ($notificationSettings['is_allow_messages'] == 1) ? "checked" : ""; ?> value="<?php echo $notificationSettings['is_allow_messages']; ?>" onchange="setMyValue(this);"/><div class="slider round"></div>
                </label>
            </td>
        </tr>
        <tr>
            <td><label for="is_allow_power_likes">Power Likes</label></td>
            <td>
                <label class="switch">
                    <input type="checkbox" id="is_allow_power_likes" name="is_allow_power_likes" <?php echo ($notificationSettings['is_allow_power_likes'] == 1) ? "checked" : ""; ?> value="<?php echo $notificationSettings['is_allow_power_likes']; ?>" onchange="setMyValue(this);"/><div class="slider round"></div>
                </label>
            </td>
        </tr>
    </table>
    <input type="submit" class="btn btn-primary" value="Save"/>
</form>
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