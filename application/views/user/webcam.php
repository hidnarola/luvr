<link href="<?php echo base_url('assets/css/video-js.min.css'); ?>" rel="stylesheet"/>
<link href="<?php echo base_url('assets/css/videojs.record.min.css'); ?>" rel="stylesheet"/>
<link href="<?php echo base_url('assets/css/style-recorder.css'); ?>" rel="stylesheet"/>
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
    </div>
</div>
<script type='text/javascript'>
    var player = videojs("webVideo",
            {
                controls: true,
                width: $(".account-r-body").width(),
                plugins: {
                    record: {
                        audio: true,
                        video: true,
                        maxLength: 5,
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
        console.log('Recorded videos');
        console.log(player.recordedData);
        /*player.recorder.saveAs({'video': 'my-video-file-name.webm'});*/
        //data.append('file', player.recordedData.video);
        var data = new FormData();
        data.append('file', player.recordedData.video);

        $.ajax({
            // url: "http://localhost/luvr/user/saverecordedvideo",
            url: "<?php echo base_url('user/saverecordedvideo'); ?>",
            type: 'POST',
            data: data,
            dataType: 'json',
            contentType: false,
            processData: false,
            success: function (data) {
                if (data.success == false)
                {
                    showMsg(data.message, 'error', true);
                }
            },
            error: function () {
                showMsg("Something went wrong!", 'error', true);
            }
        });
    });
</script>