<?php
$user_data = $this->session->userdata('user');
$show_ad = true;
$active_subscriber = 0;
if (!empty($user_data)) {
    $active_subscriber = isUserActiveSubscriber($user_data['id']);
    if ($active_subscriber == 1) {
        $show_ad = false;
    }
}
/* if ($single_video == true) {
  $show_ad = false;
  } */
/* $ad_url = "https://vast.optimatic.com/vast/getVast.aspx?id=tI8OelBpLoQd&o=3&zone=default&pageURL=" . base_url(uri_string()) . "&pageTitle=BioVideo&cb=" . uniqid() . ""; */
/*$ad_url = "" . $_SERVER['REQUEST_SCHEME'] . "://www.objectdisplay.com/a/display.php?r=1593023&acp=pre&acw=1024&ach=768&vast=3";*/
$ad_url = "" . $_SERVER['REQUEST_SCHEME'] . "://search.spotxchange.com/vast/2.0/202107?VPAID=JS&content_page_url=" . _current_url() . "&cb=" . uniqid(time()) . "&player_width=1024&player_height=768";
?>
<script type="text/javascript" src="<?php echo base_url('assets/js/jwplayer.js'); ?>"></script>
<script>jwplayer.key = "+NBpDYuEp+FQ1VZ4YR8hbrcC1s9O/eD5ul+RdSAMR04=";</script>
<div class="container">
    <div class="user-list video-swipe">
        <div class="back-btn-div"><a onclick="window.history.back();" class="for_pointer"></a></div>    
        <div id="tinderslide3">
            <ul>
                <li class="panel">
                    <div class="user-list-pic-wrapper">
                        <div id="playerObject"></div>
                    </div>
                </li>
            </ul>
        </div>
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
                <button type="button" class="btn btn-default" data-dismiss="modal">No Message</button>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .jw-progress{background:#f26f6f;}
    .jw-button-color:focus, :not(.jw-flag-touch) .jw-button-color:hover{color:#f26f6f;}
    .inner-content{position:relative;}
</style>
<script src="<?php echo base_url() . 'assets/js/jquery.transform2d.js'; ?>" type="text/javascript"></script>
<script src="<?php echo base_url() . 'assets/js/jquery.jTinder.js'; ?>" type="text/javascript"></script>
<script type="text/javascript">
                    $('#detailMsg').on('hidden.bs.modal', function () {
                    $("#hdn_tmp_uid,#txt_lng_msg").val('');
                    location.href = '<?php echo base_url(); ?>speed/<?php echo $pref; ?>';
                        })
                                var player = jwplayer('playerObject');
                        player.setup({
<?php if ($playlist != null && !empty($playlist)) { ?>
                            playlist: <?php echo json_encode($playlist); ?>,
<?php } ?>
<?php if ($show_ad == false) { ?>
                            /*repeat:true,*/
                            autostart:false,
<?php } ?>
<?php if ($show_ad == true) { ?>
                            autostart:true,
<?php } ?>
                        aspectratio:"16:9",
                                width:"100%",
<?php if ($_SERVER['HTTP_HOST'] == 'luvr.me' && $show_ad == true) { ?>
                            advertising: {
                            client:'vast',
                                    tag:'<?php echo $ad_url; ?>',
                                    requestTimeout:20000
                            }
<?php } ?>
                        });
                        player.on('error', function () {
                        alert("Could not play video!");
                        });
                        jwplayer().onError(function () {
                        alert("Could not play video!");
                        });
                        registerjTinder();
                        jwplayer().onPlaylistComplete(function () {
                        location.href = '<?php echo base_url(); ?>speed/<?php echo $pref; ?>';
                            });
                            function registerjTinder() {
                            $("#tinderslide3").jTinder({
                            onLike: function (item) {
                            likedislikeuser(<?php echo $video_user_id; ?>, 'speedpowerluv', item.index() - 1);
                            $('#detailMsg').modal('show');
                            $('#detailMsg #hdn_tmp_uid').val($(item).data("email"));
                            },
                                    onDislike: function (item) {
                                    likedislikeuser(<?php echo $video_user_id; ?>, 'dislike');
                                    location.href = '<?php echo base_url(); ?>speed/<?php echo $pref; ?>';
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
                                                location.href = '<?php echo base_url(); ?>speed/<?php echo $pref; ?>';
                                                            }, error: function () {
                                                    showMsg("Something went wrong!", "error", true);
                                                    scrollToElement("#header");
                                                    }
                                                    });
                                                    }
                                                    function likedislikeuser(user_id, mode, li_index) {
                                                    var dt = "user_id=" + user_id + "&status=" + mode;
                                                    if (mode == "speedpowerluv")
                                                            dt = "user_id=" + user_id + "&status=" + mode + "&email=<?php echo $video_user_email; ?>";
                                                    $.ajax({
                                                    url: "<?php echo base_url(); ?>match/likedislike",
                                                            type: 'POST',
                                                            dataType: 'json',
                                                            data: dt,
                                                            success: function (data) {
                                                            }, error: function () {
                                                    showMsg("Something went wrong!", "error", true);
                                                    scrollToElement("#header");
                                                    }
                                                    });
                                                    }
<?php if (!empty($ad_url) && $show_ad == true) { ?>
                                                        console.log('<?php echo $ad_url; ?>');
<?php } ?>
</script>