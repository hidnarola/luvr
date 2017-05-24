<?php
$user_data = $this->session->userdata('user');
$show_ad = true;
$IsPowerLuvsAllowed = IsPowerLuvsAllowed($user_data['id']);
if (!empty($user_data)) {
    if ($is_user_premium_member == 1) {
        $show_ad = false;
    }
}
if ($_SERVER['HTTP_HOST'] == 'dev.luvr.me' && $show_ad == true) {
    ?>
    <script data-cfasync="false" type="text/javascript" src="http://www.tradeadexchange.com/a/display.php?r=1572461"></script>
<?php } else if ($_SERVER['HTTP_HOST'] == 'luvr.me' && $show_ad == true) { ?>
    <script data-cfasync="false" type="text/javascript" src="http://www.tradeadexchange.com/a/display.php?r=1575965"></script>
<?php } ?>
<?php
if ($user_swipes_per_day >= MAX_SWIPES_PER_DAY) {
    echo '<div class="alert alert-danger alert-dismissable">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        Your likes quota per day has been reached! Therefore, right swipes for cards will not be considered.</div>';
}
$max_powerluvs = MAX_POWERLUVS_PER_DAY;
if ($is_user_premium_member == 1) {
    $max_powerluvs = MAX_POWERLUVS_PER_DAY_P;
}
if ($IsPowerLuvsAllowed == 1) {
    $max_powerluvs = $max_powerluvs + 5;
}
if ($user_powerluvs_per_day >= $max_powerluvs) {
    echo '<div class="alert alert-danger alert-dismissable">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
       Your power luvs quota per day has been reached! Therefore, further power luvs will not be considered.</div>';
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
if (!empty($user_profile) && !empty($db_user_data)) {
    ?>
    <div class='alert alert-info'>Swipe right again to give them your final approval or swipe left to pass!</div>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="user-list">
            <div class="bg-name">luvr</div>
            <div class="user-list-l">
                <div class="user-list-l-l">
                    <div class="user-list-pic">
                        <div id="tinderslide" style="visibility:hidden;">
                            <ul>
                                <?php
                                if (!empty($user_profile)) {
                                    foreach ($user_profile as $up) {
                                        if ($up['id'] == $db_user_data['profile_media_id']) {
                                            $path = $href = "";
                                            if ($up['media_type'] == 0 && !empty($up['media_thumb'])) {
                                                $path = $up['media_thumb'];
                                                $href = $up['media_name'];
                                            } else if ($up['media_type'] == 1 || $up['media_type'] == 2) {
                                                /* $path = base_url() . "assets/images/users/" . $up['media_thumb'];
                                                  if (!file_exists(PHYSICALUPLOADPATH . "/images/users/" . $up['media_thumb']))
                                                  $path = base_url() . "assets/images/big_avatar.jpg";
                                                  $href = base_url() . "assets/images/users/" . $up['media_name']; */
                                                if ($up['media_type'] == 1) {
                                                    $path = base_url() . 'bio/show_img/' . $up['media_thumb'] . "/1";
                                                    $href = base_url() . "bio/show_img/" . $up['media_name'];
                                                }
                                                if ($up['media_type'] == 2) {
                                                    $path = base_url() . 'bio/show_img/' . $up['media_thumb'] . "/1";
                                                    $href = base_url() . "video/play/" . $up['id'];
                                                }
                                            } else if ($up['media_type'] == 3 || $up['media_type'] == 4) {
                                                $path = $up['media_thumb'];
                                                $href = $up['media_name'];
                                                if ($up['media_type'] == 4) {
                                                    $href = base_url() . "video/play/" . $up['id'];
                                                }
                                            }
                                            ?>
                                            <li class="panel" data-id="<?php echo $up['userid']; ?>">
                                                <div class="user-list-pic-wrapper">
                                                    <?php if ($is_user_premium_member == 1) { ?>
                                                        <span class="_timestamp"><?php echo date("m/d/y", strtotime($up['insta_datetime'])); ?><br/><?php echo date("h:s a", strtotime($up['insta_datetime'])); ?></span>
                                                    <?php } ?>
                                                    <div class="user-list-pic-bg">
                                                        <a style="background:url('<?php echo $path; ?>') no-repeat scroll center center;" class="img"></a>
                                                        <?php if ($up['media_type'] == 2 || $up['media_type'] == 4) { ?>
                                                            <a class='play-btn-large icon-play-button' target="_blank" href="<?php echo $href; ?>"></a>
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
                                            <?php
                                        }
                                    }
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                </div>
                <div class="user-likes">
                    <a class="for_pointer" id="pass_user" title="Pass" onclick="$('#tinderslide').jTinder('dislike');">
                        <img src="<?php echo base_url(); ?>assets/images/pass.png" />
                    </a>
                    <a class="for_pointer" id="luv_user" title="Luv" onclick="$('#tinderslide').jTinder('luv');">
                        <img src="<?php echo base_url(); ?>assets/images/luv.png" />
                    </a>
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
                    <li id="right_age">Age : (<?php
                        echo $db_user_data['age'];
                        ?>)</li>
                    <li id="right_location">Location : <?php echo (!empty($db_user_data['address'])) ? $db_user_data['address'] : "N/A"; ?></li>
                    <li id="right_distance">Distance : (<?php echo ($distance != null) ? $distance : "N/A"; ?> km)</li>
                </ul>
            </div>
        </div>
    </div>
    <div class="col-md-12 col-sm-12 col-xs-12">
        <div class="account-body-body">
            <div class="account-body-head">
                <h2 class="account-title">Pictures/Videos</h2>
                <p>&nbsp;</p>
            </div>
            <ul class="my-picture-ul level2">
                <?php
                if (!empty($user_profile)) {
                    foreach ($user_profile as $up) {
                        if ($up['media_type'] != 0) {
                            $path = $href = "";
                            if ($up['media_type'] == 0 && !empty($up['media_thumb'])) {
                                $path = $up['media_thumb'];
                                $href = $up['media_name'];
                            } else if ($up['media_type'] == 1 || $up['media_type'] == 2) {
                                if ($up['media_type'] == 1) {
                                    $path = base_url() . 'bio/show_img/' . $up['media_thumb'] . "/1";
                                    $href = base_url() . "bio/show_img/" . $up['media_name'];
                                }
                                if ($up['media_type'] == 2) {
                                    $fname = replace_extension($up['media_thumb'], "png");
                                    $path = base_url() . 'bio/show_img/' . $fname . "/1";
                                    $href = base_url() . "video/play/" . $up['id'];
                                }
                            } else if ($up['media_type'] == 3 || $up['media_type'] == 4) {
                                $path = $up['media_thumb'];
                                $href = $up['media_name'];
                                if ($up['media_type'] == 4) {
                                    $href = base_url() . "video/play/" . $up['id'];
                                }
                            }
                            ?>
                            <li>
                                <div class="my-picture-box">
                                    <a><img src="<?php echo $path; ?>" onerror="this.src='<?php echo base_url() . "assets/images/placeholder.png"; ?>'"/></a>
                                    <?php if ($is_user_premium_member == 1) { ?>
                                        <span class="_timestamp"><?php echo date("m/d/y", strtotime($up['insta_datetime'])); ?><br/><?php echo date("h:s a", strtotime($up['insta_datetime'])); ?></span>
                                    <?php } ?>
                                    <div class="picture-action">
                                        <div class="picture-action-inr">
                                            <?php if ($up['media_type'] == 4) { ?>
                                                <a class='icon-play-button' target="_blank" href="<?php echo $href; ?>"></a>
                                            <?php } else if ($up['media_type'] == 2) { ?>
                                                <a class='icon-play-button' target="_blank" href="<?php echo $href; ?>"></a>
                                            <?php } else { ?>
                                                <a class="icon-full-screen image-link" data-fancybox="gallery" href="<?php echo $href; ?>"></a>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                            </li>
                            <?php
                        }
                    }
                }
                ?>
            </ul>
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
                                onLuv: function (item) {
                                    likedislikeuser($(item).data("id"), 'luv');
                                },
                                animationRevertSpeed: 200,
                                animationSpeed: 500,
                                threshold: '<?php echo (detect_browser() == 'mobile') ? 1 : 4; ?>',
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
                                    if (mode == "luv") {
                                        socket.emit('New Like Request Web', {
                                            'requestby_id': '<?php echo $user_data['id'] ?>',
                                            'requestto_id': user_id,
                                            'relation_status': 2
                                        });
                                    }
                                    if (data.success == true) {
                                        location.href = "<?php echo base_url('/match/nearby'); ?>";
                                    }
                                    if ((data.user_swipes_per_day == <?php echo MAX_SWIPES_PER_DAY; ?>) && mode == "like")
                                    {
                                        likesreached = 1;
                                        showMsg("Your likes quota per day has been reached! Therefore, right swipes for cards will not be considered.", "error");
                                        scrollToElement("#header");
                                    }
                                    if ((data.user_powerluvs_per_day == <?php echo $max_powerluvs; ?>) && mode == "powerluv")
                                    {
                                        powerluvsreached = 1;
                                        reflectUserInfo(li_index);
                                        showMsg("Your power luvs quota per day has been reached! Therefore, further power luvs will not be considered.", "error");
                                        scrollToElement("#header");
                                    }
                                }, error: function () {
                                    showMsg("Something went wrong!", "error", true);
                                    scrollToElement("#header");
                                }
                            });
                        }
    </script>
<?php } else { ?>
    <script type="text/javascript">
        socket.on('New Like Request', function (data) {
            console.log(data);
        });
        $(window).on('load', function () {
            setTimeout(function () {
                $("#loader").fadeOut();
                $("#loader-nodata .loader-container p").html("Something went wrong!");
                $("#loader-nodata").fadeIn();
                $("#tinderslide").removeAttr('style');
            }, Math.floor((Math.random() * 1000) + 1000));
        });
    </script>
<?php } ?>