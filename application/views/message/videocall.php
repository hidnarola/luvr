<?php
$sess_user_data = $this->session->userdata('user');
$room_id = random_string('alnum', 10);
$chat_user_img = my_img_url($chat_user_media['media_type'], $chat_user_media['media_thumb']);
$chatusername = (!empty($chat_user_data['user_name'])) ? $chat_user_data['user_name'] : $chat_user_data['instagram_username'];
$chat_user_data['user_name'] = $chatusername;
?>
<!--<link rel="stylesheet" href="<?php echo base_url() . 'assets/css/index.css'; ?>"/>-->
<script src="<?php echo base_url() . 'assets/js/socket.io.min.js'; ?>"></script>
<script>
    var my_id = '<?php echo $sess_user_data['id']; ?>';
    var chatuser = '<?php echo json_encode($chat_user_data); ?>';
    chatuser = $.parseJSON(chatuser);
</script>
<div class="my-account">
    <?php
    $this->load->view('side_bar_account');
    ?>
    <div class="col-md-8 col-sm-8 col-xs-12 account-r">
        <div class="account-r-head">
            <h2><big><?php echo $db_user_data['user_name']; ?></big></h2>
        </div>
        <div class="account-r-body ">
            <div class="account-body-head">
                <h2 class="account-title">Video Call</h2>                
            </div>
            <input id="room-name" type="hidden" value="<?php echo $room_id; ?>"/>
            <input id="msgid" type="hidden"/>
            <input id="theirid" type="hidden"/>
            <input id="callerid" type="hidden"/>
            <div class="account-body-body preferences">
                <div class="dasboard-message">
                    <div class="link-tab mar-btm-20">
                        <ul class="nav" id="room-controls">
                            <li id="button-call" data-name="<?php echo $chatusername; ?>"><a class="for_pointer">Video Call <?php echo $chatusername; ?></a></li>
                            <li id="button-preview" style="display:none;"><a class="for_pointer">Preview My Camera</a></li>
                            <li id="button-join" style="display:none;"><a class="for_pointer">Accept</a></li>
                            <li id="button-reject" style="display:none;"><a class="for_pointer">Reject</a></li>
                            <li id="button-leave" style="display:none;"><a class="for_pointer">End Call</a></li>
                        </ul>
                    </div>
                    <div id="preview">
                        <div id="local-media" class="col-sm-4 col-md-4 col-xs-4"></div>
                        <div id="remote-media"></div>
                    </div>
                    <div id="log" style="display:none;"></div>
                </div>
            </div>
        </div>
    </div>
</div>
<audio style="height:0;width:0;" id="caller_tune">
    <source src="<?php echo base_url(); ?>assets/caller_tune.ogg" type="audio/ogg">
    <source src="<?php echo base_url(); ?>assets/caller_tune.mp3" type="audio/mpeg">
</audio>
<script type="text/javascript" src="<?php echo base_url() . 'assets/js/jquery.min.js'; ?>"></script>
<script src="//media.twiliocdn.com/sdk/js/video/v1/twilio-video.min.js"></script>
<script src="<?php echo base_url() . 'assets/js/index.js'; ?>"></script>
<!--<script src="<?php echo base_url() . 'assets/js/index2.js'; ?>"></script>-->
<script type="text/javascript">
    var socket = io.connect('http://' + window.location.hostname + ':8100');
    var audioElement = document.getElementById('caller_tune');
    var call_timeout = 0;
    var tmptout;
    /*socket.on('connect', function () {
     socket.emit('connected', {id: socket.id});
     console.log("socket:", socket.id);
     });*/
    socket.emit('AccessTokenGet', {
        'userID': '<?php echo $sess_user_data['id']; ?>',
    }, function (data) {
        /*console.log("AccessTokenGet : \n");
         console.log(data);*/
        identity = data.identity;
        document.getElementById('room-controls').style.display = 'block';

// Bind button to join Room.
        document.getElementById('button-join').onclick = function () {
            roomName = document.getElementById('room-name').value;
            if (!roomName) {
                alert('Please enter a room name.');
                return;
            }

            log("Joining room '" + roomName + "'...");
            var connectOptions = {
                name: roomName,
                logLevel: 'debug'
            };

            if (previewTracks) {
                connectOptions.tracks = previewTracks;
            }

            // Join the Room with the token from the server and the
            // LocalParticipant's Tracks.
            Twilio.Video.connect(data.AccessToken, connectOptions).then(roomJoined, function (error) {
                log('Could not connect to Twilio: ' + error.message);
                $("#button-call").show();
                $("#button-join,#button-reject").hide();
                $("#button-call").removeAttr("disabled");
                $("#button-call a").html("Video Call " + $("#button-call").attr("data-name"));
                socket.emit('CALL Action Web', {
                    'id': $("#msgid").val(),
                    'caller_id': $("#callerid").val(),
                    'call_status': 5
                }, function (data) {
                });
            });
        };

// Bind button to leave Room.
        document.getElementById('button-leave').onclick = function () {
            log('Ending Call...');
            $("#button-call").show();
            $("#button-join,#button-reject").hide();
            if (activeRoom)
                activeRoom.disconnect();
            socket.emit('CALL Action Web', {
                'id': $("#msgid").val(),
                'caller_id': $("#callerid").val(),
                'call_status': 2,
                'call_ended': 1
            }, function (data) {
                audioElement.pause();
                audioElement.currentTime = 0;
                log("Call ended!");
                $("#button-call").show();
                $("#button-join,#button-reject").hide();
                $("#button-call").removeAttr("disabled");
                $("#button-call a").html("Video Call " + $("#button-call").attr("data-name"));
            });
        };
    });

    socket.emit('join_socket_web', {
        'userID': '<?php echo $sess_user_data['id']; ?>',
        'is_login': '1',
        'app_version': 0
    });

    $("#button-call").on("click", function () {
        socket.emit('CALL Action Web', {
            'id': 0,
            'caller_id': '<?php echo $sess_user_data['id']; ?>',
            'calling_id': '<?php echo $chat_user_data['id']; ?>',
            'call_unique_id': '<?php echo $room_id; ?>',
            'app_version': 0,
            'call_status': 1
        }, function (data) {
            if (data.is_connected == true)
            {
                $("#button-call").attr("disabled", "disabled");
                $("#button-call a").html("Calling...");
            } else
            {
                $("#button-call").removeAttr("disabled");
                $("#button-call a").html("Video Call " + $("#button-call").attr("data-name"));
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
            'call_status': 3
        }, function (data) {
            audioElement.pause();
            audioElement.currentTime = 0;
            window.clearTimeout(tmptout);
            call_timeout = 0;
            $("#button-call").show();
            $("#button-join,#button-reject").hide();
            $("#button-call").removeAttr("disabled");
            $("#button-call a").html("Video Call " + $("#button-call").attr("data-name"));
        });
    });

    socket.on('CALL Action', function (data) {
        console.log("Response : \n");
        console.log(data);
        if (data) {
            $("#msgid").val(data.id);
            $("#callerid").val(data.caller_id);
            if (data.call_status == 1)
            {
                $("#room-name").val(data.call_unique_id);
                $("#button-call").hide();
                $("#button-join,#button-reject").show();
                if (data.calling_id == my_id)
                {
                    if (data.caller_id == chatuser.id)
                        showMsgCall(chatuser.user_name + ' is calling you.', 'incoming', true);
                }
                elapseTimer();
                audioElement.play();
            } else if (data.call_status == 2)
            {
                if (data.call_ended == 1)
                {
                    $("#button-call").removeAttr("disabled");
                    $("#button-call a").html("Video Call " + $("#button-call").attr("data-name"));
                    $("#button-leave").trigger('click');
                    $("#button-leave").hide();
                } else
                {
                    $("#button-call").attr("disabled", "disabled");
                    $("#button-call a").html("Call Connected");
                    $("#button-join").trigger('click');
                    $("#button-leave").show();
                }
                $("#button-join,#button-reject").hide();
            } else if (data.call_status == 3)
            {
                if (data.caller_id == my_id)
                    showMsgCall(chatuser.user_name + ' rejected call!', 'rejected', true);
                $("#button-call").removeAttr("disabled");
                $("#button-call a").html("Video Call " + $("#button-call").attr("data-name"));
                $("#button-join,#button-reject").hide();
            } else if (data.call_status == 4)
            {
                $("#button-call").removeAttr("disabled");
                $("#button-call a").html("Video Call " + $("#button-call").attr("data-name"));
                $("#button-join,#button-reject").hide();
            } else if (data.call_status == 5)
            {
                $("#button-call").removeAttr("disabled");
                $("#button-call a").html("Video Call " + $("#button-call").attr("data-name"));
                $("#button-join,#button-reject").hide();
            } else
            {
                $("#button-call").hide();
                $("#button-join,#button-reject").show();
                elapseTimer();
                audioElement.play();
            }
        }
    });

    function elapseTimer() {
        call_timeout = call_timeout + 1;
        if (call_timeout == 30)
        {
            audioElement.pause();
            audioElement.currentTime = 0;
            log("Call timed out!");
            $("#button-call").show();
            $("#button-join,#button-reject").hide();
            $("#button-call").removeAttr("disabled");
            $("#button-call a").html("Video Call " + $("#button-call").attr("data-name"));
            socket.emit('CALL Action Web', {
                'id': $("#msgid").val(),
                'caller_id': $("#callerid").val(),
                'call_status': 5
            }, function (data) {
            });
        } else
        {
            tmptout = setTimeout(elapseTimer, 1000);
        }
    }
</script>