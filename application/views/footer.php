</div>
</div>
<br/>
<br/>
<br/>
<br/>
<br/>
<footer class="container-fluid text-center">
    <p>Footer Text</p>
</footer>
<script type="text/javascript">
<?php if ($sub_view == "userFilterSettings") { ?>
        function saveFilter(filter_id) {
            $.ajax({
                url: "<?php echo base_url(); ?>home/savestep",
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
                            location.href = '<?php echo base_url(); ?>match';
                        }
                    } else {
                        alert("Something went wrong!");
                    }
                }, error: function () {
                    alert("Something went wrong!");
                }
            });
        }
        function ignoreOther() {
            if ($("#idontcare").is(":checked")) {
                $("#updatefiltersform .subfilters").prop("checked", false);
            }
        }
        function ignoreLast() {
            $("#updatefiltersform #idontcare").prop("checked", false);
        }
<?php } ?>
    function log(text) {
        console.log(text);
    }
</script>
</body>