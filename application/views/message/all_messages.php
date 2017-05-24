<?php
    $sess_user_data = $this->session->userdata('user');
    // pr($all_matches);
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
                <div class="chatlist-user">
                    <h5>New matches</h6>
                    <div class="chatlist-user-wrap">
                        <ul>
                            <?php
                                if(!empty($all_matches)){
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
                                }
                            ?>
                        </ul>
                    </div>  
                </div>
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
                                    if(!empty($all_messages)){
                                        foreach($all_messages as $msg){

                                            $fetch_user_id = $msg['sender_id'];

                                            if($msg['sender_id'] == $db_user_data['id']){
                                                $fetch_user_id = $msg['receiver_id'];
                                            }

                                            $fetch_user_data = $this->Messages_model->get_user_message_data($fetch_user_id);

                                            $decode_uname = base64_decode($fetch_user_data['encrypted_username']);
                                            $img_user = my_img_url($fetch_user_data['media_type'],$fetch_user_data['media_thumb']);
                                ?>
                                    <tr onclick="window.location.href='<?php echo base_url()."message/chat/".$fetch_user_id; ?>' " style="cursor:pointer" >
                                        <td>
                                            <div class="message-user-pic">
                                                <span class="radius">
                                                    <img src="<?php echo $img_user; ?>" alt=""  onerror="this.src='<?php echo base_url(); ?>assets/images/default_avatar.jpg'"/>
                                                </span>
                                                <h4><?php echo $decode_uname; ?></h4>
                                            </div>
                                        </td>
                                        <td>
                                            <div class="message-count-div">
                                                <h6>Location <span>(US)</span> </h6>
                                            </div>
                                        </td>
                                        <td>
                                            <p>Wednesday 24 August 14 - 18 </p>
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
    var socket = io.connect( 'https://'+window.location.hostname+':8100' );
 
    

</script>