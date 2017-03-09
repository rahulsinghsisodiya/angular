<script type="text/javascript">
    $(document).ready(function() {
        $("#user-form").validate({
            rules: {
                "name": {
                    "required": true,
                    "minlength": 3,
                    "maxlength": 100
                },
                "surname": {
                    "required": true,
                    "minlength": 3,
                    "maxlength": 100
                },
                "email": {
                    "required": true,
                    "email":true,
                    "minlength": 6,
                    "maxlength": 50
                },
                "password": {
                    required: function(element) {
                        if ($("#userid").val() == '') {
                            return true;
                        }
                        else {
                            return false;
                        }
                    },
                    "minlength": 6,
                    "maxlength": 20

                },
                "confirm_password": {
                    equalTo: '#password'
                },
                "mainphone": {
                    "required": true,
                    "minlength": 8,
                    "maxlength": 20
                }

            },
            messages: {
                "name": {
                    required: "<?php echo lang('name_required_error'); ?>",
                    maxlength: "<?php echo lang('name_maxlength_error'); ?>",
                    minlength: "<?php echo lang('name_minlength_error'); ?>"
                },
               "email":{
                    required: "<?php echo lang('email_required_error'); ?>",
                    email: "<?php echo lang('enter_valid_email'); ?>",
                    minlength: "<?php echo lang('email_minlength_error'); ?>",
                    maxlength: "<?php echo lang('email_maxlength_error'); ?>"
                },
                 "mainphone":{
                    required: "<?php echo lang('phone_required_error'); ?>",
                    minlength: "<?php echo lang('phone_minlength_error'); ?>",
                    maxlength: "<?php echo lang('phone_maxlength_error'); ?>"
                },
                "surname":{
                    required: "<?php echo lang('surname_required_error'); ?>",
                    minlength: "<?php echo lang('surname_minlength_error'); ?>",
                    maxlength: "<?php echo lang('surname_maxlength_error'); ?>"
                },
                 "password":{
                    required: "<?php echo lang('password_required_error'); ?>",
                    minlength: "<?php echo lang('password_minlength_error'); ?>",
                    maxlength: "<?php echo lang('password_maxlength_error'); ?>"
                },
                "confirm_password":{
                     equalTo: "<?php echo lang('confirmpassword_required_error'); ?>"
                    
                }        
            }
        });


        $('#picture').dropify();

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
                        <div class="alert alert-danger col-md-9 col-md-offset-2">
                            <p><?php echo $errors; ?></p>
                        </div>
                        <?php
                    }
                ?>

                <?php
                    $attributes = array('id' => 'user-form', 'class' => 'form-horizontal', 'method' => 'post');
                    echo form_open_multipart($form_action, $attributes);
                ?>
                <input type="hidden" name="userid" id="userid" value="<?php echo empty($userid) ? NULL : $userid; ?>" />

                <div class="box-body">



                    <div class="form-group">
                        <label class="col-md-3 control-label" for="name"><?php echo lang('name'); ?></label>
                        <div class="col-md-8">
                            <?php
                                $data = array(
                                    'name' => 'name',
                                    'id' => 'name',
                                    'value' => set_value('name', empty($name) ? "" : $name,FALSE),
                                    'class' => 'form-control',
                                    'autofocus' => 'autofocus'
                                );

                                echo form_input($data);
                            ?>
                        </div>
                    </div>
<div class="form-group">
                        <label class="col-md-3 control-label" for="surname"><?php echo lang('surname'); ?></label>
                        <div class="col-md-8">
                            <?php
                                $data = array(
                                    'name' => 'surname',
                                    'id' => 'surname',
                                    'value' => set_value('surname', empty($surname) ? "" : $surname),
                                    'class' => 'form-control',
                                );

                                echo form_input($data);
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-md-3 control-label" for="email"><?php echo lang('email'); ?></label>
                        <div class="col-md-8">
                            <?php
                                $data = array(
                                    'name' => 'email',
                                    'id' => 'email',
                                    'value' => set_value('email', empty($email) ? "" : $email),
                                    'class' => 'form-control',
                                );

                                echo form_input($data);
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="mainphone"><?php echo lang('mainphone'); ?></label>
                        <div class="col-md-8">
                            <?php
                                $data = array(
                                    'name' => 'mainphone',
                                    'id' => 'mainphone',
                                    'value' => set_value('mainphone', empty($mainphone) ? "" : $mainphone),
                                    'class' => 'form-control',
                                );

                                echo form_input($data);
                            ?>
                        </div>
                    </div>

                    


                    <div class="form-group">
                        <label class="col-md-3 control-label" for="password"><?php echo lang("password") ?></label>
                        <div class="col-md-8">
                            <?php
                                $data = array(
                                    'name' => 'password',
                                    'id' => 'password',
                                    'value' => set_value(''),
                                    'class' => 'form-control',
                                );

                                echo form_password($data);
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="confirm_password"><?php echo lang("confirm_password") ?></label>
                        <div class="col-md-8">
                            <?php
                                $data = array(
                                    'name' => 'confirm_password',
                                    'id' => 'confirm_password',
                                    'value' => set_value(''),
                                    'class' => 'form-control',
                                );

                                echo form_password($data);
                            ?>
                        </div>
                    </div>


                    <div class="form-group">
                        <label class="col-md-3 control-label" ><?php echo lang("picture") ?></label>
                        <div class="col-md-8">
                            <?php
                                $data = array(
                                    'name' => 'picture',
                                    'id' => 'picture',
                                    'value' => '',
                                    'class' => 'dropify',
                                    'data-max-file-size' => '1M',
                                    'accept' => 'image/*',
                                    'data-default-file' => empty($picture_path) ? '' : $picture_path,
                                    'data-show-remove' => FALSE
                                );
                                echo form_upload($data);
                            ?>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-md-3 control-label" for="status"><?php echo lang("status"); ?></label>
                        <div class="col-md-8">
                            <?php
                                echo form_dropdown('status', empty($arr_status) ? NULL : $arr_status, empty($status) ? NULL : $status, ' id = "status" class="form-control select2" ');
                            ?> 
                        </div>
                    </div>

                    <div class="box-footer">
                        <div class="form-group">
                            <div class="col-md-3 col-md-offset-3">
                                <?php
                                    echo form_submit('submit', lang('save'), ' class="btn bg-blue btn-flat"');

                                    echo form_reset('reset', lang('reset'), ' class="btn bg-blue btn-flat" id="reset"');
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