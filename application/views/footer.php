</div>
</div>
<br/>
<br/>
<br/>
<br/>
<br/>
<footer class="container-fluid text-center">
    <p>Footer Text</p>
</footer>
<script type="text/javascript">
<?php if ($sub_view == "user/userFilterSettings") { ?>
        function saveFilter(filter_id) {
            $.ajax({
                url: "<?php echo base_url(); ?>user/savestep",
                type: 'POST',
                dataType: 'json',
                data: "filter_id=" + filter_id + "&" + $('#updatefiltersform').serialize(),
                success: function (data) {
                    if (data.success == true) {
                        $("#save_step_btn").attr("onclick", "saveFilter(" + data.next_filter_id + ")");
                        $("#save_step_btn").attr("data-step", parseInt($("#save_step_btn").attr("data-step")) + 1);
                        if (data.next_filter_name)
                            $("#lbl_filter_name").text(data.next_filter_name);
                        $("#updatefiltersform tbody").html(data.next_filter_html);
                        if ($("#save_step_btn").attr("data-step") > $("#save_step_btn").attr("data-total-steps")) {
                            location.href = '<?php echo base_url() . $redirect; ?>';
                        }
                    } else {
                        alert("Something went wrong!");
                    }
                }, error: function () {
                    alert("Something went wrong!");
                }
            });
        }
        function ignoreOther() {
            if ($("#idontcare").is(":checked")) {
                $("#updatefiltersform .subfilters").prop("checked", false);
            }
        }
        function ignoreLast() {
            $("#updatefiltersform #idontcare").prop("checked", false);
        }
<?php } ?>
<?php if ($sub_view == "match/nearByMatches") { ?>
        var likedislikecounts = 0;
        registerjTinder();
        function registerjTinder() {
            $("#tinderslide").jTinder({
                onLike: function (item) {
                    likedislikeuser($(item).data("id"), 'like');
                },
                onDislike: function (item) {
                    likedislikeuser($(item).data("id"), 'dislike');
                },
                animationRevertSpeed: 200,
                animationSpeed: 500,
                threshold: 4,
                likeSelector: '.like',
                dislikeSelector: '.dislike'
            });
        }
        function likedislikeuser(user_id, mode) {
            $.ajax({
                url: "<?php echo base_url(); ?>match/likedislike",
                type: 'POST',
                dataType: 'json',
                data: "user_id=" + user_id + "&status=" + mode,
                success: function (data) {
                    likedislikecounts++;
                    if (data.success == true) {
                    }
                    if (likedislikecounts == $("#tinderslide ul li.panel").length)
                    {
                        loadMoreNearBys();
                    }
                }, error: function () {
                    alert("Something went wrong!");
                }
            });
        }
        function loadMoreNearBys() {
            $("#tinderslide").css('visibility', 'hidden');
            $("#radar").show();
            $.ajax({
                url: "<?php echo base_url(); ?>match/loadMoreNearBys",
                type: 'POST',
                dataType: 'json',
                success: function (data) {
                    if (data.success == true) {
                        likedislikecounts = 0;
                        if (data.data) {
                            var ul_html = "";
                            for (var i = 0; i < data.data.length; i++) {
                                var user = data.data[i];
                                var path = "";
                                if (user.media_type == 1 || user.media_type == 2) {
                                    if (user.media_type == 1)
                                        path = "<?php echo base_url(); ?>assets/images/users/" + user.user_profile;
                                    else
                                        path = "<?php echo base_url(); ?>assets/videos/users/" + user.user_profile;
                                } else if (user.media_type == 3 || user.media_type == 4) {
                                    path = user.user_profile;
                                }
                                ul_html += '<li class="panel" data-id="' + user.id + '">';
                                ul_html += '<div style="background:url(\'' + path + '\') no-repeat scroll center center;" class="img"></div>';
                                ul_html += '<div>' + user.user_name + '</div>';
                                ul_html += '<div class="like"></div>';
                                ul_html += '<div class="dislike"></div>';
                                ul_html += '</li>';
                            }
                            $("#tinderslide ul").html(ul_html);
                            setTimeout(function () {
                                $("#radar").hide();
                                $("#tinderslide").removeAttr('style');
                            }, Math.floor((Math.random() * 1000) + 1000));
                            registerjTinder();
                        }
                    }
                }
            });
        }
<?php } ?>
    function log(text) {
        console.log(text);
    }
</script>
</body>