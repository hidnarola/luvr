<div class="container">
    <div class="row">
        <?php if (!empty($video_url)) { ?>
            <div id="myDiv"></div>        
        <?php } else { ?>
            <p class="alert alert-danger">Invalid Video URL!</p>
        <?php } ?>
    </div>
</div>

<?php if (!empty($video_url)) { ?>
    <script type="text/javascript" src="https://content.jwplatform.com/libraries/Xcqi8yCH.js"></script>
    <script type="text/javascript">
        jwplayer("myDiv").setup({
            "file": "<?php echo $video_url; ?>",
        });
    </script>
<?php } ?>