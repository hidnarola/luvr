<br/>
<br/>
<br/>

<a href="https://api.instagram.com/oauth/authorize/?client_id=<?php echo INSTA_CLIENT_ID; ?>&redirect_uri=<?php echo base_url().'register/return_url'; ?>&response_type=code&scope=likes+comments+follower_list+relationships+public_content"
   class="btn btn-primary" >Login with instagram</a>

<a href="<?php echo $fb_login_url; ?>" class="btn btn-primary" >Login with FB</a>

<?php	
	$url = 'https://www.facebook.com/logout.php?next=' . base_url() .'&access_token=EAAbDCk5vXTEBADwSVkjjHgteqbGS7zZCgn5St0HCfyBrKbP1ocqBiHG4QH9wHqBuRpHvhCV4wEuxVIf8TugY3X5pLRTgiHIF8XYYZCeu32KlUD0aCHJP1kgDQTlmRYj4Nwa54rZCMiwZBAR3RnEvaQ8ZBbCgpoZBdH8Rdh4QBzeQZDZD';
?>

<a href="<?php echo $url; ?>">
	Logout
</a>