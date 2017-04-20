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
                <div class="col-md-6 col-sm-6 col-xs-12"><p>© 2017 <a href="<?php echo base_url(); ?>">LUVR</a>. All Rights Reserved.</p></div>
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
<script type="text/javascript">
    function log(text) {
        console.log(text);
    }
    function showMsg(text, msg_class, autofadeout) {
        $("#msg_txt").html(text);
        $("#msg_txt").attr("class", msg_class);
        if (autofadeout == true)
            $('#msg_txt').fadeIn().delay(8000).fadeOut('slow');
        else
            $('#msg_txt').fadeIn();
    }
    function scrollToElement(id) {
        $('html,body').animate({
            scrollTop: $(id).offset().top}, 'slow');
    }    

</script>
    
        
    
    <link rel="stylesheet" type="text/css" href="<?php echo base_url() . 'assets/css/jquery.fancybox.min.css'; ?>">
    <script src="<?php echo base_url() . 'assets/js/jquery.fancybox.min.js'; ?>" type="text/javascript"></script>
    <script type="text/javascript" src="<?php echo base_url().'assets/js/bootstrap-notify.js'; ?>"></script>    

</body>