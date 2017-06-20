<div class="container">
    <div class="row">
        <div class="back-btn-div"><a onclick="window.history.back();" class="for_pointer"></a></div>    
    </div>
</div>
<div class="dating-box">
    <ul class="my-picture-ul">
        <?php
        for ($i = 1; $i <= 12; $i++) {
            echo '<li>
                    <div class="my-picture-box">
                        <a><img src="' . S3_URL . '/Videos/Dating/thumbs/video' . $i . '.jpg" alt="" /></a>
                        <div class="picture-action">
                            <div class="picture-action-inr">
                                <a href="' . base_url("drluvr/video" . $i) . '" class="icon-play-button"></a>
                            </div>
                        </div>
                    </div>
                </li>';
        }
        ?>
    </ul>
</div>