<div class="col-sm-12">
    <?php
    echo validation_errors('<div class="text-danger">', '</div>');
    echo $this->session->flashdata('error');
    ?>
    <h2>My Luvr Profile</h2>
    <?php
    $hidden = array('id' => $userData['id']);
    echo form_open('home/setup_userprofile', '', $hidden);
    ?>
    <div class="form-group">
        <?php
        echo form_label('Username:', 'username');
        echo form_input(array("name" => "username", "class" => "form-control", 'value' => set_value('username')));
        ?>
    </div>
    <div class="form-group">
        <?php
        echo form_label('Email:', 'email');
        echo form_input(array("name" => "email", "type" => "text", "class" => "form-control", 'value' => set_value('email')));
        ?>
    </div>
    <div class="form-group">
        <?php
        echo form_label('Gender:', 'gender');
        ?>
        <label class="radio-inline">
            <?php echo form_radio(array("name" => 'gender', 'value' => 'male', 'checked' => set_radio('gender', 'male'))); ?> Male
        </label>
        <label class="radio-inline">
            <?php echo form_radio(array("name" => 'gender', 'value' => 'female', 'checked' => set_radio('gender', 'female'))); ?> Female
        </label>
    </div>
    <div class="form-group">
        <?php
        echo form_label('Age:', 'age');
        echo form_input(array("name" => "age", "class" => "form-control", "type" => "number", "min" => "1", 'value' => set_value('age')));
        ?>
    </div>
    <div class="form-group">
        <?php
        echo form_label('One Liner:', 'one_liner');
        echo form_input(array("name" => "one_liner", "class" => "form-control", 'value' => set_value('one_liner')));
        ?>
    </div>
    <div class="form-group">
        <?php
        echo form_label('Job|Work:', 'job');
        echo form_input(array("name" => "work", "class" => "form-control", 'value' => set_value('work')));
        ?>
    </div>
    <div class="form-group">
        <?php
        echo form_label('Education:', 'school');
        echo form_input(array("name" => "school", "class" => "form-control", 'value' => set_value('school')));
        ?>
    </div>
    <div class="form-group">
        <?php
        echo form_label('Location:', 'address');
        echo form_input(array("name" => "address", "class" => "form-control", 'value' => set_value('address')));
        ?>
    </div>
    <div class="form-group">
        <?php
        echo form_label('About Me:', 'bio');
        echo form_textarea(array("name" => "bio", "class" => "form-control", 'value' => set_value('bio')));
        ?>
    </div>
    <?php
    echo form_submit(array("name" => "btn_submit", "value" => "Save", "class" => "btn btn-primary"));
    echo form_reset(array("value" => "Reset", "class" => "btn btn-primary"));
    echo form_close();
    ?>
</div>