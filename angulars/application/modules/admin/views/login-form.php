<!DOCTYPE html>
<html>
    <head>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <title><?php echo $this->lang->line('login_page_title_admin'); ?></title>
        <!-- Tell the browser to be responsive to screen width -->
        <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
        <!-- Bootstrap 3.3.5 -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/bootstrap/css/bootstrap.min.css">
        <!-- Font Awesome -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/font-awesome/4.4.0/css/font-awesome.min.css">
        <!-- Ionicons -->
        <link rel="stylesheet" href="https://code.ionicframework.com/ionicons/2.0.1/css/ionicons.min.css">
        <!-- Theme style -->
        <link rel="stylesheet" href="<?php echo base_url(); ?>assets/css/AdminLTE.min.css">
        <!-- iCheck -->

        <!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
        <!--[if lt IE 9]>
            <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
            <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
        <![endif]-->

    </head>
    <body class="hold-transition login-page">
        <div class="login-box">
            <div class="login-logo">
                <a href="<?php echo base_url(); ?>">
<!--                      <img src="<?php echo base_url() ?>assets/img/avatar5.png" class="img-circle" alt="User Image">-->
                      <img src="<?php echo base_url() ?>assets/images/logo.png" class="img"  border-radius: 50%; alt="User Image" height="150" >
                      
                    <?php //echo lang('SITENAME'); ?>
                </a>
            </div><!-- /.login-logo -->
            <div class="login-box-body">
                <?php
                    if ($this->session->flashdata('login_operation_message'))
                    {
                        ?>
                        <div class="alert alert-danger alert-bold-border fade in alert-dismissable">
                            <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                            <?php echo $this->session->flashdata('login_operation_message'); ?>
                        </div>
                        <?php
                    }
                ?>  
                <?php
                    $attributes = array('id' => 'login-form', 'class' => '');
                    echo form_open_multipart('admin/users/validate/redirectForcefully', $attributes);
                ?>
                <div class="form-group has-feedback">
                    <input type="text" class="form-control" id="username" name="username" value="<?php echo set_value('username'); ?>" placeholder="<?php echo $this->lang->line('username'); ?>" autofocus="autofocus">
                    <span class="glyphicon glyphicon-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback">
                    <input type="password" class="form-control" id="password" name="password" value="<?php echo set_value('password'); ?>" placeholder="<?php echo $this->lang->line('password'); ?>">
                    <span class="glyphicon glyphicon-lock form-control-feedback"></span>
                </div>
                <div class="row">
                    <div class="col-xs-4 pull-right">
                        <button type="submit" class="btn btn-primary btn-block btn-flat">
                            <?php echo lang('login'); ?>
                        </button>
                    </div><!-- /.col -->
                </div>
                </form>


            </div><!-- /.login-box-body -->
        </div><!-- /.login-box -->

        <script src="<?php echo base_url(); ?>assets/plugins/jQuery/jQuery-2.1.4.min.js"></script>
        <!-- Bootstrap 3.3.5 -->
        <script src="<?php echo base_url(); ?>assets/bootstrap/js/bootstrap.min.js"></script>
        <!-- iCheck -->

        <script src="<?php echo base_url(); ?>assets/plugins/validation/jquery.validate.min.js"></script>

       
    </body>
</html>
