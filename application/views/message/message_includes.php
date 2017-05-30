<?php
	$sess_user_data = $this->session->userdata('user');
?>
<!-- v! Message include -->
<script type="text/javascript">	
	socket.on('New Message',function(data){    	
    	$.get('<?php echo base_url()."message/get_msg_unread_cnt"; ?>',
    			function(data){ 
    				show_notification('<strong> Success </strong>',
                                    'You have a new unread message.',
                                    'success');    				
    				var total_cnt_message = parseInt(data);
    				var new_str = '';

    				new_str += '<div class="notification-count">';
    				new_str += '<a href="<?php echo base_url()."message/all_chats"; ?>">';
    				new_str += total_cnt_message;
    				new_str += '</a></div>';    				  

    				if(total_cnt_message > 0){
    					$('.notification-count:first').remove();
    					$('#dropdownMenu1').before(new_str);
    				}
    			});
    });

</script>