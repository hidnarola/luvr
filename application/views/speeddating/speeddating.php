<?php
$user_data = $this->session->userdata('user');
$success = $this->session->flashdata('success');
$playlist[0] = array("file" => ASSETS_URL . "/Videos/Commercials/vid1.mp4", "image" => ASSETS_URL . "/Videos/Commercials/vid1.jpg");
$playlist[1] = array("file" => ASSETS_URL . "/Videos/Commercials/vid2.mp4", "image" => ASSETS_URL . "/Videos/Commercials/vid2.jpg");
$playlist[2] = array("file" => ASSETS_URL . "/Videos/Commercials/vid3.mp4", "image" => ASSETS_URL . "/Videos/Commercials/vid3.jpg");
$playlist = json_encode($playlist);
$ad_url = "https://vast.optimatic.com/vast/getVast.aspx?id=tI8OelBpLoQd&o=3&zone=default&pageURL=" . base_url(uri_string()) . "&pageTitle=BioVideo&cb=" . uniqid() . "";
if (!empty($randomUsers)) {
    $i = 0;
    foreach ($randomUsers as $nbu) {
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
        $randomUsers[$i]['distance'] = $distance;
        $i++;
    }
    $lastObj = end($randomUsers);
}
?>
<script type="text/javascript">
    var randomUsers = <?php echo json_encode($randomUsers); ?>
</script>
<?php if ($_SERVER['HTTP_HOST'] == 'luvr.me') { ?>
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<?php } ?>
<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12 ad-div text-center">
    <?php if ($_SERVER['HTTP_HOST'] == 'luvr.me') { ?>
        <div class="addvertise-img1 adv">
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
    <div class="user-list">
        <div class="back-btn-div"><a onclick="window.history.back();" class="for_pointer"></a></div>    
        <div class="bg-name">luvr</div>
        <?php if (!empty($randomUsers)) { ?>
            <div class="user-list-l">
                <div class="user-list-l-l">    
                    <div class="user-list-pic">
                        <div id="tinderslide3" style="visibility:hidden;">
                            <ul>
                                <?php
                                $it = 1;
                                foreach ($randomUsers as $user) {
                                    $path = $user['media_thumb'];
                                    $href = $user['media_name'];
                                    if ($user['media_type'] == 4) {
                                        $href = base_url() . "video/vid/" . $user['mid'] . "/" . $pref;
                                    }
                                    $path = str_replace("https", $_SERVER['REQUEST_SCHEME'], $path);
                                    $path = str_replace("http", $_SERVER['REQUEST_SCHEME'], $path);
                                    /* $file_headers = @get_headers($path);
                                      if (!$file_headers || $file_headers[0] == 'HTTP/1.1 404 Not Found' || $file_headers[0] == 'HTTP/1.0 404 Key Not Found') {
                                      $path = S3_URL . "assets/images/big_avatar.jpg";
                                      } */
                                    echo '<li class="panel" data-id="' . $user['uid'] . '" data-email="' . $user['email'] . '">
                                            <div class="user-list-pic-wrapper">
                                                <div class="user-list-pic-bg">
                                                    <a style="background:url(\'' . $path . '\') no-repeat scroll center center;" class="img"></a>';
                                    echo '<a class="play-btn-large icon-play-button" href="' . $href . '"></a>';
                                    echo '</div>
                                            <div class="user-list-pic-close">
                                            <a class="for_pointer" onclick="$(\'#tinderslide3\').jTinder(\'dislike\');">
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
            </div>    
            <?php
        }
        if (!empty($randomUsers)) {
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
                    <big id="right_username" title="<?php
                    echo $lastObj['full_name'];
                    ?>">
                             <?php
                             echo $lastObj['full_name'];
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
</div>

<div id="detailMsg" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Want to say something?</h4>
            </div>
            <div class="modal-body">
                <input type="hidden" id="hdn_tmp_uid"/>
                <textarea class="form-control" id="txt_lng_msg" maxlength="140" placeholder="140 character message."></textarea>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="sendMessage();">Send</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
            </div>
        </div>
    </div>
</div>

<div class="col-lg-2 col-md-3 col-sm-3 col-xs-12 ad-div text-center">
    <?php if ($_SERVER['HTTP_HOST'] == 'luvr.me') { ?>
        <div class="addvertise-img3 adv">
            <iframe src="//rcm-na.amazon-adsystem.com/e/cm?o=1&p=11&l=ez&f=ifr&linkID=50e379b0a4c5a4f0cbaa6b826d1497c7&t=luvrweb-20&tracking_id=luvrweb-20" width="120" height="600" scrolling="no" border="0" marginwidth="0" style="border:none;" frameborder="0"></iframe>
        </div>
        <div class="addvertise-img4 adv">
            <script data-cfasync="false" type="text/javascript" src="<?php echo $_SERVER['REQUEST_SCHEME']; ?>://www.tradeadexchange.com/a/display.php?r=1582363"></script>
        </div>
    <?php } ?>
</div>
<style type="text/css">
    .inner-content{position:relative;}
</style>
<script src="<?php echo base_url() . 'assets/js/jquery.transform2d.js'; ?>" type="text/javascript"></script>
<script src="<?php echo base_url() . 'assets/js/jquery.jTinder.js'; ?>" type="text/javascript"></script>
<script type="text/javascript">
                    $(window).on('load', function () {
                        setTimeout(function () {
                            $("#loader").fadeOut();
                            $("#tinderslide3").removeAttr('style');
                        }, Math.floor((Math.random() * 1000) + 1000));
                        manageAutoSkips();
                    });
                    $('#detailMsg').on('hidden.bs.modal', function () {
                        $("#hdn_tmp_uid,#txt_lng_msg").val('');
                    })
                    function manageAutoSkips() {
                        var _counter = 10;
                        var _timer = setInterval(function () {
                            if (_counter === 0)
                            {
                                clearInterval(_timer);
<?php if ($_SERVER['HTTP_HOST'] == 'luvr.me') { ?>
                                    $('#tinderslide3').jTinder('dislike');
<?php } ?>
                                manageAutoSkips();
                            }
                            /*console.log(_counter + " seconds");*/
                            _counter--;
                        }, 1000);
                    }
                    var likedislikecounts = 0;
                    registerjTinder();
                    function registerjTinder() {
                        $("#tinderslide3").jTinder({
                            onLike: function (item) {
                                likedislikeuser($(item).data("id"), 'speedpowerluv', item.index() - 1);
                                $('#detailMsg').modal('show');
                                $('#detailMsg #hdn_tmp_uid').val($(item).data("email"));
                                reflectUserInfo(item.index() - 1);
                            },
                            onDislike: function (item) {
                                likedislikeuser($(item).data("id"), 'dislike');
                                reflectUserInfo(item.index() - 1);
                            },
                            animationRevertSpeed: 200,
                            animationSpeed: 500,
                            threshold: '<?php echo (detect_browser() == 'mobile') ? 1 : 4; ?>',
                            likeSelector: '.like',
                            dislikeSelector: '.dislike'
                        });
                    }
                    function sendMessage() {
                        var msg = $.trim($("#txt_lng_msg").val());
                        var email = $("#hdn_tmp_uid").val();
                        $.ajax({
                            url: "<?php echo base_url(); ?>match/sendemailtouser",
                            type: 'POST',
                            dataType: 'json',
                            data: "msg=" + msg + "&email=" + email,
                            success: function (data) {
                                $('#detailMsg').modal('hide');
                                showMsg("Message delivered successfully!", "success", true);
                            }, error: function () {
                                showMsg("Something went wrong!", "error", true);
                                scrollToElement("#header");
                            }
                        });
                    }
                    function reflectUserInfo(index) {
                        if (index >= 0 && index < $("#tinderslide3 ul li.panel").length)
                        {
                            $("#right_username").html(randomUsers[index].full_name);
                            $("#right_oneliner").html(randomUsers[index].one_liner);
                            $("#right_bio").html((randomUsers[index].bio) ? randomUsers[index].bio : "&nbsp;");
                            $("#right_age").html("Age : (" + randomUsers[index].age + ")");
                            $("#right_location").html((randomUsers[index].address) ? "Location : " + randomUsers[index].address : "Location : N/A");
                            $("#right_distance").html((randomUsers[index].distance) ? "Distance : " + randomUsers[index].distance + " km" : "Distance : N/A");
                        }
                    }
                    function likedislikeuser(user_id, mode, li_index) {
                        var dt = "user_id=" + user_id + "&status=" + mode;
                        if (mode == "speedpowerluv")
                            dt = "user_id=" + user_id + "&status=" + mode + "&email=" + randomUsers[li_index + 1].email;
                        $.ajax({
                            url: "<?php echo base_url(); ?>match/likedislike",
                            type: 'POST',
                            dataType: 'json',
                            data: dt,
                            success: function (data) {
                                likedislikecounts++;
                                if (data.success == true) {
                                }
                                reflectUserInfo(li_index);
                                if (likedislikecounts == $("#tinderslide3 ul li.panel").length)
                                {
                                    location.reload();
                                }
                            }, error: function () {
                                showMsg("Something went wrong!", "error", true);
                                scrollToElement("#header");
                            }
                        });
                    }
                    socket.on('New Like Request', function (data) {
                        console.log(data);
                    });
</script>