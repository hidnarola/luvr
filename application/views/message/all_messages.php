<?php
    $sess_user_data = $this->session->userdata('user');    

    $all_sorted_msgs = [];

    if(!empty($all_messages)){
        $i = 0;
        foreach($all_messages as $msg){
            $fetch_user_id = $msg['sender_id'];

            if($msg['sender_id'] == $db_user_data['id']){
                $fetch_user_id = $msg['receiver_id'];
            }

            $fetch_user_data = $this->Messages_model->get_user_message_data($fetch_user_id);
            
            $unread_cnt = $this->Messages_model->unread_cnt_message_indivisual($fetch_user_id,$sess_user_data['id']);
            $decode_uname = base64_decode($fetch_user_data['encrypted_username']);
            $img_user = my_img_url($fetch_user_data['media_type'],$fetch_user_data['media_thumb']);
            $last_msg = $this->Messages_model->fetch_last_message($sess_user_data['id'],$fetch_user_id);            

            $all_sorted_msgs[$i]['fetch_user_id'] = $fetch_user_id;
            $all_sorted_msgs[$i]['img_user'] = $img_user;
            $all_sorted_msgs[$i]['decode_uname'] = $decode_uname;
            $all_sorted_msgs[$i]['unread_cnt'] = $unread_cnt;

            $all_sorted_msgs[$i]['msg_type'] = $last_msg['message_type'];
            $all_sorted_msgs[$i]['msg_text'] = base64_decode($last_msg['encrypted_message']);
            $all_sorted_msgs[$i]['msg_created'] = $last_msg['created_date'];

            $i++;
        }
        usort($all_sorted_msgs, 'date_compare');
    }    

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
        <div class="account-r-body">
            <div class="account-body-head">
                <h2 class="account-title">My Chat</h2>                                   
            </div>  
            <div class="account-body-body chat-related">
                <?php if(!empty($all_matches)){ ?>
                    <div class="chatlist-user">
                        <h5>New matches</h6>
                        <div class="chatlist-user-wrap">
                            <ul>
                                <?php                                
                                    foreach($all_matches as $match){                                        
                                        
                                        if(in_array($match['id'], $all_messages_user_ids) == false) {

                                            $img_user = my_img_url($match['media_type'],$match['media_thumb']);
                                            $decode_uname = base64_decode($match['encrypted_username']);
                                ?>
                                    <li>
                                        <a href="<?php echo base_url().'message/chat/'.$match['id']; ?>">
                                            <span>
                                                <img src="<?php echo $img_user; ?>" alt="" onerror="this.src='<?php echo base_url(); ?>assets/images/default_avatar.jpg'" />
                                            </span>
                                            <h4>
                                                <?php echo character_limiter($decode_uname,1); ?>
                                            </h4>
                                        </a>
                                    </li>
                                <?php
                                        }
                                    }
                                ?>
                            </ul>
                        </div>  
                    </div>
                <?php } ?>
                <div class="message-conversations">
                    <div class="link-tab">
                        <ul class="nav">
                            <li>
                                <a href="">Messages</a>
                            </li>
                        </ul>
                    </div>
                    <div class="tab-content">
                        <table class="table">
                            <tbody>
                                <?php
                                    if(!empty($all_sorted_msgs)){
                                        foreach($all_sorted_msgs as $msg_new){                                            
                                ?>
                                    <tr onclick="window.location.href='<?php echo base_url()."message/chat/".$msg_new['fetch_user_id']; ?>' " style="cursor:pointer" >
                                        <td>
                                            <div class="message-user-pic">
                                                <span class="radius">
                                                    <img src="<?php echo $msg_new['img_user']; ?>" alt=""  onerror="this.src='<?php echo base_url(); ?>assets/images/default_avatar.jpg'"/>
                                                </span>
                                                <h4>
                                                    <?php echo $msg_new['decode_uname']; ?>
                                                    <?php echo '<small>'.$msg_new['unread_cnt'].'</small>'; ?>
                                                </h4>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="message-count-div">
                                                <h6>
                                                    <?php 
                                                        if($msg_new['msg_type'] == '1') {
                                                            echo $msg_new['msg_text'];
                                                        }else if($msg_new['msg_type'] == '2'){
                                                            echo 'Image';
                                                        }else if($msg_new['msg_type'] == '3'){
                                                            echo 'Video';
                                                        }else if($msg_new['msg_type'] == '5'){
                                                            echo 'Video Snap';
                                                        }else if($msg_new['msg_type'] == '6'){
                                                            switch ($msg_new['msg_text']) {
                                                                case '1': echo 'Called'; break;
                                                                case '2': echo 'Called'; break;
                                                                case '3': echo 'Called'; break;
                                                                case '4': echo 'Called'; break;
                                                                case '5': echo 'Called'; break;
                                                                case '6': echo 'Missed call'; break;
                                                            }
                                                        }
                                                    ?>
                                                </h6>
                                            </div>
                                        </td>
                                        <td>
                                            <p><?php echo $msg_new['msg_created']; ?> </p>
                                        </td>
                                    </tr>
                                <?php
                                        }
                                    }
                                ?>

                            </tbody>
                        </table>
                    </div>      
                </div>
            </div>
        </div>
    </div>
</div>

<script type="text/javascript">    
    // Manually call Join_socket
</script>