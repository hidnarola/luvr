<?php
$user_data = $this->session->userdata('user');
if ($user_swipes_per_day >= MAX_SWIPES_PER_DAY) {
    echo '<div class="alert alert-danger alert-dismissable">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        Your likes quota per day has been reached! Therefore, right swipes for cards will not be considered.</div>';
}
$max_powerluvs = MAX_POWERLUVS_PER_DAY;
$pl_onclick = "onclick=\"$('#tinderslide').jTinder('powerluv');\"";
if ($is_user_premium_member == 1) {
    $max_powerluvs = MAX_POWERLUVS_PER_DAY_P;
}
if ($user_powerluvs_per_day >= $max_powerluvs) {
    $pl_onclick = "";
    echo '<div class="alert alert-danger alert-dismissable">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
       Your power luvs quota per day has been reached! Therefore, further power luvs will not be considered.</div>';
}
$path = $href = "";
if ($user_profile['media_type'] == 0 && !empty($user_profile['media_thumb'])) {
    $path = $user_profile['media_thumb'];
    $href = $user_profile['media_name'];
} else if ($user_profile['media_type'] == 1 || $user_profile['media_type'] == 2) {
    $path = base_url() . "assets/images/users/" . $user_profile['media_thumb'];
    if (!file_exists(PHYSICALUPLOADPATH . "/images/users/" . $user_profile['media_thumb']))
        $path = base_url() . "assets/images/big_avatar.jpg";
    $href = base_url() . "assets/images/users/" . $user_profile['media_name'];
} else if ($user_profile['media_type'] == 3 || $user_profile['media_type'] == 4) {
    $path = $user_profile['media_thumb'];
    $href = $user_profile['media_name'];
}

$distance = null;
if (!empty($latlong) && !empty($db_user_data['latlong'])) {
    $loc1 = explode(",", $latlong);
    $lat1 = (double) $loc1[0];
    $lon1 = (double) $loc1[1];
    $loc2 = explode(",", $db_user_data['latlong']);
    $lat2 = (double) $loc2[0];
    $lon2 = (double) $loc2[1];
    if (!empty($loc1) && !empty($loc2)) {
        $distance = distance($lat1, $lon1, $lat2, $lon2, "K");
        $distance = number_format($distance, 2);
    }
}
$md = "";
if ($mode == 1)
    $md = "like";
else if ($mode == 2)
    $md = "luv";
?>
<div class='alert alert-info'>Swipe right again to give them your final approval or swipe left to pass!</div>
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="user-list">
        <div class="bg-name">luvr</div>
        <div class="user-list-l">
            <div class="user-list-pic">
                <div id="tinderslide" style="visibility:hidden;">
                    <ul>
                        <li class="panel" data-id="<?php echo $user_profile['userid']; ?>">
                            <div class="user-list-pic-wrapper">
                                <div class="user-list-pic-bg">
                                    <a style="background:url('<?php echo $path; ?>') no-repeat scroll center center;" class="img"></a>
                                    <?php if ($user_profile['media_type'] == 2 || $user_profile['media_type'] == 4) { ?>
                                        <a class='play-btn' data-fancybox href="<?php echo $href; ?>"></a>
                                    <?php } ?>
                                </div>
                                <div class="user-list-pic-close">
                                    <a class="for_pointer" onclick="$('#tinderslide').jTinder('dislike');">
                                        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                             viewBox="0 0 371.23 371.23" style="enable-background:new 0 0 371.23 371.23;" xml:space="preserve">
                                            <polygon points="371.23,21.213 350.018,0 185.615,164.402 21.213,0 0,21.213 164.402,185.615 0,350.018 21.213,371.23 
                                                     185.615,206.828 350.018,371.23 371.23,350.018 206.828,185.615 "/>
                                            <g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g>
                                        </svg>
                                    </a>
                                </div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="user-list-r">
            <h2>
                <big id="right_username">
                    <?php
                    echo $db_user_data['user_name'];
                    ?>
                </big>
                <small id="right_oneliner">
                    <?php
                    echo $db_user_data['one_liner'];
                    ?>
                </small>
            </h2>
            <p id="right_bio">
                <?php
                echo $db_user_data['bio'];
                ?>
            </p>
            <ul class="user-info">
                <li id="right_age">Age (<?php
                    echo $db_user_data['age'];
                    ?>)</li>
                <li id="right_location">Location : <?php echo (!empty($db_user_data['address'])) ? $db_user_data['address'] : "N/A"; ?></li>
                <li id="right_distance">Distance : (<?php echo ($distance != null) ? $distance : "N/A"; ?> km)</li>
            </ul>
        </div>
    </div>
</div>
<style type="text/css">
    .inner-content{position:relative;}
</style>
<script src="<?php echo base_url() . 'assets/js/jquery.transform2d.js'; ?>" type="text/javascript"></script>
<script src="<?php echo base_url() . 'assets/js/jquery.jTinder.js'; ?>" type="text/javascript"></script>
<script type="text/javascript">
    var likedislikecounts = 0;
    $(window).on('load', function () {
        setTimeout(function () {
            /*$("#radar").hide();*/
            $("#loader").fadeOut();
            $("#tinderslide").removeAttr('style');
        }, Math.floor((Math.random() * 1000) + 1000));
    });
    var likesreached = powerluvsreached = 0;
    registerjTinder();
    function registerjTinder() {
        $("#tinderslide").jTinder({
            onLike: function (item) {
<?php if ($user_swipes_per_day < MAX_SWIPES_PER_DAY) { ?>
                    likedislikeuser($(item).data("id"), '<?php echo $md; ?>');
<?php } ?>
            },
            onDislike: function (item) {
                likedislikeuser($(item).data("id"), 'dislike');
            },
            animationRevertSpeed: 200,
            animationSpeed: 500,
            threshold: 4,
            likeSelector: '.like',
            dislikeSelector: '.dislike'
        });
    }
    function likedislikeuser(user_id, mode, li_index) {
        $.ajax({
            url: "<?php echo base_url(); ?>match/likedislike",
            type: 'POST',
            dataType: 'json',
            data: "user_id=" + user_id + "&status=" + mode + "&totallikesreached=" + likesreached,
            success: function (data) {
                likedislikecounts++;
                if (data.success == true) {
                    location.href = "<?php echo base_url('/match/nearby'); ?>";
                }
                if ((data.user_swipes_per_day == <?php echo MAX_SWIPES_PER_DAY; ?>) && mode == "like")
                {
                    likesreached = 1;
                    showMsg("Your likes quota per day has been reached! Therefore, right swipes for cards will not be considered.", "alert alert-danger");
                    scrollToElement("#msg_txt");
                }
<?php if ($is_user_premium_member == 1) { ?>
                    if ((data.user_powerluvs_per_day == <?php echo MAX_POWERLUVS_PER_DAY_P; ?>) && mode == "powerluv")
                    {
                        powerluvsreached = 1;
                        reflectUserInfo(li_index);
                        showMsg("Your power luvs quota per day has been reached! Therefore, further power luvs will not be considered.", "alert alert-danger");
                        scrollToElement("#msg_txt");
                    }
<?php } else { ?>
                    if ((data.user_powerluvs_per_day == <?php echo MAX_POWERLUVS_PER_DAY; ?>) && mode == "powerluv")
                    {
                        powerluvsreached = 1;
                        reflectUserInfo(li_index);
                        showMsg("Your power luvs quota per day has been reached! Therefore, further power luvs will not be considered.", "alert alert-danger");
                        scrollToElement("#msg_txt");
                    }
<?php } ?>
            }, error: function () {
                showMsg("Something went wrong!", "alert alert-danger", true);
                scrollToElement("#msg_txt");
            }
        });
    }
</script>
<script src="<?php echo base_url() . 'assets/js/jquery.fancybox.min.js'; ?>" type="text/javascript"></script>