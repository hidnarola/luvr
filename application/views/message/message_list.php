<?php
    
    $sess_user_data = $this->session->userdata('user');    
    $db_user_img = my_img_url($db_user_media['media_type'],$db_user_media['media_thumb']);
    $chat_user_img = my_img_url($chat_user_media['media_type'],$chat_user_media['media_thumb']);

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
                                <h3>You're writing a message to you like it <a href=""> see details</a></h3>

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
                                    <textarea name="message" id="msg_id" placeholder="Write here..."></textarea>
                                    
                                    <input type="hidden" name="session_id" id="session_id" value="<?php echo $db_user_data['id']; ?>">
                                    <input type="hidden" name="chat_user_id" id="chat_user_id" value="<?php echo $chat_user_id; ?>">
                                    
                                    <input type="hidden" name="db_user_img" id="db_user_img" value="<?php echo $db_user_img; ?>">
                                    <input type="hidden" name="chat_user_img" id="chat_user_img" value="<?php echo $chat_user_img; ?>">

                                    <button type="submit"> Send Message </button>
                                    <p>Typesetting remaining essentially unchanged and scrambled. <br/> type specimen book has. <a href="#">see guidelines</a></p>
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
    
    // Manually call Join_socket     
    var socket = io.connect( 'http://'+window.location.hostname+':8100' );

    socket.emit('join_socket_web', {
        'userID':'<?php echo $db_user_data["id"]; ?>',        
        'is_login':'1',
        'app_version':'<?php echo $chat_user_data["app_version"]; ?>'
    });

    socket.emit( 'Get Old Messages', {
        'app_version':'<?php echo $chat_user_data["app_version"]; ?>',
        'chat_user' :'<?php echo $chat_user_id; ?>',
        'message_id':'<?php echo $last_message_id; ?>'        
    });

    //-------------------------------------------------------------------------------------------------    
    // Get initial messages limit of 10
    socket.on('Get Old Messages',function(data){
        console.log('Get OLD Messages');        
        if(data.messages.length > 0){
            
            var first_id = data['messages'][0]['id'];
            
            data.messages = data.messages.reverse();

            var new_str = generate_msg_html(data.messages);
            
            if($.trim($('#all_messages_ul').html()).length == 0){
                $('#all_messages_ul').html(new_str);
                $(".message-to-owner").scrollTop($('#all_messages_ul').prop("scrollHeight")); // Scroll Bottom of that DIV
            }else{
                $('#all_messages_ul li:first').before(new_str);
                
                // $('.message-to-owner').animate({
                //    scrollTop: $("#"+first_id).offset().top
                // }, 1000);
            }            

        }
    });

    socket.on('Get New Messages',function(data){
        console.log('Get New Messages');
        console.log(data);
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
        //message = $.trim(message);

        if(message == ''){
            show_notification('<strong> OOPS </strong>',
                            'Please enter message field.',
                            'error');
            return false;
        }

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
                // console.log(data);
                $('#msg_form')[0].reset(); // Reset Form
                //var socket = io.connect( 'http://'+window.location.hostname+':8100' );
                
            }
        });        
    }

    function generate_msg_html(messages){
        var new_str = '';
        var msg = [];
        var sess_user_id = '<?php echo $db_user_data["id"]; ?>';
        var cls = ''; var alt_1= '';
        var img_url = '';

        for(var i =0; i<messages.length; i++){

            msg = messages[i];

            if(msg['sender_id'] == sess_user_id){                
                cls = 'rider-talk session_user';
                alt_1 = 'session_user';
                img_url = '<?php echo $db_user_img; ?>';
            }else{                
                cls = 'user-talk chat_user';
                alt_1 = 'chat_user';
                img_url = '<?php echo $chat_user_img; ?>';
            }

            new_str += '<li id="'+msg['id']+'" class="'+cls+'"><div class="pic-01">';
            new_str += '<img src="'+img_url+'" alt="'+alt_1+'" onerror="this.src=<?php echo base_url(); ?>assets/images/default_avatar.jpg" />';
            new_str += '</div><p>'+msg['message']+'</p></li>';

            if(i == 0){ $('#last_msg_id').val(msg['id']); }
        }
        return new_str;
    }

    

</script>