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
                <h2 class="account-title">Video Snap Requests</h2>
                <p>&nbsp;</p>
            </div>
            <div class="main-box no-header clearfix">
                <div class="main-box-body clearfix">
                    <div class="table-responsive" id="tbl_requests">
                        <table class="table usr-list">
                            <thead>
                                <tr>
                                    <th><span>User</span></th>
                                    <th class="text-center"><span>Status</span></th>
                                    <th><span>Action</span></th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                    <p class="alert alert-info" id="norequests" style="display:none;">No video requests available!</p>
                </div>
            </div>
        </div>
    </div>
</div>
<script type='text/javascript'>
    socket.on('GetVideosnapRequest', function (data) {
        console.log(data);
        if (data)
        {
            $("#norequests").hide();
            var html = '';
            for (var i = 0; i < data.VideoRequests.length; i++)
            {
                var obj = data.VideoRequests[i];                
                var new_img_url = my_img_url_js(obj['media_type'],obj['media_thumb']);
                
                if(obj['media_type'] == '1' || obj['media_type'] == '2'){
                    new_img_url = '<?php echo base_url(); ?>'+my_img_url_js(obj['media_type'],obj['media_thumb']);
                }

                html += '<tr id="request_' + obj.id + '">';
                html += '<td><img class="pro_pic" src="' + new_img_url + '" alt="' + obj.user_name + '" title="' + obj.user_name + '" ><span class="user-link">' + obj.user_name + '</span><span class="user-subhead">Age : ' + obj.age + '</span></td>';
                html += '<td class="text-center" id="status_txt"><span class="label label-success">New Request</span></td>';
                html += '<td><a class="btn btn-success" title="Approve" onclick="manageVideoRequest(' + obj.id + ', 2);"><i class="fa fa-check"></i></a><a class="btn btn-danger" title="Reject" onclick="manageVideoRequest(' + obj.id + ', 0);"><i class="fa fa-ban"></i></a></td>';
                html += '</tr>';
            }
            $("#tbl_requests tbody").html(html);
        } else
        {
            $("#tbl_requests").hide();
            $("#norequests").show();
        }
    });
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
                    console.log(data);
                    if (data.success == true) {
                        showMsg("Request " + mode_txted + " successfully.", "success", true);
                        $('#request_'+data['last_id']).fadeOut();
                    } else {
                        showMsg("Something went wrong!", "error", true);
                        scrollToElement("#header");
                    }
                }, error: function () {
                    showMsg("Something went wrong!", "error", true);
                    scrollToElement("#header");
                }
            });
        }
    }
</script>