<?php
$sess_user_data = $this->session->userdata('user');
$chat_user_img = my_img_url($chat_user_media['media_type'], $chat_user_media['media_thumb']);
$chatusername = (!empty($chat_user_data['user_name'])) ? $chat_user_data['user_name'] : $chat_user_data['instagram_username'];
$chat_user_data['user_name'] = $chatusername;
?>
<div class="my-account">
    <?php
    $this->load->view('side_bar_account');
    ?>
    <div class="col-md-8 col-sm-8 col-xs-12 account-r" id="video_call_container">
        <div class="account-r-head">
            <h2><big><?php echo $db_user_data['user_name']; ?></big></h2>
        </div>
        <span id="status"></span>
        <div class="account-r-body ">
            <div class="account-body-head">
                <h2 class="account-title">Video Call</h2>
                <div>
                    <label class="label pull-left" id="login_status"></label>
                    <span id="log"></span>
                </div>
            </div>
            <div class="account-body-body preferences">
                <div class="dasboard-message">
                    <div id="preview" class="mar-btm-20">
                        <div id="local-media" style="position:relative;"></div>
                        <div id="remote-media"></div>
                    </div>
                    <div class="link-tab mar-btm-20">
                        <ul class="nav" id="room-controls">
                            <li id="button-call" data-name="<?php echo $chatusername; ?>" title="Call"></li>
                            <li id="button-preview" style="display:none;" title="Preview camera"><a class="for_pointer">Preview My Camera</a></li>
                            <li id="button-join" style="display:none;" title="Accept Call"></li>
                            <li id="button-reject" style="display:none;" title="Reject Call"></li>
                            <li id="button-leave" style="display:none;" title="End Call"></li>
                        </ul>
                    </div>
                    <!--<div id="log" style="display:none;"></div>-->
                </div>
            </div>
        </div>
    </div>
</div>
<script type="text/javascript">
    var call_timeout = 0;
    var tmptout;
    /*socket.on('connect', function () {
     socket.emit('connected', {id: socket.id});
     console.log("socket:", socket.id);
     });*/
    socket.emit('isUserOnline', {
        'userID': '<?php echo $sess_user_data['id']; ?>',
        'front_user_id': '<?php echo $chat_user_data['id']; ?>'
    });

    /* IMPORTANT : Script to check tab status constantly. */
    /*$(document).one('click', function () {
     setInterval(function () {
     $('body').append('has focus? ' + window_focus + '<br>');
     }, 1000);
     });*/

    $("#button-call").on("click", function () {
        $("#button-reject").show();
        socket.emit('CALL Action Web', {
            'id': 0,
            'user_name': '<?php echo $db_user_data['user_name']; ?>',
            'caller_id': '<?php echo $sess_user_data['id']; ?>',
            'sender_id': '<?php echo $sess_user_data['id']; ?>',
            'calling_id': '<?php echo $chat_user_data['id']; ?>',
            'receiver_id': '<?php echo $chat_user_data['id']; ?>',
            'call_unique_id': '<?php echo $room_id; ?>',
            'unique_id': '<?php echo $room_id; ?>',
            'app_version': 0,
            'call_status': 1,
            'created_date': '<?php echo date("Y-m-d H:i:s") . " +0000"; ?>',
            'message': 1
        }, function (data) {
            if (data.is_connected == true)
            {
                $("#button-call").attr("disabled", "disabled");
                $("#msgid").val(data.id);
                log_status('Connecting Call...');
                /*$("#button-call a").html("Calling...");*/
            } else
            {
                $("#button-call").removeAttr("disabled");
                log_status('This user is offline!');
                /*$("#button-call a").html("Video Call " + $("#button-call").attr("data-name"));*/
                /*alert("This user is currently offline!");*/
            }
            console.log("Request : \n");
            console.log(data);
        });
    });

    $("#button-reject").on("click", function () {
        socket.emit('CALL Action Web', {
            'id': $("#msgid").val(),
            'caller_id': $("#callerid").val(),
            'sender_id': $("#callerid").val(),
            'calling_id': $("#callingid").val(),
            'receiver_id': $("#callingid").val(),
            'call_status': 3,
            'message': 3
        }, function (data) {
            audioElement.pause();
            audioElement.currentTime = 0;
            window.clearTimeout(tmptout);
            call_timeout = 0;
            $("#local-media").removeClass("col-sm-4 col-md-4 col-xs-4").css('position', 'relative');
            $("#button-call").show();
            $("#button-join,#button-reject").hide();
            $("#button-call").removeAttr("disabled");
            /*$("#button-call a").html("Video Call " + $("#button-call").attr("data-name"));*/
        });
    });
    // Preview LocalParticipant's Tracks.
    document.getElementById('button-preview').onclick = function () {
        var localTracksPromise = previewTracks
                ? Promise.resolve(previewTracks)
                : Twilio.Video.createLocalTracks();

        localTracksPromise.then(function (tracks) {
            previewTracks = tracks;
            var previewContainer = document.getElementById('local-media');
            if (!previewContainer.querySelector('video')) {
                attachTracks(tracks, previewContainer);
            }
        }, function (error) {
            console.error('Unable to access local media', error);
            log('Unable to access Camera and Microphone');
        });
    };
</script>