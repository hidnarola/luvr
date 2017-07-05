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
if ($this->uri->segment(4) == 2 || $this->uri->segment(4) == 3) {
    $show_ad = false;
}
if ($single_video == true) {
    $show_ad = false;
}
if (!empty($next_random)) {
    $next_random_url = base_url() . "video/play/" . $next_random . "/2";
}
if (isset($_GET['p']) && !empty($_GET['p'])) {
    $next_random_url .= '?p=' . $_GET['p'] . '';
    $show_ad = true;
}
if (isset($_GET['s']) && !empty($_GET['s'])) {
    if (isset($_GET['p']) && !empty($_GET['p'])) {
        $next_random_url .= '&s=' . $_GET['s'] . '';
    } else {
        $next_random_url .= '?s=' . $_GET['s'] . '';
    }
    $show_ad = true;
    echo "IP : " . $this->input->ip_address();
}
$manage_errors = true;
if (isset($_GET['p']) && !empty($_GET['p']) && (empty($_GET['s']) || !isset($_GET['s']))) {
    $playlist = array("file" => ASSETS_URL . "/Videos/Commercials/luvr-logo.mp4", "image" => ASSETS_URL . "/Videos/Commercials/luvr-logo.jpg");
    $manage_errors = false;
    if ($active_subscriber == 1) {
        $show_ad = false;
    }
}
if (isset($ad_url) && !empty($ad_url)) {
    if (strpos($ad_url, 'streamrail') !== false) {
        $next_random_url = str_replace("https", "http", $next_random_url);
    }
}
?>
<?php if ($show_header_footer == 0) { ?>
    <script type="text/javascript" src="<?php echo base_url('assets/js/jwplayer.js'); ?>"></script>
    <script>jwplayer.key = "+NBpDYuEp+FQ1VZ4YR8hbrcC1s9O/eD5ul+RdSAMR04=";</script>
<?php } ?>
<div class="container">
    <div class="row">
        <div class="back-btn-div"><a onclick="window.history.back();" class="for_pointer"></a></div>    
        <div id="playerObject"></div>        
    </div>
</div>
<style type="text/css">
    .jw-progress{background:#f26f6f;}
    .jw-button-color:focus, :not(.jw-flag-touch) .jw-button-color:hover{color:#f26f6f;}
</style>
<?php if ($_SERVER['HTTP_HOST'] == 'dev.luvr.me' && $show_ad == true) { ?>
    <script data-cfasync="false" type="text/javascript" src="http://www.tradeadexchange.com/a/display.php?r=1572461"></script>
<?php } else if ($_SERVER['HTTP_HOST'] == 'luvr.me' && $show_ad == true) { ?>
    <script data-cfasync="false" type="text/javascript" src="<?php echo $_SERVER['REQUEST_SCHEME']; ?>://www.tradeadexchange.com/a/display.php?r=1592351"></script>
    <script type="text/javascript">
            var exoOpts = {
            cat: '492',
                    login: 'luvrinc',
                    idzone_300x250: '2657732',
                    idsite: '669986',
                    preroll: {},
                    pause: {},
                    postroll: {},
                    show_thumb: '1'
            };</script>
    <script type="text/javascript" src="https://ads.exdynsrv.com/invideo.js"></script>
<?php } ?>
<script type="text/javascript">
            console.log('<?php echo count($playlist); ?>');
            var player = jwplayer('playerObject');
            player.setup({
<?php if ($playlist != null && !empty($playlist)) { ?>
                playlist: <?php echo json_encode($playlist); ?>,
<?php } ?>
<?php if ($show_ad == true) { ?>
                /*primary:'flash',*/
<?php } ?>
<?php if ($show_ad == false) { ?>
                repeat:true,
                        autostart:false,
<?php } ?>
<?php if ($show_ad == true) { ?>
                autostart:true,
<?php } ?>
            aspectratio:"16:9",
                    width:"100%",
<?php if (($_SERVER['HTTP_HOST'] == 'dev.luvr.me' || $_SERVER['HTTP_HOST'] == 'luvr.me') && $show_ad == true) { ?>
                advertising: {
                client:'vast',
                        tag:'<?php echo $ad_url; ?>',
                        requestTimeout:20000
                },
<?php } ?>
            });
<?php if ($manage_errors == true) { ?>
                player.on('error', function () {
                var next = parseInt(jwplayer().getPlaylistIndex()) + 1;
                if (next < <?php echo count($playlist); ?>) {
                jwplayer().playlistItem(next);
                } else {
    <?php if (!empty($next_random_url) && $show_ad == true) { ?>
                    location.href = '<?php echo $next_random_url; ?>';
    <?php } ?>
                /*player.playlistItem(0); */
                }
                });
                jwplayer().onError(function () {
                var next = parseInt(jwplayer().getPlaylistIndex()) + 1;
                if (next < <?php echo count($playlist); ?>) {
                jwplayer().playlistItem(next);
                } else {
    <?php if (!empty($next_random_url) && $show_ad == true) { ?>
                    location.href = '<?php echo $next_random_url; ?>';
    <?php } ?>
                /*player.playlistItem(0); */
                }
                });
    <?php if (!empty($next_random_url) && $show_ad == true) { ?>
                    jwplayer().onPlaylistComplete(function () {
                    location.href = '<?php echo $next_random_url; ?>';
                    });
    <?php } ?>
<?php } ?>
<?php if ($manage_errors == false && !empty($user_data)) { ?>
                jwplayer().onPlaylistComplete(function () {
    <?php if (isset($_GET['uid']) && !empty($_GET['uid'])) { ?>
                    location.href = "<?php echo base_url('/match/level2/') . $_GET['uid']; ?>/1/2";
    <?php } else { ?>
                    location.href = '<?php echo base_url('match/nearby'); ?>';
    <?php } ?>
                });
<?php } ?>
<?php if (!empty($ad_url) && $show_ad == true) { ?>
                console.log('<?php echo $ad_url; ?>');
<?php } ?>
<?php if (!empty($next_random_url) && $show_ad == true) { ?>
                console.log('<?php echo $next_random_url; ?>');
<?php } ?>
</script>