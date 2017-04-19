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
        <div class="account-r-head">
            <h2>
                <big><?php echo ucfirst($db_user_data['user_name']); ?></big>
            </h2>
        </div>
        <div class="account-r-body">
            <div class="account-body-head">
                <h2 class="account-title">user Details</h2>
                <p>&nbsp;</p>
            </div>	
            <div class="account-body-body">
                <div class="table-wrapper content-list">
                    <table class="table">
                        <tbody>
                            <tr>
                                <td>Username : </td>
                                <td><?php echo $db_user_data['user_name']; ?></td>
                            </tr>
                            <tr>
                                <td>Instagram Username : </td>
                                <td><?php echo $db_user_data['instagram_username']; ?></td>
                            </tr>
                            <tr>
                                <td>Email : </td>
                                <td><a href="mailto:<?php echo $db_user_data['email']; ?>"><?php echo $db_user_data['email']; ?></a></td>
                            </tr>
                            <tr>
                                <td>Bio : </td>
                                <td><?php echo $db_user_data['bio']; ?></td>
                            </tr>
                            <tr>
                                <td>Age : </td>
                                <td><?php echo $db_user_data['age']; ?></td>
                            </tr>
                            <tr>
                                <td>Address : </td>
                                <td><?php echo $db_user_data['address']; ?></td>
                            </tr>
                            <tr>
                                <td>One Liner : </td>
                                <td><?php echo $db_user_data['one_liner']; ?></td>
                            </tr>
                            <tr>
                                <td>Gender : </td>
                                <td><?php echo $db_user_data['gender']; ?></td>
                            </tr>
                            <tr>
                                <td>Work : </td>
                                <td><?php echo $db_user_data['work']; ?></td>
                            </tr>
                            <tr>
                                <td>School : </td>
                                <td><?php echo $db_user_data['school']; ?></td>
                            </tr>
                        </tbody>
                    </table>
                </div>
                <div class="col-md-12 col-sm-12 col-xs-12 text-center mar-btm-20">
                    <a class="color-btn" href="<?php echo base_url() . 'user/setup_userprofile/edit'; ?>">Edit Profile</a>
                </div>
            </div>
        </div>
    </div>
</div>