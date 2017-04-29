<div class="my-account">

    <?php
    $message = $this->session->flashdata('message');
    if (!empty($message)) {
        echo '<div class="' . $message['class'] . '">' . $message['message'] . '</div>';
    }$this->load->view('side_bar_account');
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
                <h2 class="account-title">My Medias</h2>
                <p> &nbsp; </p>
            </div>  
            <div class="account-body-body">
                
                <form action="<?php echo base_url().'bio/upload_feed'; ?>" enctype="multipart/form-data" method="post">
                    <input type="file" name="feed" >                    
                    <input type="submit" value="Submit" class="btn btn-primary">
                </form>

                <ul class="my-picture-ul" id="insta_img_list">
                    <?php
                    if (!empty($all_images)) {

                        foreach ($all_images as $image) {

                            $is_delete = 'yes';
                            $dynamic_id = random_string();

                            $type = $image['media_type'];

                            $thumb = $image['media_thumb'];
                            $image_link = $image['media_name'];
                                                        
                            // If type is uploaded video or video URL from instagram or facebook
                            if ($type == '4') {
                                $fancybox_str = '';
                                $anchor_target = '_blank';                                
                                $img_thumb = $link = $image['media_thumb'];
                                $image_link = base_url() . "video/play/" . $image['id'];
                            }

                            if($type == '3'){
                                $fancybox_str = 'data-fancybox="gallery"';
                                $anchor_target = '';
                                $img_thumb = $link = $image['media_name'];
                            }

                            if($type == '2'){
                                $fancybox_str = '';
                                $anchor_target = '_blank';
                                $image['media_name'] = str_replace('.mp4','.png', $image['media_name']);
                                $img_thumb = base_url().'bio/show_img/'.$image['media_thumb'].'/1';
                                $image_link = $link = base_url() . "video/play/".$image['id'];
                            }

                            if($type == '1'){
                                $fancybox_str = 'data-fancybox="gallery"';
                                $anchor_target = '';
                                $image_link = $img_thumb = $link = base_url().'bio/show_img/'.$image['media_name'];
                            }

                        ?>
                            <li id="<?php echo $dynamic_id; ?>">
                                <div class="my-picture-box">
                                    <a>
                                        <img src="<?php echo $img_thumb; ?>" alt="" />
                                    </a>
                                    <div class="picture-action">
                                        <div class="picture-action-inr">

                                            <a data-type="<?php echo $type; ?>" data-insta-id="<?= $image['id'] ?>" data-insta-time="<?= strtotime($image['insta_datetime']) ?>"
                                               data-val="<?= urlencode($link) ?>" class="for_pointer icon-picture js-mytooltip type-inline-block style-block style-block-one" 
                                               data-thumb="<?= urlencode($thumb) ?>" onclick="ajax_set_profile(this)"
                                               data-mytooltip-custom-class="align-center" data-mytooltip-content="Set as a profile pic" >
                                            </a>

                                            <a <?php echo $fancybox_str; ?> href="<?php echo $image_link; ?>" target="<?php echo $anchor_target; ?>"
                                                class="for_pointer icon-full-screen image-link js-mytooltip type-inline-block style-block style-block-one"
                                                data-mytooltip-custom-class="align-center" data-mytooltip-content="Full screen">
                                            </a>

                                            <a data-type="<?php echo $type; ?>" data-insta-id="<?= $image['media_id'] ?>" data-insta-time="<?= strtotime($image['insta_datetime']) ?>"
                                               data-val="<?= $link ?>" class="for_pointer icon-cancel js-mytooltip type-inline-block style-block style-block-one" data-thumb="<?= $thumb ?>" 
                                               onclick="ajax_save_bio(this)" data-is-delete="<?= $is_delete ?>" data-dynamic-id="<?php echo $dynamic_id; ?>"
                                               data-mytooltip-custom-class="align-center" data-mytooltip-content="Delete from Bio">
                                            </a>

                                        </div>
                                    </div>
                                </div>
                            </li>                                                          
                        <?php 
                            //if($type == '2'){ pr($image,1); } 
                        } ?>
                    <?php } ?>
                </ul>


                <?php if (!empty($next_link)) { ?>                    
                    <div class="load-more">
                        <a data-val="<?php echo $next_link; ?>" class="for_pointer" id="load_more_id" onclick="load_more(this)"> Load More </a>
                    </div>                                            
                <?php } ?>

            </div>
        </div>
    </div>
</div>

<input type="hidden" id="all_saved_media"  value="<?php echo (!empty($all_saved_media)) ? implode(',', $all_saved_media) : ''; ?>">

<script type="text/javascript">

    function ajax_save_bio(obj) {

        var img_name = $(obj).data('val');
        var type = $(obj).data('type');
        var insta_id = $(obj).data('insta-id');
        var insta_time = $(obj).data('insta-time');
        var thumb = $(obj).data('thumb');
        var is_delete = $(obj).data('is-delete');
        var dynamic_id = $(obj).data('dynamic-id');

        $.ajax({
            url: "<?php echo base_url() . 'bio/ajax_save_bio'; ?>",
            method: "POST",
            data: {img_name: img_name, type: type, insta_id: insta_id, insta_time: insta_time, thumb: thumb, is_delete: is_delete},
            dataType: "JSON",
            success: function (data) {
                show_notification('<strong> Success </strong>',
                            'Your feed has been removed from Bio.',
                            'success');                    
                $('#' + dynamic_id).fadeOut();
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