<?php
$user_data = $this->session->userdata('user');
$success = $this->session->flashdata('success');
$IsPowerLuvsAllowed = IsPowerLuvsAllowed($user_data['id']);
if ($user_swipes_per_day >= MAX_SWIPES_PER_DAY) {
    echo '<div class="alert alert-danger alert-dismissable">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        Your likes quota per day has been reached! Therefore, right swipes for cards will not be considered.</div>';
}
$playlist[0] = array("file" => "" . $_SERVER['REQUEST_SCHEME'] . "://s3.ap-south-1.amazonaws.com/luvr/Videos/Commercials/vid1.mp4", "image" => "" . $_SERVER['REQUEST_SCHEME'] . "://s3.ap-south-1.amazonaws.com/luvr/Videos/Commercials/vid1.jpg");
$playlist[1] = array("file" => "" . $_SERVER['REQUEST_SCHEME'] . "://s3.ap-south-1.amazonaws.com/luvr/Videos/Commercials/vid2.mp4", "image" => "" . $_SERVER['REQUEST_SCHEME'] . "://s3.ap-south-1.amazonaws.com/luvr/Videos/Commercials/vid2.jpg");
$playlist[2] = array("file" => "" . $_SERVER['REQUEST_SCHEME'] . "://s3.ap-south-1.amazonaws.com/luvr/Videos/Commercials/vid3.mp4", "image" => "" . $_SERVER['REQUEST_SCHEME'] . "://s3.ap-south-1.amazonaws.com/luvr/Videos/Commercials/vid3.jpg");
$playlist = json_encode($playlist);
$ad_url = "https://vast.optimatic.com/vast/getVast.aspx?id=tI8OelBpLoQd&o=3&zone=default&pageURL=" . base_url(uri_string()) . "&pageTitle=BioVideo&cb=" . uniqid() . "";
$show_ad = true;
if (!empty($user_data)) {
    if (isUserActiveSubscriber($user_data['id']) == 1) {
        $show_ad = false;
    }
}
$max_powerluvs = MAX_POWERLUVS_PER_DAY;
$pl_onclick = "onclick=powerLuv();";
if ($is_user_premium_member == 1) {
    $max_powerluvs = MAX_POWERLUVS_PER_DAY_P;
}
if ($IsPowerLuvsAllowed == 1) {
    $max_powerluvs = $max_powerluvs + 5;
}
if ($user_powerluvs_per_day >= $max_powerluvs) {
    $pl_onclick = "";
    echo '<div class="alert alert-danger alert-dismissable">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
       Your power luvs quota per day has been reached! Therefore, further power luvs will not be considered.</div>';
}
if (!empty($nearByUsers)) {
    $i = 0;
    foreach ($nearByUsers as $nbu) {
        $distance = null;
        if (!empty($nbu['latlong'])) {
            $loc1 = explode(",", $latlong);
            $lat1 = (double) $loc1[0];
            $lon1 = (double) $loc1[1];
            $loc2 = explode(",", $nbu['latlong']);
            $lat2 = (double) $loc2[0];
            $lon2 = (double) $loc2[1];
            if (!empty($loc1) && !empty($loc2)) {
                $distance = distance($lat1, $lon1, $lat2, $lon2, "K");
                $distance = number_format($distance, 2);
            }
        }
        $nearByUsers[$i]['distance'] = $distance;
        $i++;
    }
    $lastObj = end($nearByUsers);
}
?>
<script type="text/javascript">
    var nearby_matches = <?php echo json_encode($nearByUsers); ?>
</script>
<?php if ($this->uri->segment(3) == "c") { ?>
    <script>
        $(window).load(function () {
            //$(".addvertise-img1 iframe").attr("sandbox","allow-scripts allow-forms allow-popups allow-popups-to-escape-sandbox allow-pointer-lock");


            //$(".addvertise-img1 iframe").contents().find("a:first").trigger('click');
            var ur = $(".addvertise-img1 iframe").contents().find("a:first").attr('href');
            log(ur);
            location.href = ur;
            //window.open(ur, '_blank');



            //$(".addvertise-img1 iframe").contents().find("a:first").click();
            /*var $iframe = $(".addvertise-img1 iframe").contents();
             $("body", $iframe).trigger("click");*/
        });
    </script>
<?php } ?>
<?php if ($_SERVER['HTTP_HOST'] == 'luvr.me' && $show_ad == true) { ?>
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<?php } ?>
<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12 ad-div text-center">
    <?php if ($_SERVER['HTTP_HOST'] == 'luvr.me' && $show_ad == true) { ?>
        <div class="addvertise-img1 adv">
            <!--<script data-cfasync="false" type="text/javascript" src="http://www.tradeadexchange.com/a/display.php?r=1582351"></script>-->
            <iframe src="//rcm-na.amazon-adsystem.com/e/cm?o=1&p=14&l=ur1&category=gift_certificates&banner=0S32YAVKXXKQGNQSSGG2&f=ifr&linkID=d657de821aa5bba1c26d3a3d5de1e99d&t=luvrweb-20&tracking_id=luvrweb-20" width="160" height="600" scrolling="no" border="0" marginwidth="0" style="border:none;" frameborder="0"></iframe>
        </div>
        <div class="addvertise-img2 adv">
            <!-- FMX Tag Start, Do Not Modify: WebsiteID 7177 > ZoneID 37428 > Size 160x600 -->
            <script type="text/javascript">document.write('<scr' + 'ipt type="text/javascript" src="' + (location.protocol == 'https:' ? 'https:' : 'http:') + '//x.fidelity-media.com/delivery/sjs.php?zoneid=37428&amp;cb=<?php echo uniqid(); ?>&amp;loc=<?php echo urlencode(base_url(uri_string())); ?>&amp;click=_blank"><\/scr' + 'ipt>');</script>
            <!-- FMX Tag End -->
        </div>
    <?php } ?>
</div>
<div class="col-lg-8 col-md-6 col-sm-6 col-xs-12 content-div">
    <div class="clearfix"></div>
    <button type="button" style="margin-top:20px;" class="btn btn-danger mar-btm-20" onclick="window.history.back();">Back</button>
    <div class="clearfix"></div>
    <div class="user-list">
        <div class="bg-name">luvr</div>
        <?php if (!empty($nearByUsers)) { ?>
            <div class="user-list-l">
                <div class="user-list-l-l">    
                    <div class="user-list-pic">
                        <div id="tinderslide" style="visibility:hidden;">
                            <ul>
                                <?php
                                $it = 1;
                                foreach ($nearByUsers as $user) {
                                    $path = $href = "";
                                    if ($user['media_type'] == 0 && !empty($user['media_thumb'])) {
                                        $path = $user['media_thumb'];
                                        $href = $user['user_profile'];
                                    } else if ($user['media_type'] == 1 || $user['media_type'] == 2) {
                                        /* $path = base_url() . "assets/images/users/" . $user['media_thumb'];
                                          if (!file_exists(PHYSICALUPLOADPATH . "/images/users/" . $user['media_thumb']))
                                          $path = base_url() . "assets/images/big_avatar.jpg";
                                          $href = base_url() . "assets/images/users/" . $user['user_profile']; */
                                        if ($user['media_type'] == 1) {
                                            $path = base_url() . 'bio/show_img/' . $user['media_thumb'] . "/1";
                                            $href = base_url() . "bio/show_img/" . $user['user_profile'];
                                        }
                                        if ($user['media_type'] == 2) {
                                            $fname = replace_extension($user['media_thumb'], "png");
                                            $path = base_url() . 'bio/show_img/' . $fname . "/1";
                                            $href = base_url() . "video/play/" . $user['mid'];
                                        }
                                    } else if ($user['media_type'] == 3 || $user['media_type'] == 4) {
                                        $path = $user['media_thumb'];
                                        $href = $user['user_profile'];
                                        if ($user['media_type'] == 4) {
                                            $href = base_url() . "video/play/" . $user['mid'];
                                        }
                                    }
                                    $timestamp_html = "";
                                    if ($is_user_premium_member == 1) {
                                        $timestamp_html = '<span class="_timestamp">' . date("m/d/y", strtotime($user['insta_datetime'])) . '<br/>' . date("h:s a", strtotime($user['insta_datetime'])) . '</span>';
                                    }
                                    echo '<li class="panel" data-id="' . $user['id'] . '">
                                            <div class="user-list-pic-wrapper">
                                                ' . $timestamp_html . '
                                                <div class="user-list-pic-bg">
                                                    <a style="background:url(\'' . $path . '\') no-repeat scroll center center;" class="img"></a>';
                                    if ($user['media_type'] == 2 || $user['media_type'] == 4) {
                                        echo '<a class="play-btn-large icon-play-button" target="_blank" href="' . $href . '"></a>';
                                    }
                                    echo '</div>
                                            <div class="user-list-pic-close">
                                            <a class="for_pointer" onclick="$(\'#tinderslide\').jTinder(\'dislike\');">
                                                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                     viewBox="0 0 371.23 371.23" style="enable-background:new 0 0 371.23 371.23;" xml:space="preserve">
                                                <polygon points="371.23,21.213 350.018,0 185.615,164.402 21.213,0 0,21.213 164.402,185.615 0,350.018 21.213,371.23 
                                                         185.615,206.828 350.018,371.23 371.23,350.018 206.828,185.615 "/>
                                                <g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g>
                                                </svg>
                                            </a>
                                        </div>
                                        </div>
                                        </li>';
                                    $it++;
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                    <div id="loader-pl" class="loader-style" style="background:none;display:none;">
                        <div class="loader-container">
                            <img src="<?php echo base_url(); ?>assets/images/loader.gif" />
                        </div>
                    </div>
                </div>
                <div class="user-likes">
                    <a class="for_pointer" id="rewind_user" title="Rewind" onclick="prevMatch(<?php echo $lastObj['id']; ?>)">
                        <img src="<?php echo base_url(); ?>assets/images/rewind.png" />
                    </a>
                    <a class="for_pointer" id="pass_user" title="Pass" onclick="$('#tinderslide').jTinder('dislike');">
                        <img src="<?php echo base_url(); ?>assets/images/pass.png" />
                    </a>
                    <a class="for_pointer" id="luv_user" title="Luv" onclick="$('#tinderslide').jTinder('luv');">
                        <img src="<?php echo base_url(); ?>assets/images/luv.png" />
                    </a>
                    <a class="for_pointer" id="power_luv_user" title="Power Luv" <?php echo $pl_onclick; ?>>
                        <img src="<?php echo base_url(); ?>assets/images/powerluv.png" />
                    </a>
                </div>
            </div>    
            <?php
        }
        if (!empty($nearByUsers)) {
            $distance = null;
            if (!empty($latlong) && !empty($lastObj['latlong'])) {
                $loc1 = explode(",", $latlong);
                $lat1 = (double) $loc1[0];
                $lon1 = (double) $loc1[1];
                $loc2 = explode(",", $lastObj['latlong']);
                $lat2 = (double) $loc2[0];
                $lon2 = (double) $loc2[1];
                if (!empty($loc1) && !empty($loc2)) {
                    $distance = distance($lat1, $lon1, $lat2, $lon2, "K");
                    $distance = number_format($distance, 2);
                }
            }
            ?>
            <div class="user-list-r">
                <h2>
                    <big id="right_username">
                        <?php
                        echo $lastObj['user_name'];
                        ?>
                    </big>
                    <small id="right_oneliner">
                        <?php
                        echo $lastObj['one_liner'];
                        ?>
                    </small>
                </h2>
                <p id="right_bio">
                    <?php
                    echo $lastObj['bio'];
                    ?>
                </p>
                <ul class="user-info">
                    <li id="right_age">Age : (<?php
                        echo $lastObj['age'];
                        ?>)</li>
                    <li id="right_location">Location : <?php echo (!empty($lastObj['address'])) ? $lastObj['address'] : "N/A"; ?></li>
                    <li id="right_distance">Distance : (<?php echo ($distance != null) ? $distance : "N/A"; ?> km)</li>
                </ul>
            </div>
        <?php } ?>
    </div>
    <div id="loader-nodatanbm" class="loader-style" style='background:none;display:none;'>
        <div class="loader-container">
            <img src="<?php echo base_url(); ?>assets/images/loader.gif"/>
            <?php if (empty($nearByUsers) || $nearByUsers == null) { ?>
                <p>Hey Luvr! Right now, there is no one else to Luv in your area! Check back soon!<br/>We are growing fast with your help! Spread the word about Luvr on all your social media!</p>
            <?php } ?>
        </div>
    </div>
    <div id="nbmpplayer"></div>
    <script type="text/javascript">
        var player_nbmp = jwplayer('nbmpplayer');
        player_nbmp.setup({
        playlist: <?php echo $playlist; ?>,
                primary:'flash',
                repeat:true,
                autostart:true,
                aspectratio:"16:9",
                width:"100%",
<?php if ($_SERVER['HTTP_HOST'] == 'luvr.me') { ?>
            advertising: {
            client:'vast',
                    tag:'<?php echo $ad_url; ?>',
            },
<?php } ?>
        });
    </script>
</div>
<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12 ad-div text-center">
    <?php if ($_SERVER['HTTP_HOST'] == 'luvr.me' && $show_ad == true) { ?>
        <div class="addvertise-img3 adv">
            <!--<script data-cfasync="false" type="text/javascript" src="http://www.tradeadexchange.com/a/display.php?r=1582359"></script>-->
            <iframe src="//rcm-na.amazon-adsystem.com/e/cm?o=1&p=11&l=ez&f=ifr&linkID=50e379b0a4c5a4f0cbaa6b826d1497c7&t=luvrweb-20&tracking_id=luvrweb-20" width="120" height="600" scrolling="no" border="0" marginwidth="0" style="border:none;" frameborder="0"></iframe>
        </div>
        <div class="addvertise-img4 adv">
            <script data-cfasync="false" type="text/javascript" src="<?php echo $_SERVER['REQUEST_SCHEME']; ?>://www.tradeadexchange.com/a/display.php?r=1582363"></script>
        </div>
    <?php } ?>
</div>
<div class="modal fade" id="adpopup" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <input type="hidden" id="hdn_tmp_id"/>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Sneak Peak</h4>
            </div>
            <div class="modal-body">
                <iframe src="" id="sneak_peak_frame" frameborder="0" scrolling="no" style="overflow:hidden;height:300px;width:100%;"></iframe>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .inner-content{position:relative;}
</style>
<script src="<?php echo base_url() . 'assets/js/jquery.transform2d.js'; ?>" type="text/javascript"></script>
<script src="<?php echo base_url() . 'assets/js/jquery.jTinder.js'; ?>" type="text/javascript"></script>
<script type="text/javascript">
        var popup_seconds = 0;
<?php if (!empty($success)) { ?>
            $(document).ready(function () {
                showMsg("<?php echo $success; ?>", "success", true);
            });
<?php } ?>
        $(window).on('load', function () {
            setTimeout(function () {
                /*$("#radar").hide();*/
                $("#loader").fadeOut();
<?php if (empty($nearByUsers) || $nearByUsers == null) { ?>
                    $("#loader-nodatanbm").fadeIn();
<?php } ?>
                $("#tinderslide").removeAttr('style');
            }, Math.floor((Math.random() * 1000) + 1000));
        });
        var likedislikecounts = 0;
        var likesreached = powerluvsreached = 0;
        registerjTinder();
        function registerjTinder() {
            $("#tinderslide").jTinder({
                onLike: function (item) {
<?php if ($user_swipes_per_day < MAX_SWIPES_PER_DAY) { ?>
                        /*loadLevel2(item, 'like');*/
    <?php if ($is_user_premium_member == 1) { ?>
                            location.href = "<?php echo base_url('/match/level2/'); ?>" + $(item).data("id") + "/1/2";
    <?php } else { ?>
                            $("#adpopup").modal('show');
                            $("#hdn_tmp_id").val($(item).data("id"));
                            $("#sneak_peak_frame").attr('src', '<?php echo base_url('video/adcash'); ?>');
                            $("#sneak_peak_frame").on('load', function () {
                                showSneakPeak();
                            });
                            $("#sneak_peak_frame").on('error', function () {
                                location.href = "<?php echo base_url('match/level2/'); ?>" + $(item).data("id") + "/1/2";
                            });
    <?php } ?>
<?php } ?>
                    reflectUserInfo(item.index() - 1);
                },
                onDislike: function (item) {
                    likedislikeuser($(item).data("id"), 'dislike');
                    reflectUserInfo(item.index() - 1);
                },
                onLuv: function (item) {
                    /*loadLevel2(item, 'luv');*/
                    location.href = "<?php echo base_url('/match/level2/'); ?>" + $(item).data("id") + "/1/2";
                },
                onPowerLuv: function (item) {
                    likedislikeuser($(item).data("id"), 'powerluv', item.index() - 1);
                },
                onPrev: function (item) {
                    reflectUserInfo(item.index());
                },
                animationRevertSpeed: 200,
                animationSpeed: 500,
                threshold: '<?php echo (detect_browser() == 'mobile') ? 1 : 4; ?>',
                likeSelector: '.like',
                dislikeSelector: '.dislike'
            });
        }
        function reflectUserInfo(index) {
            if (index >= 0 && index < $("#tinderslide ul li.panel").length)
            {
                $("#right_username").html(nearby_matches[index].user_name);
                $("#right_oneliner").html(nearby_matches[index].one_liner);
                $("#right_bio").html((nearby_matches[index].bio) ? nearby_matches[index].bio : "&nbsp;");
                $("#right_age").html("Age : (" + nearby_matches[index].age + ")");
                $("#right_location").html((nearby_matches[index].address) ? "Location : " + nearby_matches[index].address : "Location : N/A");
                $("#right_distance").html((nearby_matches[index].distance) ? "Distance : " + nearby_matches[index].distance + " km" : "Distance : N/A");
                if (powerluvsreached == 1)
                {
                    $("#power_luv_user").removeAttr("onclick");
                } else
                {
                    if (nearby_matches[index].id)
                    {
                        $("#rewind_user").attr("onclick", "prevMatch(" + nearby_matches[index].id + ")");
                        $("#pass_user").attr("onclick", "$('#tinderslide').jTinder('dislike');");
                        $("#luv_user").attr("onclick", "$('#tinderslide').jTinder('luv');");
                        $("#power_luv_user").attr("onclick", "powerLuv();");
                    } else
                    {
                        $("#rewind_user,#pass_user,#luv_user,#power_luv_user").attr("onclick", "showMsg('Something went wrong!','error',true);");
                    }
                }
            }
        }
        function powerLuv() {
            $("#loader-pl").show();
            setTimeout(function () {
                $("#loader-pl").hide();
                $('#tinderslide').jTinder('powerluv');
            }, 2000);
        }
        function prevMatch(id) {
<?php if ($is_user_premium_member == 1) { ?>
                if ($("#tinderslide ul li[data-id='" + id + "']").attr("data-nav") != 1)
                    $('#tinderslide').jTinder('prev');
<?php } else { ?>
                showMsg("You need to be Luvr premium member to swipe back! <a href='<?php echo base_url('home/#packages') ?>'>Click here to join</a>", "error", true);
                scrollToElement("#header");
<?php } ?>
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
                    }
                    if (mode == "luv" || mode == "powerluv") {
                        var md_v = (mode == "luv") ? 2 : 3;
                        socket.emit('New Like Request Web', {
                            'requestby_id': '<?php echo $user_data['id'] ?>',
                            'requestto_id': user_id,
                            'relation_status': md_v
                        });
                    }
                    if ((data.user_swipes_per_day == <?php echo MAX_SWIPES_PER_DAY; ?>) && mode == "like")
                    {
                        /*$("#tinderslide").unbind('touchstart mousedown');
                         $("#tinderslide").unbind('touchmove mousemove');
                         $("#tinderslide").unbind('touchend mouseup');*/
                        likesreached = 1;
                        showMsg("Your likes quota per day has been reached!<br/>Therefore, right swipes for cards will not be considered.", "error");
                        scrollToElement("#header");
                    }
                    if ((data.user_powerluvs_per_day == <?php echo $max_powerluvs; ?>) && mode == "powerluv")
                    {
                        powerluvsreached = 1;
                        reflectUserInfo(li_index);
                        showMsg("Your power luvs quota per day has been reached!<br/>Therefore, further power luvs will not be considered.", "error");
                        scrollToElement("#header");
                    }
                    if (likedislikecounts == $("#tinderslide ul li.panel").length)
                    {
                        loadMoreNearBys();
                    }
                }, error: function () {
                    showMsg("Something went wrong!", "error", true);
                    scrollToElement("#header");
                }
            });
        }
        function loadMoreNearBys() {
            $("#tinderslide").css('visibility', 'hidden');
            $("#loader").show();
            $.ajax({
                url: "<?php echo base_url(); ?>match/loadMoreNearBys",
                type: 'POST',
                dataType: 'json',
                success: function (data) {
                    if (data.success == true) {
                        likedislikecounts = 0;
                        if (data.data) {
                            nearby_matches = data.data;
                            $("#tinderslide ul").html(data.html);
                            reflectUserInfo(parseInt(data.data.length) - 1);
                            registerjTinder();
                        }
                    } else {
                        $("#loader").hide();
                        $(".user-list-l,.user-list-r").hide();
                        $("#loader-nodatanbm .loader-container").append('<p>Hey Luvr! Right now, there is no one else to Luv in your area! Check back soon!<br/>We are growing fast with your help! Spread the word about Luvr on all your social media!</p>');
                        $("#loader-nodatanbm").show();
                    }
                    setTimeout(function () {
                        /*$("#radar").hide();*/
                        $("#loader").fadeOut();
                        $("#tinderslide").removeAttr('style');
                    }, Math.floor((Math.random() * 1000) + 1000));
                }
            });
        }
        /*function loadLevel2(item, mode) {
         var index = item.index();
         $("#tinderslide2 ul li.panel").attr("data-id", nearby_matches[index].id);
         $("#tinderslide2 ul li.panel .user-list-pic-bg").attr("style", "background:url('" + nearby_matches[index].media_thumb + "') no-repeat scroll center center;");
         $('#level2Popup').modal('show');
         $('.secondSwiper ul li,panel').removeAttr('style');
         $('.secondSwiper ul li,panel').show();
         }*/
        /*$("#adpopup").on("shown.bs.modal", function () {
         showSneakPeak();
         });*/
        function showSneakPeak() {
            popup_seconds = popup_seconds + 1;
            if (popup_seconds == 5)
            {
                location.href = "<?php echo base_url('match/level2/'); ?>" + $("#hdn_tmp_id").val() + "/1/2";
            } else
            {
                setTimeout(showSneakPeak, 1000);
            }
        }
        socket.on('New Like Request', function (data) {
            console.log(data);
        });
</script>