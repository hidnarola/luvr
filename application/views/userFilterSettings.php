<div class="col-sm-12">
    <div class="alert-danger" id="step_error" style="display:none;"></div>
    <h2>My Luvr Preferences</h2>
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
                    $i_dont_care = (strtolower($fdata['sub_filter_name']) == strtolower('I don\'t care')) ? "onclick='ignoreOther()' id='idontcare' class='subfilters_ignoreme'" : "onclick='ignoreLast()' class='subfilters'";
                    echo '<tr><td>' . $fdata['sub_filter_name'] . '</td><td><label class="switch"><input type="checkbox" name="sub_filters[]" value="' . $fdata['sub_filter_id'] . '" ' . $is_checked . ' ' . $i_dont_care . '/><div class="slider round"></div></label></td></tr>';
                }
                ?>
            </table>
        </form>
    <?php } ?>
    <input type="button" class="btn btn-primary" onclick="saveFilter(1);" data-step="1" data-total-steps="<?php echo $totalFilters; ?>" id="save_step_btn" value="Next"/>
</div>