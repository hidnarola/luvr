<?php //pr($nearByUsers);               ?>
<div class="wrap">
    <div id="tinderslide">
        <ul>
            <?php
            if (!empty($nearByUsers)) {
                foreach ($nearByUsers as $user) {
                    if ($user['media_type'] != 0) {
                        $path = "";
                        if ($user['media_type'] == 1 || $user['media_type'] == 2) {
                            if ($user['media_type'] == 1)
                                $path = base_url() . "assets/images/users/" . $user['user_profile'];
                            else
                                $path = base_url() . "assets/videos/users/" . $user['user_profile'];
                        } else if ($user['media_type'] == 3 || $user['media_type'] == 4) {
                            $path = $user['user_profile'];
                        }
                        echo '<li class="panel" data-id="' . $user['id'] . '">
                        <div style="background:url(\'' . $path . '\') no-repeat scroll center center;" class="img"></div>
                        <div>' . $user['user_name'] . '</div>
                        <div class="like"></div>
                        <div class="dislike"></div>
                    </li>';
                    }
                }
            }
            ?>
        </ul>
    </div>
</div>