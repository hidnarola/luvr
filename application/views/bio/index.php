
<link rel="stylesheet" href="<?php echo base_url().'assets/css/asTooltip.min.css'; ?>"> <!-- Css for the tooltip -->

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
                <h2 class="account-title">My Instagram Feeds</h2>
                <p> &nbsp; </p>
            </div>  
            <div class="account-body-body">
                <ul class="my-picture-ul" id="insta_img_list">
                    <?php
                    if (!empty($all_images)) {
                        $i = 30;

                        foreach ($all_images as $image) {

                            $type = $image['type'];
                            $thumb = $image['images']['thumbnail']['url'];
                            $image_link = $link = $image['images']['standard_resolution']['url'];

                            $dynamic_id = random_string();

                            if ($type == 'video') {
                                $image_link = $image['videos']['standard_resolution']['url'];
                            }

                            $is_delete = 'no';

                            if (in_array($image['id'], $all_saved_media)) {
                                $is_delete = 'yes';
                            }

                            if ($is_delete == 'no') {
                                ?>
                                <li id="<?php echo $dynamic_id; ?>">
                                    <div class="my-picture-box">
                                        <a>
                                            <img src="<?php echo $link; ?>" alt="" />
                                        </a>
                                        <div class="picture-action">
                                            <div class="picture-action-inr">

                                                <a data-type="<?php echo $type; ?>" data-insta-id="<?= $image['id'] ?>" data-insta-time="<?= $image['created_time'] ?>"
                                                   data-val="<?= $link ?>" class="for_pointer icon-picture" data-thumb="<?= $thumb ?>" onclick="ajax_set_profile(this)">
                                                </a>

                                                <a data-fancybox="gallery" href="<?php echo $image_link; ?>" class="icon-full-screen image-link"></a>

                                                <a data-type="<?php echo $type; ?>" data-insta-id="<?= $image['id'] ?>" data-insta-time="<?= $image['created_time'] ?>"
                                                   data-val="<?= $link ?>" class="for_pointer icon-tick-inside-circle" data-thumb="<?= $thumb ?>" 
                                                   onclick="ajax_save_bio(this)" data-is-delete="<?= $is_delete ?>" data-dynamic-id="<?php echo $dynamic_id; ?>">
                                                </a>

                                            </div>
                                        </div>
                                    </div>
                                </li>
                                <?php
                                $i--;
                            }
                            ?>
                        <?php } ?>
                    <?php } else { ?>

                    </ul>

                    <div class="alert alert-danger">No Feed found</div>    

                <?php } ?>

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

    function load_more(obj) {
        var all_saved_media = $('#all_saved_media').val();
        $.ajax({
            url: "<?php echo base_url() . 'bio/fetch_insta_bio'; ?>",
            method: "POST",
            data: {next_url: $(obj).data('val'), all_saved_media: all_saved_media},
            dataType: "JSON",
            success: function (data) {
                if (data['all_images'] != '') {

                    $('#insta_img_list').append(data['all_images']);
                    if (data['next_link'] != '') {
                        $('#load_more_id').data('val', data['next_link']);
                    } else {
                        $('#load_more_id').data('val', '');
                        $('#load_more_id').hide();
                    }
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
                } else {
                    alert('ERROR:CAN NOT SAVE MORE THAN 50 IMAGES');
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

<?php if ($i == 30 || ($i >= 15 && $i <= 30)) { ?>
        $('#load_more_id').click();
<?php } ?>
</script>

<script type="text/javascript" href="<?php echo base_url().'assets/js/jquery-asTooltip.min.js'; ?>"></script>