<div class="alert-danger" id="step_error" style="display:none;"></div>
<h2>My Luvr Preferences</h2>
<hr>
<?php if (!empty($filtersData)) { ?>
    <label id="lbl_filter_name"><?php echo $filtersData[0]['filter_name']; ?></label>
    <form id="updatefiltersform">
        <table class="table-responsive table-condensed">
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
                echo '<tr><td><label for="chk_' . $fdata['sub_filter_id'] . '">' . $fdata['sub_filter_name'] . '</label></td><td><label class="switch"><input type="checkbox" name="sub_filters[]" value="' . $fdata['sub_filter_id'] . '" ' . $is_checked . ' ' . $i_dont_care . '/><div class="slider round"></div></label></td></tr>';
            }
            ?>
        </table>
    </form>
<?php } ?>
<input type="button" class="btn btn-primary" onclick="saveFilter(1);" data-step="1" data-total-steps="<?php echo $totalFilters; ?>" id="save_step_btn" value="Next"/>
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
                        $("#updatefiltersform tbody").html(data.next_filter_html);
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