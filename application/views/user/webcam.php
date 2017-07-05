<?php
$sess_user_data = $this->session->userdata('user');
$is_active_usr = isUserActiveSubscriber($sess_user_data['id']);
$playlist[0] = array("file" => ASSETS_URL . "/Videos/Commercials/vid1.mp4", "image" => ASSETS_URL . "/Videos/Commercials/vid1.jpg");
$playlist[1] = array("file" => ASSETS_URL . "/Videos/Commercials/vid2.mp4", "image" => ASSETS_URL . "/Videos/Commercials/vid2.jpg");
$playlist[2] = array("file" => ASSETS_URL . "/Videos/Commercials/vid3.mp4", "image" => ASSETS_URL . "/Videos/Commercials/vid3.jpg");
$playlist = json_encode($playlist);
$ad_url = "https://vast.optimatic.com/vast/getVast.aspx?id=tI8OelBpLoQd&o=3&zone=default&pageURL=" . base_url(uri_string()) . "&pageTitle=BioVideo&cb=" . uniqid() . "";
?>
<link href="<?php echo base_url('assets/css/video-js.min.css'); ?>" rel="stylesheet"/>
<link href="<?php echo base_url('assets/css/videojs.record.min.css'); ?>" rel="stylesheet"/>
<link href="<?php echo base_url('assets/css/style-recorder.css'); ?>" rel="stylesheet"/>
<script type="text/javascript" src="<?php echo base_url() . 'assets/js/jquery.browser.min.js'; ?>"></script>

<script src="<?php echo base_url('assets/js/video.min.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/RecordRTC.js'); ?>"></script>
<script src="<?php echo base_url('assets/js/videojs.record.min.js'); ?>"></script>
<style>
    /* change player background color */
    #webVideo {background-color: #f26f6f;}
    .vjs-record-button{color:#ff0000;}
</style>
<div class="my-account">
    <?php
    $this->load->view('side_bar_account');
    $user_data = $this->session->userdata('user');
    $username = (!empty($user_data['user_name'])) ? ucfirst($user_data['user_name']) : $user_data['instagram_username'];
    ?>
    <div class="col-md-8 col-sm-8 col-xs-12 account-r">
        <div class="account-r-head"><h2><big><?php echo $username; ?></big></h2></div>
        <div class="account-r-body">
            <div class="account-body-head">
                <h2 class="account-title">Webcam</h2>
                <p>&nbsp;</p>
            </div>
            <div class="account-body-body preferences">
                <video id="webVideo" class="video-js vjs-default-skin"></video>
            </div>
        </div>
        <div id="wcplayer"></div>
    </div>
</div>
<script type='text/javascript'>
    jwplayer('wcplayer').setup({
        playlist: <?php echo $playlist; ?>,
        primary: 'flash',
        repeat: true,
        autostart: false,
        aspectratio: "16:9",
        width: "100%",
    });
<?php if ($is_active_usr == '1') { ?>
        var video_length = '60';
<?php } else { ?>
        var video_length = '15';
<?php } ?>

    var player = videojs("webVideo",
            {
                controls: true,
                width: $(".account-r-body").width(),
                plugins: {
                    record: {
                        audio: true,
                        video: true,
                        maxLength: video_length,
                        debug: true
                    }
                }
            });

    // error handling
    player.on('deviceError', function ()
    {
        showMsg('Device error : ' + player.deviceErrorCode, 'error', true);
    });
    player.on('error', function (error)
    {
        showMsg('Error : ' + error, 'error', true);
    });
    // user clicked the record button and started recording
    player.on('startRecord', function ()
    {
        console.log('started recording!');
    });

    // user completed recording and stream is available
    player.on('finishRecord', function ()
    {

        // the blob object contains the recorded data that can be downloaded by the user, stored on server etc.        
        /*player.recorder.saveAs({'video': 'my-video-file-name.webm'});*/
        //data.append('file', player.recordedData.video);

        var data = new FormData();
        // var is_mozzila = false;

        if ($.browser.mozilla == true) {
            // is_mozzila = true;
            data.append('file', player.recordedData);
        } else {
            data.append('file', player.recordedData.video);
        }

        data.append('receiver_id', '<?php echo $chat_user_id; ?>');

        $.ajax({
            // url: "http://localhost/luvr/user/saverecordedvideo",
            url: "<?php echo base_url('user/saverecordedvideo'); ?>",
            type: 'POST',
            data: data,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (data) {
                console.log(data);
                if (data.success == false) {
                    showMsg(data.message, 'error', true);
                } else {

                    socket.emit('New Message', {
                        'message_type': '5',
                        'message': null,
                        'media_name': data['filename'] + '.mp4',
                        'unique_id': data['filename'],
                        'sender_id': data['sender_id'],
                        'receiver_id': data['receiver_id'],
                        'created_date': data['created_date'],
                        'is_encrypted': '0',
                        'encrypted_message': ''
                    }, function (data) {

                    });

                    window.location.href = "<?php echo base_url() . 'message/chat/'; ?>" + data['receiver_id'];
                }
            },
            error: function () {
                showMsg("Something went wrong!", 'error', true);
            }
        });
    });
</script>