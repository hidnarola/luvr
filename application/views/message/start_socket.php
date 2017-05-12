<html>
<head>
	<title></title>
	<?php	    
	    $js = array('jquery.min.js', 'bootstrap.min.js');	    
	    $this->minify->js($js);
	    echo $this->minify->deploy_js(FALSE, 'combined.min.js');
    ?> 
	<script src="<?php echo base_url('node_modules/socket.io/node_modules/socket.io-client/socket.io.js');?>"></script>

	<script type="text/javascript">   
	    // Manually call Join_socket     
	   	var socket = io.connect( 'http://'+window.location.hostname+':8100');
	   	
	   	socket.emit('join_socket_web', {
	        'userID':'<?php echo $u_data["id"]; ?>',
	        'is_login':'1',
	        'app_version':'<?php echo $u_data["app_version"]; ?>'
	    });
 
	</script>
</head>
<body>

</body>
</html>