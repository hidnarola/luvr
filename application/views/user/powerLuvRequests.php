<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/font-awesome/css/font-awesome.min.css"/>
<style type="text/css">
    .main-box.no-header {
        padding-top: 20px;
    }
    .main-box {
        margin-bottom: 16px;
        -webikt-border-radius: 3px;
        -moz-border-radius: 3px;
        border-radius: 3px;
    }
    .table a.table-link.danger {
        color: #e74c3c;
    }
    .label {
        border-radius: 3px;
        font-size: 0.875em;
        font-weight: 600;
    }
    .usr-list tbody td .user-subhead {
        font-size: 0.8em;
    }
    .usr-list tbody td .user-link {
        display: block;
        font-size: 1em;
        padding-top: 3px;
        margin-left: 60px;
    }
    a {
        color: #3498db;
        outline: none!important;
    }
    .usr-list tbody td>img {
        position: relative;
        max-width: 50px;
        float: left;
        margin-right: 15px;
    }

    .table thead tr th {
        text-transform: uppercase;
        font-size: 0.875em;
    }
    .table thead tr th {
        border-bottom: 2px solid #e7ebee;
    }
    .table tbody tr td:first-child {
        font-size: 1.125em;
        font-weight: 300;
    }
    .table tbody tr td {
        font-size: 0.875em;
        vertical-align: middle !important;
        border-top: 1px solid #e7ebee;
        padding: 12px 8px;
    }
</style>
<h2>Powerluv Requests</h2>
<hr>
<div class="container bootstrap snippet">
    <div class="row">
        <div class="main-box no-header clearfix">
            <div class="main-box-body clearfix">
                <?php if (!empty($powerLuvRequests)) { ?>
                    <div class="table-responsive">
                        <table class="table usr-list">
                            <thead>
                                <tr>
                                    <th><span>User</span></th>
                                    <th class="text-center"><span>Status</span></th>
                                    <th><span>Email</span></th>
                                    <th>Action</th>
                                    <th><span>Request Date</span></th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($powerLuvRequests as $plr) {
                                    ?>
                                    <tr id="user_<?php echo $plr['requestto_id']; ?>">
                                        <td>
                                            <img class="pro_pic" src="<?php echo $plr['media_thumb']; ?>" alt="<?php echo $plr['user_name']; ?>" title="<?php echo $plr['user_name']; ?>" onerror='this.src="<?php echo base_url(); ?>assets/images/default_avatar.jpg"'>
                                            <span class="user-link"><?php echo $plr['user_name']; ?></span>
                                            <span class="user-subhead">Age : <?php echo $plr['age']; ?></span>
                                        </td>
                                        <td class="text-center">
                                            <span class="label label-danger">Blocked</span>
                                        </td>
                                        <td>
                                            <a href="mailto:<?php echo $plr['email']; ?>"><?php echo $plr['email']; ?></a>
                                        </td>
                                        <td>
                                            <a title="Unblock" class="btn btn-success" onclick="unblockUser(<?php echo $plr['requestto_id']; ?>);"><i class="fa fa-unlock"></i></a>
                                        </td>
                                        <td><?php echo date("Y-m-d H:i:s", strtotime($plr['request_date'])); ?></td>
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