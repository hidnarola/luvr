<?php
    
    $sess_user_data = $this->session->userdata('user');    
    $db_user_img = my_img_url($db_user_media['media_type'],$db_user_media['media_thumb']);
    $chat_user_img = my_img_url($chat_user_media['media_type'],$chat_user_media['media_thumb']);

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
                    <img src="<?php echo $chat_user_img; ?>" alt="<?php echo $chat_user_data['user_name']; ?>" />
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
                                <ul id="all_messages_ul">
                                    <?php 
                                        //user-talk ,  
                                        if(!empty($all_messages)){
                                            foreach($all_messages as $message){
                                                if($message['sender_id'] == $db_user_data['id']){
                                                    // Login User
                                                    $cls = 'rider-talk';
                                                    $img_type = $message['med1_type'];
                                                    $img_url = my_img_url($img_type,$message['med1_name']);
                                                }else{
                                                    // Chat User
                                                    $cls = 'user-talk';
                                                    $img_type = $message['med2_type'];
                                                    $img_url = my_img_url($img_type,$message['med2_name']);
                                                }
                                    ?>
                                        <li class="<?php echo $cls; ?>">
                                            <div class="pic-01">
                                                <img src="<?php echo $img_url; ?>" alt="" onerror="this.src='<?php echo base_url(); ?>assets/images/default_avatar.jpg'" />
                                            </div>
                                            <p><?php echo $message['message']; ?></p>
                                        </li>
                                    <?php
                                            }
                                        }
                                    ?>
                                </ul>
                            </div>
                            <form method="post" onsubmit="event.preventDefault(); submit_message(this); " id="msg_form" enctype="multipart/form-data">
                                <div class="col-md-6 col-sm-6 col-xs-12 mar-btm-20 choose-file">
                                    <h6>Upload Image or Video</h6>
                                    <div class="input-group">
                                        <input type="text" class="form-control" readonly>
                                        <label class="input-group-btn">
                                            <span class="btn btn-primary">
                                                Browse <input type="file" style="display: none;" name="msg_file">
                                            </span>
                                        </label>
                                    </div>  
                                </div>

                                <div class="message-to-talk">
                                    <!--  -->
                                    <textarea name="message" 
                                             onkeypress="socket.emit('Typing',{'receiver_id':<?php echo $chat_user_id; ?>});"
                                             id="msg_id" placeholder="Write here..."></textarea>
                                    <h3>You're writing a message to you like it</h3>
                                    <input type="hidden" name="session_id" id="session_id" value="<?php echo $db_user_data['id']; ?>">
                                    <input type="hidden" name="chat_user_id" id="chat_user_id" value="<?php echo $chat_user_id; ?>">
                                    
                                    <input type="hidden" name="db_user_img" id="db_user_img" value="<?php echo $db_user_img; ?>">
                                    <input type="hidden" name="chat_user_img" id="chat_user_img" value="<?php echo $chat_user_img; ?>">

                                    <button type="submit"> Send Message </button>
                                    <?php if($is_active_usr == '1') { ?>
                                        <button type="button" onclick="location.href='<?php echo base_url('message/videocall/'.$chat_user_data['id']) ?>'">
                                            Video Call
                                        </button>
                                    <?php } ?>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<input type="hidden" id="last_msg_id" val="">

<script type="text/javascript">

    function formatAMPM(date) {
        date = date.replace(' +0000','');
        date = new Date(date);
        var hours = date.getHours();
        var minutes = date.getMinutes();
        var ampm = hours >= 12 ? 'pm' : 'am';
        hours = hours % 12;
        hours = hours ? hours : 12; // the hour '0' should be '12'
        minutes = minutes < 10 ? '0'+minutes : minutes;
        var strTime = hours + ':' + minutes + ' ' + ampm;
        return strTime;
    }

    function set_video_url(){
        $.each($('.msg_vid'), function(index, val) {            
            $.post('<?php echo base_url()."message/get_video_id"; ?>',
                {image_name:$(val).data('url')},
                function(data){
                    $(val).attr('href','<?php echo base_url()."video/play/"; ?>'+data);
                    $(val).removeClass('msg_vid');
                });            
        });
    }

    // Manually call Join_socket     
    // var socket = io.connect( 'http://'+window.location.hostname+':8100' ); // Load socket connection into header

    socket.emit('join_socket_web', {
        'userID':'<?php echo $db_user_data["id"]; ?>',        
        'is_login':'1',
        'app_version':'<?php echo $chat_user_data["app_version"]; ?>'
    });

    socket.emit( 'Get Old Messages', {
        'app_version':'<?php echo $chat_user_data["app_version"]; ?>',
        'chat_user' :'<?php echo $chat_user_id; ?>',
        'message_id':'<?php echo $last_message_id + 1; ?>'
    });

    //socket.emit('get_users');   

    //-------------------------------------------------------------------------------------------------    
    // Get initial messages limit of 10 and also fetch pagination for next 10 and so on
    socket.on('Get Old Messages',function(data){
        
        console.log('Get Old Messages');

        if(data.messages.length > 0){
            
            var first_id = data['messages'][0]['id'];
            data.messages = data.messages.reverse();
            var new_str = generate_all_message(data.messages);
            
            if($.trim($('#all_messages_ul').html()).length == 0){                
                $('#all_messages_ul').html(new_str);
                $(".message-to-owner").scrollTop($('#all_messages_ul').prop("scrollHeight")); // Scroll Bottom of that DIV
            }else{
                $('.message-to-owner').animate({
                   scrollTop: $('#'+$('#all_messages_ul li:first').attr('id')).offset().top
                }, 500);
                
                if($('#all_messages_ul li').length != 0){
                    $('#all_messages_ul li:first').before(new_str);
                }else{
                    $('#all_messages_ul').html(new_str);
                }
            }

            set_video_url(); // Set Video URl for the message
        }
    });

    socket.on('New Message',function(data){
        console.log('New Message');
        if($('#all_messages_ul li').length != 0){
            $('#all_messages_ul li:last').after(generate_new_message(data,'no'));
        }else{
            $('#all_messages_ul').html(generate_new_message(data,'no'));
        }
        $('.message-to-owner').animate({
           scrollTop: $('#all_messages_ul').prop("scrollHeight")
        }, 1000);
        set_video_url(); // Set Video URl for the message
    });

    socket.on('Typing',function(data){
        // console.log(data);
    });

    $('.message-to-owner').scroll(function() {
        var pos = $('.message-to-owner').scrollTop();
        
        if (pos == 0) {
            socket.emit( 'Get Old Messages', {
                'app_version':'<?php echo $chat_user_data["app_version"]; ?>',
                'chat_user' :'<?php echo $chat_user_id; ?>',
                'message_id':$('#last_msg_id').val()        
            });

        }
    });

    //-------------------------------------------------------------------------------------------
    
    function submit_message(obj){
        
        var message = $.trim($('#msg_id').val());

        var formData = new FormData($(obj)[0]);

        $.ajax({
            url:"<?php echo base_url().'message/insert_data'; ?>",
            method:'POST',
            dataType:'json',
            async: false,
            cache: false,
            contentType: false,
            processData: false,
            data:formData,
            success:function(data){

                $('#msg_form')[0].reset(); // Reset Form                                

                if(data['message_type'] == '1' &&  data['message'] == ''){
                    show_notification('<strong> OOPS </strong>',
                                    'Please enter message field.',
                                    'error');
                    return false;
                }                

                socket.emit('New Message',{
                    'message_type':data['message_type'],
                    'message':data['message'],                    
                    'media_name':data['media_name'],
                    'unique_id':data['unique_id'],
                    'sender_id':data['sender_id'],
                    'receiver_id':data['receiver_id'],                    
                    'created_date':data['created_date'],                    
                    'is_encrypted':data['is_encrypted'],
                    'encrypted_message':data['encrypted_message']
                },function(data){

                });

                if($('#all_messages_ul li').length != 0){
                    $('#all_messages_ul li:last').after(generate_new_message(data,'yes'));
                }else{
                    $('#all_messages_ul').html(generate_new_message(data,'yes'));
                }

                $('.message-to-owner').animate({
                   scrollTop: $('#all_messages_ul').prop("scrollHeight")
                }, 1000);  

                set_video_url(); // Set Video URl for the message
            }
        });
    }

    // Generate HTML string for the old messgaes and also for paginations old messages
    function generate_all_message(messages){
        var new_str = '';
        var msg = [];
        var sess_user_id = '<?php echo $db_user_data["id"]; ?>';
        var cls = ''; var alt_1= '';
        var img_url = '';
        var img_base_url = '<?php echo base_url()."bio/show_img/"; ?>';        
        var delete_str = '';

        for(var i =0; i<messages.length; i++){

            msg = messages[i];

            var msg_date = formatAMPM(msg["created_date"]);

            if(msg['sender_id'] == sess_user_id){                
                cls = 'rider-talk session_user';
                alt_1 = 'session_user';
                img_url = '<?php echo $db_user_img; ?>';
                delete_str = '<a data-msg-id="'+msg['id']+'" onclick="delete_chat(this)"> X </a>';
            }else{                
                cls = 'user-talk chat_user';
                alt_1 = 'chat_user';
                img_url = '<?php echo $chat_user_img; ?>';
                delete_str = '';
            }

            new_str += '<li id="li_'+msg['id']+'" class="'+cls+'"><div class="pic-01">';
            new_str += '<img src="'+img_url+'" alt="'+alt_1+'" onerror="this.src=<?php echo base_url(); ?>assets/images/default_avatar.jpg" />';
            new_str += '</div><p>';
            
            // 1: text message 2: Image 3: Video
            if(msg['message_type'] == '1'){
                new_str += atob(msg['encrypted_message']);
            }else if(msg['message_type'] == '2'){                
                new_str += '<a data-fancybox="" href="'+img_base_url+msg['media_name']+'">';
                new_str += '<img width="50px" height="50px" src="'+img_base_url+msg['media_name']+'/1"/></a>';
            }else if(msg['message_type'] == '3'){                
                new_str += '<a href="" class="msg_vid" data-url="'+msg['media_name']+'" target="_blank">';
                msg['media_name'] = msg['media_name'].replace('.mp4','.png');
                new_str += '<img width="50px" height="50px" src="'+img_base_url+msg['media_name']+'/1"/></a>';
            }

            new_str +='<span>'+msg_date+'</span>'+delete_str+'</p></li>';

            if(i == 0){ $('#last_msg_id').val(msg['id']); }
         
        }
        return new_str;
    }

    // Generate HTML for single message
    function generate_new_message(msg,is_db_user){
        
        var new_str = '';
        var delete_str = '';

        var img_base_url = '<?php echo base_url()."bio/show_img/"; ?>';
        if(is_db_user == 'yes'){
            var cls = 'rider-talk'; 
            var alt_1= 'rider-talk';
            var img_url = '<?php echo $db_user_img; ?>';
            delete_str = '<a data-msg-id="'+msg['id']+'" onclick="delete_chat(this)"> X </a>';
        }else{
            var cls = 'user-talk chat_user'; 
            var alt_1= 'chat_user';
            var img_url = '<?php echo $chat_user_img; ?>';
            delete_str = '';
        }

        var msg_date = formatAMPM(msg["created_date"]);
        // Login User
        
        new_str += '<li id="li_'+msg['id']+'" class="'+cls+'"><div class="pic-01">';
        new_str += '<img src="'+img_url+'" alt="'+alt_1+'" onerror="this.src=<?php echo base_url(); ?>assets/images/default_avatar.jpg" />';
        new_str += '</div><p>'
        
        if(msg['message_type'] == '1'){
            new_str += atob(msg['encrypted_message']);
        }else if(msg['message_type'] == '2'){
            new_str += '<a data-fancybox="" href="'+img_base_url+msg['media_name']+'">';
            new_str += '<img width="50px" height="50px" src="'+img_base_url+msg['media_name']+'/1"/></a>';
        }else if(msg['message_type'] == '3'){
            new_str += '<a href="" class="msg_vid" data-url="'+msg['media_name']+'" target="_blank">';
            msg['media_name'] = msg['media_name'].replace('.mp4','.png');
            new_str += '<img width="50px" height="50px" src="'+img_base_url+msg['media_name']+'/1"/></a>';
        }

        new_str += '<span>'+msg_date+'</span>'+delete_str+'</p></li>';
        return new_str;
    }

    function delete_chat(obj){
        var msg_id = $(obj).data('msg-id');
        $.ajax({
            url:'<?php echo base_url()."message/delete_chat"; ?>',
            data:{msg_id:msg_id},
            method:'POST',
            success:function(data){
                $('#li_'+msg_id).fadeOut();
            }
        });        
    }

</script>