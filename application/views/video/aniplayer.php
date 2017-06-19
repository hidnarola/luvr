<div id="aniplayer"></div>
<script type="text/javascript" id="aniviewJS">
    (function ()
    {
        var myPlayer;
        var adConfig = {
            publisherId: '59394e4828a06156ac564965',
            channelId: '59395c9728a06118183e72cf',
            ref1: 'AV_URL=[URL_MACRO]',
            width: 1024,
            height: 768,
            HD: true,
            mode: 0,
            autoPlay: true,
            loop: true,
            vastRetry: 3,
            errorLimit: 5,
            soundButton: true,
            pauseButton: true,
            closeButton: true,
            format: "html5",
            position: 'aniplayer'
        };

        (new Image).src = "https://track.aniview.com/track?pid=" + adConfig.publisherId + "&cid=" + adConfig.channelId + "&e=playerLoaded" + "&cb=" + Date.now();
        var PlayerUrl = 'https://player.aniview.com/script/6.1/aniview.js';
        function downloadScript(src, adData) {
            var scp = document.createElement('script');
            scp.src = src;
            scp.onload = function () {
                myPlayer = new avPlayer(adData);
                myPlayer.play(adConfig);
            }
            document.getElementsByTagName('head')[0].appendChild(scp);
        }
        ;
        downloadScript(PlayerUrl, adConfig);
    })();

</script>