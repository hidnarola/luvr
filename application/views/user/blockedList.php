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
                                    <th>&nbsp;</th>
                                    <!--<th><span>Created</span></th>-->
                                </tr>
                            </thead>
                            <tbody>
                                <?php
                                foreach ($blockedUsers as $bu) {
                                    ?>
                                    <tr>
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
                                            <a href="#" class="table-link">
                                                <span class="fa-stack" title="Unblock">
                                                    <i class="fa fa-square fa-stack-2x"></i>
                                                    <i class="fa fa-unlock fa-stack-1x fa-inverse"></i>
                                                </span>
                                            </a>
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
                    echo '<p class="alert alert-info">Nothing to show!</p>';
                }
                ?>
            </div>
        </div>
    </div>
</div>