<div class="container-fluid bg-3 text-center">
    <h3>SAVED BIO FEED </h3>
    <br>
    <div class="row" id="insta_img_list">
        <?php
            $i=0;                    
            if(!empty($all_images)){                
                foreach($all_images as $image){

                    $type = $image['media_type'];
                    $thumb = $image['media_thumb'];
                    $link = $image['media_name'];
                    
                    $is_delete = 'yes';
                    $save_link_class = 'danger';
                    $save_icon = 'remove';

                    // if(in_array($image['media_id'], $all_saved_media) == false){
                    //     $is_delete = 'yes';
                    //     $save_icon = 'ok';
                    //     $save_link_class = 'success';    
                    // }
        ?>
                        <div class="col-sm-3" style="margin-bottom:10px;" id="dynamic_<?=$i?>">

                            <img src="<?php echo $link; ?>" class="img-responsive" style="width:100%" alt="Image">

                            <a style="margin-top:10px" href="<?php echo $link; ?>" target="_blank" class="btn btn-primary"> 
                                <span class="glyphicon glyphicon-link"></span>
                            </a>

                            <a style="margin-top:10px" data-type="<?php echo $type; ?>" data-insta-id="<?=$image['media_id']?>" data-insta-time="<?= strtotime($image['insta_datetime'])?>"
                               data-val="<?=$link?>" class="btn btn-<?=$save_link_class?>" data-thumb="<?=$thumb?>" onclick="ajax_save_bio(this)" data-is-delete="<?=$is_delete?>"
                               data-dynamic-id="<?php echo $i; ?>" >
                                <span class="glyphicon glyphicon-<?=$save_icon?>"></span>
                            </a>
                            
                            <a style="margin-top:10px" data-type="<?php echo $type; ?>" data-insta-id="<?=$image['id']?>" data-insta-time="<?= strtotime($image['insta_datetime'])?>"
                                data-val="<?=$link?>" class="btn btn-warning" data-thumb="<?=$thumb?>" onclick="ajax_set_profile(this)">
                                <span class="glyphicon glyphicon-picture"></span>
                            </a>
                        </div>                    
                <?php $i++; } ?>
            <?php } ?>
    </div>
</div>

<br>
<br>
<br>
<br>

<input type="hidden" id="all_saved_media"  value="<?php echo (!empty($all_saved_media)) ? implode(',',$all_saved_media):'';?>">

<script type="text/javascript">
 

    function ajax_save_bio(obj){

        var img_name = $(obj).data('val');
        var type = $(obj).data('type');
        var insta_id = $(obj).data('insta-id');
        var insta_time = $(obj).data('insta-time');
        var thumb = $(obj).data('thumb');
        var is_delete = $(obj).data('is-delete');
        var i_id = $(obj).data('dynamic-id');

        $.ajax({
            url:"<?php echo base_url().'bio/ajax_save_bio'; ?>",
            method:"POST",
            data:{img_name:img_name,type:type,insta_id:insta_id,insta_time:insta_time,thumb:thumb,is_delete:is_delete},
            dataType:"JSON",
            success:function(data){                
                $('#dynamic_'+i_id).fadeOut();
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

        $.ajax({
            url:"<?php echo base_url().'bio/ajax_picture_set_profile'; ?>",
            method:"POST",
            data:{img_name:img_name,type:type,insta_id:insta_id,insta_time:insta_time,thumb:thumb,is_delete:is_delete},
            dataType:"JSON",
            success:function(data){
                location.reload();
            }
        });
    }
 

</script>

 