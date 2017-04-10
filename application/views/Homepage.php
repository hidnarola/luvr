<h1>Welcome to Luvr!</h1>
<div id="body">
    <p>We will be online soon...</p>
    <a href="https://api.instagram.com/oauth/authorize/?client_id=<?php echo INSTA_CLIENT_ID; ?>&redirect_uri=<?php echo base_url() . 'register/return_url'; ?>&response_type=code&scope=likes+comments+follower_list+relationships+public_content" class="btn btn-primary">Login with instagram</a>
</div>