<script type="text/javascript">
    $(document).ready(function () {
        $("#gallery-form").validate({
            rules: {
                "imagetitle": {
                    "required": true,
                    "maxlength": 255
                }
            },
            messages: {
                "imagetitle": {
                    required: "<?php echo lang('imagetitle_required_error'); ?>",
                    maxlength: "<?php echo lang('imagetitle_maxlength_error'); ?>"
                }
            },
            errorPlacement: function (error, element) {
                if (element.attr("name") === "regionid") {
                    error.appendTo('#region-error');
                } else {
                    error.insertAfter(element);
                }
            }
        });
    });

</script>
<div class="container-fluid">
    <div class="row">
        <div class="col-xs-12 col-md-8 col-md-offset-2">
            <div class="box box-info">
                <div class="box-header with-border margin-bottom">
                    <h3 class="box-title"><?php echo empty($form_caption) ? "" : $form_caption; ?></h3>
                    <div class="pull-right">
                        <a class="btn bg-blue btn-sm btn-flat" href="<?php echo $back_link; ?>">
                            <i class="fa fa-arrow-left"> <?php echo lang('back'); ?></i>
                        </a>
                    </div>
                </div>
                <?php
                    $errors = validation_errors();
                    if (!empty($errors))
                    {
                        ?>  
                        <div class="alert alert-danger col-md-8 col-md-offset-2">
                            <p><?php echo $errors; ?></p>
                        </div>
                        <?php
                    }
                ?>

                <?php
                    $attributes = array('id' => 'gallery-form', 'class' => 'form-horizontal', 'method' => 'post');
                    echo form_open_multipart($form_action, $attributes);
                ?>
                <input type="hidden" name="imageid" id="imageid" value="<?php echo empty($imageid) ? NULL : $imageid; ?>" />

                <div class="box-body">
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="imagetitle"><?php echo lang('imagetitle'); ?></label>
                        <div class="col-md-6">
                            <?php
                                $data = array(
                                    'name' => 'imagetitle',
                                    'id' => 'imagetitle',
                                    'value' => set_value('imagetitle', empty($imagetitle) ? "" : $imagetitle, FALSE),
                                    'class' => 'form-control',
                                    'autofocus' => 'autofocus'
                                );

                                echo form_input($data);
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="image"><?php echo lang('image'); ?></label>
                        <div class="col-md-6">
                            <?php
                                $data = array(
                                    'name' => 'image',
                                    'id' => 'image',
                                    'class' => 'form-control',
                                );
                                echo form_upload($data);
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="thumbimage"><?php echo lang('thumbimage'); ?></label>
                        <div class="col-md-6">
                            <?php
                                $data = array(
                                    'name' => 'thumbimage',
                                    'id' => 'thumbimage',
                                    'class' => 'form-control',
                                );
                                echo form_upload($data);
                            ?>
                        </div>
                    </div>

                      <div class="box-footer">
                        <div class="form-group">
                            <label class="col-lg-3 control-label">&nbsp;</label>
                            <div class="col-lg-9">
                                <?php
                                    echo form_submit('submit', 'Save', ' class="btn bg-blue btn-flat"');
                                ?>
                                <?php
                                    echo form_reset('reset', 'Reset', ' class="btn bg-blue btn-flat"');
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    echo form_close();
                ?>
            </div>

        </div>
    </div>
</div>