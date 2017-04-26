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
    <script type="text/javascript" src="<?php echo base_url('assets/js/jwplayer.js'); ?>"></script>
    <script>jwplayer.key = "+NBpDYuEp+FQ1VZ4YR8hbrcC1s9O/eD5ul+RdSAMR04=";</script>
    <script type="text/javascript">
        jwplayer("playerObject").setup({
        file: "<?php echo $video_url; ?>",
                autostart: true,
                aspectratio:"16:9",
                width: "100%",
    <?php if ($_SERVER['HTTP_HOST'] == 'dev.luvr.me') { ?>
            advertising: {
            client: 'vast',
                    tag: '<?php echo $ad_url; ?>',
            },
    <?php } ?>
        });
    </script>
<?php } ?>