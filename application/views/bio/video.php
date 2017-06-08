<?php
$user_data = $this->session->userdata('user');
$show_ad = true;
if (!empty($user_data)) {
    if (isUserActiveSubscriber($user_data['id']) == 1) {
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
}
if (isset($ad_url) && !empty($ad_url)) {
    if (strpos($ad_url, 'streamrail') !== false) {
        $next_random_url = str_replace("https", "http", $next_random_url);
    }
}
?>
<div class="container">
    <div class="row">
        <?php if (!empty($playlist)) { ?>
            <div id="playerObject"></div>        
        <?php } else { ?>
            <p class="alert alert-danger">Invalid Video URL!</p>
        <?php } ?>
    </div>
</div>
<style type="text/css">
    .jw-progress{background:#f26f6f;}
    .jw-button-color:focus, :not(.jw-flag-touch) .jw-button-color:hover{color:#f26f6f;}
</style>
<?php if (!empty($playlist)) { ?>
    <?php if ($_SERVER['HTTP_HOST'] == 'dev.luvr.me' && $show_ad == true) { ?>
        <script data-cfasync="false" type="text/javascript" src="http://www.tradeadexchange.com/a/display.php?r=1572461"></script>
    <?php } else if ($_SERVER['HTTP_HOST'] == 'luvr.me' && $show_ad == true) { ?>
        <!--<script data-cfasync="false" type="text/javascript" src="<?php echo $_SERVER['REQUEST_SCHEME']; ?>://www.tradeadexchange.com/a/display.php?r=1592351"></script>-->
    <?php } ?>
    <script type="text/javascript">
            console.log('<?php echo count($playlist); ?>');
            var player = jwplayer('playerObject');
            player.setup({
            playlist: <?php echo json_encode($playlist); ?>,
    <?php if ($show_ad == true) { ?>
                primary:'flash',
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
                },
    <?php } ?>
            });
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
    <?php if (!empty($ad_url) && $show_ad == true) { ?>
                console.log('<?php echo $ad_url; ?>');
    <?php } ?>
    <?php if (!empty($next_random_url) && $show_ad == true) { ?>
                console.log('<?php echo $next_random_url; ?>');
    <?php } ?>
    </script>
<?php } ?>