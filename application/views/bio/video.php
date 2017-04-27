<?php
$user_data = $this->session->userdata('user');
$show_ad = true;
if (!empty($user_data)) {
    if (isUserActiveSubscriber($user_data['id']) == 1) {
        $show_ad = false;
    }
}
?>
<div class="container">
    <div class="row">
        <?php if (!empty($video_url)) { ?>
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
<?php if (!empty($video_url)) { ?>
    <?php if (($_SERVER['HTTP_HOST'] == 'dev.luvr.me' || $_SERVER['HTTP_HOST'] == 'luvr.me') && $show_ad == true) { ?>
        <script data-cfasync="false" type="text/javascript" src="http://www.tradeadexchange.com/a/display.php?r=1572461"></script>
    <?php } ?>
    <script type="text/javascript" src="<?php echo base_url('assets/js/jwplayer.js'); ?>"></script>
    <script>jwplayer.key = "+NBpDYuEp+FQ1VZ4YR8hbrcC1s9O/eD5ul+RdSAMR04=";</script>
    <script type="text/javascript">
        jwplayer("playerObject").setup({
        file: "<?php echo $video_url; ?>",
                autostart: true,
                aspectratio:"16:9",
                width: "100%",
    <?php if (($_SERVER['HTTP_HOST'] == 'dev.luvr.me' || $_SERVER['HTTP_HOST'] == 'luvr.me') && $show_ad == true) { ?>
            primary: 'flash',
                    advertising: {
                    client: 'vast',
                            tag: '<?php echo $ad_url; ?>',
                    },
    <?php } ?>
        });
    <?php if (!empty($ad_url) && $show_ad == true) { ?>
            console.log('<?php echo $ad_url; ?>');
    <?php } ?>
    </script>
<?php } ?>