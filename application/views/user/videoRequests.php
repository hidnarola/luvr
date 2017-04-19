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
        font-size: 0.8em;
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
<div class="my-account">
    <?php
    $message = $this->session->flashdata('message');
    if (!empty($message)) {
        echo '<div class="' . $message['class'] . '">' . $message['message'] . '</div>';
    }
    $this->load->view('side_bar_account');
    $user_data = $this->session->userdata('user');
    $username = (!empty($user_data['user_name'])) ? ucfirst($user_data['user_name']) : $user_data['instagram_username'];
    ?>
    <div class="col-md-8 col-sm-8 col-xs-12 account-r">
        <div class="account-r-head"><h2><big><?php echo $username; ?></big></h2></div>
        <div class="account-r-body">
            <div class="account-body-head">
                <h2 class="account-title">Video Requests</h2>
                <p>&nbsp;</p>
            </div>
            <div class="main-box no-header clearfix">
                <div class="main-box-body clearfix">
                    <?php if (!empty($videoRequests)) { ?>
                        <div class="table-responsive">
                            <table class="table usr-list">
                                <thead>
                                    <tr>
                                        <th><span>User</span></th>
                                        <th class="text-center"><span>Status</span></th>
                                        <th><span>Email</span></th>
                                        <th><span>Request Date</span></th>
                                        <th><span>Action</span></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    foreach ($videoRequests as $vr) {
                                        ?>
                                        <tr id="request_<?php echo $vr['vrid']; ?>">
                                            <td>
                                                <img class="pro_pic" src="<?php echo $vr['media_thumb']; ?>" alt="<?php echo $vr['user_name']; ?>" title="<?php echo $vr['user_name']; ?>" onerror='this.src="<?php echo base_url(); ?>assets/images/default_avatar.jpg"'>
                                                <span class="user-link"><?php echo $vr['user_name']; ?></span>
                                                <span class="user-subhead">Age : <?php echo $vr['age']; ?></span>
                                            </td>
                                            <td class="text-center" id="status_txt">
                                                <?php
                                                if ($vr['status'] == 1)
                                                    echo '<span class="label label-success">New Request';
                                                else if ($vr['status'] == 2)
                                                    echo '<span class="label label-success">Request Approved</span>';
                                                else
                                                    echo '<span class="label label-danger">Request Rejected</span>';
                                                ?>
                                            </td>
                                            <td>
                                                <a href="mailto:<?php echo $vr['email']; ?>"><?php echo $vr['email']; ?></a>
                                            </td>
                                            <td><?php echo date("Y-m-d H:i:s", strtotime($vr['vs_created_date'])); ?></td>
                                            <td>
                                                <a class="btn btn-success" title="Approve" onclick="manageVideoRequest(<?php echo $vr['vrid']; ?>, 2);"><i class="fa fa-check"></i></a>
                                                <a class="btn btn-danger" title="Reject" onclick="manageVideoRequest(<?php echo $vr['vrid']; ?>, 0);"><i class="fa fa-ban"></i></a>
                                            </td>
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
                        echo '<p class="alert alert-info">No video requests available!</p>';
                    }
                    ?>
                </div>
            </div>
        </div>
    </div>
</div>
<script type='text/javascript'>
    function manageVideoRequest(request_id, mode) {
        var mode_txt = (mode == 2) ? "approve" : "reject";
        var mode_txted = (mode == 2) ? "approved" : "rejected";
        if (confirm("Are you sure you want to " + mode_txt + " this request?"))
        {
            $.ajax({
                url: "<?php echo base_url(); ?>user/manageVideoRequest",
                type: 'POST',
                dataType: 'json',
                data: "mode=" + mode + "&request_id=" + request_id,
                success: function (data) {
                    if (data.success == true) {
                        showMsg("Request " + mode_txted + " successfully.", "alert alert-success", true);
                        $("#request_" + request_id + " #status_txt span").attr("class", (mode == 2) ? "label label-success" : "label label-danger");
                        $("#request_" + request_id + " #status_txt span").html((mode == 2) ? "Request Approved" : "Request Rejected");
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