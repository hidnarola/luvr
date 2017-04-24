<?php if($loginwith == 'instagram') { ?>
	<iframe src="https://instagram.com/accounts/logout/" width="0" height="0" onload="location.href = '<?php echo base_url(); ?>'" frameBorder="0"/>
<?php } else { ?>	
	<script type="text/javascript">
		window.location.href="<?php echo $fb_url; ?>";
	</script>
<?php } ?>