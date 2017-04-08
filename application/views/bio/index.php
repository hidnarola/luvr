 <?php //pr($all_images); ?>
  
<div class="container-fluid bg-3 text-center">    
    <h3>Instagram BIO</h3><br>

    <div class="row" id="insta_img_list">
        <?php
            if(!empty($all_images)){
                foreach($all_images as $image){
                    // pr($image);
        ?>
            <div class="col-sm-3">
                <img src="<?php echo $image['images']['standard_resolution']['url']; ?>" class="img-responsive" style="width:100%" alt="Image">
                <a href="<?php echo $image['link']; ?>" target="_blank" class="btn btn-primary"> Insta Link </a>
            </div>
        <?php } } ?>

    </div>
    <div class="row">           
        <a class="btn btn-success" data-val="<?php echo $next_link; ?>" id="load_more_id" onclick="load_more(this)"> Load More </a>
        <span class="span_next_link"> </span>
    </div>
  
</div>
<br>
<br>
<br>
<br>
<script type="text/javascript">
    function load_more(obj){
        $.ajax({
            url:"<?php echo base_url().'home/fetch_insta_bio'; ?>",
            method:"POST",
            data:{next_url:$(obj).data('val')},
            dataType:"JSON",
            success:function(data){
                if(data['all_images'] != ''){
                    $('#insta_img_list').append(data['all_images']);
                    $('#load_more_id').attr('data-val',data['next_link']);
                    $('.span_next_link').html(data['next_link']);
                }
            }
        });
        console.log($(obj).data('val'));
    }
</script>

 