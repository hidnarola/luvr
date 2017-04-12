<link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/font-awesome/css/font-awesome.min.css"/>
<h2>Video Requests</h2>
<hr>
<div class="container bootstrap snippet">
    <div class="row">
        <div class="main-box no-header clearfix">
            <div class="main-box-body clearfix">
                <?php if (!empty($videoRequests)) { ?>
                    <div class="table-responsive">
                        <table class="table user-list">
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
                                            <img src="<?php echo $vr['media_thumb']; ?>" alt="<?php echo $vr['user_name']; ?>" title="<?php echo $vr['user_name']; ?>" onerror='this.src="<?php echo base_url(); ?>assets/images/default_avatar.jpg"'>
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