<link href='<?php echo base_url('/assets/fancybox/dist/jquery.fancybox.min.css'); ?>' rel='stylesheet' media="screen"/>
<?php
$user_data = $this->session->userdata('user');
if ($user_swipes_per_day >= MAX_SWIPES_PER_DAY) {
    echo '<div class="alert alert-danger alert-dismissable">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
        Your likes quota per day has been reached! Therefore, right swipes for cards will not be considered.</div>';
}
$max_powerluvs = MAX_POWERLUVS_PER_DAY;
$pl_onclick = "onclick=\"$('#tinderslide').jTinder('powerluv');\"";
if ($is_user_premium_member == 1) {
    $max_powerluvs = MAX_POWERLUVS_PER_DAY_P;
}
if ($user_powerluvs_per_day >= $max_powerluvs) {
    $pl_onclick = "";
    echo '<div class="alert alert-danger alert-dismissable">
        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
       Your power luvs quota per day has been reached! Therefore, further power luvs will not be considered.</div>';
}
if ((empty($nearByUsers) || $nearByUsers == null) && $view_card == 0) {
    echo '<div class="alert alert-info">We could not find any nearby matches around you!</div>';
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
<div class="col-md-12 col-sm-12 col-xs-12">
    <div class="user-list">
        <div class="bg-name">luvr</div>
        <?php if (!empty($nearByUsers)) { ?>
            <div class="user-list-l">
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
                                    $path = base_url() . "assets/images/users/" . $user['media_thumb'];
                                    if (!file_exists(PHYSICALUPLOADPATH . "/images/users/" . $user['media_thumb']))
                                        $path = base_url() . "assets/images/big_avatar.jpg";
                                    $href = base_url() . "assets/images/users/" . $user['user_profile'];
                                } else if ($user['media_type'] == 3 || $user['media_type'] == 4) {
                                    $path = $user['media_thumb'];
                                    $href = $user['user_profile'];
                                }
                                echo '<li class="panel" data-id="' . $user['id'] . '">
                                        <div class="user-list-pic-wrapper">
                                            <div class="user-list-pic-bg">
                                                <a style="background:url(\'' . $path . '\') no-repeat scroll center center;" class="img"></a>';
                                if ($user['media_type'] == 2 || $user['media_type'] == 4) {
                                    echo '<span class = "play-btn" data-fancybox href = "' . $href . '"></span>';
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
                                    <div class="user-next-prev">
                                        <a class="for_pointer" onclick="prevMatch(' . $user['id'] . ')">
                                            <svg version="1.0" id="Layer_1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;"
                                                 xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="27.08px"
                                                 height="17.699px" viewBox="0 0 27.08 17.699" enable-background="new 0 0 27.08 17.699" xml:space="preserve">
                                            <switch>
                                            <foreignObject requiredExtensions="&ns_ai;" x="0" y="0" width="1" height="1">
                                            <i:pgfRef  xlink:href="#adobe_illustrator_pgf">
                                            </i:pgfRef>
                                            </foreignObject>
                                            <g i:extraneous="self">
                                            <path d="M26.54,9.917H10.858v5.865c0,0.391-0.225,0.75-0.586,0.938s-0.802,0.172-1.148-0.04L1.056,9.747
                                                  C0.734,9.551,0.54,9.212,0.54,8.85c0-0.363,0.194-0.701,0.516-0.898l8.068-6.933c0.346-0.212,0.787-0.227,1.148-0.04
                                                  c0.361,0.188,0.586,0.547,0.586,0.938v5.865h15.67 M3.743,8.85l4.865,4.975V3.875L3.743,8.85z"/>
                                            </g>
                                            </switch>
                                            </svg>
                                        </a>
                                        <a href="#">
                                            <svg version="1.0" id="Layer_1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;"
                                                 xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="27.08px"
                                                 height="17.699px" viewBox="0 0 27.08 17.699" enable-background="new 0 0 27.08 17.699" xml:space="preserve">
                                            <switch>
                                            <foreignObject requiredExtensions="&ns_ai;" x="0" y="0" width="1" height="1">
                                            <i:pgfRef  xlink:href="#adobe_illustrator_pgf">
                                            </i:pgfRef>
                                            </foreignObject>
                                            <g i:extraneous="self">
                                            <path d="M0.54,7.782h15.682V1.917c0-0.391,0.225-0.75,0.586-0.938s0.802-0.172,1.148,0.04l8.068,6.933
                                                  c0.321,0.196,0.516,0.535,0.516,0.897c0,0.363-0.194,0.701-0.516,0.898l-8.068,6.933c-0.346,0.212-0.787,0.227-1.148,0.04
                                                  c-0.361-0.188-0.586-0.547-0.586-0.938V9.918H0.552 M23.337,8.85l-4.865-4.975v9.949L23.337,8.85z"/>
                                            </g>
                                            </switch>
                                            </svg>	
                                        </a>
                                    </div>
                                    </div>
                                    </li>';
                                $it++;
                            }
                            ?>
                        </ul>
                        <div class="user-likes">
                            <a class="for_pointer" id="luv_user" title="Luv" onclick="$('#tinderslide').jTinder('luv');">
                                <svg version="1.0" id="Layer_1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;"
                                     xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="26.962px"
                                     height="21.416px" viewBox="0 0 26.962 21.416" enable-background="new 0 0 26.962 21.416" xml:space="preserve">
                                    <switch>
                                        <foreignObject requiredExtensions="&ns_ai;" x="0" y="0" width="1" height="1">
                                            <i:pgfRef  xlink:href="#adobe_illustrator_pgf"></i:pgfRef>
                                        </foreignObject>
                                        <g i:extraneous="self">
                                            <path d="M24.411,2.564c-1.361-1.191-3.163-1.842-5.087-1.842s-3.732,0.656-5.093,1.847L13.52,3.191L12.798,2.56
                                                  c-1.361-1.191-3.175-1.852-5.099-1.852c-1.918,0-3.726,0.656-5.082,1.842C1.256,3.741,0.506,5.323,0.512,7.006
                                                  c0,1.683,0.755,3.26,2.116,4.452l10.351,9.057c0.144,0.126,0.336,0.193,0.524,0.193s0.38-0.063,0.523-0.188L24.4,11.477
                                                  c1.361-1.191,2.111-2.773,2.111-4.456C26.517,5.338,25.772,3.756,24.411,2.564z M23.354,10.556l-9.85,8.584l-9.828-8.599
                                                  C2.596,9.596,2,8.342,2,7.006s0.59-2.59,1.67-3.53c1.075-0.94,2.508-1.461,4.029-1.461c1.527,0,2.965,0.521,4.046,1.466
                                                  l1.246,1.09c0.292,0.256,0.761,0.256,1.053,0l1.235-1.08c1.08-0.945,2.519-1.466,4.04-1.466s2.954,0.521,4.035,1.461
                                                  c1.08,0.945,1.67,2.199,1.67,3.535C25.028,8.357,24.434,9.611,23.354,10.556z"/>
                                        </g>
                                    </switch>
                                </svg>
                            </a>
                            <a class="for_pointer" id="power_luv_user" title="Power Luv" <?php echo $pl_onclick; ?>>
                                <svg version="1.0" id="Layer_1" xmlns:x="&ns_extend;" xmlns:i="&ns_ai;" xmlns:graph="&ns_graphs;"
                                     xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" x="0px" y="0px" width="28.968px"
                                     height="26.607px" viewBox="0 0 28.968 26.607" enable-background="new 0 0 28.968 26.607" xml:space="preserve">
                                    <switch>
                                        <foreignObject requiredExtensions="&ns_ai;" x="0" y="0" width="1" height="1">
                                            <i:pgfRef  xlink:href="#adobe_illustrator_pgf"></i:pgfRef>
                                        </foreignObject>
                                        <g i:extraneous="self">
                                            <path d="M27.867,12.087c0.551-0.521,0.745-1.285,0.508-1.995c-0.238-0.71-0.858-1.217-1.62-1.325l-6.775-0.955
                                                  c-0.289-0.041-0.537-0.216-0.666-0.47l-3.029-5.954c-0.34-0.669-1.031-1.084-1.8-1.084c-0.769,0-1.459,0.416-1.799,1.084
                                                  L9.655,7.343c-0.129,0.254-0.379,0.429-0.667,0.47L2.213,8.768c-0.761,0.107-1.381,0.614-1.62,1.324
                                                  c-0.238,0.71-0.043,1.474,0.507,1.995l4.902,4.635c0.209,0.197,0.305,0.482,0.256,0.76l-1.157,6.545
                                                  c-0.13,0.734,0.176,1.463,0.798,1.902s1.432,0.496,2.114,0.148l6.059-3.09c0.258-0.131,0.566-0.131,0.825,0l6.06,3.09
                                                  c0.296,0.151,0.616,0.226,0.936,0.226c0.414,0,0.826-0.126,1.178-0.374c0.623-0.439,0.928-1.168,0.799-1.902l-1.158-6.544
                                                  c-0.049-0.278,0.047-0.563,0.256-0.761L27.867,12.087z M21.606,17.666l1.156,6.544c0.059,0.329-0.073,0.644-0.352,0.84
                                                  c-0.279,0.197-0.628,0.221-0.934,0.066l-6.06-3.09c-0.292-0.148-0.614-0.224-0.934-0.224s-0.642,0.075-0.934,0.224l-6.059,3.09
                                                  c-0.306,0.154-0.654,0.131-0.933-0.066c-0.279-0.196-0.41-0.51-0.353-0.84l1.157-6.544c0.111-0.632-0.104-1.275-0.577-1.722
                                                  l-4.903-4.635c-0.247-0.234-0.331-0.563-0.224-0.881c0.106-0.318,0.374-0.537,0.715-0.585l6.774-0.955
                                                  c0.654-0.092,1.219-0.49,1.511-1.064l3.03-5.954c0.152-0.3,0.45-0.479,0.794-0.479c0.345,0,0.642,0.179,0.795,0.479l3.029,5.954
                                                  c0.293,0.575,0.857,0.973,1.512,1.064l6.774,0.955c0.341,0.048,0.608,0.267,0.715,0.585c0.106,0.318,0.023,0.647-0.224,0.881
                                                  l-4.902,4.634C21.71,16.391,21.494,17.034,21.606,17.666z"/>
                                        </g>
                                    </switch>
                                </svg>
                            </a>
                        </div>
                    </div>
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
                    <li id="right_age">Age (<?php
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
<style type="text/css">
    .inner-content{position:relative;}
</style>
<script type="text/javascript" src='<?php echo base_url('/assets/fancybox/dist/jquery.fancybox.min.js'); ?>'></script>
<script type="text/javascript">
                                $(window).on('load', function () {
                                    setTimeout(function () {
                                        /*$("#radar").hide();*/
                                        $("#loader").fadeOut();
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
                                                location.href = "<?php echo base_url('/match/level2/'); ?>" + $(item).data("id") + "/1/1";
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
                                        $("#right_age").html("Age (" + nearby_matches[index].age + ")");
                                        $("#right_location").html((nearby_matches[index].address) ? "Location : " + nearby_matches[index].address : "Location : N/A");
                                        $("#right_distance").html((nearby_matches[index].distance) ? "Distance : " + nearby_matches[index].distance + " km" : "Distance : N/A");
                                        if (powerluvsreached == 1)
                                        {
                                            $("#power_luv_user").removeAttr("onclick");
                                        } else
                                        {
                                            if (nearby_matches[index].id)
                                            {
                                                $("#luv_user").attr("onclick", "$('#tinderslide').jTinder('luv');");
                                                $("#power_luv_user").attr("onclick", "$('#tinderslide').jTinder('powerluv');");
                                            } else
                                            {
                                                $("#luv_user").attr("onclick", "showMsg('Something went wrong!','alert alert-danger',true);");
                                                $("#power_luv_user").attr("onclick", "showMsg('Something went wrong!','alert alert-danger',true);");
                                            }
                                        }
                                    }
                                }
                                function prevMatch(id) {
<?php if ($is_user_premium_member == 1) { ?>
                                        if ($("#tinderslide ul li[data-id='" + id + "']").attr("data-nav") != 1)
                                            $('#tinderslide').jTinder('prev');
<?php } else { ?>
                                        showMsg("You need to be Luvr premium member to swipe back! <a href='<?php echo base_url() ?>#packages'>Click here to join</a>", "alert alert-danger", true);
                                        scrollToElement("#msg_txt");
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
                                                showMsg("Your likes quota per day has been reached! Therefore, right swipes for cards will not be considered.", "alert alert-danger");
                                                scrollToElement("#msg_txt");
                                            }
<?php if ($is_user_premium_member == 1) { ?>
                                                if ((data.user_powerluvs_per_day == <?php echo MAX_POWERLUVS_PER_DAY_P; ?>) && mode == "powerluv")
                                                {
                                                    powerluvsreached = 1;
                                                    reflectUserInfo(li_index);
                                                    showMsg("Your power luvs quota per day has been reached! Therefore, further power luvs will not be considered.", "alert alert-danger");
                                                    scrollToElement("#msg_txt");
                                                }
<?php } else { ?>
                                                if ((data.user_powerluvs_per_day == <?php echo MAX_POWERLUVS_PER_DAY; ?>) && mode == "powerluv")
                                                {
                                                    powerluvsreached = 1;
                                                    reflectUserInfo(li_index);
                                                    showMsg("Your power luvs quota per day has been reached! Therefore, further power luvs will not be considered.", "alert alert-danger");
                                                    scrollToElement("#msg_txt");
                                                }
<?php } ?>
                                            if (likedislikecounts == $("#tinderslide ul li.panel").length)
                                            {
                                                loadMoreNearBys();
                                            }
                                        }, error: function () {
                                            showMsg("Something went wrong!", "alert alert-danger", true);
                                            scrollToElement("#msg_txt");
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
                                                $(".user-list-l,.user-list-r").hide();
                                                $("#msg_txt").after('<div class="alert alert-info" id="nomoredata">We could not find more nearby matches around you!</div>');
                                                scrollToElement("#nomoredata");
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
</script>