<script type="text/javascript">
    $(document).ready(function() {
        $("#gallery-form").validate({
            rules: {
                "slidetitle": {
                    "required": true,
                    "maxlength": 255
                }
            },
            messages: {
                "slidetitle": {
                    required: "<?php echo lang('slidetitle_required_error'); ?>",
                    maxlength: "<?php echo lang('slidetitle_maxlength_error'); ?>"
                }
            },
            errorPlacement: function(error, element) {
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
                    if (!empty($errors)||!empty($errors_msg))
                    {
                        ?>  
                        <div class="alert alert-danger col-md-8 col-md-offset-2">
                            <p><?php  echo empty($errors)?$errors_msg['error']:$errors; ?></p>
                        </div>
                        <?php
                    }
                ?>

                <?php
                    $attributes = array('id' => 'slider-form', 'class' => 'form-horizontal', 'method' => 'post');
                    echo form_open_multipart($form_action, $attributes);
                ?>
                <input type="hidden" name="slideid" id="slideid" value="<?php echo empty($slideid) ? NULL : $slideid; ?>" />

                <div class="form-group">
                        <label class="col-lg-3 control-label" for="pagename"><?php echo lang('select_page'); ?></label>
                        <div class="col-lg-8">
                            <?php
                                echo form_dropdown('pagename', $arr_pages, empty($pagename) ? NULL : $pagename, ' id = "pagename", autofocus= "autofocus", class="form-control" ');
                            ?>
                            <div id="pagename-error"></div>
                        </div>
                    </div>
                
                <div class="box-body">
                    <div class="form-group">
                        <label class="col-lg-3 control-label" ><?php echo lang('slidetitle'); ?></label>
                        <div class="col-lg-8">
                            
                            <?php
                                $data = array(
                                    'name' => 'slidetitle',
                                    'id' => 'slidetitle',
                                    'value' => set_value('slidetitle', empty($slidetitle) ? "" : $slidetitle, FALSE),
                                    'class' => 'form-control',
                                   // 'autofocus' => 'autofocus'
                                );

                                echo form_input($data);
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-lg-3 control-label" for="choose file"><?php echo lang('choose file'); ?></label>
                        <div class="col-lg-8">
                            
                            <?php
                                $data = array(
                                    'name' => 'slide',
                                    'id' => 'slide',
                                    'class' => 'form-control',
                                    'multiple' => "multiple",
                  
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