<?php
    //pr($this->session->userdata('user'));
?>
<link href="<?php echo base_url(); ?>assets/css/bootstrap-select.min.css" rel="stylesheet" />

<div class="my-account">

    <?php $this->load->view('side_bar_account'); ?>
    
    <div class="col-md-8 col-sm-8 col-xs-12 account-r">
        <div class="account-r-head">
            <h2>
                <big>
                    <?php 
                        $uname = $this->session->userdata('user')['user_name']; 
                        echo ucfirst($uname);
                    ?>
                </big>
            </h2>
        </div>
        <div class="account-r-body">
            <div class="account-body-head">
                <h2 class="account-title">My Profile</h2>
                <p> &nbsp; </p>
            </div> 
            
            <?php
                $all_errors = validation_errors();

                if ($all_errors != '') {
                    echo '<div class="alert alert-danger">';
                    echo $all_errors;
                    echo '</div>';
                }

                if ($this->session->flashdata('error')){
                    echo '<div class="alert alert-danger">' . $this->session->flashdata('error') . '</div>';
                }

                if ($this->session->flashdata('success')){
                    echo '<div class="alert alert-success">' . $this->session->flashdata('success') . '</div>';
                }
            ?>

            <div class="account-body-body my-profile">
                    <?php
                        $hidden = array('id' => $userData['id']);
                        if ($mode && $mode == "edit"){                            
                            echo form_open('user/setup_userprofile/' . $mode, '', $hidden);
                        } else {                            
                            echo form_open('user/setup_userprofile', '', $hidden);
                        }
                    ?>
                    <div class="col-md-6 col-sm-6 col-xs-12 mar-btm-20">
                        <div class="input-wrapper">
                            <?php
                                $uname = $userData['user_name'];
                                $post_uname = ($_POST) ? set_value('username') : $uname;
                                echo form_label('Username:', 'username');
                                echo form_input(array("name" => "username", 'placeholder'=>"User name",'value' => $post_uname));
                            ?>                            
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 mar-btm-20">
                        <div class="input-wrapper">
                            <?php
                                $email = $userData['email'];
                                $post_email = ($_POST) ? set_value('email') : $email;
                                echo form_label('Email:', 'email');
                                echo form_input(array("name" => "email", "type" => "text", "placeholder" => "Email", 'value' => $post_email));
                            ?>
                        </div>
                    </div>                   
                    <div class="col-md-6 col-sm-6 col-xs-12 mar-btm-20">
                        <div class="input-wrapper">
                            <?php echo form_label('Gender:', 'gender'); ?>
                            <select class="selectpicker" name="gender">                                
                                <option value="male" <?php if($userData['gender']=='male'){ echo 'selected'; } ?>>Male</option>
                                <option value="female" <?php if($userData['gender']=='female'){ echo 'selected'; } ?>>Female</option>
                            </select>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 mar-btm-20">
                        <div class="input-wrapper">
                            <?php
                                $age = $userData['age'];
                                $post_age = ($_POST) ? set_value('age') : $age;
                                echo form_label('Age:', 'age');
                                echo form_input(array("name" => "age", "placeholder" => "age", "type" => "text", 'value' => $post_age));
                            ?>
                        </div>
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12 mar-btm-20">
                        <div class="input-wrapper">
                            <?php
                                $one_liner = $userData['one_liner'];
                                $post_one_liner = ($_POST) ? set_value('one_liner') : $one_liner;
                                echo form_label('One Liner:', 'one_liner');
                                echo form_input(array("name" => "one_liner", "placeholder" => "One Liner", 'value' => $post_one_liner));
                            ?>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-sm-6 col-xs-12 mar-btm-20">
                        <div class="input-wrapper">
                            <?php
                                $work = $userData['work'];
                                $post_work = ($_POST) ? set_value('work') : $work;
                                echo form_label('Job|Work:', 'job');
                                echo form_input(array("name" => "work", "placeholder" => "Work", 'value' => $post_work));
                            ?>
                        </div>
                    </div>

                    <div class="col-md-6 col-sm-6 col-xs-12 mar-btm-20">
                        <div class="input-wrapper">
                            <?php
                                $school = $userData['school'];
                                $post_school = ($_POST) ? set_value('school') : $school;
                                echo form_label('Education:', 'school');
                                echo form_input(array("name" => "school", "placeholder" => "School", 'value' => $post_school));
                            ?>
                        </div>
                    </div>
                    
                    <div class="col-md-6 col-sm-6 col-xs-12 mar-btm-20">
                        <div class="input-wrapper">
                            <?php
                                $address = $userData['address'];
                                $post_address = ($_POST) ? set_value('address') : $address;
                                echo form_label('Location:', 'address');
                                echo form_input(array("name" => "address", "id" => "address", "placeholder" => "Address", 'value' => $post_address));
                            ?>
                        </div>
                    </div>

                    <div class="col-md-12 col-sm-12 col-xs-12 mar-btm-20">
                        <div class="input-wrapper">
                            <?php
                                $bio = $userData['bio'];
                                $post_bio = ($_POST) ? set_value('bio') : $bio;
                                echo form_label('About Me:', 'bio');
                                echo form_textarea(array("name" => "bio", "palceholder" => "Bio", 'value' => $post_bio));
                            ?>
                        </div>
                    </div>
                    <div class="col-md-12 col-sm-12 col-xs-12 text-center mar-btm-20">
                        <?php
                            echo form_submit(array("name" => "btn_submit", "value" => "Save", "class" => "color-btn"));
                            echo form_reset(array("value" => "Reset", "class" => "dark-btn"));                            
                        ?>
                    </div>
                </form>
            </div>
        </div>
    </div>
    
</div>

<div id="map"></div>

<script src="<?php echo base_url(); ?>assets/js/bootstrap-select.min.js"></script>
<script type="text/javascript">
    function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {center: {lat: -33.8688, lng: 151.2195}, zoom: 13});
        var input = document.getElementById('address');
        var autocomplete = new google.maps.places.Autocomplete(input);

        // Bind the map's bounds (viewport) property to the autocomplete object,
        // so that the autocomplete requests use the current map bounds for the
        // bounds option in the request.
        autocomplete.bindTo('bounds', map);
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAP_API; ?>&libraries=places&callback=initMap" async defer></script>
