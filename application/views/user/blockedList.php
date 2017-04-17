<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/font-awesome/css/font-awesome.min.css"/>
<h2>Blocked Users</h2>
<hr>
<div class="container bootstrap snippet">
    <div class="row">
        <div class="main-box no-header clearfix">
            <div class="main-box-body clearfix">
                <?php if (!empty($blockedUsers)) { ?>
                    <div class="table-responsive">
                        <table class="table user-list">
                            <thead>
                                <tr>
                                    <th><span>User</span></th>
                                    <th class="text-center"><span>Status</span></th>
                                    <th><span>Email</span></th>
                                    <th>Action</th>
                                    <!--<th><span>Created</span></th>-->
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($blockedUsers as $bu) {
                                    ?>
                                    <tr id="user_<?php echo $bu['requestto_id']; ?>">
                                        <td>
                                            <img src="<?php echo $bu['media_thumb']; ?>" alt="<?php echo $bu['user_name']; ?>" title="<?php echo $bu['user_name']; ?>" onerror='this.src="<?php echo base_url(); ?>assets/images/default_avatar.jpg"'>
                                            <span class="user-link"><?php echo $bu['user_name']; ?></span>
                                            <span class="user-subhead">Age : <?php echo $bu['age']; ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span class="label label-danger">Blocked</span>
                                        </td>
                                        <td>
                                            <a href="mailto:<?php echo $bu['email']; ?>"><?php echo $bu['email']; ?></a>
                                        </td>
                                        <td>
                                            <a title="Unblock" class="btn btn-success" onclick="unblockUser(<?php echo $bu['requestto_id']; ?>);"><i class="fa fa-unlock"></i></a>
                                        </td>
                                        <!--<td>2013/08/12</td>-->
                                    </tr>
                                    <?php
                                }
                                ?>
                            </tbody>
                        </table>
                    </div>
                    <?php
                    echo $pagination;
                } else {
                    echo '<p class="alert alert-info">No blocked user available!</p>';
                }
                ?>
            </div>
        </div>
    </div>
</div>
<script type='text/javascript'>
    function unblockUser(user_id) {
        if (confirm("Are you sure you want to unblock this user?"))
        {
            $.ajax({
                url: "<?php echo base_url(); ?>user/unblockUser",
                type: 'POST',
                dataType: 'json',
                data: "user_id=" + user_id,
                success: function (data) {
                    if (data.success == true) {
                        $("#user_" + user_id).fadeOut(function () {
                            $(this).remove();
                            showMsg("User unblocked successfully.", "alert alert-success", true);
                            scrollToElement("#msg_txt");
                        });
                    } else {
                        showMsg("Something went wrong!", "alert alert-danger", true);
                        scrollToElement("#msg_txt");
                    }
                }, error: function () {
                    showMsg("Something went wrong!", "alert alert-danger", true);
                    scrollToElement("#msg_txt");
                }
            });
        }
    }
</script>