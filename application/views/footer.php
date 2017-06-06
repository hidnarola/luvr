<?php
$sess_user_data = $this->session->userdata('user');
?>
<?php if ($sub_view != "Homepage") { ?>
    </div>
    </div>
    <?php if ($sub_view == "match/nearByMatches" || $sub_view == "match/level2") { ?>
        <div id="loader" class="loader-style">
            <div class="loader-container">
                <img src="<?php echo base_url(); ?>assets/images/loader.gif"/>
            </div>
        </div>
    <?php } ?>
    <div id="loader-nodata" class="loader-style" style='background:none;display:none;'>
        <div class="loader-container">
            <img src="<?php echo base_url(); ?>assets/images/loader.gif"/>
            <?php if (empty($nearByUsers) || $nearByUsers == null) { ?>
                <p>Hey Luvr! Right now, there is no one else to Luv in your area! Check back soon!<br/>We are growing fast with your help! Spread the word about Luvr on all your social media!</p>
            <?php } ?>
        </div>
    </div>
    </section>
<?php } ?>
<div class="success-message message-css" id="op_success" style="display:none;">
    <div class="message-l">
        <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
             width="510px" height="510px" viewBox="0 0 510 510" style="enable-background:new 0 0 510 510;" xml:space="preserve">
            <g>
                <g id="check-circle-outline">
                    <path d="M150.45,206.55l-35.7,35.7L229.5,357l255-255l-35.7-35.7L229.5,285.6L150.45,206.55z M459,255c0,112.2-91.8,204-204,204
                          S51,367.2,51,255S142.8,51,255,51c20.4,0,38.25,2.55,56.1,7.65l40.801-40.8C321.3,7.65,288.15,0,255,0C114.75,0,0,114.75,0,255
                          s114.75,255,255,255s255-114.75,255-255H459z"/>
                </g>
            </g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g>
        </svg>
    </div>
    <div class="message-r">
        <h3>Success</h3>
        <p>Thanks for your Payment.</p>
    </div>
</div>
<div class="cancel-message message-css" id="op_error" style="display:none;">
    <div class="message-l">
        <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
             viewBox="0 0 490.442 490.442" style="enable-background:new 0 0 490.442 490.442;" xml:space="preserve">
            <g>
                <g>
                    <path d="M270.421,245.342l42-42c7.3-7.1,7.3-18.5,0.2-25.5c-7-7-18.4-7-25.5,0l-42,42l-42-42c-7-7-18.4-7-25.5,0
                          c-7,7-7,18.4,0,25.5l42,42l-42,42c-7,7-7,18.4,0,25.5c3.5,3.5,8.1,5.3,12.7,5.3c4.6,0,9.2-1.8,12.7-5.3l42-42l42,42
                          c3.5,3.5,8.1,5.3,12.7,5.3c4.6,0,9.2-1.8,12.7-5.3c7-7,7-18.4,0-25.5L270.421,245.342z"/>
                </g>
            </g>
            <g>
                <g>
                    <path d="M418.621,71.842c-7-7-18.4-7-25.4,0l-56,55.9c-7,7-7,18.4,0,25.5c7,7,18.4,7,25.5,0l42.7-42.7
                          c69.2,82.1,65.1,205.3-12.2,282.6c-39.5,39.5-92.1,61.3-148,61.3c-55.9,0-108.4-21.7-148-61.2c-81.6-81.6-81.6-214.3,0-295.9
                          c50.1-50.1,121.6-71.4,191.1-56.8c9.7,2,19.3-4.2,21.3-13.9c2-9.7-4.2-19.3-13.9-21.3c-81.4-17.2-165.1,7.7-223.9,66.5
                          c-46.3,46.3-71.8,107.9-71.8,173.4s25.5,127.1,71.8,173.4c46.3,46.3,107.9,71.8,173.4,71.8s127.1-25.5,173.4-71.8
                          s71.8-107.9,71.8-173.4S464.921,118.142,418.621,71.842z"/>
                </g>
            </g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g>
        </svg>
    </div>
    <div class="message-r">
        <h3>Oops!</h3>
        <p>Something went wrong.</p>
    </div>
</div>
<div class="success-message message-css" id="call_statuses" style="display:none;">
    <div class="message-l">
        <img src="" alt="incoming" style="display:none;" id="call_img"/>
    </div>
    <div class="message-r">
        <h3></h3>
        <p></p>
    </div>
</div>
<footer id="footer" class="footer">
    <div class="footer-top">
        <div class="container">
            <div class="quick-link footer-column">
                <h3>&nbsp;</h3>
                <ul class="footer-ul">
                    <li> <a href="https://www.luvr.us">www.luvr.us</a></li>
                </ul>
            </div>
            <div class="ftr-contact footer-column">
                <h3>Contact us</h3>
                <ul class="footer-ul">
                    <li><a href="mailto:support@luvr.com">info@luvr.us</a></li>
                </ul>
            </div>
            <div class="ftr-newsletter footer-column">
                <h3>Get our Newsletter</h3>
                <form>
                    <input type="text" name="" placeholder="E-mail"/>
                    <button type="submit">Send</button>
                </form>
            </div>
            <div class="follow-luvr footer-column">
                <h3>follow on luvr</h3>
                <a href="https://www.facebook.com/therealluvr/" class="">
                    <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                         viewBox="0 0 310 310" style="enable-background:new 0 0 310 310;" xml:space="preserve">
                        <g id="XMLID_834_">
                            <path id="XMLID_835_" d="M81.703,165.106h33.981V305c0,2.762,2.238,5,5,5h57.616c2.762,0,5-2.238,5-5V165.765h39.064
                                  c2.54,0,4.677-1.906,4.967-4.429l5.933-51.502c0.163-1.417-0.286-2.836-1.234-3.899c-0.949-1.064-2.307-1.673-3.732-1.673h-44.996
                                  V71.978c0-9.732,5.24-14.667,15.576-14.667c1.473,0,29.42,0,29.42,0c2.762,0,5-2.239,5-5V5.037c0-2.762-2.238-5-5-5h-40.545
                                  C187.467,0.023,186.832,0,185.896,0c-7.035,0-31.488,1.381-50.804,19.151c-21.402,19.692-18.427,43.27-17.716,47.358v37.752H81.703
                                  c-2.762,0-5,2.238-5,5v50.844C76.703,162.867,78.941,165.106,81.703,165.106z"/>
                        </g>
                        <g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g>
                    </svg>
                </a>
                <a href="https://twitter.com/therealluvr">
                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                         width="512.002px" height="512.002px" viewBox="0 0 512.002 512.002" style="enable-background:new 0 0 512.002 512.002;"
                         xml:space="preserve">
                        <g>
                            <path d="M512.002,97.211c-18.84,8.354-39.082,14.001-60.33,16.54c21.686-13,38.342-33.585,46.186-58.115
                                  c-20.299,12.039-42.777,20.78-66.705,25.49c-19.16-20.415-46.461-33.17-76.674-33.17c-58.011,0-105.042,47.029-105.042,105.039
                                  c0,8.233,0.929,16.25,2.72,23.939c-87.3-4.382-164.701-46.2-216.509-109.753c-9.042,15.514-14.223,33.558-14.223,52.809
                                  c0,36.444,18.544,68.596,46.73,87.433c-17.219-0.546-33.416-5.271-47.577-13.139c-0.01,0.438-0.01,0.878-0.01,1.321
                                  c0,50.894,36.209,93.348,84.261,103c-8.813,2.399-18.094,3.687-27.674,3.687c-6.769,0-13.349-0.66-19.764-1.888
                                  c13.368,41.73,52.16,72.104,98.126,72.949c-35.95,28.176-81.243,44.967-130.458,44.967c-8.479,0-16.84-0.496-25.058-1.471
                                  c46.486,29.807,101.701,47.197,161.021,47.197c193.211,0,298.868-160.062,298.868-298.872c0-4.554-0.104-9.084-0.305-13.59
                                  C480.111,136.775,497.92,118.275,512.002,97.211z"/>
                        </g>
                        <g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g>
                    </svg>
                </a>
                <a href="https://www.instagram.com/therealluvr/">
                    <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                         width="510px" height="510px" viewBox="0 0 510 510" style="enable-background:new 0 0 510 510;" xml:space="preserve">
                        <g>
                            <g id="post-instagram">
                                <path d="M459,0H51C22.95,0,0,22.95,0,51v408c0,28.05,22.95,51,51,51h408c28.05,0,51-22.95,51-51V51C510,22.95,487.05,0,459,0z
                                      M255,153c56.1,0,102,45.9,102,102c0,56.1-45.9,102-102,102c-56.1,0-102-45.9-102-102C153,198.9,198.9,153,255,153z M63.75,459
                                      C56.1,459,51,453.9,51,446.25V229.5h53.55C102,237.15,102,247.35,102,255c0,84.15,68.85,153,153,153c84.15,0,153-68.85,153-153
                                      c0-7.65,0-17.85-2.55-25.5H459v216.75c0,7.65-5.1,12.75-12.75,12.75H63.75z M459,114.75c0,7.65-5.1,12.75-12.75,12.75h-51
                                      c-7.65,0-12.75-5.1-12.75-12.75v-51c0-7.65,5.1-12.75,12.75-12.75h51C453.9,51,459,56.1,459,63.75V114.75z"/>
                            </g>
                        </g>
                        <g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g>
                    </svg>
                </a>
            </div>
        </div>
    </div>
    <div class="footer-bottom">
        <div class="container">
            <div class="row">
                <div class="col-md-6 col-sm-6 col-xs-12"><p>© 2017 <a href="<?php echo base_url('home'); ?>">LUVR</a>. All Rights Reserved.</p></div>
                <div class="col-md-6 col-sm-6 col-xs-12">
                    <div class="policy-link">
                        <a href="https://www.luvr.us/privacy-policy">Privacy</a>
                        <a href="https://www.luvr.us/terms-of-use">Terms of use</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</footer>
<audio style="height:0;width:0;" id="caller_tune">
    <source src="<?php echo base_url(); ?>assets/caller_tune.ogg" type="audio/ogg"/>
    <source src="<?php echo base_url(); ?>assets/caller_tune.mp3" type="audio/mpeg"/>
</audio>
<script type="text/javascript">
    audioElement = document.getElementById('caller_tune');
    if ($("#spplayer1").length > 0)
    {
    jwplayer('spplayer1').setup({
    file: "<?php echo $_SERVER['REQUEST_SCHEME']; ?>://s3.ap-south-1.amazonaws.com/luvr/Videos/Commercials/vid2.mp4",
            image: "<?php echo $_SERVER['REQUEST_SCHEME']; ?>://s3.ap-south-1.amazonaws.com/luvr/Videos/Commercials/vid2.jpg",
            primary:'flash',
            repeat:true,
            autostart:true,
            aspectratio:"16:9",
            width:"100%",
<?php if ($_SERVER['HTTP_HOST'] == 'luvr.me') { ?>
        advertising: {
        client:'vast',
                tag:'<?php echo "https://vast.optimatic.com/vast/getVast.aspx?id=tI8OelBpLoQd&o=3&zone=default&pageURL=" . base_url(uri_string()) . "&pageTitle=BioVideo&cb=" . uniqid() . ""; ?>',
        },
<?php } ?>
    });
    }
    if ($("#spplayer2").length > 0)
    {
    jwplayer('spplayer2').setup({
    file: "<?php echo $_SERVER['REQUEST_SCHEME']; ?>://s3.ap-south-1.amazonaws.com/luvr/Videos/Commercials/vid3.mp4",
            image: "<?php echo $_SERVER['REQUEST_SCHEME']; ?>://s3.ap-south-1.amazonaws.com/luvr/Videos/Commercials/vid3.jpg",
            primary:'flash',
            repeat:true,
            autostart:true,
            aspectratio:"16:9",
            width:"100%",
<?php if ($_SERVER['HTTP_HOST'] == 'luvr.me') { ?>
        advertising: {
        client:'vast',
                tag:'<?php echo "https://vast.optimatic.com/vast/getVast.aspx?id=tI8OelBpLoQd&o=3&zone=default&pageURL=" . base_url(uri_string()) . "&pageTitle=BioVideo&cb=" . uniqid() . ""; ?>',
        },
<?php } ?>
    });
    }
</script>
<script src="//media.twiliocdn.com/sdk/js/video/v1/twilio-video.min.js"></script>
<?php if (!empty($sess_user_data)) { ?>
    <?php if ($sub_view == "message/videocall") { ?>
        <input id="room-name" type="hidden" value="<?php echo $room_id; ?>"/>
        <input id="msgid" type="hidden" value="<?php echo $msg_id; ?>"/>
        <input id="callerid" type="hidden" value="<?php echo $chat_user_data['id']; ?>"/>
        <input id="callingid" type="hidden" value="<?php echo $sess_user_data['id']; ?>"/>
    <?php } else { ?>
        <input id="room-name" type="hidden" value=""/>
        <input id="msgid" type="hidden" value=""/>
        <input id="callerid" type="hidden" value=""/>
        <input id="callingid" type="hidden"/>
    <?php } ?>
    <script type="text/javascript">
    myid = '<?php echo $sess_user_data['id']; ?>';
    </script>
    <script src="<?php echo base_url() . 'assets/js/index.js'; ?>"></script>
    <?php
}
if (!empty($sess_user_data)) {
    $this->load->view('message/video_includes');
    $this->load->view('message/message_includes');
}
?>
<script type="text/javascript">
    function log(text) {
    console.log(text);
    }
    function showMsg(text, mode, autofadeout) {
    if (mode == "success")
    {
    $("#op_success .message-r p").html(text);
    if (autofadeout == true)
            $("#op_success").fadeIn().delay(8000).fadeOut('slow');
    else
            $('#op_success').fadeIn();
    } else
    {
    $("#op_error .message-r p").html(text);
    if (autofadeout == true)
            $("#op_error").fadeIn().delay(8000).fadeOut('slow');
    else
            $('#op_error').fadeIn();
    }
    }
    function showMsgCall(text, mode, autofadeout) {
    if (mode == "incoming")
    {
    $("#call_statuses .message-r h3").html("Incoming Call");
    $("#call_statuses .message-r p").html(text);
    $("#call_statuses").addClass('success-message');
    $("#call_statuses").removeClass('cancel-message');
    if (autofadeout == true)
            $("#call_statuses").fadeIn().delay(8000).fadeOut('slow');
    else
            $('#call_statuses').fadeIn();
    } else if (mode == "rejected")
    {
    $("#call_statuses .message-r h3").html("Call Rejected!");
    $("#call_statuses .message-r p").html(text);
    $("#call_statuses").addClass('cancel-message');
    $("#call_statuses").removeClass('success-message');
    if (autofadeout == true)
            $("#call_statuses").fadeIn().delay(8000).fadeOut('slow');
    else
            $('#call_statuses').fadeIn();
    } else
    {
    $("#call_statuses .message-r h3").html(text);
    $("#call_statuses .message-r p").html(text);
    $("#call_statuses").addClass('cancel-message');
    $("#call_statuses").removeClass('success-message');
    if (autofadeout == true)
            $("#call_statuses").fadeIn().delay(8000).fadeOut('slow');
    else
            $('#call_statuses').fadeIn();
    }
    }
    function scrollToElement(id) {
    $('html,body').animate({
    scrollTop: $(id).offset().top}, 'slow');
    }
</script>
<link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/css/jquery.fancybox.min.css'; ?>"/>
<script src="<?php echo base_url() . 'assets/js/jquery.fancybox.min.js'; ?>" type="text/javascript"></script>
<script type="text/javascript" src="<?php echo base_url() . 'assets/js/bootstrap-notify.js'; ?>"></script>    
</body>