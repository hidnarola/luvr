<?php
$user_data = $this->session->userdata('user');
$show_ad = true;
if (!empty($user_data)) {
    if (isUserActiveSubscriber($user_data['id']) == 1) {
        $show_ad = false;
    }
}
if (!empty($next_random)) {
    $next_random_url = base_url() . "video/play/" . $next_random . "/2";
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
        <script data-cfasync="false" type="text/javascript" src="<?php echo $_SERVER['REQUEST_SCHEME']; ?>://www.tradeadexchange.com/a/display.php?r=1592351"></script>
    <?php } ?>
    <script type="text/javascript" src="<?php echo base_url('assets/js/jwplayer.js'); ?>"></script>
    <script>jwplayer.key = "+NBpDYuEp+FQ1VZ4YR8hbrcC1s9O/eD5ul+RdSAMR04=";</script>
    <script type="text/javascript">
        var player = jwplayer('playerObject');
        player.setup({
        playlist: <?php echo json_encode($playlist); ?>,
                primary:'flash',
                repeat:true,
                autostart:true,
                aspectratio:"16:9",
                width:"100%",
    <?php if (($_SERVER['HTTP_HOST'] == 'dev.luvr.me' || $_SERVER['HTTP_HOST'] == 'luvr.me') && $show_ad == true) { ?>
            advertising: {
            client:'vast',
                    tag:'<?php echo $ad_url; ?>',
            },
    <?php } ?>
        });
                jwplayer().onError(function () {
            var next = parseInt(jwplayer().getPlaylistIndex()) + 1;
            if (next <= <?php echo count($playlist); ?>) {
                jwplayer().playlistItem(next);
            } else {
                location.href = '<?php echo $next_random_url; ?>';
                /*player.playlistItem(0); */
            }
        });
        jwplayer().onPlaylistComplete(function () {
            location.href = '<?php echo $next_random_url; ?>';
        });
    <?php if (!empty($ad_url) && $show_ad == true) { ?>
            console.log('<?php echo $ad_url; ?>');
    <?php } ?>
    </script>
<?php } ?>