<link href='<?php echo base_url('/assets/css/jTinder.css'); ?>' rel='stylesheet'/>
<?php
$user_data = $this->session->userdata('user');
$success = $this->session->flashdata('success');
if ($user_swipes_per_day >= MAX_SWIPES_PER_DAY) {
    echo '<div class="alert alert-danger alert-dismissable">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        Your likes quota per day has been reached! Therefore, right swipes for cards will not be considered.</div>';
}
$show_ad = true;
if (!empty($user_data)) {
    if (isUserActiveSubscriber($user_data['id']) == 1) {
        $show_ad = false;
    }
}
$max_powerluvs = MAX_POWERLUVS_PER_DAY;
$pl_onclick = "onclick=powerLuv();";
if ($is_user_premium_member == 1) {
    $max_powerluvs = MAX_POWERLUVS_PER_DAY_P;
}
if ($user_powerluvs_per_day >= $max_powerluvs) {
    $pl_onclick = "";
    echo '<div class="alert alert-danger alert-dismissable">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
       Your power luvs quota per day has been reached! Therefore, further power luvs will not be considered.</div>';
}
if (!empty($nearByUsers)) {
    $i = 0;
    foreach ($nearByUsers as $nbu) {
        $distance = null;
        if (!empty($nbu['latlong'])) {
            $loc1 = explode(",", $latlong);
            $lat1 = (double) $loc1[0];
            $lon1 = (double) $loc1[1];
            $loc2 = explode(",", $nbu['latlong']);
            $lat2 = (double) $loc2[0];
            $lon2 = (double) $loc2[1];
            if (!empty($loc1) && !empty($loc2)) {
                $distance = distance($lat1, $lon1, $lat2, $lon2, "K");
                $distance = number_format($distance, 2);
            }
        }
        $nearByUsers[$i]['distance'] = $distance;
        $i++;
    }
    $lastObj = end($nearByUsers);
}
?>
<script type="text/javascript">
    var nearby_matches = <?php echo json_encode($nearByUsers); ?>
</script>
<?php if ($_SERVER['HTTP_HOST'] == 'luvr.me' && $show_ad == true) { ?>
    <script async src="//pagead2.googlesyndication.com/pagead/js/adsbygoogle.js"></script>
<?php } ?>
<div class="col-md-2 col-sm-2 col-xs-12">
    <?php if ($_SERVER['HTTP_HOST'] == 'luvr.me' && $show_ad == true) { ?>
        <div class="addvertise-img1 adv">
            <!-- Column ad1 -->
            <ins class="adsbygoogle"
                 style="display:inline-block;width:250px;height:600px"
                 data-ad-client="ca-pub-8931925329892531"
                 data-ad-slot="4333141005"></ins>
        </div>
        <div class="addvertise-img2 adv">
            <!-- Column ad2 -->
            <ins class="adsbygoogle"
                 style="display:inline-block;width:250px;height:600px"
                 data-ad-client="ca-pub-8931925329892531"
                 data-ad-slot="5809874207"></ins>
        </div>
    <?php } ?>
</div>
<div class="col-md-8 col-sm-8 col-xs-12">
    <div class="user-list">
        <div class="bg-name">luvr</div>
        <?php if (!empty($nearByUsers)) { ?>
            <div class="user-list-l">
                <div class="user-list-l-l">    
                    <div class="user-list-pic">
                        <div id="tinderslide" style="visibility:hidden;">
                            <ul>
                                <?php
                                $it = 1;
                                foreach ($nearByUsers as $user) {
                                    $path = $href = "";
                                    if ($user['media_type'] == 0 && !empty($user['media_thumb'])) {
                                        $path = $user['media_thumb'];
                                        $href = $user['user_profile'];
                                    } else if ($user['media_type'] == 1 || $user['media_type'] == 2) {
                                        /* $path = base_url() . "assets/images/users/" . $user['media_thumb'];
                                          if (!file_exists(PHYSICALUPLOADPATH . "/images/users/" . $user['media_thumb']))
                                          $path = base_url() . "assets/images/big_avatar.jpg";
                                          $href = base_url() . "assets/images/users/" . $user['user_profile']; */
                                        if ($user['media_type'] == 1) {
                                            $path = base_url() . 'bio/show_img/' . $user['media_thumb'] . "/1";
                                            $href = base_url() . "bio/show_img/" . $user['user_profile'];
                                        }
                                        if ($user['media_type'] == 2) {
                                            $fname = replace_extension($user['media_thumb'], "png");
                                            $path = base_url() . 'bio/show_img/' . $fname . "/1";
                                            $href = base_url() . "video/play/" . $user['mid'];
                                        }
                                    } else if ($user['media_type'] == 3 || $user['media_type'] == 4) {
                                        $path = $user['media_thumb'];
                                        $href = $user['user_profile'];
                                        if ($user['media_type'] == 4) {
                                            $href = base_url() . "video/play/" . $user['mid'];
                                        }
                                    }
                                    $timestamp_html = "";
                                    if ($is_user_premium_member == 1) {
                                        $timestamp_html = '<span class="_timestamp">' . date("m/d/y", strtotime($user['insta_datetime'])) . '<br/>' . date("h:s a", strtotime($user['insta_datetime'])) . '</span>';
                                    }
                                    echo '<li class="panel" data-id="' . $user['id'] . '">
                                            <div class="user-list-pic-wrapper">
                                                ' . $timestamp_html . '
                                                <div class="user-list-pic-bg">
                                                    <a style="background:url(\'' . $path . '\') no-repeat scroll center center;" class="img"></a>';
                                    if ($user['media_type'] == 2 || $user['media_type'] == 4) {
                                        echo '<a class="play-btn-large icon-play-button" target="_blank" href="' . $href . '"></a>';
                                    }
                                    echo '</div>
                                            <div class="user-list-pic-close">
                                            <a class="for_pointer" onclick="$(\'#tinderslide\').jTinder(\'dislike\');">
                                                <svg version="1.1" id="Layer_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px"
                                                     viewBox="0 0 371.23 371.23" style="enable-background:new 0 0 371.23 371.23;" xml:space="preserve">
                                                <polygon points="371.23,21.213 350.018,0 185.615,164.402 21.213,0 0,21.213 164.402,185.615 0,350.018 21.213,371.23 
                                                         185.615,206.828 350.018,371.23 371.23,350.018 206.828,185.615 "/>
                                                <g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g><g></g>
                                                </svg>
                                            </a>
                                        </div>
                                        </div>
                                        </li>';
                                    $it++;
                                }
                                ?>
                            </ul>
                        </div>
                    </div>
                    <div id="loader-pl" class="loader-style" style="background:none;display:none;">
                        <div class="loader-container">
                            <img src="<?php echo base_url(); ?>assets/images/loader.gif" />
                        </div>
                    </div>
                </div>
                <div class="user-likes">
                    <a class="for_pointer" id="rewind_user" title="Rewind" onclick="prevMatch(<?php echo $lastObj['id']; ?>)">
                        <img src="<?php echo base_url(); ?>assets/images/rewind.png" />
                    </a>
                    <a class="for_pointer" id="pass_user" title="Pass" onclick="$('#tinderslide').jTinder('dislike');">
                        <img src="<?php echo base_url(); ?>assets/images/pass.png" />
                    </a>
                    <a class="for_pointer" id="luv_user" title="Luv" onclick="$('#tinderslide').jTinder('luv');">
                        <img src="<?php echo base_url(); ?>assets/images/luv.png" />
                    </a>
                    <a class="for_pointer" id="power_luv_user" title="Power Luv" <?php echo $pl_onclick; ?>>
                        <img src="<?php echo base_url(); ?>assets/images/powerluv.png" />
                    </a>
                </div>
            </div>    
            <?php
        }
        if (!empty($nearByUsers)) {
            $distance = null;
            if (!empty($latlong) && !empty($lastObj['latlong'])) {
                $loc1 = explode(",", $latlong);
                $lat1 = (double) $loc1[0];
                $lon1 = (double) $loc1[1];
                $loc2 = explode(",", $lastObj['latlong']);
                $lat2 = (double) $loc2[0];
                $lon2 = (double) $loc2[1];
                if (!empty($loc1) && !empty($loc2)) {
                    $distance = distance($lat1, $lon1, $lat2, $lon2, "K");
                    $distance = number_format($distance, 2);
                }
            }
            ?>
            <div class="user-list-r">
                <h2>
                    <big id="right_username">
                        <?php
                        echo $lastObj['user_name'];
                        ?>
                    </big>
                    <small id="right_oneliner">
                        <?php
                        echo $lastObj['one_liner'];
                        ?>
                    </small>
                </h2>
                <p id="right_bio">
                    <?php
                    echo $lastObj['bio'];
                    ?>
                </p>
                <ul class="user-info">
                    <li id="right_age">Age : (<?php
                        echo $lastObj['age'];
                        ?>)</li>
                    <li id="right_location">Location : <?php echo (!empty($lastObj['address'])) ? $lastObj['address'] : "N/A"; ?></li>
                    <li id="right_distance">Distance : (<?php echo ($distance != null) ? $distance : "N/A"; ?> km)</li>
                </ul>
                <div class="user-list-r-btn">
                    <a href="#" class="video-option white-btn">
                        <svg version="1.0" id="Layer_1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;"
                             xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="25.133px"
                             height="20.201px" viewBox="0 0 25.133 20.201" enable-background="new 0 0 25.133 20.201" xml:space="preserve">
                        <switch>
                        <foreignObject requiredExtensions="&ns_ai;" x="0" y="0" width="1" height="1">
                        <i:pgfRef  xlink:href="#adobe_illustrator_pgf">
                        </i:pgfRef>
                        </foreignObject>
                        <g i:extraneous="self">
                        <g>
                        <path fill="none" d="M427.122,145.958c-0.18-0.104-0.402-0.109-0.587-0.013l-3.844,2.02v-1.731c0-0.32-0.267-0.58-0.595-0.58
                              h-0.968c1.206-0.954,1.979-2.411,1.979-4.042c0-2.868-2.391-5.202-5.33-5.202c-2.509,0-4.617,1.7-5.182,3.982
                              c-0.738-0.869-1.852-1.424-3.096-1.424c-2.216,0-4.02,1.76-4.02,3.924c0,1.076,0.447,2.053,1.169,2.762h-0.543
                              c-0.328,0-0.595,0.26-0.595,0.581v2.634h-1.505c-0.329,0-0.595,0.26-0.595,0.581v2.282c0,0.32,0.266,0.58,0.595,0.58h1.505v2.635
                              c0,0.32,0.267,0.58,0.595,0.58h7.015h1.961h7.015c0.328,0,0.595-0.26,0.595-0.58v-1.731l3.844,2.021
                              c0.089,0.046,0.186,0.068,0.282,0.068c0.105,0,0.211-0.027,0.305-0.082c0.18-0.104,0.29-0.294,0.29-0.498v-8.268
                              C427.412,146.251,427.301,146.062,427.122,145.958z M405.511,151.15h-0.91v-1.121h0.91V151.15z M417.777,137.569
                              c2.283,0,4.141,1.813,4.141,4.042c0,2.229-1.857,4.042-4.141,4.042s-4.141-1.813-4.141-4.042
                              C413.636,139.382,415.494,137.569,417.777,137.569z M413.237,144.333c0.317,0.503,0.72,0.949,1.188,1.319h-2.074
                              C412.731,145.28,413.034,144.831,413.237,144.333z M409.5,140.128c1.561,0,2.83,1.239,2.83,2.763c0,1.522-1.27,2.762-2.83,2.762
                              s-2.83-1.239-2.83-2.762C406.669,141.368,407.939,140.128,409.5,140.128z M421.501,148.936v3.307v2.122h-7.39
                              c-0.001,0-0.003,0-0.003,0h-0.017c0,0-0.001,0-0.002,0h-7.39v-2.634v-2.283v-2.634h14.801V148.936z M426.222,153.751
                              l-3.531-1.855v-2.613l3.531-1.855V153.751z M409.5,144.654c0.996,0,1.807-0.791,1.807-1.763c0-0.973-0.811-1.764-1.807-1.764
                              s-1.806,0.791-1.806,1.764C407.694,143.863,408.503,144.654,409.5,144.654z M409.5,142.172c0.406,0,0.736,0.322,0.736,0.718
                              c0,0.396-0.33,0.719-0.736,0.719c-0.405,0-0.735-0.322-0.735-0.719C408.764,142.495,409.094,142.172,409.5,142.172z
                              M417.777,144.143c1.431,0,2.594-1.136,2.594-2.532c0-1.396-1.163-2.531-2.594-2.531c-1.43,0-2.594,1.136-2.594,2.531
                              C415.183,143.007,416.347,144.143,417.777,144.143z M417.777,140.24c0.774,0,1.404,0.615,1.404,1.371
                              c0,0.757-0.63,1.371-1.404,1.371s-1.404-0.614-1.404-1.371C416.373,140.855,417.002,140.24,417.777,140.24z M409.394,152.677
                              h6.465c0.312,0,0.564-0.247,0.564-0.551v-3.074c0-0.304-0.253-0.551-0.564-0.551h-6.465c-0.313,0-0.565,0.247-0.565,0.551v3.074
                              C408.829,152.43,409.082,152.677,409.394,152.677z M409.958,149.604h5.335v1.971h-5.335V149.604z M418.977,150.213
                              c0.328,0,0.595-0.26,0.595-0.58s-0.267-0.581-0.595-0.581s-0.595,0.261-0.595,0.581S418.649,150.213,418.977,150.213z
                              M418.977,152.125c0.328,0,0.595-0.26,0.595-0.58s-0.267-0.58-0.595-0.58s-0.595,0.26-0.595,0.58
                              S418.649,152.125,418.977,152.125z"/>
                        </g>
                        <g>
                        <g>
                        <path d="M24.122,9.958c-0.18-0.104-0.402-0.109-0.587-0.013l-3.844,2.02v-1.731c0-0.32-0.267-0.58-0.595-0.58h-0.968
                              c1.206-0.954,1.979-2.411,1.979-4.042c0-2.868-2.391-5.202-5.33-5.202c-2.509,0-4.617,1.7-5.182,3.982
                              C8.857,3.522,7.744,2.967,6.5,2.967c-2.216,0-4.02,1.76-4.02,3.924c0,1.076,0.447,2.053,1.169,2.762H3.106
                              c-0.328,0-0.595,0.26-0.595,0.581v2.634H1.006c-0.329,0-0.595,0.26-0.595,0.581v2.282c0,0.32,0.266,0.58,0.595,0.58h1.505v2.635
                              c0,0.32,0.267,0.58,0.595,0.58h15.99c0.328,0,0.595-0.26,0.595-0.58v-1.731l3.844,2.021c0.089,0.046,0.186,0.068,0.282,0.068
                              c0.105,0,0.211-0.027,0.305-0.082c0.18-0.104,0.29-0.294,0.29-0.498v-8.268C24.412,10.251,24.301,10.062,24.122,9.958z
                              M2.511,15.15h-0.91v-1.121h0.91V15.15z M14.777,1.569c2.283,0,4.141,1.813,4.141,4.042c0,2.229-1.857,4.042-4.141,4.042
                              s-4.141-1.813-4.141-4.042C10.636,3.382,12.494,1.569,14.777,1.569z M10.237,8.333c0.317,0.503,0.72,0.949,1.188,1.319H9.351
                              C9.731,9.28,10.034,8.831,10.237,8.333z M6.5,4.128c1.561,0,2.83,1.239,2.83,2.763c0,1.522-1.27,2.762-2.83,2.762
                              s-2.83-1.239-2.83-2.762C3.669,5.368,4.939,4.128,6.5,4.128z M18.501,18.365h-7.39c-0.001,0-0.003,0-0.003,0h-0.017
                              c0,0-0.001,0-0.002,0h-7.39v-7.551h14.801V18.365z M23.222,17.751l-3.531-1.855v-2.613l3.531-1.855V17.751z M6.5,8.654
                              c0.996,0,1.807-0.791,1.807-1.763c0-0.973-0.811-1.764-1.807-1.764S4.694,5.918,4.694,6.891C4.694,7.863,5.503,8.654,6.5,8.654z
                              M6.5,6.172c0.406,0,0.736,0.322,0.736,0.718c0,0.396-0.33,0.719-0.736,0.719c-0.405,0-0.735-0.322-0.735-0.719
                              C5.764,6.495,6.094,6.172,6.5,6.172z M14.777,8.143c1.431,0,2.594-1.136,2.594-2.532c0-1.396-1.163-2.531-2.594-2.531
                              c-1.43,0-2.594,1.136-2.594,2.531C12.183,7.007,13.347,8.143,14.777,8.143z M14.777,4.24c0.774,0,1.404,0.615,1.404,1.371
                              c0,0.757-0.63,1.371-1.404,1.371s-1.404-0.614-1.404-1.371C13.373,4.855,14.002,4.24,14.777,4.24z M6.394,16.677h6.465
                              c0.312,0,0.564-0.247,0.564-0.551v-3.074c0-0.304-0.253-0.551-0.564-0.551H6.394c-0.313,0-0.565,0.247-0.565,0.551v3.074
                              C5.829,16.43,6.082,16.677,6.394,16.677z M6.958,13.604h5.335v1.971H6.958V13.604z M15.977,14.213
                              c0.328,0,0.595-0.26,0.595-0.58s-0.267-0.581-0.595-0.581s-0.595,0.261-0.595,0.581S15.649,14.213,15.977,14.213z
                              M15.977,16.125c0.328,0,0.595-0.26,0.595-0.58s-0.267-0.58-0.595-0.58s-0.595,0.26-0.595,0.58S15.649,16.125,15.977,16.125z"/>
                        </g>
                        </g>
                        </g>
                        </switch>
                        </svg>
                    </a>
                    <a href="#" class="chat-option green-btn">
                        <span><svg version="1.0" id="Layer_1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;"
                                   xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="19.232px"
                                   height="18.82px" viewBox="0 0 19.232 18.82" enable-background="new 0 0 19.232 18.82" xml:space="preserve">
                            <switch>
                            <foreignObject requiredExtensions="&ns_ai;" x="0" y="0" width="1" height="1">
                            <i:pgfRef  xlink:href="#adobe_illustrator_pgf"></i:pgfRef>
                            </foreignObject>
                            <g i:extraneous="self">
                            <path d="M12.429,9.973H6.804c-0.311,0-0.563,0.252-0.563,0.562c0,0.311,0.252,0.563,0.563,0.563h5.625
                                  c0.311,0,0.563-0.252,0.563-0.563C12.991,10.225,12.739,9.973,12.429,9.973z M13.554,6.598H5.679
                                  c-0.311,0-0.563,0.252-0.563,0.563s0.252,0.563,0.563,0.563h7.875c0.311,0,0.563-0.252,0.563-0.563S13.864,6.598,13.554,6.598z
                                  M9.617,0.412c-4.97,0-8.999,3.525-8.999,7.874c0,2.485,1.319,4.697,3.375,6.141v3.982l3.941-2.392
                                  c0.546,0.09,1.106,0.142,1.683,0.142c4.97,0,8.998-3.524,8.998-7.873S14.587,0.412,9.617,0.412z M9.617,15.035
                                  c-0.657,0-1.292-0.077-1.901-0.207l-2.647,1.592l0.035-2.608c-2.029-1.221-3.36-3.238-3.36-5.525c0-3.728,3.525-6.749,7.874-6.749
                                  c4.347,0,7.873,3.021,7.873,6.749C17.489,12.012,13.964,15.035,9.617,15.035z"/>
                            </g>
                            </switch>
                            </svg></span>
                        <small>Chat Now</small>
                    </a>
                </div>
            </div>
        <?php } ?>
    </div>
</div>
<div class="col-md-2 col-sm-2 col-xs-12">
    <?php if ($_SERVER['HTTP_HOST'] == 'luvr.me' && $show_ad == true) { ?>
        <div class="addvertise-img3 adv">
            <!-- Column ad3 -->
            <ins class="adsbygoogle"
                 style="display:inline-block;width:250px;height:600px"
                 data-ad-client="ca-pub-8931925329892531"
                 data-ad-slot="4274265409"></ins>
        </div>
        <div class="addvertise-img4 adv">
            <!-- Column ad4 -->
            <ins class="adsbygoogle"
                 style="display:inline-block;width:250px;height:600px"
                 data-ad-client="ca-pub-8931925329892531"
                 data-ad-slot="5750998606"></ins>
        </div>
        <script>
            [].forEach.call(document.querySelectorAll('.adsbygoogle'), function () {
                (adsbygoogle = window.adsbygoogle || []).push({});
            });
        </script>
    <?php } ?>
</div>
<div class="modal fade" id="adpopup" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <input type="hidden" id="hdn_tmp_id"/>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Sneak Peak</h4>
            </div>
            <div class="modal-body">
                <iframe src="" id="sneak_peak_frame" frameborder="0" scrolling="no" style="overflow:hidden;height:100%;width:100%;"></iframe>
            </div>
        </div>
    </div>
</div>
<style type="text/css">
    .inner-content{position:relative;}
</style>
<script src="<?php echo base_url() . 'assets/js/jquery.transform2d.js'; ?>" type="text/javascript"></script>
<script src="<?php echo base_url() . 'assets/js/jquery.jTinder.js'; ?>" type="text/javascript"></script>
<script type="text/javascript">
            var popup_seconds = 0;
<?php if (!empty($success)) { ?>
                $(document).ready(function () {
                    showMsg("<?php echo $success; ?>", "success", true);
                });
<?php } ?>
            $(window).on('load', function () {
                setTimeout(function () {
                    /*$("#radar").hide();*/
                    $("#loader").fadeOut();
<?php if (empty($nearByUsers) || $nearByUsers == null) { ?>
                        $("#loader-nodata").fadeIn();
<?php } ?>
                    $("#tinderslide").removeAttr('style');
                }, Math.floor((Math.random() * 1000) + 1000));
            });
            var likedislikecounts = 0;
            var likesreached = powerluvsreached = 0;
            registerjTinder();
            function registerjTinder() {
                $("#tinderslide").jTinder({
                    onLike: function (item) {
<?php if ($user_swipes_per_day < MAX_SWIPES_PER_DAY) { ?>
                            /*loadLevel2(item, 'like');*/
    <?php if ($is_user_premium_member == 1) { ?>
                                location.href = "<?php echo base_url('/match/level2/'); ?>" + $(item).data("id") + "/1/1";
    <?php } else { ?>
                                $("#adpopup").modal('show');
                                $("#hdn_tmp_id").val($(item).data("id"));
                                $("#sneak_peak_frame").attr('src', '<?php echo base_url('video/aol1/'); ?>');
    <?php } ?>
<?php } ?>
                        reflectUserInfo(item.index() - 1);
                    },
                    onDislike: function (item) {
                        likedislikeuser($(item).data("id"), 'dislike');
                        reflectUserInfo(item.index() - 1);
                    },
                    onLuv: function (item) {
                        /*loadLevel2(item, 'luv');*/
                        location.href = "<?php echo base_url('/match/level2/'); ?>" + $(item).data("id") + "/1/2";
                    },
                    onPowerLuv: function (item) {
                        likedislikeuser($(item).data("id"), 'powerluv', item.index() - 1);
                    },
                    onPrev: function (item) {
                        reflectUserInfo(item.index());
                    },
                    animationRevertSpeed: 200,
                    animationSpeed: 500,
                    threshold: 4,
                    likeSelector: '.like',
                    dislikeSelector: '.dislike'
                });
            }
            function reflectUserInfo(index) {
                if (index >= 0 && index < $("#tinderslide ul li.panel").length)
                {
                    $("#right_username").html(nearby_matches[index].user_name);
                    $("#right_oneliner").html(nearby_matches[index].one_liner);
                    $("#right_bio").html((nearby_matches[index].bio) ? nearby_matches[index].bio : "&nbsp;");
                    $("#right_age").html("Age : (" + nearby_matches[index].age + ")");
                    $("#right_location").html((nearby_matches[index].address) ? "Location : " + nearby_matches[index].address : "Location : N/A");
                    $("#right_distance").html((nearby_matches[index].distance) ? "Distance : " + nearby_matches[index].distance + " km" : "Distance : N/A");
                    if (powerluvsreached == 1)
                    {
                        $("#power_luv_user").removeAttr("onclick");
                    } else
                    {
                        if (nearby_matches[index].id)
                        {
                            $("#rewind_user").attr("onclick", "prevMatch(" + nearby_matches[index].id + ")");
                            $("#pass_user").attr("onclick", "$('#tinderslide').jTinder('dislike');");
                            $("#luv_user").attr("onclick", "$('#tinderslide').jTinder('luv');");
                            $("#power_luv_user").attr("onclick", "powerLuv();");
                        } else
                        {
                            $("#rewind_user,#pass_user,#luv_user,#power_luv_user").attr("onclick", "showMsg('Something went wrong!','error',true);");
                        }
                    }
                }
            }
            function powerLuv() {
                $("#loader-pl").show();
                setTimeout(function () {
                    $("#loader-pl").hide();
                    $('#tinderslide').jTinder('powerluv');
                }, 2000);
            }
            function prevMatch(id) {
<?php if ($is_user_premium_member == 1) { ?>
                    if ($("#tinderslide ul li[data-id='" + id + "']").attr("data-nav") != 1)
                        $('#tinderslide').jTinder('prev');
<?php } else { ?>
                    showMsg("You need to be Luvr premium member to swipe back! <a href='<?php echo base_url('home/#packages') ?>'>Click here to join</a>", "error", true);
                    scrollToElement("#header");
<?php } ?>
            }
            function likedislikeuser(user_id, mode, li_index) {
                $.ajax({
                    url: "<?php echo base_url(); ?>match/likedislike",
                    type: 'POST',
                    dataType: 'json',
                    data: "user_id=" + user_id + "&status=" + mode + "&totallikesreached=" + likesreached,
                    success: function (data) {
                        likedislikecounts++;
                        if (data.success == true) {
                        }
                        if ((data.user_swipes_per_day == <?php echo MAX_SWIPES_PER_DAY; ?>) && mode == "like")
                        {
                            /*$("#tinderslide").unbind('touchstart mousedown');
                             $("#tinderslide").unbind('touchmove mousemove');
                             $("#tinderslide").unbind('touchend mouseup');*/
                            likesreached = 1;
                            showMsg("Your likes quota per day has been reached!<br/>Therefore, right swipes for cards will not be considered.", "error");
                            scrollToElement("#header");
                        }
<?php if ($is_user_premium_member == 1) { ?>
                            if ((data.user_powerluvs_per_day == <?php echo MAX_POWERLUVS_PER_DAY_P; ?>) && mode == "powerluv")
                            {
                                powerluvsreached = 1;
                                reflectUserInfo(li_index);
                                showMsg("Your power luvs quota per day has been reached!<br/>Therefore, further power luvs will not be considered.", "error");
                                scrollToElement("#header");
                            }
<?php } else { ?>
                            if ((data.user_powerluvs_per_day == <?php echo MAX_POWERLUVS_PER_DAY; ?>) && mode == "powerluv")
                            {
                                powerluvsreached = 1;
                                reflectUserInfo(li_index);
                                showMsg("Your power luvs quota per day has been reached!<br/>Therefore, further power luvs will not be considered.", "error");
                                scrollToElement("#header");
                            }
<?php } ?>
                        if (likedislikecounts == $("#tinderslide ul li.panel").length)
                        {
                            loadMoreNearBys();
                        }
                    }, error: function () {
                        showMsg("Something went wrong!", "error", true);
                        scrollToElement("#header");
                    }
                });
            }
            function loadMoreNearBys() {
                $("#tinderslide").css('visibility', 'hidden');
                $("#loader").show();
                $.ajax({
                    url: "<?php echo base_url(); ?>match/loadMoreNearBys",
                    type: 'POST',
                    dataType: 'json',
                    success: function (data) {
                        if (data.success == true) {
                            likedislikecounts = 0;
                            if (data.data) {
                                nearby_matches = data.data;
                                $("#tinderslide ul").html(data.html);
                                reflectUserInfo(parseInt(data.data.length) - 1);
                                registerjTinder();
                            }
                        } else {
                            $("#loader").hide();
                            $(".user-list-l,.user-list-r").hide();
                            $("#loader-nodata .loader-container").append('<p>Hey Luvr! Right now, there is no one else to Luv in your area! Check back soon!<br/>We are growing fast with your help! Spread the word about Luvr on all your social media!</p>');
                            $("#loader-nodata").show();
                        }
                        setTimeout(function () {
                            /*$("#radar").hide();*/
                            $("#loader").fadeOut();
                            $("#tinderslide").removeAttr('style');
                        }, Math.floor((Math.random() * 1000) + 1000));
                    }
                });
            }
            /*function loadLevel2(item, mode) {
             var index = item.index();
             $("#tinderslide2 ul li.panel").attr("data-id", nearby_matches[index].id);
             $("#tinderslide2 ul li.panel .user-list-pic-bg").attr("style", "background:url('" + nearby_matches[index].media_thumb + "') no-repeat scroll center center;");
             $('#level2Popup').modal('show');
             $('.secondSwiper ul li,panel').removeAttr('style');
             $('.secondSwiper ul li,panel').show();
             }*/
            $("#adpopup").on("shown.bs.modal", function () {
                showSneakPeak();
            });
            function showSneakPeak() {
                popup_seconds = popup_seconds + 1;
                if (popup_seconds == 4)
                {
                    location.href = "<?php echo base_url('match/level2/'); ?>" + $("#hdn_tmp_id").val() + "/1/1";
                } else
                {
                    setTimeout(showSneakPeak, 1000);
                }
            }
</script>