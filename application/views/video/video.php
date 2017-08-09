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
$proxies = array(
    '198.46.144.104',
    '107.172.65.59',
    '172.106.148.134',
    '172.106.148.8',
    '104.202.129.236',
    '172.106.148.105',
    '196.17.11.85',
    '104.202.129.242',
    '107.172.64.40',
    '104.202.137.148'
);
/* if (in_array($this->input->ip_address(), $proxies)) { */
$next_random_url = base_url() . "drluvr/video" . rand(1, 12);
$next_random_url = str_replace("https", "http", $next_random_url);
/* } */
/* $ad_url = "https://vast.optimatic.com/vast/getVast.aspx?id=tI8OelBpLoQd&o=3&zone=default&pageURL=" . base_url(uri_string()) . "&pageTitle=BioVideo&cb=" . uniqid() . ""; */
/* $ad_url = "" . $_SERVER['REQUEST_SCHEME'] . "://search.spotxchange.com/vast/2.0/202107?VPAID=JS&content_page_url=" . _current_url() . "&cb=" . uniqid(time()) . "&player_width=1024&player_height=768"; */
if ($_SERVER['REQUEST_SCHEME'] == "https")
    $ad_url = "https://api.avidadserver.com/api/vast/video?tid=59778d2a1ee0530b30d1df75&pid=598419fa1ee05504fccb97fa&rnd=" . uniqid(time()) . "&vv=2";
else
    $ad_url = "http://api.avidadserver.com/api/vast/video?tid=59778d2a1ee0530b30d1df75&pid=5982a8ac1ee05502f01c829e&rnd=" . uniqid(time()) . "&vv=2";
?>
<script type="text/javascript" src="<?php echo base_url('assets/js/jwplayer.js'); ?>"></script>
<script>jwplayer.key = "+NBpDYuEp+FQ1VZ4YR8hbrcC1s9O/eD5ul+RdSAMR04=";</script>
<div class="container">
    <div class="row">
        <div class="back-btn-div"><a onclick="window.history.back();" class="for_pointer"></a></div>
        <div id="playerObject"></div>
        <div id='ad-container'>
            <div id='ad-slot'>
                <video id='video-slot'></video>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .jw-progress{background:#f26f6f;}
    .jw-button-color:focus, :not(.jw-flag-touch) .jw-button-color:hover{color:#f26f6f;}
</style>
<script type="text/javascript">
    var player = jwplayer('playerObject');
    var isPaused = false;
    player.setup({
<?php if ($playlist != null && !empty($playlist)) { ?>
        playlist: <?php echo json_encode($playlist); ?>,
<?php } ?>
<?php if ($show_ad == true) { ?>
        /*primary:'flash',*/
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
<?php if (($_SERVER['HTTP_HOST'] == 'dev.luvr.me' || $_SERVER['HTTP_HOST'] == 'luvr.me') && $show_ad == true) { ?>
        /*advertising: {
         client:'vast',
         tag:'<?php echo $ad_url; ?>',
         requestTimeout:20000
         },*/
<?php } ?>
    });
    player.onReady(playSpotXAd);
    function playSpotXAd(){
    var directAdOS = new SpotX.DirectAdOS({
    channel_id: 202107, // Your channel ID here
            slot: $('#ad-slot').get(0),
            video_slot: $('#video-slot').get(0),
            hide_skin: false,
            autoplay: true,
            content_width: player.getWidth(),
            content_height: player.getHeight()
    });
    function onAdEnd() {
    $('#ad-container').hide();
    player.play(true);
    }

    directAdOS.subscribe(onAdEnd, 'AdStopped');
    directAdOS.subscribe(onAdEnd, 'AdError');
    directAdOS.subscribe(onAdEnd, 'AdSkipped');
    /* Uncomment if being used for mid-roll */
    // jwplayer().pause(true);
    // $('#ad-container').show();

    directAdOS.loadAd();
    }
    jwplayer().onPlaylistItem(function(){
    manageCounter();
    });
    jwplayer().onPlay(function(){
    isPaused = false;
    });
    jwplayer().onPause(function(){
    isPaused = true;
    });
    jwplayer().onBeforePlay(function () {
    isPaused = true;
    });
    jwplayer().onAdComplete(function () {
    isPaused = false;
    });
    jwplayer().onAdError(function () {
    isPaused = false;
    });
<?php if (!empty($next_random_url) && $show_ad == true) { ?>
        jwplayer().onPlaylistComplete(function () {
        location.href = '<?php echo $next_random_url; ?>';
        });
<?php } ?>
<?php if (!empty($ad_url) && $show_ad == true) { ?>
        console.log('<?php echo $ad_url; ?>');
<?php } ?>
<?php if (!empty($next_random_url) && $show_ad == true) { ?>
        console.log('<?php echo $next_random_url; ?>');
<?php } ?>
    function manageCounter(){
    var counter = Math.floor(Math.random() * 11) + 10;
    console.log(counter);
    var timer = setInterval(function () {
    if (!isPaused) {
    if (counter === 0)
    {
    console.log("Index : " + jwplayer().getPlaylistIndex());
    if (jwplayer().getPlaylistIndex() < <?php echo (count($playlist) - 1) ?>)
    {
    player.next();
    return clearInterval(timer);
    } else{
    location.href = '<?php echo $next_random_url; ?>';
    }
    }
    /*console.log(counter + " seconds");*/
    counter--;
    }
    }, 1000);
    }
</script>