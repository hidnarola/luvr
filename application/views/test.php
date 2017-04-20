<!DOCTYPE html>
<html lang="en">

<head>
    <title>Bootstrap Example</title>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/css/bootstrap.min.css">
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.2.0/jquery.min.js"></script>
    <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
</head>

<body>

    <div id="myDiv">This text will be replaced with a player.</div>

    <script type="text/javascript" src="https://content.jwplatform.com/libraries/Xcqi8yCH.js"></script>
    <script>
        jwplayer("myDiv").setup({
            // "file": "<?php echo base_url().'assets/uploads/Video/testvideo.mp4'; ?>",
            "file":"https://scontent.cdninstagram.com/t50.2886-16/12938986_209693549404343_833375835_s.mp4",
            "height": 360,
            "width": 640
        });
    </script>

</body>

</html>
