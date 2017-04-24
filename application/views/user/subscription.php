<div class="my-account">
    <?php
    $this->load->view('side_bar_account');
    $user_data = $this->session->userdata('user');
    $username = (!empty($user_data['user_name'])) ? ucfirst($user_data['user_name']) : $user_data['instagram_username'];
    if (!empty($user_settings['main_receipe_token']))
        $order_details = json_decode($user_settings['main_receipe_token'], true);
    ?>
    <div class="col-md-8 col-sm-8 col-xs-12 account-r">
        <div class="account-r-head">
            <h2>
                <big><?php echo ucfirst($username); ?></big>
            </h2>
        </div>
        <div class="account-r-body">
            <div class="account-body-head">
                <h2 class="account-title">Subscription Details</h2>
                <p>&nbsp;</p>
            </div>	
            <div class="account-body-body">
                <div class="table-wrapper content-list">
                    <?php if ($is_user_premium_member == 1) { ?>
                        <table class="table">
                            <tbody>
                                <tr>
                                    <td>Transaction ID : </td>
                                    <td><?php echo (!empty($order_details)) ? $order_details['id'] : " - "; ?></td>
                                </tr>
                                <tr>
                                    <td>Paid Amount : </td>
                                    <td><?php echo (!empty($order_details)) ? "$" . number_format($order_details['amount'] / 100, 2) : " - "; ?></td>
                                </tr>
                                <tr>
                                    <td>Expiry Date : </td>
                                    <td><?php echo (!empty($user_settings)) ? date("Y-m-d H:i:s", strtotime($user_settings['premium_expiry_date'])) : " - "; ?></td>
                                </tr>
                                <tr>
                                    <td>Status : </td>
                                    <td><?php echo ($is_user_premium_member == 1) ? "<label class='label label-success'>Active</label>" : "<label class='label label-danger'>Expired</label>"; ?></td>
                                </tr>
                            </tbody>
                        </table>
                        <?php ?>
                    <?php } else { ?>
                        <p class="alert alert-danger">There is no subscription tied to this account! <a href="<?php echo base_url('home/#packages'); ?>">Click here</a> to buy.</p>
                    <?php } ?>
                </div>
            </div>
        </div>
    </div>
</div>