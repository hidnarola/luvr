<?php
$sess_user_data = $this->session->userdata('user');
?>
<script type="text/javascript">
    var my_id = '<?php echo $sess_user_data['id']; ?>';
    /*socket.on('connect', function () {
     socket.emit('connected', {id: socket.id});
     console.log("socket:", socket.id);
     });*/
    socket.on('isUserOnline Callback', function (isOnline) {
        if (isOnline == 1)
        {
            $("#login_status").addClass('label-success').removeClass('label-danger');
            $("#login_status").html("ONLINE");
        } else
        {
            $("#login_status").addClass('label-danger').removeClass('label-success');
            $("#login_status").html("OFFLINE");
        }
    });
    $(window).focus(function () {
        socket.emit('inForeground');
    })/*.blur(function () {
     socket.emit('inBackground');
     });*/
    $(window).unload(function () {
        socket.emit('disconnect');
    });
    socket.on('user_Connection_changed', function (data) {
        console.log(data);
        if (data.isOnline == 1)
        {
            $("#login_status").addClass('label-success').removeClass('label-danger');
            $("#login_status").html("ONLINE");
        } else
        {
            $("#login_status").addClass('label-danger').removeClass('label-success');
            $("#login_status").html("OFFLINE");
        }
    });
    socket.emit('AccessTokenGet', {
        'userID': '<?php echo $sess_user_data['id']; ?>',
    }, function (data) {
        /*console.log("AccessTokenGet : \n");
         console.log(data);*/
        $("#button-preview").trigger('click');
        identity = data.identity;
        $('#room-controls').css('display', 'table');
        // Bind button to join Room.
        $('#button-join').on('click', function () {
            /*alert("join1");*/
            join_room(data);
        });
<?php if ($this->uri->segment(1) != "match" && $this->uri->segment(1) != "video" && !empty($this->uri->segment(3)) && !empty($this->uri->segment(4)) && !empty($this->uri->segment(3))) { ?>
            /*alert("join2");*/
            join_room(data);
<?php } ?>
        // Bind button to leave Room.
        $('#button-leave').on('click', function () {
            log_status('Ending Call...');
            $("#button-call").show();
            $("#button-join,#button-reject").hide();
            $("#local-media").removeClass("col-sm-4 col-md-4 col-xs-4").css('position', 'relative');
            if (activeRoom)
                activeRoom.disconnect();
            socket.emit('CALL Action Web', {
                'id': $("#msgid").val(),
                'user_name': '<?php echo $sess_user_data['user_name']; ?>',
                'caller_id': $("#callerid").val(),
                'sender_id': $("#callerid").val(),
                'calling_id': $("#callingid").val(),
                'receiver_id': $("#callingid").val(),
                'call_status': 3,
                'message': 3
            }, function (data) {
                audioElement.pause();
                audioElement.currentTime = 0;
                log_status("Call ended!");
                $("#button-call").show();
                $("#button-join,#button-reject").hide();
                $("#button-call").removeAttr("disabled");
                /*$("#button-call a").html("Video Call " + $("#button-call").attr("data-name"));*/
            });
        });
    });
    /* IMPORTANT : Script to check tab status constantly. */
    /*$(document).one('click', function () {
     setInterval(function () {
     $('body').append('has focus? ' + window_focus + '<br>');
     }, 1000);
     });*/

    socket.on('CALL Action', function (data) {
        console.log("Response : \n");
        console.log(data);
        if (data) {
            $("#room-name").val(data.unique_id);
            $("#msgid").val(data.id);
            $("#callerid").val(data.sender_id);
            $("#callingid").val(data.receiver_id);
            if (data.message == 1 || data.message == '1')
            {
                $("#button-call").hide();
                $("#button-join,#button-reject").show();
                if (data.receiver_id == my_id)
                {
                    getUserInfo(data.sender_id, function (data) {
                        $("#call_img").attr("src", '<?php echo base_url('assets/images/icon-05.png'); ?>').show();
                        showMsgCall(data.user_name + ' is calling you.', 'incoming', true);
                    });
                }
                elapseTimer();
                audioElement.play();
                $(".success-message #call_img").on("click", function () {
                    if ($("#video_call_container").length > 0)
                    {
                        $("#button-join").trigger('click');
                    } else
                    {
                        location.href = '<?php echo base_url('message/videocall/'); ?>' + data.sender_id + '/' + data.receiver_id + '/' + data.id + '/' + data.unique_id + '';
                    }
                });
            } else if (data.message == 2 || data.message == '2')
            {
                log_status("Call connected.");
                $("#button-join").trigger('click');
                $("#button-leave").show();
                $("#button-call,#button-join,#button-reject").hide();
                $("#button-preview").trigger('click');
            } else if (data.message == 3 || data.message == '3')
            {
                if (data.sender_id == my_id)
                {
                    getUserInfo(data.sender_id, function (data) {
                        $("#call_img").attr("src", '<?php echo base_url('assets/images/icon-06.png'); ?>').show();
                        showMsgCall('Call rejected!', 'rejected', true);
                    });
                }
                window.clearTimeout(tmptout);
                call_timeout = 0;
                audioElement.pause();
                audioElement.currentTime = 0;
                $("#button-call").removeAttr("disabled");
                /*$("#button-call a").html("Video Call " + $("#button-call").attr("data-name"));*/
                $("#button-join,#button-reject,#button-leave").hide();
                $("#button-leave").trigger('click');
                $("#button-preview").trigger('click');
            } else if (data.message == 4 || data.message == '4')
            {
                window.clearTimeout(tmptout);
                call_timeout = 0;
                audioElement.pause();
                audioElement.currentTime = 0;
                $("#button-call").removeAttr("disabled");
                log_status("Call busy!");
                /*$("#button-call a").html("Video Call " + $("#button-call").attr("data-name"));*/
                $("#button-join,#button-reject").hide();
                $("#button-preview").trigger('click');
            } else if (data.message == 5 || data.message == '5')
            {
                $("#button-call").removeAttr("disabled");
                log_status("Call timed out!");
                /*$("#button-call a").html("Video Call " + $("#button-call").attr("data-name"));*/
                $("#button-join,#button-reject").hide();
                $("#button-preview").trigger('click');
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
            log_status("Call timed out!");
            $("#button-call").show();
            $("#button-join,#button-reject").hide();
            $("#button-call").removeAttr("disabled");
            /*$("#button-call a").html("Video Call " + $("#button-call").attr("data-name"));*/
            socket.emit('CALL Action Web', {
                'id': $("#msgid").val(),
                'caller_id': $("#callerid").val(),
                'sender_id': $("#callerid").val(),
                'calling_id': $("#callingid").val(),
                'receiver_id': $("#callingid").val(),
                'call_status': 5,
                'message': 5
            }, function (data) {
            });
        } else
        {
            tmptout = setTimeout(elapseTimer, 1000);
        }
    }
    function join_room(data) {
        roomName = document.getElementById('room-name').value;
        if (!roomName) {
            alert('Please enter a room name.');
            return;
        }

        log_status("Joining call...");
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
            log_status('Could not connect to Twilio: ' + error.message);
            $("#button-call").show();
            $("#button-join,#button-reject").hide();
            $("#button-call").removeAttr("disabled");
            /*$("#button-call a").html("Video Call " + $("#button-call").attr("data-name"));*/
            socket.emit('CALL Action Web', {
                'id': $("#msgid").val(),
                'caller_id': $("#callerid").val(),
                'sender_id': $("#callerid").val(),
                'calling_id': $("#callingid").val(),
                'receiver_id': $("#callingid").val(),
                'app_version': 0,
                'call_status': 5,
                'message': 5
            }, function (data) {
            });
        });
    }

    function getUserInfo(id, callback) {
        $.ajax({
            'type': 'post',
            'dataType': 'json',
            'url': '<?php echo base_url("message/getUserDetail"); ?>',
            'data': {'select': 'id,user_name', 'id': id},
            success: function (data) {
                callback(data);
            }
        })
    }
</script>