 <?php //pr($all_saved_media); //pr($all_images); ?>

 <div class="my-account">
    
    <?php $this->load->view('side_bar_account'); ?>

    <div class="col-md-8 col-sm-8 col-xs-12 account-r">
        <div class="account-r-head">
            <h2><big>Luvr Admin Name</big> <small>Standard dummy</small></h2>
            <a href="" class="green-btn" ><big>015</big><small> View</small></a>
        </div>
        <div class="account-r-body">
            <div class="account-body-head">
                <h2 class="account-title">My Picture</h2>
                <p>luvr.com</p>
            </div>  
            <div class="account-body-body">
                <ul class="my-picture-ul">
                    <li>
                        <div class="my-picture-box">
                            <a ><img src="<?php echo base_url(); ?>assets/images/popup-img02.jpg" alt="" /></a>
                            <div class="picture-action">
                                <div class="picture-action-inr">
                                    <a href="" class="icon-picture"></a>
                                    <a href="<?php echo base_url(); ?>assets/images/popup-img01.jpg" class="icon-full-screen image-link"></a>
                                    <a href="" class="icon-cancel"></a>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="my-picture-box">
                            <a ><img src="<?php echo base_url(); ?>assets/images/popup-img02.jpg" alt="" /></a>
                            <div class="picture-action">
                                <div class="picture-action-inr">
                                    <a href="" class="icon-picture"></a>
                                    <a href="<?php echo base_url(); ?>assets/images/popup-img01.jpg" class="icon-full-screen image-link"></a>
                                    <a href="" class="icon-tick-inside-circle"></a>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="my-picture-box">
                            <a ><img src="<?php echo base_url(); ?>assets/images/popup-img03.jpg" alt="" /></a>
                            <div class="picture-action">
                                <div class="picture-action-inr">
                                    <a href="" class="icon-picture"></a>
                                    <a href="<?php echo base_url(); ?>assets/images/popup-img01.jpg" class="icon-full-screen image-link" ></a>
                                    <a href="" class="icon-tick-inside-circle"></a>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="my-picture-box">
                            <a ><img src="<?php echo base_url(); ?>assets/images/popup-img04.jpg" alt="" /></a>
                            <div class="picture-action">
                                <div class="picture-action-inr">
                                    <a href="" class="icon-picture"></a>
                                    <a href="<?php echo base_url(); ?>assets/images/popup-img01.jpg" class="icon-full-screen image-link" ></a>
                                    <a href="" class="icon-tick-inside-circle"></a>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="my-picture-box">
                            <a ><img src="<?php echo base_url(); ?>assets/images/popup-img01.jpg" alt="" /></a>
                            <div class="picture-action">
                                <div class="picture-action-inr">
                                    <a class="icon-picture image-link" href="<?php echo base_url(); ?>assets/images/popup-img01.jpg"></a>
                                    <a href="<?php echo base_url(); ?>assets/images/popup-img01.jpg" class="icon-full-screen image-link"></a>
                                    <a href="" class="icon-tick-inside-circle"></a>
                                </div>
                            </div>
                        </div>
                    </li>
                    <li>
                        <div class="my-picture-box">
                            <a ><img src="<?php echo base_url(); ?>assets/images/popup-img02.jpg" alt="" /></a>
                            <div class="picture-action">
                                <div class="picture-action-inr">
                                    <a class="image-link icon-picture" href="<?php echo base_url(); ?>assets/images/popup-img02.jpg"></a>
                                    <a href="<?php echo base_url(); ?>assets/images/popup-img01.jpg" class="icon-full-screen image-link" ></a>
                                    <a href="" class="icon-tick-inside-circle"></a>
                                </div>
                            </div>
                        </div>
                    </li>
                </ul>
                <div class="load-more"><a href="#">Load more</a></div>
            </div>
        </div>
    </div>
</div>  


<!-- NEW SECTION   -->

<div class="container-fluid bg-3 text-center">    
    
    <h3>Instagram BIO</h3>    

    <a class="test-popup-link" href="<?php echo base_url(); ?>assets/images/popup-img01.jpg">Open popup</a>

    <br>
    <div class="row" id="insta_img_list">
        <?php
            if(!empty($all_images)){
                $i = 30;

                foreach($all_images as $image){
                    
                    $type = $image['type'];
                    $thumb = $image['images']['thumbnail']['url'];
                    $link = $image['images']['standard_resolution']['url'];
                    
                    $is_delete = 'no';
                    $save_icon = 'ok';
                    $save_link_class = 'success';
                    
                    if(in_array($image['id'], $all_saved_media)){
                        $is_delete = 'yes';
                        $save_link_class = 'danger';
                        $save_icon = 'remove';
                    }

                    if($is_delete == 'no') {
                        ?>
                        <div class="col-sm-3" style="margin-bottom:10px;">

                            <img src="<?php echo $link; ?>" class="img-responsive" style="width:100%" alt="Image">
                            
                            <a style="margin-top:10px" href="<?php echo $image['link']; ?>" target="_blank" class="btn btn-primary"> 
                                <span class="glyphicon glyphicon-link"></span>
                            </a>

                            <a style="margin-top:10px" data-type="<?php echo $type; ?>" data-insta-id="<?=$image['id']?>" data-insta-time="<?= $image['created_time']?>"
                               data-val="<?=$link?>" class="btn btn-<?=$save_link_class?>" data-thumb="<?=$thumb?>" onclick="ajax_save_bio(this)" data-is-delete="<?=$is_delete?>" >
                                <span class="glyphicon glyphicon-<?=$save_icon?>"></span>
                            </a>
                            
                            <a style="margin-top:10px" data-type="<?php echo $type; ?>" data-insta-id="<?=$image['id']?>" data-insta-time="<?= $image['created_time']?>"
                                data-val="<?=$link?>" class="btn btn-warning" data-thumb="<?=$thumb?>" onclick="ajax_set_profile(this)">
                                <span class="glyphicon glyphicon-picture"></span>
                            </a>
                        </div>
                    <?php $i--; } ?>
                <?php } ?>
            <?php } ?>
    </div>

    
    
    <?php if(!empty($next_link)) { ?>
        <div class="row">           
            <a class="btn btn-success" data-val="<?php echo $next_link; ?>" id="load_more_id" onclick="load_more(this)"> Load More </a>
        </div>
    <?php } ?>

    <span class="span_next_link"> </span> 
</div>
<br>
<br>
<br>
<br>

<input type="hidden" id="all_saved_media"  value="<?php echo (!empty($all_saved_media)) ? implode(',',$all_saved_media):'';?>">

<script type="text/javascript">

    function load_more(obj){
        var all_saved_media = $('#all_saved_media').val();
        $.ajax({
            url:"<?php echo base_url().'bio/fetch_insta_bio'; ?>",
            method:"POST",
            data:{next_url:$(obj).data('val'),all_saved_media:all_saved_media},
            dataType:"JSON",
            success:function(data){
                if(data['all_images'] != ''){

                    $('#insta_img_list').append(data['all_images']);
                    if(data['next_link'] != ''){
                        $('#load_more_id').data('val',data['next_link']);
                    }else{
                        $('#load_more_id').data('val','');
                        $('#load_more_id').hide();
                    }                    
                }
            }
        });
    }

    function ajax_save_bio(obj){

        var img_name = $(obj).data('val');
        var type = $(obj).data('type');
        var insta_id = $(obj).data('insta-id');
        var insta_time = $(obj).data('insta-time');
        var thumb = $(obj).data('thumb');
        var is_delete = $(obj).data('is-delete');

        $.ajax({
            url:"<?php echo base_url().'bio/ajax_save_bio'; ?>",
            method:"POST",
            data:{img_name:img_name,type:type,insta_id:insta_id,insta_time:insta_time,thumb:thumb,is_delete:is_delete},
            dataType:"JSON",
            success:function(data){
                if(data['status'] != 'error'){
                    if(is_delete == 'yes'){
                        $(obj).data('is-delete','no');
                        $(obj).removeClass('btn-danger').addClass('btn-success');
                        $(obj).find("span").removeClass('glyphicon-remove').addClass('glyphicon-ok');
                    }else{
                        $(obj).data('is-delete','yes');
                        $(obj).addClass('btn-danger').removeClass('btn-success');
                        $(obj).find("span").removeClass('glyphicon-ok').addClass('glyphicon-remove');
                    }
                }else{
                    alert('ERROR:CAN NOT SAVE MORE THAN 50 IMAGES');
                }
            }
        });
    }

    function ajax_set_profile(obj){
        
        var img_name = $(obj).data('val');
        var type = $(obj).data('type');
        var insta_id = $(obj).data('insta-id');
        var insta_time = $(obj).data('insta-time');
        var thumb = $(obj).data('thumb');
        var is_delete = $(obj).data('is-delete');

        var new_str = "?img_name="+img_name + "&type="+type + "&insta_id="+insta_id + "&insta_time="+insta_time + "&thumb="+thumb + "&is_delete="+is_delete;

        window.location.href = "<?php echo base_url().'bio/ajax_picture_set_profile'; ?>"+new_str;
        
    }

    <?php if($i == 30 || ($i>=15 && $i<=30)) { ?>
        $('#load_more_id').click();
    <?php } ?>


</script>    
<script src="<?php echo base_url() . 'assets/js/jquery.fancybox.min.js'; ?>" type="text/javascript"></script>
<script src="<?php echo base_url(); ?>assets/js/custom.js"></script>
