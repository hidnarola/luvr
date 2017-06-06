<?php
$playlist[0] = array("file" => "http://s3.ap-south-1.amazonaws.com/luvr/Videos/Commercials/vid2.mp4", "image" => "http://s3.ap-south-1.amazonaws.com/luvr/Videos/Commercials/vid2.jpg");
$playlist[1] = array("file" => "http://s3.ap-south-1.amazonaws.com/luvr/Videos/Commercials/vid3.mp4", "image" => "http://s3.ap-south-1.amazonaws.com/luvr/Videos/Commercials/vid3.jpg");
$playlist = json_encode($playlist);
$ad_url = "https://vast.optimatic.com/vast/getVast.aspx?id=tI8OelBpLoQd&o=3&zone=default&pageURL=" . base_url(uri_string()) . "&pageTitle=BioVideo&cb=" . uniqid() . "";
$sess_user_data = $this->session->userdata('user');

$db_user_img = my_img_url($db_user_media['media_type'], $db_user_media['media_thumb']);
$chat_user_img = my_img_url($chat_user_media['media_type'], $chat_user_media['media_thumb']);

$is_active_usr = isUserActiveSubscriber($sess_user_data['id']);
?>

<div class="my-account">
    <?php
    $message = $this->session->flashdata('message');
    if (!empty($message)) {
        echo '<div class="' . $message['class'] . '">' . $message['message'] . '</div>';
    }
    $this->load->view('side_bar_account');
    ?>
    <div class="col-md-8 col-sm-8 col-xs-12 account-r">
        <div class="account-r-head">
            <h2><big><?php echo $db_user_data['user_name']; ?></big></h2>
        </div>
        <div class="account-r-body ">
            <div class="account-body-head">
                <h2 class="account-title">My Chat</h2>                
                <!-- <span id="login_status"> </span> -->
            </div>
            <div class="live-chat-user">
                <h4>
                    <?php echo $chat_user_data['user_name']; ?>                    
                </h4>
                <span>
                    <img style="width:100%" src="<?php echo $chat_user_img; ?>" alt="<?php echo $chat_user_data['user_name']; ?>" />
                </span>
            </div>

            <div class="account-body-body chat-related">
                <div class="dasboard-message">
                    <div class="link-tab">
                        <ul class="nav">
                            <li><a href="">Chat</a></li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <div class="talk-to-carowner">
                            <div class="message-to-owner">
                                <ul id="all_messages_ul"></ul>
                            </div>
                            <form method="post" onsubmit="event.preventDefault(); submit_message(this);" id="msg_form" enctype="multipart/form-data">

                                <div class="chat-option">
                                    <ul class="nav nav-tabs" role="tablist">
                                        <li role="presentation" class="active msg_text">
                                            <a href="#chat-tab" aria-controls="messages" role="tab" data-toggle="tab"> Text Message </a>
                                        </li>
                                        <li role="presentation" class="msg_files">
                                            <a href="#upload-tab" aria-controls="settings" role="tab" data-toggle="tab"> File uploads </a>
                                        </li>
                                    </ul>
                                    <div class="tab-content">

                                        <div role="tabpanel" class="tab-pane active" id="chat-tab">                                            
                                            <div class="message-to-talk">
                                                <!--  -->
                                                <textarea name="message" 
                                                          onkeypress="socket.emit('Typing', {'receiver_id':<?php echo $chat_user_id; ?>});"
                                                          id="msg_id" placeholder="Write here..."></textarea>
                                                <input type="hidden" name="session_id" id="session_id" value="<?php echo $db_user_data['id']; ?>">
                                                <input type="hidden" name="chat_user_id" id="chat_user_id" value="<?php echo $chat_user_id; ?>">

                                                <input type="hidden" name="db_user_img" id="db_user_img" value="<?php echo $db_user_img; ?>">
                                                <input type="hidden" name="chat_user_img" id="chat_user_img" value="<?php echo $chat_user_img; ?>">
                                            </div>
                                        </div>
                                        <div role="tabpanel" class=" tab-pane" id="upload-tab">
                                            <div class="choose-file">

                                                <h6>Upload Image or Video</h6>
                                                <span class="all_files">
                                                    <div class="input-group myfile div_1">
                                                        <input type="text" class="form-control input_file_1" readonly>
                                                        <label class="input-group-btn">
                                                            <span class="btn btn-primary">
                                                                Browse <input type="file" style="display: none;" name="msg_file_1">
                                                            </span>
                                                        </label>
                                                    </div>
                                                </span>
                                                <div class="all_files_add">
                                                    <a onclick="duplicate_files();" class="for_pointer btn btn-warning"> Add </a>
                                                    <a onclick="remove_file();" class="for_pointer btn btn-danger delete_file" style="display:none;">
                                                        Remove
                                                    </a>
                                                </div>

                                            </div>
                                        </div>
                                    </div>
                                    <div class="btm-btn-div">
                                        <button type="submit"> Send Message </button>


                                        <?php if (empty($video_snap_data)) { ?>
                                            <a class="send_req for_pointer" onclick="send_snap_request('<?php echo $sess_user_data["id"]; ?>', '<?php echo $chat_user_id; ?>')" title="Send Video Request"><img src="<?php echo base_url() . 'assets/images/video-request.png'; ?>" alt="img"></a>
                                            <?php
                                        } else {
                                            $snap_status = $video_snap_data['status'];
                                            if ($sess_user_data["id"] == $video_snap_data['requestby_id']) {
                                                $by_user = 'requestby_id';
                                            }
                                            if ($sess_user_data["id"] == $video_snap_data['requestto_id']) {
                                                $by_user = 'requestto_id';
                                            }

                                            if ($by_user == 'requestby_id' && $snap_status == '1') {
                                                echo '<a class="for_pointer already-requested" title="Already Requested"><img src="' . base_url() . 'assets/images/video-request.png" alt="img"></a>';
                                            }

                                            if ($by_user == 'requestto_id' && $snap_status == '1') {
                                                echo '<a href="' . base_url() . 'user/video_requests" title="View Snap Request"><img src="' . base_url() . 'assets/images/video-list.png" alt="img"></a>';
                                            }

                                            if ($snap_status == '2') {
                                                echo '<a href="' . base_url() . 'user/send_video_snap/' . $chat_user_id . '" title="Send video snap"><img src="' . base_url() . 'assets/images/video-snap.png" alt="img"></a>';
                                            }
                                        } // End of IF condition for video snap data
                                        ?>
                                        <?php if ($is_active_usr == '1') { ?>
                                            <a href="<?php echo base_url('message/videocall/' . $chat_user_data['id']) ?>" title="Video Call">
                                                <img src="<?php echo base_url() . 'assets/images/icon-01.png'; ?>" alt="img">
                                            </a>
                                        <?php } ?>

                                        <a onclick="blockFriend()" class="for_pointer" title="Block">
                                            <img src="<?php echo base_url() . 'assets/images/user-block.png'; ?>" alt="img">
                                        </a>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div id="msgplayer"></div>
    </div>
</div>

<input type="hidden" id="last_msg_id" val="">

<script type="text/javascript">
    var player_msg = jwplayer('msgplayer');
    player_msg.setup({
    playlist: <?php echo $playlist; ?>,
            primary:'flash',
            repeat:true,
            autostart:true,
            aspectratio:"16:9",
            width:"100%",
<?php if ($_SERVER['HTTP_HOST'] == 'luvr.me') { ?>
        advertising: {
        client:'vast',
                tag:'<?php echo $ad_url; ?>',
        },
<?php } ?>
    });
    var first_counter = 0;

    function formatAMPM(date) {
        date = date.replace(' +0000', '');
        date = new Date(date);
        var hours = date.getHours();
        var minutes = date.getMinutes();
        var ampm = hours >= 12 ? 'pm' : 'am';
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        minutes = minutes < 10 ? '0' + minutes : minutes;
        var strTime = hours + ':' + minutes + ' ' + ampm;
        return strTime;
    }

    function set_video_url() {
        $.each($('.msg_vid'), function (index, val) {
            $.post('<?php echo base_url() . "message/get_video_id"; ?>',
                    {image_name: $(val).data('url')},
                    function (data) {
                        $(val).attr('href', '<?php echo base_url() . "video/play/"; ?>' + data);
                        $(val).removeClass('msg_vid');
                    });
        });
    }

    socket.emit('join_socket_web', {
        'userID': '<?php echo $db_user_data["id"]; ?>',
        'is_login': '1',
        'app_version': '<?php echo $chat_user_data["app_version"]; ?>'
    });

    socket.emit('Get Old Messages', {
        'app_version': '<?php echo $chat_user_data["app_version"]; ?>',
        'chat_user': '<?php echo $chat_user_id; ?>',
        'message_id': '<?php echo $last_message_id + 1; ?>'
    });

    function blockFriend() {
        socket.emit('Block Friend', {
            'requestby_id': '<?php echo $sess_user_data["id"]; ?>',
            'requestto_id': '<?php echo $chat_user_id; ?>',
            'is_blocked': 1
        },
                function (data) {
                    console.log(data);
                });
    }

    //-------------------------------------------------------------------------------------------------    
    // Get initial messages limit of 10 and also fetch pagination for next 10 and so on
    socket.on('Get Old Messages', function (data) {

        console.log('Get Old Messages');

        if (data.messages.length > 0) {

            var first_id = data['messages'][0]['id'];
            data.messages = data.messages.reverse();
            var new_str = generate_all_message(data.messages);

            if ($.trim($('#all_messages_ul').html()).length == 0) {
                $('#all_messages_ul').html(new_str);
                $(".message-to-owner").scrollTop($('#all_messages_ul').prop("scrollHeight")); // Scroll Bottom of that DIV
            } else {
                $('.message-to-owner').animate({
                    scrollTop: $('#' + $('#all_messages_ul li:first').attr('id')).offset().top
                }, 500);

                if ($('#all_messages_ul li').length != 0) {
                    $('#all_messages_ul li:first').before(new_str);
                } else {
                    $('#all_messages_ul').html(new_str);
                }
            }

            set_video_url(); // Set Video URl for the message
        }

        if (first_counter == 0) {
            if ($.trim($('#all_messages_ul').html()).length == 0) {
                $('#all_messages_ul').html('<?php echo $unread_video_snaps_str; ?>');
            } else {
                $('#all_messages_ul li:last').after('<?php echo $unread_video_snaps_str; ?>');
            }

            $('.message-to-owner').animate({
                scrollTop: $('#all_messages_ul').offset().top + 1000
            }, 500);
        }

        first_counter++;
    });

    socket.on('New Message', function (data) {

        if ($('#all_messages_ul li').length != 0) {
            $('#all_messages_ul li:last').after(generate_new_message(data, 'no'));
        } else {
            $('#all_messages_ul').html(generate_new_message(data, 'no'));
        }
        $('.message-to-owner').animate({
            scrollTop: $('#all_messages_ul').prop("scrollHeight")
        }, 1000);

        set_video_url(); // Set Video URl for the message

    });

    socket.on('Typing', function (data) {
        // console.log(data);
    });

    $('.message-to-owner').scroll(function () {
        var pos = $('.message-to-owner').scrollTop();
        if (pos == 0) {
            socket.emit('Get Old Messages', {
                'app_version': '<?php echo $chat_user_data["app_version"]; ?>',
                'chat_user': '<?php echo $chat_user_id; ?>',
                'message_id': $('#last_msg_id').val()
            });
        }
    });

    //-------------------------------------------------------------------------------------------

    function submit_message(obj) {

        var message = $.trim($('#msg_id').val());
        var formData = new FormData($(obj)[0]);

        if ($('.msg_text').hasClass('active')) {
            if (message == '') {
                show_notification('<strong> OOPS </strong>', 'Please enter message field.', 'error');
                return false;
            }
        }

        if ($('.msg_files').hasClass('active')) {
            var total_len = $('.myfile').length;
            var error_file_upload = 0;
            var file_name = '';

            for (var i = 1; i <= total_len; i++) {
                file_name = $('.input_file_' + i).val();

                if (file_name == '') {
                    error_file_upload++; // If file is empty
                } else {
                    var file_name_split = file_name.split('.');
                    var file_ext = file_name_split[file_name_split.length - 1];
                    if ($.inArray(file_ext, ['jpg', 'png', 'jpeg', 'mp4', 'JPG', 'PNG', 'JPEG', 'MP4']) == '-1') {
                        error_file_upload++; // If file extension is not valid
                    }
                }
            } // End of for loop

            if (error_file_upload != 0) {
                show_notification('<strong> OOPS </strong>', 'Please select valid files for upload.', 'error');
                return false;
            }
        }

        $.ajax({
            url: "<?php echo base_url() . 'message/insert_data'; ?>",
            method: 'POST',
            dataType: 'json',
            async: false,
            cache: false,
            contentType: false,
            processData: false,
            data: formData,
            success: function (data) {

                $('.delete_file').hide();
                for (var j = 2; j < 11; j++) {
                    $('.div_' + j).remove();
                }
                $('#msg_form')[0].reset(); // Reset Form

                if (data['status'] == 'upload') {
                    var ret_data = $.parseJSON(data['str']);
                    for (var i = 0; i < ret_data.length; i++) {

                        var r_data = ret_data[i];

                        console.log(r_data);

                        socket.emit('New Message', {
                            'message_type': r_data['message_type'],
                            'message': r_data['message'],
                            'media_name': r_data['media_name'],
                            'unique_id': r_data['unique_id'],
                            'sender_id': r_data['sender_id'],
                            'receiver_id': r_data['receiver_id'],
                            'created_date': r_data['created_date'],
                            'is_encrypted': r_data['is_encrypted'],
                            'encrypted_message': r_data['encrypted_message']
                        }, function (data) {

                        });

                        if ($('#all_messages_ul li').length != 0) {
                            $('#all_messages_ul li:last').after(generate_new_message(r_data, 'yes'));
                        } else {
                            $('#all_messages_ul').html(generate_new_message(r_data, 'yes'));
                        }

                        $('.message-to-owner').animate({scrollTop: $('#all_messages_ul').prop("scrollHeight")}, 1000);

                    } // End of For Loop
                } else {
                    var ret_data = $.parseJSON(data['str']);

                    socket.emit('New Message', {
                        'message_type': ret_data['message_type'],
                        'message': ret_data['message'],
                        'media_name': ret_data['media_name'],
                        'unique_id': ret_data['unique_id'],
                        'sender_id': ret_data['sender_id'],
                        'receiver_id': ret_data['receiver_id'],
                        'created_date': ret_data['created_date'],
                        'is_encrypted': ret_data['is_encrypted'],
                        'encrypted_message': ret_data['encrypted_message']
                    }, function (data) {

                    });

                    if ($('#all_messages_ul li').length != 0) {
                        $('#all_messages_ul li:last').after(generate_new_message(ret_data, 'yes'));
                    } else {
                        $('#all_messages_ul').html(generate_new_message(ret_data, 'yes'));
                    }
                    $('.message-to-owner').animate({scrollTop: $('#all_messages_ul').prop("scrollHeight")}, 1000);
                }

                set_video_url(); // Set Video URl for the message
            }
        });
    }

    // Generate HTML string for the old messgaes and also for paginations old messages
    function generate_all_message(messages) {
        var new_str = '';
        var msg = [];
        var sess_user_id = '<?php echo $db_user_data["id"]; ?>';
        var cls = '';
        var alt_1 = '';
        var img_url = '';
        var img_base_url = '<?php echo base_url() . "bio/show_img/"; ?>';
        var delete_str = '';
        var all_unread_msg_ids = [];
        var msg_status = '0';

        for (var i = 0; i < messages.length; i++) {

            msg = messages[i];

            var msg_date = formatAMPM(msg["created_date"]);

            if (msg['sender_id'] == sess_user_id) {
                cls = 'rider-talk session_user';
                alt_1 = 'session_user';
                img_url = '<?php echo $db_user_img; ?>';
                delete_str = '<a class="remove-chat for_pointer" data-msg-id="' + msg['id'] + '" onclick="delete_chat(this)"> X </a>';
            } else {
                cls = 'user-talk chat_user';
                alt_1 = 'chat_user';
                img_url = '<?php echo $chat_user_img; ?>';
                delete_str = '';
                msg_status = '2';

                if (msg['status'] == '0') {
                    all_unread_msg_ids.push(msg['id']);
                }
            }

            new_str += '<li id="li_' + msg['id'] + '" class="' + cls + '"><div class="pic-01">';
            new_str += '<img src="' + img_url + '" alt="' + alt_1 + '" onerror="this.src=<?php echo base_url(); ?>assets/images/default_avatar.jpg" />';
            new_str += '</div><p>';

            // 1: text message 2: Image 3: Video
            if (msg['message_type'] == '1') {
                new_str += atob(msg['encrypted_message']);
            } else if (msg['message_type'] == '2') {
                new_str += '<a class="chat_img" data-fancybox="" href="' + img_base_url + msg['media_name'] + '">';
                new_str += '<img width="50px" height="50px" src="' + img_base_url + msg['media_name'] + '/1"/></a>';
            } else if (msg['message_type'] == '3') {
                new_str += '<a href="" data-url="' + msg['media_name'] + '" target="_blank" class="msg_vid chat_video">';
                msg['media_name'] = msg['media_name'].replace('.mp4', '.png');
                new_str += '<img width="50px" height="50px" src="' + img_base_url + msg['media_name'] + '/1"/></a>';
            } else if (msg['message_type'] == '5') {
                new_str += '<a onclick="delete_snap()" href="" data-url="' + msg['media_name'] + '" target="_blank" class="msg_vid chat_video">';
                msg['media_name'] = msg['media_name'].replace('.mp4', '.png');
                new_str += '<img width="50px" height="50px" src="' + img_base_url + msg['media_name'] + '/1"/></a>';
            } else if (msg['message_type'] == '6') {
                var call_var = '';
                switch (msg['message']) {
                    case '1':
                        call_var = 'Called at';
                        break;
                    case '2':
                        call_var = 'Called at';
                        break;
                    case '3':
                        call_var = 'Called at';
                        break;
                    case '4':
                        call_var = 'Called at';
                        break;
                    case '5':
                        call_var = 'Missed at';
                        break;
                }
                if (msg['message'] == 5)
                    new_str += "<label class='missed'>" + call_var + ' ' + msg_date + "</label>";
                else
                    new_str += call_var + ' ' + msg_date;
            }

            new_str += '<span>' + msg_date + '</span>' + delete_str + '</p></li>';

            if (i == 0) {
                $('#last_msg_id').val(msg['id']);
            }

        }

        change_message_status(msg_status, all_unread_msg_ids); // Change unread status message to read

        return new_str;
    }

    // Generate HTML for single message
    function generate_new_message(msg, is_db_user) {

        var new_str = '';
        var delete_str = '';
        var all_unread_msg_ids = [];
        var msg_status = '0';

        var img_base_url = '<?php echo base_url() . "bio/show_img/"; ?>';
        if (is_db_user == 'yes') {
            var cls = 'rider-talk';
            var alt_1 = 'rider-talk';
            var img_url = '<?php echo $db_user_img; ?>';
            delete_str = '<a class="remove-chat for_pointer" data-msg-id="' + msg['id'] + '" onclick="delete_chat(this)"> X </a>';
        } else {
            var cls = 'user-talk chat_user';
            var alt_1 = 'chat_user';
            var img_url = '<?php echo $chat_user_img; ?>';
            delete_str = '';
            msg_status = '2';
            all_unread_msg_ids.push(msg['id']);
        }

        var msg_date = formatAMPM(msg["created_date"]);
        // Login User

        new_str += '<li id="li_' + msg['id'] + '" class="' + cls + '"><div class="pic-01">';
        new_str += '<img src="' + img_url + '" alt="' + alt_1 + '" onerror="this.src=<?php echo base_url(); ?>assets/images/default_avatar.jpg" />';
        new_str += '</div><p>'

        if (msg['message_type'] == '1') {
            new_str += atob(msg['encrypted_message']);
        } else if (msg['message_type'] == '2') {
            new_str += '<a class="chat_img" data-fancybox="" href="' + img_base_url + msg['media_name'] + '">';
            new_str += '<img width="50px" height="50px" src="' + img_base_url + msg['media_name'] + '/1"/></a>';
        } else if (msg['message_type'] == '3') {
            new_str += '<a  href="" class="msg_vid chat_video" data-url="' + msg['media_name'] + '" target="_blank">';
            msg['media_name'] = msg['media_name'].replace('.mp4', '.png');
            new_str += '<img width="50px" height="50px" src="' + img_base_url + msg['media_name'] + '/1"/></a>';
        } else if (msg['message_type'] == '5') {

            new_str += '<a data-cls="' + cls + '" data-msg-id="' + msg['id'] + '" onclick="delete_snap(this)" data-url="' + msg['media_name'] + '" target="_blank" class="chat_video for_pointer">';
            msg['media_name'] = msg['media_name'].replace('.mp4', '.png');
            new_str += '<img width="50px" height="50px" src="' + img_base_url + msg['media_name'] + '/1"/></a>';
            delete_str = '';
        } else if (msg['message_type'] == '6') {
            var call_var = '';
            var style_inline = '';
            switch (msg['message']) {
                case '1':
                    call_var = 'Called at';
                    break;
                case '2':
                    call_var = 'Called at';
                    break;
                case '3':
                    call_var = 'Called at';
                    break;
                case '4':
                    call_var = 'Called at';
                    break;
                case '5':
                    call_var = 'Missed at';
                    style_inline = 'style="color:#ff0000;"';
                    break;
            }
            new_str += '<span ' + style_inline + '>' + call_var + ' ' + msg_date + '</span>';
        }

        new_str += '<span>' + msg_date + '</span>' + delete_str + '</p></li>';

        change_message_status(msg_status, all_unread_msg_ids); // Change unread status message to read

        return new_str;
    }

    function delete_chat(obj) {

        var is_confirm = confirm("Are you sure for delete this message ?");

        if (is_confirm) {
            var msg_id = $(obj).data('msg-id');
            $.ajax({
                url: '<?php echo base_url() . "message/delete_chat"; ?>',
                data: {msg_id: msg_id},
                method: 'POST',
                success: function (data) {
                    $('#li_' + msg_id).fadeOut();
                }
            });
        }
    }

    function change_message_status(msg_status, all_unread_msg_ids) {

        if (msg_status != 0 && all_unread_msg_ids.length > 0) {
            $.ajax({
                url: '<?php echo base_url() . "message/change_message_status"; ?>',
                method: 'POST',
                data: {msg_status: msg_status, all_unread_msg_ids: all_unread_msg_ids},
                dataType: 'JSON',
                success: function (data) {
                    var total_unread = data['total_unread'];

                    if (total_unread > 0) {
                        $('.notification-count a').html(total_unread);
                        $('.sidebar_message span.badge').html(total_unread)
                    } else {
                        $('.sidebar_message span.badge').fadeOut()
                        $('.notification-count').fadeOut();
                    }
                }
            });
        }
    }

    // For add miltiple files 
    function duplicate_files() {

        var total_len = $('.myfile').length;

        if ($('.input_file_' + total_len).val() == '' || $('.input_file_' + total_len).val() == undefined) {
            show_notification('<strong> OOPS </strong>', 'Please select file before add any more files.', 'error');
            return false;
        }

        total_len = total_len + 1;

        if (total_len < 11) {
            var new_str_file = '';
            new_str_file += '<div class="input-group myfile div_' + total_len + '">';
            new_str_file += '<input type="text" class="form-control input_file_' + total_len + '" readonly>';
            new_str_file += '<label class="input-group-btn"><span class="btn btn-primary">';
            new_str_file += 'Browse <input type="file" style="display: none;" name="msg_file_' + total_len + '">';
            new_str_file += '</span> </label> </div>';

            $('.all_files').append(new_str_file);

            $('.delete_file').show();

            $(document).on('change', ':file', function () {
                var input = $(this),
                        numFiles = input.get(0).files ? input.get(0).files.length : 1,
                        label = input.val().replace(/\\/g, '/').replace(/.*\//, '');
                input.trigger('fileselect', [numFiles, label]);
            });

            $(document).ready(function () {
                $(':file').on('fileselect', function (event, numFiles, label) {
                    var input = $(this).parents('.input-group').find(':text'),
                            log = numFiles > 1 ? numFiles + ' files selected' : label;
                    if (input.length) {
                        input.val(log);
                    } else {
                        // if( log ) alert(log);
                    }
                });
            });

        } else {
            show_notification('<strong> OOPS </strong>', 'Can enter only upto 10 files at a time.', 'error');
        }
    }

    // Remove file
    function remove_file() {
        var total_len = $('.myfile').length;
        $('.div_' + total_len).remove();
        if (total_len == 2) {
            $('.delete_file').hide();
        }
    }

    function send_snap_request(user1, user2) {
        console.log('Over here');
        // New VideoSnap Request        

        socket.emit('New VideoSnap Request', {
            'requestby_id': user1,
            'requestto_id': user2,
            'status': '1'
        }, function (data) {
            console.log(data);
            if (data.status == '1') {
                $('.send_req').after('<a class="for_pointer already-requested" title="Already Requested"><img src="<?php echo base_url() . 'assets/images/video-request.png'; ?>" alt="img"></a>');
                $('.send_req').remove();
            }
        });
    }

    function delete_snap(obj) {
        var curr_clss = $(obj).data('cls');
        var msg_id = $(obj).data('msg-id');
        var vid_url = $(obj).data('url');

        if (curr_clss == 'user-talk' || curr_clss == 'user-talk chat_user') {
            $.ajax({
                url: '<?php echo base_url() . "message/update_message_status"; ?>',
                method: 'POST',
                data: {vid_url: vid_url, msg_id: msg_id},
                dataType: 'JSON',
                success: function (data) {
                    $('#li_' + msg_id).fadeOut();
                    window.open('<?php echo base_url() . "video/play/" ?>' + data['media_id'], '_blank');
                }
            });
        }
    }

</script>