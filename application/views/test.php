<link href='<?php echo base_url('/assets/css/jTinder.css'); ?>' rel='stylesheet'/>
<?php
$playlist[0] = array("file" => ASSETS_URL . "/Videos/Commercials/vid1.mp4", "image" => ASSETS_URL . "/Videos/Commercials/vid1.jpg");
$playlist[1] = array("file" => ASSETS_URL . "/Videos/Commercials/vid2.mp4", "image" => ASSETS_URL . "/Videos/Commercials/vid2.jpg");
$playlist[2] = array("file" => ASSETS_URL . "/Videos/Commercials/vid3.mp4", "image" => ASSETS_URL . "/Videos/Commercials/vid3.jpg");
$ad_url = "https://vast.optimatic.com/vast/getVast.aspx?id=tI8OelBpLoQd&o=3&zone=default&pageURL=" . base_url(uri_string()) . "&pageTitle=BioVideo&cb=" . uniqid() . "";
?>
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

<style type="text/css">
    .inner-content{position:relative;}
</style>
<script src="<?php echo base_url() . 'assets/js/jquery.transform2d.js'; ?>" type="text/javascript"></script>
<script src="<?php echo base_url() . 'assets/js/jquery.jTinder.js'; ?>" type="text/javascript"></script>
<script type="text/javascript">
            var player = jwplayer('playerObject');
            player.setup({
                playlist: <?php echo json_encode($playlist); ?>,
                autostart: false,
                aspectratio: "16:9",
                width: "100%",
            });
            player.on('error', function () {
                alert("Could not play video!");
            });
            jwplayer().onError(function () {
                alert("Could not play video!");
            });
            registerjTinder();
            function registerjTinder() {
                $("#tinderslide3").jTinder({
                    onLike: function (item) {
                        console.log("like");
                        /*likedislikeuser($(item).data("id"), 'speedpowerluv', item.index() - 1);
                        $('#detailMsg').modal('show');
                        $('#detailMsg #hdn_tmp_uid').val($(item).data("email"));*/
                    },
                    onDislike: function (item) {
                        console.log("dislike");
                        /*likedislikeuser($(item).data("id"), 'dislike');*/
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
                    }, error: function () {
                        showMsg("Something went wrong!", "error", true);
                        scrollToElement("#header");
                    }
                });
            }
</script>