<?php
$user_data = $this->session->userdata('user');
$error = $this->session->flashdata('error');
$playlist[0] = array("file" => ASSETS_URL . "/Videos/Commercials/vid1.mp4", "image" => ASSETS_URL . "/Videos/Commercials/vid1.jpg");
$playlist[1] = array("file" => ASSETS_URL . "/Videos/Commercials/vid2.mp4", "image" => ASSETS_URL . "/Videos/Commercials/vid2.jpg");
$playlist[2] = array("file" => ASSETS_URL . "/Videos/Commercials/vid3.mp4", "image" => ASSETS_URL . "/Videos/Commercials/vid3.jpg");
$playlist = json_encode($playlist);
/* $ad_url = "https://vast.optimatic.com/vast/getVast.aspx?id=tI8OelBpLoQd&o=3&zone=default&pageURL=" . base_url(uri_string()) . "&pageTitle=BioVideo&cb=" . uniqid() . ""; */
$ad_url = "" . $_SERVER['REQUEST_SCHEME'] . "://search.spotxchange.com/vast/2.0/202107?VPAID=JS&content_page_url=" . _current_url() . "&cb=" . uniqid(time()) . "&player_width=1024&player_height=768";
?>
<section id="hero-section" class="hero-section">
    <ul class="bxslider">
        <li>
            <img src="<?php echo base_url(); ?>assets/images/hero-01.jpg" />
            <div class="hero-caption">
                <h2>Welcome to the Future <br/>of Online Dating.</h2>
                <p>Introducing Luvr, the world's first and <br/>most advanced video dating application.<br/>Luvr allows you to connect with your <br/>perfect match via 3 levels of <br/>video based communication.</p>
                <a href="#packages">View more</a>
            </div>
        </li>
        <li>
            <img src="<?php echo base_url(); ?>assets/images/hero-02.jpg" />
            <div class="hero-caption">
                <h2>Welcome to the Future <br/>of Online Dating.</h2>
                <p>Introducing Luvr, the world's first and <br/>most advanced video dating application.<br/>Luvr allows you to connect with your <br/>perfect match via 3 levels of <br/>video based communication.</p>
                <a href="#packages">View more</a>
            </div>
        </li>
        <li>
            <img src="<?php echo base_url(); ?>assets/images/hero-03.jpg" />
            <div class="hero-caption">
                <h2>Welcome to the Future <br/>of Online Dating.</h2>
                <p>Introducing Luvr, the world's first and <br/>most advanced video dating application.<br/>Luvr allows you to connect with your <br/>perfect match via 3 levels of <br/>video based communication.</p>
                <a href="#packages">View more</a>
            </div>
        </li>
    </ul>
    <div class="homepage-player-outer">
        <div id="hpplayer"></div>
    </div>
    <script type="text/javascript">
        var isPaused = false;
        var player_hp = jwplayer('hpplayer');
        player_hp.setup({
        playlist: <?php echo $playlist; ?>,
                repeat:true,
                autostart:true,
                aspectratio:"16:9",
                width:"100%",
<?php if ($_SERVER['HTTP_HOST'] == 'luvr.me') { ?>
            /*advertising: {
            client:'vast',
                    tag:'<?php echo $ad_url; ?>',
                    requestTimeout:20000
            },*/
<?php } ?>
        });
                jwplayer().onPlaylistItem(function () {
            manageCounter();
        });
        jwplayer().onPlay(function () {
            isPaused = false;
        });
        jwplayer().onPause(function () {
            isPaused = true;
        });
        jwplayer().onBeforePlay(function () {
            isPaused = true;
        });
        jwplayer().onAdComplete(function () {
            isPaused = false;
        });
        jwplayer().onAdError(function () {
            isPaused = false;
        });
        function manageCounter() {
            var counter = Math.floor(Math.random() * 11) + 10;
            console.log(counter);
            var timer = setInterval(function () {
                if (!isPaused) {
                    if (counter === 0)
                    {
                        jwplayer().next();
                        return clearInterval(timer);
                    }
                    /*console.log(counter + " seconds");*/
                    counter--;
                }
            }, 1000);
        }
    </script>
</section>

<section id="welcome" class="home-welcome">
    <div class="container">
        <a href='<?php echo (isset($user_data) && !empty($user_data)) ? base_url('speed') : base_url("user/login_callback/speed"); ?>' class='luvrcloud-btn' title="Luvr Speed Dating"><img src="<?php echo base_url(); ?>assets/images/luvrcloud.png"/></a>
        <a href='<?php echo base_url('drluvr'); ?>' class='drluvr-btn'><img src="<?php echo ASSETS_URL; ?>/images/drluvr.jpg"/></a>
        <h2>
            <span><img src="<?php echo base_url(); ?>assets/images/welcome-icon.png" alt="" /></span>
            <big>Welcome to luvr</big>
        </h2>
        <p>Luvr's patent pending technology makes it easier for you to connect with the person of your dreams or find a new hookup if thats your thing!<br/> Utilizing video as opposed to photos will ensure you never get catfished again! <br/> Remember, if a picture is worth a thousand words, then a video is worth a million!</p>
    </div>
</section>

<section class="luvr-benefits">
    <ul class="luvr-benefits-ul">
        <li class="free-ragistration">
            <div class="benefits-img"><img src="<?php echo base_url(); ?>assets/images/free-ragistration.jpg" alt="" /></div>
            <div class="benefits-content">
                <h2>Luvr is Free</h2>
                <p>Luvr is free to use. If you want to enjoy all of the features of the app or website then you pay the low cost of $4.99! We want luvr affordable to everyone.</p>
            </div>	
        </li>
        <li class="matching-partners">
            <div class="benefits-img"><img src="<?php echo base_url(); ?>assets/images/matching-partners.jpg" alt="" /></div>
            <div class="benefits-content">
                <h2>Dual swipe verification system</h2>
                <p>Luvr's dual swipe feature ensures that you are matching with the right person. It takes only an extra 5 seconds to learn up to 3x more information using our system.</p>
            </div>	
        </li>
        <li class="share-experiences">
            <div class="benefits-img"><img src="<?php echo base_url(); ?>assets/images/share-experiences.jpg" alt="" /></div>
            <div class="benefits-content">
                <h2>Timestamps</h2>
                <p>Using Luvr's timestamp feature you are able to see exactly when a photo or video was created. This ensures that someone is showing you the current them, and not them from 5 years ago.</p>
            </div>	
        </li>
        <li class="count-people">
            <div class="benefits-img"><img src="<?php echo base_url(); ?>assets/images/count-people.jpg" alt="" /></div>
            <div class="benefits-content">
                <h2>Advanced filters</h2>
                <p>Luvr's Advanced Filters allow you to select over 1,000 different combinations that you want in a match. You can be as picky or as flexible as you want.<br/>&nbsp;</p>
            </div>	
        </li>
    </ul>
</section>

<section id="packages" class="home-package">
    <div class="container">
        <div class="package-table">
            <table class="table">
                <thead>
                    <tr>
                        <th>
                            <h2><small>Select</small> <big>Luvr package</big></h2>
                            <svg version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                 viewBox="0 0 57 57" style="enable-background:new 0 0 57 57;" xml:space="preserve">
                            <path d="M57,12.002H41.741C42.556,10.837,43,9.453,43,7.995c0-1.875-0.726-3.633-2.043-4.95c-2.729-2.729-7.17-2.729-9.899,0
                                  l-2.829,2.829l-2.828-2.829c-2.729-2.729-7.17-2.729-9.899,0c-1.317,1.317-2.043,3.075-2.043,4.95c0,1.458,0.444,2.842,1.259,4.007
                                  H0v14h5v30h48v-30h4V12.002z M32.472,4.459c1.95-1.949,5.122-1.949,7.071,0C40.482,5.399,41,6.654,41,7.995
                                  c0,1.34-0.518,2.596-1.457,3.535l-0.472,0.472H24.929l4.714-4.714l0,0L32.472,4.459z M16.916,11.53
                                  c-0.939-0.939-1.457-2.195-1.457-3.535c0-1.341,0.518-2.596,1.457-3.536c1.95-1.949,5.122-1.949,7.071,0l2.828,2.829l-3.535,3.535
                                  c-0.207,0.207-0.397,0.441-0.581,0.689c-0.054,0.073-0.107,0.152-0.159,0.229c-0.06,0.088-0.123,0.167-0.18,0.26h-4.972
                                  L16.916,11.53z M2,24.002v-10h14.559h4.733h2.255H28v10H5H2z M28,26.002v12H7v-12H28z M7,40.002h21v14H7V40.002z M30,54.002v-14h21
                                  v14H30z M51,38.002H30v-12h21V38.002z M55,24.002h-2H30v-10h9.899H55V24.002z"/>
                            <g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g>
                            </svg>
                        </th>
                        <th>
                            <h4>Luvr</h4>
                            <h3>Free</h3>
                        </th>
                        <th colspan="5">
                            <h4>Luvr premium</h4>
                            <h3>
                                <span>$</span>
                                <big>4.99</big>
                                <small>Per Month</small>
                            </h3>
                            <h3>
                                <span>$</span>
                                <big>25</big>
                                <small>6 Months</small>
                            </h3>
                            <h3>
                                <span>$</span>
                                <big>40</big>
                                <small>Per year</small>
                            </h3>
                            <h3>
                                <span>$</span>
                                <big>75</big>
                                <small>2 years</small>
                            </h3>
                            <h3>
                                <span>$</span>
                                <big>350</big>
                                <small>5 years</small>
                            </h3>
                        </th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>Likes/Swipe Right <span>(Per day)</span></td>
                        <td>100</td>
                        <td>Unlimited</td>
                        <td>Unlimited</td>
                        <td>Unlimited</td>
                        <td>Unlimited</td>
                        <td>Unlimited</td>
                    </tr>
                    <tr>
                        <td>Power Luv's <span>(Per day)</span></td>
                        <td>5</td>
                        <td>25</td>
                        <td>25</td>
                        <td>25</td>
                        <td>25</td>
                        <td>25</td>
                    </tr>
                    <tr>
                        <td>Timestamps <span>(Can you see them?)</span></td>
                        <td>No</td>
                        <td>Yes</td>
                        <td>Yes</td>
                        <td>Yes</td>
                        <td>Yes</td>
                        <td>Yes</td>
                    </tr>
                    <tr>
                        <td>Ads <span>(Do You See Them?)</span></td>
                        <td>Yes</td>
                        <td>No</td>
                        <td>No</td>
                        <td>No</td>
                        <td>No</td>
                        <td>No</td>
                    </tr>
                    <tr>
                        <td>Location <span>(How many cities can you search?)</span></td>
                        <td>1</td>
                        <td>5</td>
                        <td>5</td>
                        <td>5</td>
                        <td>5</td>
                        <td>5</td>
                    </tr>
                    <tr>
                        <td>Rewind last swipe</td>
                        <td>No</td>
                        <td>Yes</td>
                        <td>Yes</td>
                        <td>Yes</td>
                        <td>Yes</td>
                        <td>Yes</td>
                    </tr>
                    <tr>
                        <td>Upload IG Videos</td>
                        <td>Yes</td>
                        <td>Yes</td>
                        <td>Yes</td>
                        <td>Yes</td>
                        <td>Yes</td>
                        <td>Yes</td>
                    </tr>
                    <tr>
                        <td>Upload All Outside Videos</td>
                        <td>No</td>
                        <td>Yes</td>
                        <td>Yes</td>
                        <td>Yes</td>
                        <td>Yes</td>
                        <td>Yes</td>
                    </tr>
                    <tr>
                        <td>Video Snaps</td>
                        <td>Yes</td>
                        <td>Yes</td>
                        <td>Yes</td>
                        <td>Yes</td>
                        <td>Yes</td>
                        <td>Yes</td>
                    </tr>
                    <tr>
                        <td>Video Chat</td>
                        <td>No</td>
                        <td>Yes</td>
                        <td>Yes</td>
                        <td>Yes</td>
                        <td>Yes</td>
                        <td>Yes</td>
                    </tr>
                    <tr>
                        <td>Video Length</td>
                        <td>15 Seconds</td>
                        <td>60 Seconds</td>
                        <td>60 Seconds</td>
                        <td>60 Seconds</td>
                        <td>60 Seconds</td>
                        <td>60 Seconds</td>
                    </tr>
                    <tr>
                        <td>Text</td>
                        <td>Yes</td>
                        <td>Yes</td>
                        <td>Yes</td>
                        <td>Yes</td>
                        <td>Yes</td>
                        <td>Yes</td>
                    </tr>
                    <tr>
                        <td></td>
                        <td></td>
                        <?php if (!empty($user_data)) { ?>
                            <?php if ($is_user_premium_member == 0) { ?>
                                <td class='package-buy'>
                                    <form action="<?php echo base_url() . "user/manage_subscription"; ?>" method="post" id='frm_monthly'>
                                        <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                                data-key="<?php echo ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') ? PK_TEST : PK_LIVE; ?>"
                                                data-description="Luvr Premium (1 Month)"
                                                data-amount="499"
                                                data-image='<?php echo base_url() . "assets/images/luvrlogo.png" ?>'
                                        data-locale="auto"></script>
                                        <input name="subplan" value="monthly" type="hidden"/>
                                        <input name="amt" value="499" type="hidden"/>
                                    </form>
                                </td>
                                <td class='package-buy'>
                                    <form action="<?php echo base_url() . "user/manage_subscription"; ?>" method="post" id='frm_6monthly'>
                                        <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                                data-key="<?php echo ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') ? PK_TEST : PK_LIVE; ?>"
                                                data-description="Luvr Premium (6 Months)"
                                                data-amount="2500"
                                                data-image='<?php echo base_url() . "assets/images/luvrlogo.png" ?>'
                                        data-locale="auto"></script>
                                        <input name="subplan" value="6monthly" type="hidden"/>
                                        <input name="amt" value="2500" type="hidden"/>
                                    </form>
                                </td>
                                <td class='package-buy'>
                                    <form action="<?php echo base_url() . "user/manage_subscription"; ?>" method="post" id='frm_yearly'>
                                        <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                                data-key="<?php echo ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') ? PK_TEST : PK_LIVE; ?>"
                                                data-description="Luvr Premium (1 Year)"
                                                data-amount="4000"
                                                data-image='<?php echo base_url() . "assets/images/luvrlogo.png" ?>'
                                        data-locale="auto"></script>
                                        <input name="subplan" value="yearly" type="hidden"/>
                                        <input name="amt" value="4000" type="hidden"/>
                                    </form>
                                </td>
                                <td class='package-buy'>
                                    <form action="<?php echo base_url() . "user/manage_subscription"; ?>" method="post" id='frm_2yearly'>
                                        <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                                data-key="<?php echo ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') ? PK_TEST : PK_LIVE; ?>"
                                                data-description="Luvr Premium (2 Years)"
                                                data-amount="7500"
                                                data-image='<?php echo base_url() . "assets/images/luvrlogo.png" ?>'
                                        data-locale="auto"></script>
                                        <input name="subplan" value="2years" type="hidden"/>
                                        <input name="amt" value="7500" type="hidden"/>
                                    </form>
                                </td>
                                <td class='package-buy'>
                                    <form action="<?php echo base_url() . "user/manage_subscription"; ?>" method="post" id='frm_5yearly'>
                                        <script src="https://checkout.stripe.com/checkout.js" class="stripe-button"
                                                data-key="<?php echo PK_TEST; ?>"
                                                data-description="Luvr Premium (5 Years)"
                                                data-amount="35000"
                                                data-image='<?php echo base_url() . "assets/images/luvrlogo.png" ?>'
                                        data-locale="auto"></script>
                                        <input name="subplan" value="5years" type="hidden"/>
                                        <input name="amt" value="35000" type="hidden"/>
                                    </form>
                                </td>
                            <?php } else { ?>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                                <td>&nbsp;</td>
                            <?php } ?>
                        <?php } else { ?>
                            <td class='package-buy'>
                                <a href="<?php echo base_url() . 'user/login_callback'; ?>">Buy Now</a>
                            </td>
                            <td class='package-buy'>
                                <a href="<?php echo base_url() . 'user/login_callback'; ?>">Buy Now</a>
                            </td>
                            <td class='package-buy'>
                                <a href="<?php echo base_url() . 'user/login_callback'; ?>">Buy Now</a>
                            </td>
                            <td class='package-buy'>
                                <a href="<?php echo base_url() . 'user/login_callback'; ?>">Buy Now</a>
                            </td>
                            <td class='package-buy'>
                                <a href="<?php echo base_url() . 'user/login_callback'; ?>">Buy Now</a>
                            </td>
                        <?php } ?>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</section>
<script src="<?php echo base_url(); ?>assets/js/jquery.bxslider.min.js"></script>
<script type="text/javascript">
<?php if (!empty($error)) { ?>
            $(document).ready(function () {
                showMsg("<?php echo $error; ?>", "error", true);
            });
<?php } ?>
        $(function () {
            $("#frm_monthly .stripe-button-el").html('1 Month');
            $("#frm_6monthly .stripe-button-el").html('6 Months');
            $("#frm_yearly .stripe-button-el").html('1 Year');
            $("#frm_2yearly .stripe-button-el").html('2 Years');
            $("#frm_5yearly .stripe-button-el").html('5 Years');
            $(".stripe-button-el").removeClass('stripe-button-el');

            $('.bxslider').bxSlider({
                mode: 'fade',
                captions: true,
                auto: true,
                pager: false,
                responsive: true
            });
        });
</script>