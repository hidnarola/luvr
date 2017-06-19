<link href="<?php echo $_SERVER['REQUEST_SCHEME']; ?>://vjs.zencdn.net/4.7.1/video-js.css" rel="stylesheet">
<link href="<?php echo S3_URL; ?>/assets/css/videojs.ads.css" rel="stylesheet">
<link href="<?php echo S3_URL; ?>/assets/css/videojs.vast.css" rel="stylesheet">

<script src="<?php echo S3_URL; ?>/assets/js/video4.7.1.js"></script>
<script src="<?php echo S3_URL; ?>/assets/js/videojs.ads.js"></script>
<script src="<?php echo S3_URL; ?>/assets/js/vast-client.js"></script>
<script src="<?php echo S3_URL; ?>/assets/js/videojs.vast.js"></script>
<video id="vid1" class="video-js vjs-default-skin" loop autoplay controls preload="auto" width="100%" height="768" poster="<?php echo S3_URL; ?>/Videos/Commercials/luvr-logo.jpg" data-setup="{}">
    <source src="<?php echo S3_URL; ?>/Videos/Commercials/luvr-logo.mp4" type='video/mp4'>
    <p class="vjs-no-js">
        To view this video please enable JavaScript, and consider upgrading to a web browser that
        <a href="http://videojs.com/html5-video-support/" target="_blank">supports HTML5 video</a>
    </p>
</video>
<script>
    var vid1 = videojs('vid1');
    vid1.muted(true);
    vid1.ads();
    vid1.vast({
        url: 'http://playertest.longtailvideo.com/vast-30s-ad.xml',
        skip: 5
    });
</script>