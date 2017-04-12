<div class="wrap">
    <div id="radar">
        <div id="rad"></div>
        <?php
        for ($i = 0; $i < 10; $i++) {
            $x = rand(100, 300);
            $y = rand(100, 300);
            echo '<div class="obj" data-x="' . $x . '" data-y="' . $y . '"></div>';
        }
        ?>
    </div>
    <div id="tinderslide" style="visibility:hidden;">
        <ul>
            <?php
            if (!empty($nearByUsers)) {
                foreach ($nearByUsers as $user) {
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
            }else{
                echo '<p class="alert alert-info">We could not find any nearby matches around you!</p>';
            }
            ?>
        </ul>
    </div>
</div>
<script type="text/javascript">
    $(function () {
        var $rad = $('#rad'),
                $obj = $('.obj'),
                deg = 0,
                rad = $rad.width() / 2;

        $obj.each(function () {
            var pos = $(this).data(),
                    getAtan = Math.atan2(pos.x - rad, pos.y - rad),
                    getDeg = (-getAtan / (Math.PI / 180) + 180) | 0;
            // Read/set positions and store degree
            $(this).css({left: pos.x, top: pos.y}).attr('data-atDeg', getDeg);
        });

        (function rotate() {
            $rad.css({transform: 'rotate(' + deg + 'deg)'}); // Radar rotation
            $('[data-atDeg=' + deg + ']').stop().fadeTo(0, 1).fadeTo(1700, 0.2); // Animate dot at deg
            deg = ++deg % 360;      // Increment and reset to 0 at 360
            setTimeout(rotate, 15); // LOOP
        })();

    });
    $(window).on('load', function () {
        setTimeout(function () {
            $("#radar").hide();
            $("#tinderslide").removeAttr('style');
        }, Math.floor((Math.random() * 1000) + 1000));
    });
</script>
<style>
    #radar{
        position:relative;
        overflow:hidden;
        width:321px; height:321px;
        background:#222 url(<?php echo base_url(); ?>assets/images/vY6Tl.png);
        border-radius: 50%;
    }
    #rad{
        position:absolute;
        width:321px;
        height:321px; background:url(<?php echo base_url(); ?>assets/images/fbgUD.png);
    }
    .obj{
        background:#cf5;
        position:absolute;
        border-radius: 50%;
        width:4px; height:4px; margin:-2px;
        box-shadow:0 0 10px 5px rgba(100,255,0,0.5);
        opacity:0.2;
    }
</style>