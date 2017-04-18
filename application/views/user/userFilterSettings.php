<div class="alert-danger" id="step_error" style="display:none;"></div>
<?php
$this->load->view('side_bar_account');
$user_data = $this->session->userdata('user');
$username = (!empty($user_data['user_name'])) ? $user_data['user_name'] : $user_data['instagram_username'];
?>
<div class="col-md-8 col-sm-8 col-xs-12 account-r">
    <div class="account-r-head">
        <h2><big><?php echo $username; ?></big></h2>
    </div>
    <div class="account-r-body">
        <div class="account-body-head">
            <h2 class="account-title">My Luvr Preferences</h2>
            <p id="lbl_filter_name"><?php echo $filtersData[0]['filter_name']; ?></p>
        </div>
        <?php if (!empty($filtersData)) { ?>
            <div class="account-body-body preferences">
                <form id="updatefiltersform">
                    <ul class="preferences-ul">
                        <?php
                        $users_filters = array();
                        if (!empty($userFilters)) {
                            foreach ($userFilters as $uf) {
                                if ($uf['is_filter_on'] == 1) {
                                    $users_filters[] = $uf['sub_filter_id'];
                                }
                            }
                        }
                        foreach ($filtersData as $fdata) {
                            $is_checked = (in_array($fdata['sub_filter_id'], $users_filters)) ? "checked" : "";
                            $i_dont_care = (strtolower($fdata['sub_filter_name']) == strtolower('I don\'t care')) ? "onchange='ignoreOther()' id='idontcare' class='subfilters_ignoreme'" : "onchange='ignoreLast()' id='chk_" . $fdata['sub_filter_id'] . "' class='subfilters'";
                            $id = "chk_" . $fdata['sub_filter_id'] . "";
                            if (strtolower($fdata['sub_filter_name']) == strtolower('I don\'t care'))
                                $id = "idontcare";
                            echo '<li><span><label for="' . $id . '">' . $fdata['sub_filter_name'] . '</label></span><label class="switch"><input type="checkbox" name="sub_filters[]" value="' . $fdata['sub_filter_id'] . '" ' . $is_checked . ' ' . $i_dont_care . '/><div class="slider round"></div></label></li>';
                        }
                        ?>
                    </ul>
                    <div class="col-md-12 col-sm-12 col-xs-12 text-center mar-btm-20">
                        <button type="button" onclick="saveFilter(1);" data-step="1" data-total-steps="<?php echo $totalFilters; ?>" id="save_step_btn" class="color-btn">Next</button>
                    </div>
                </form>
            </div>
        <?php } ?>
    </div>
</div>
<script type="text/javascript">
    function saveFilter(filter_id) {
        if ($('#updatefiltersform input[type="checkbox"]:checked').length > 0)
        {
            $.ajax({
                url: "<?php echo base_url(); ?>user/savestep",
                type: 'POST',
                dataType: 'json',
                data: "filter_id=" + filter_id + "&" + $('#updatefiltersform').serialize(),
                success: function (data) {
                    if (data.success == true) {
                        $("#save_step_btn").attr("onclick", "saveFilter(" + data.next_filter_id + ")");
                        $("#save_step_btn").attr("data-step", parseInt($("#save_step_btn").attr("data-step")) + 1);
                        if (data.next_filter_name)
                            $("#lbl_filter_name").text(data.next_filter_name);
                        $("#updatefiltersform .preferences-ul").html(data.next_filter_html);
                        if ($("#save_step_btn").attr("data-step") > $("#save_step_btn").attr("data-total-steps")) {
                            location.href = '<?php echo base_url() . $redirect; ?>';
                        }
                    } else {
                        showMsg("Something went wrong!", "alert alert-danger", true);
                        scrollToElement("#msg_txt");
                    }
                }, error: function () {
                    showMsg("Something went wrong!", "alert alert-danger", true);
                    scrollToElement("#msg_txt");
                }
            });
        } else {
            showMsg("Please select atleast one of the below choices!", "alert alert-danger", true);
            scrollToElement("#msg_txt");
        }
    }
    function ignoreOther() {
        if ($("#idontcare").is(":checked")) {
            $("#updatefiltersform .subfilters").prop("checked", false);
        }
    }
    function ignoreLast() {
        $("#updatefiltersform #idontcare").prop("checked", false);
    }
</script>