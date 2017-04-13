<div class="container">
    <div class="row">
        
        <div class="col-xs-12 col-sm-12 col-md-6 col-lg-6 col-xs-offset-0 col-sm-offset-0 col-md-offset-3 col-lg-offset-3 toppad">
            <div class="panel panel-info">
                <div class="panel-heading">
                    <h3 class="panel-title"><?php echo $db_user_data['user_name']; ?></h3>
                </div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-3 col-lg-3 " align="center">
                            <img alt="User Pic" src="<?php echo $user_profile['media_name']; ?>" class="img-circle img-responsive">
                        </div>
                        <div class=" col-md-9 col-lg-9 ">
                            <table class="table table-user-information">
                                <tbody>
                                    <tr>
                                        <td>Username:</td>
                                        <td><?php echo $db_user_data['user_name']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Instagram Username:</td>
                                        <td><?php echo $db_user_data['instagram_username']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Email</td>
                                        <td><?php echo $db_user_data['email']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Bio</td>
                                        <td><?php echo $db_user_data['bio']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Address</td>
                                        <td><?php echo $db_user_data['address']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Age</td>
                                        <td><?php echo $db_user_data['age']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Gender</td>
                                        <td><?php echo $db_user_data['gender']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>Work</td>
                                        <td><?php echo $db_user_data['work']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>School</td>
                                        <td><?php echo $db_user_data['school']; ?></td>
                                    </tr>
                                    <tr>
                                        <td>One Liner</td>
                                        <td><?php echo $db_user_data['one_liner']; ?></td>
                                    </tr>

                                </tbody>
                            </table>
                            
                            
                            <a data-toggle="tooltip" type="button" class="btn btn-sm btn-warning">
                                <i class="glyphicon glyphicon-edit"></i>
                                 Edit Profile
                            </a>
                        </div>
                    </div>                    
                </div>                
            </div>
        </div>
    </div>
</div>
