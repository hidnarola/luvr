<div class="my-account">
    <?php
        $message = $this->session->flashdata('message');
        if (!empty($message)) {
            echo '<div class="' . $message['class'] . '">' . $message['message'] . '</div>';
        }
        $this->load->view('side_bar_account');
    ?>
    <div class="col-md-8 col-sm-8 col-xs-12 account-r">
        <div class="account-r-head">                        
            <h2>
                <big>
                    <?php 
                        $user_data = $this->session->userdata('user');
                        $username = (!empty($user_data['user_name'])) ? ucfirst($user_data['user_name']) : $user_data['instagram_username'];
                        echo $username;
                    ?>
                </big>
            </h2>
        </div>
        <div class="account-r-body">
            <div class="account-body-head">
                <h2 class="account-title">My Facebook Feeds</h2>
                <p> &nbsp; </p>
            </div>  
            <div class="account-body-body">
                <ul class="my-picture-ul" id="fb_img_list">
                    <?php
                    if (!empty($all_images)) {

                        foreach ($all_images as $image) {

                            if($image['type'] == 'video' || $image['type'] == 'photo'){

                                $type = '3'; // For online image link
                                $thumb = $image['full_picture'];
                                $image_link = $link = $data_val = $image['full_picture'];
                                $fancybox_str = 'data-fancybox="gallery"';
                                $anchor_target = '';
                                $dynamic_id = random_string();
                                $is_video_class = '';

                                if ($image['type'] == 'video') {
                                    $type = '4'; // For the online video link
                                    if(strpos($image['source'],"video.xx.fbcdn.net") == FALSE){ continue; }
                                    $fancybox_str = '';
                                    $anchor_target = '_blank';
                                    $image_link = base_url() . "video/play?url=".urlencode($image['source']);
                                    $data_val =  $image['source'];
                                    $is_video_class = 'video-tag';
                                }

                                $is_delete = 'no';
                                if (in_array($image['id'], $all_saved_media)) { continue; }

                                    ?>
                                    <li id="<?php echo $dynamic_id; ?>">
                                        <div class="my-picture-box">
                                            <a class="<?php echo $is_video_class; ?>">
                                                <img src="<?php echo $link; ?>" alt="" />
                                            </a>
                                            <div class="picture-action">
                                                <div class="picture-action-inr">

                                                    <a data-type="<?php echo $type; ?>" data-insta-id="<?= $image['id'] ?>" data-insta-time="<?= $image['created_time'] ?>"
                                                       data-val="<?= urlencode($link) ?>" class="for_pointer icon-picture js-mytooltip type-inline-block style-block style-block-one" 
                                                       data-thumb="<?= urlencode($thumb) ?>" onclick="ajax_set_profile(this)" data-is-delete="<?= $is_delete ?>"
                                                       data-mytooltip-custom-class="align-center" data-mytooltip-content="Set as a profile pic">
                                                    </a>

                                                    <a <?php echo $fancybox_str; ?> href="<?php echo $image_link; ?>" target="<?php echo $anchor_target; ?>"
                                                        class="icon-full-screen image-link js-mytooltip type-inline-block style-block style-block-one"
                                                        data-mytooltip-custom-class="align-center" data-mytooltip-content="Full screen">
                                                    </a>

                                                    <a data-type="<?php echo $type; ?>" data-insta-id="<?= $image['id'] ?>" data-insta-time="<?= $image['created_time'] ?>"
                                                       data-val="<?= $data_val ?>" data-thumb="<?= $thumb ?>" 
                                                       class="for_pointer icon-tick-inside-circle js-mytooltip type-inline-block style-block style-block-one"
                                                       onclick="ajax_save_bio(this)" data-is-delete="<?= $is_delete ?>" data-dynamic-id="<?php echo $dynamic_id; ?>"
                                                       data-mytooltip-custom-class="align-center" data-mytooltip-content="Save into Bio">
                                                    </a>

                                                </div>
                                            </div>
                                        </div>
                                    </li>
                            <?php } ?>
                        <?php } ?>
                    <?php } else { ?>

                    </ul>

                    <div class="alert alert-danger">No Feed found</div>    

                <?php } ?>               

            </div>
             <?php if (!empty($next_link)) { ?>                    
                    <div class="load-more">
                        <a data-val="<?php echo $next_link; ?>" class="for_pointer" id="load_more_id" onclick="load_more(this)"> Load More </a>
                    </div>                                            
                <?php } ?>
        </div>
    </div>
</div>

<input type="hidden" id="all_saved_media"  value="<?php echo (!empty($all_saved_media)) ? implode(',', $all_saved_media) : ''; ?>">

<script type="text/javascript">

    function load_more(obj) {
        var all_saved_media = $('#all_saved_media').val();
        $.ajax({
            url: "<?php echo base_url() . 'bio/fetch_facebook_bio'; ?>",
            method: "POST",
            data: {next_url: $(obj).data('val'), all_saved_media: all_saved_media},
            dataType: "JSON",
            success: function (data) {

                $('#fb_img_list').append(data['all_images']);
                $('.js-mytooltip').myTooltip();                 

                if (data['next_link'] != '') {                    
                    $('#load_more_id').data('val', data['next_link']);
                } else {
                    
                    $('#load_more_id').data('val', '');
                    $('#load_more_id').hide();
                    show_notification('<strong> Error </strong>','No Feed available','error');
                }

            }
        });
    }

    function ajax_save_bio(obj) {

        var img_name = $(obj).data('val');
        var type = $(obj).data('type');
        var insta_id = $(obj).data('insta-id');
        var insta_time = $(obj).data('insta-time');
        var thumb = $(obj).data('thumb');
        var is_delete = $(obj).data('is-delete');
        var dynamic_id = $(obj).data('dynamic-id');

        // console.log(is_delete);
        // return false;

        $.ajax({
            url: "<?php echo base_url() . 'bio/ajax_save_bio'; ?>",
            method: "POST",
            data: {img_name: img_name, type: type, insta_id: insta_id, insta_time: insta_time, thumb: thumb, is_delete: is_delete},
            dataType: "JSON",
            success: function (data) {
                if (data['status'] != 'error') {
                    
                    $('#' + dynamic_id).fadeOut();
                    show_notification('<strong> Success </strong>',
                            'Your feed has been saved into Bio.',
                            'success');
                } else {
                    show_notification('<strong> Error </strong>',
                            'Can Not Save More Than 50 Images.',
                            'error'); 
                    //alert('ERROR:CAN NOT SAVE MORE THAN 50 IMAGES');
                }
            }
        });
    }

    function ajax_set_profile(obj) {

        var img_name = $(obj).data('val');
        var type = $(obj).data('type');
        var insta_id = $(obj).data('insta-id');
        var insta_time = $(obj).data('insta-time');
        var thumb = $(obj).data('thumb');
        var is_delete = $(obj).data('is-delete');        

        var new_str = "?img_name=" + img_name + "&type=" + type + "&insta_id=" + insta_id + "&insta_time=" + insta_time + "&thumb=" + thumb + "&is_delete=" + is_delete;

        window.location.href = "<?php echo base_url() . 'bio/ajax_picture_set_profile'; ?>" + new_str;
    }    

    $(document).ready(function() {
        if($('.js-mytooltip').length != 0){
            $('.js-mytooltip').myTooltip();
        }
    });

</script>