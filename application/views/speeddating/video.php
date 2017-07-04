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
$ad_url = "https://vast.optimatic.com/vast/getVast.aspx?id=tI8OelBpLoQd&o=3&zone=default&pageURL=" . base_url(uri_string()) . "&pageTitle=BioVideo&cb=" . uniqid() . "";
?>
<?php if ($show_header_footer == 0) { ?>
    <script type="text/javascript" src="<?php echo base_url('assets/js/jwplayer.js'); ?>"></script>
    <script>jwplayer.key = "+NBpDYuEp+FQ1VZ4YR8hbrcC1s9O/eD5ul+RdSAMR04=";</script>
<?php } ?>
<div class="container">
    <?php if (detect_browser() != 'mobile' && $_SERVER['HTTP_HOST'] == 'luvr.me') { ?>
        <div class="text-center clearfix">
            <script data-cfasync=false src="<?php echo $_SERVER['REQUEST_SCHEME']; ?>://s.ato.mx/p.js#id=1220822&size=728x90"></script>
        </div>
    <?php } ?>
    <div class="row">
        <div class="back-btn-div"><a onclick="window.history.back();" class="for_pointer"></a></div>
        <?php if ($_SERVER['HTTP_HOST'] == 'luvr.me') { ?>
            <?php if (detect_browser() == 'mobile') { ?>
                <div class="col-md-2 col-sm-3 left-add"><script data-cfasync=false src="<?php echo $_SERVER['REQUEST_SCHEME']; ?>://s.ato.mx/p.js#id=1220823&size=300x250"></script></div>
            <?php } else { ?>
                <div class="col-md-2 col-sm-3 left-add"><script data-cfasync=false src="<?php echo $_SERVER['REQUEST_SCHEME']; ?>://s.ato.mx/p.js#id=1220822&size=160x600"></script></div>
            <?php } ?>
        <?php } ?>
        <div class="col-md-8 col-sm-6 center-content">
            <div id="playerObject"></div>        
        </div>
        <?php if ($_SERVER['HTTP_HOST'] == 'luvr.me') { ?>
            <?php if (detect_browser() == 'mobile') { ?>
                <div class="col-md-2 col-sm-3 right-add"><script data-cfasync=false src="<?php echo $_SERVER['REQUEST_SCHEME']; ?>://s.ato.mx/p.js#id=1220823&size=300x250"></script></div>
            <?php } else { ?>
                <div class="col-md-2 col-sm-3 right-add"><script data-cfasync=false src="<?php echo $_SERVER['REQUEST_SCHEME']; ?>://s.ato.mx/p.js#id=1220824&size=160x600"></script></div>
            <?php } ?>
        <?php } ?>
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
    var player = jwplayer('playerObject');
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
    jwplayer().onPlaylistComplete(function () {
    location.href = '<?php echo base_url(); ?>speed/<?php echo $pref; ?>';
        /*$.ajax({
         url: "<?php echo base_url(); ?>match/pluvuser",
         type: 'POST',
         dataType: 'json',
         data:"user_id=<?php echo $video_user_id; ?>",
         success: function (data) {
         if (data.success == true) {
         location.href = '<?php echo base_url(); ?>speed/<?php echo $pref; ?>';
         } else{
         alert("Something went wrong!");
         }
         }
         });*/
        });
<?php if (!empty($ad_url) && $show_ad == true) { ?>
            console.log('<?php echo $ad_url; ?>');
<?php } ?>
</script>