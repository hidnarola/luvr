<br/>

<div class="col-sm-12">
    <?php
        $all_errors = validation_errors();

        if($all_errors != ''){
            echo '<div class="alert alert-danger">';
            echo $all_errors;
            echo '</div>';
        }
        echo $this->session->flashdata('error');
    ?>
    <h2>My Luvr Profile</h2>
    
    <?php
        $hidden = array('id' => $userData['id']);
        echo form_open('home/setup_userprofile', '', $hidden);
    ?>

    <div class="form-group">
        <?php
            $uname = $userData['user_name'];
            $post_uname = ($_POST) ? set_value('username'): $uname;
            echo form_label('Username:', 'username');
            echo form_input(array("name" => "username", "class" => "form-control", 'value' => $post_uname));
        ?>
    </div>
    <div class="form-group">
        <?php
            $email = $userData['email'];
            $post_email = ($_POST) ? set_value('email'): $email;
            echo form_label('Email:', 'email');
            echo form_input(array("name" => "email", "type" => "text", "class" => "form-control", 'value' => $post_email));
        ?>
    </div>
    <div class="form-group">
        <?php
            echo form_label('Gender:', 'gender');
        ?>
        <label class="radio-inline">
            <?php echo form_radio(array("name" => 'gender', 'value' => 'male', 'checked' => set_radio('gender', 'male',true))); ?> Male
        </label>
        <label class="radio-inline">
            <?php echo form_radio(array("name" => 'gender', 'value' => 'female', 'checked' => set_radio('gender', 'female'))); ?> Female
        </label>
    </div>
    <div class="form-group">
        <?php
            $age = $userData['age'];
            $post_age = ($_POST) ? set_value('age'): $age;
            echo form_label('Age:', 'age');
            echo form_input(array("name" => "age", "class" => "form-control", "type" => "text", "min" => "1", 'value' => $post_age));
        ?>
    </div>
    <div class="form-group">
        <?php
            $one_liner = $userData['one_liner'];
            $post_one_liner = ($_POST) ? set_value('one_liner'): $one_liner;
            echo form_label('One Liner:', 'one_liner');
            echo form_input(array("name" => "one_liner", "class" => "form-control", 'value' => $post_one_liner));
        ?>
    </div>
    <div class="form-group">
        <?php
            $work = $userData['work'];
            $post_work = ($_POST) ? set_value('work'): $work;
            echo form_label('Job|Work:', 'job');
            echo form_input(array("name" => "work", "class" => "form-control", 'value' => $post_work));
        ?>
    </div>
    <div class="form-group">
        <?php
            $school = $userData['school'];
            $post_school = ($_POST) ? set_value('school'): $school;
            echo form_label('Education:', 'school');
            echo form_input(array("name" => "school", "class" => "form-control", 'value' => $post_school));
        ?>
    </div>
    <div class="form-group">
        <?php
            $address = $userData['address'];
            $post_address = ($_POST) ? set_value('address'): $address;
            echo form_label('Location:', 'address');
            echo form_input(array("name" => "address","id"=>"address", "class" => "form-control", 'value' => $post_address));
        ?>
    </div>
    <div class="form-group">
        <?php
            $bio = $userData['bio'];
            $post_bio = ($_POST) ? set_value('bio'): $bio;
            echo form_label('About Me:', 'bio');
            echo form_textarea(array("name" => "bio", "class" => "form-control", 'value' => $post_bio));
        ?>
    </div>
    <?php
        echo form_submit(array("name" => "btn_submit", "value" => "Save", "class" => "btn btn-primary"));
        echo form_reset(array("value" => "Reset", "class" => "btn btn-primary"));
        echo form_close();
    ?>
</div>

<div id="map"></div>
<script type="text/javascript">
    function initMap() {
        var map = new google.maps.Map(document.getElementById('map'), {center: {lat: -33.8688, lng: 151.2195},zoom: 13 });        
        var input = document.getElementById('address');
        var autocomplete = new google.maps.places.Autocomplete(input);
    
        // Bind the map's bounds (viewport) property to the autocomplete object,
        // so that the autocomplete requests use the current map bounds for the
        // bounds option in the request.
        autocomplete.bindTo('bounds', map);
    }
</script>
<script src="https://maps.googleapis.com/maps/api/js?key=<?php echo GOOGLE_MAP_API; ?>&libraries=places&callback=initMap" async defer></script>