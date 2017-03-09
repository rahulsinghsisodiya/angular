<script type="text/javascript">
    $(document).ready(function () {
        $("#sortable").sortable({
            update: function () {
                var order = $('#sortable').sortable('toArray');
                $("#sortable_msg").hide();
                $.ajax({
                    type: "POST",
                    async: true,
                    url: "<?php echo base_url() . "gallery/setordersave/" ?>",
                    data: {"item": order},
                    beforeSend: function () {
                        $("#sortable_msg").show();
                        // setting a timeout
                        $("#showmsg").html("Order are updating.");
                    },
                    dataType: 'json'
                }).done(function (data) {
                    $("#sortable_msg").show();
                    if (data.status == "failure")
                    {
                        $("#showmsg").html("Error occured");
                    } else
                    {
                        $("#showmsg").html("Order updated successfully.");
                    }
                });
            }
        });
    });
</script>
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
                <h3 class="box-title"><?php echo $this->lang->line('setorder'); ?></h3>
                <?php echo anchor("admin/gallery/listgallery", "Back", ' class="btn btn-primary pull-right  btn-flat" '); ?>
                <?php echo anchor("admin/gallery/setordersave", "Save", ' class="btn btn-default pull-right  btn-flat" '); ?>
            </div>
            <div class="box-body">
                <div class="col-lg-12">
                    <div id="sortable_msg" style="display:none;">
                        <div class="alert alert-success alert-bold-border fade in alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <span id="showmsg"></span>
                        </div>

                    </div>

                </div>
                <div class="clearfix">&nbsp;</div>
                <div class="col-lg-12">
                    <ul id="sortable" >
                        <?php
                            if (!empty($arr_gallery))
                            {
                                foreach ($arr_gallery as $id => $value)
                                {
                                    ?>
                                    <li id="orderid_<?php echo $id; ?>" class="ui-state-default">
                                        <span class="icon fa fa-arrows"></span>
                                        <?php echo $value; ?> 
                                    </li>
                                    <?php
                                }
                            }
                        ?>
                    </ul>	
                </div>     
            </div><!-- /.the-box .default -->
            <!-- END DATA TABLE -->
        </div>
    </div>
</div>