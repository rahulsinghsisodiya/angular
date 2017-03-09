<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <META NAME="ROBOTS" CONTENT="NOINDEX, NOFOLLOW"> 
        <title></title>   
        <!-- BOOTSTRAP CSS (REQUIRED ALL PAGE)-->
        <link href="<?php echo base_url(); ?>static/css/admin/bootstrap.min.css" rel="stylesheet">
        <!-- MAIN CSS (REQUIRED ALL PAGE)-->
        <link href="<?php echo base_url(); ?>static/css/font-awesome/css/font-awesome.min.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>static/css/admin/admin-styles.css" rel="stylesheet">
        <link href="<?php echo base_url(); ?>static/css/admin/style-responsive.css" rel="stylesheet">
        <script src="<?php echo base_url(); ?>static/js/admin/jquery.min.js"></script>
        <!-- HTML5 shim and Respond.js IE8 support of HTML5 elements and media queries -->
        <!--[if lt IE 9]>
        <script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
        <script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
        <![endif]-->
        <script type="text/javascript">
            $().ready(function() {

                $("#login-form").validate({
                    rules: {
                        login_username: "required",
                        login_password: "required",
                    },
                    messages: {
                        login_username: "Enter Username",
                        login_password: "Enter password",
                    }
                });
            });
        </script>
    </head>
    <!--
    ===========================================================
    BEGIN PAGE
    ===========================================================
    -->
    <body class="login tooltips">
        <div class="login-header text-center">
    <!--            <img src="<?php echo base_url(); ?>static/img/logo-login.png" class="logo" alt="Logo">-->
        </div>
        <div class="login-wrapper">
            <?php
                if (!empty($message))
                {
                    ?>
                    <div class="alert alert-warning alert-bold-border fade in alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo $message; ?>
                    </div>
                    <?php
                }

                $errors = validation_errors();
                if (!empty($errors))
                {
                    ?>  
                    <div class="alert alert-warning alert-bold-border fade in alert-dismissable">
                        <button type="button" class="close" data-dismiss="alert" aria-hidden="true">&times;</button>
                        <?php echo $errors; ?>
                    </div>
                    <?php
                }
            ?>
            <form name="login-form" id="login-form" role="form" action="<?php echo base_url(); ?>login/index/validate" method="post">
                <div class="form-group has-feedback lg left-feedback no-label">
                    <input name="login_username" id="login_username" type="text" class="form-control no-border input-lg rounded" placeholder="Enter username" autofocus>
                    <span class="fa fa-user form-control-feedback"></span>
                </div>
                <div class="form-group has-feedback lg left-feedback no-label">
                    <input name="login_password" id="login_password" type="password" class="form-control no-border input-lg rounded" placeholder="Enter password">
                    <span class="fa fa-unlock-alt form-control-feedback"></span>
                </div>
                <!--                <div class="form-group">
                                    <div class="checkbox">
                                        <label>
                                            <input type="checkbox" class="i-yellow-flat"> Remember me
                                        </label>
                                    </div>
                                </div>-->
                <div class="form-group">
                    <button type="submit" class="btn btn-warning btn-lg btn-perspective btn-block">LOGIN</button>
                </div>
            </form>
            <p class="text-center"><strong><a href="#">Forgot your password?</a></strong></p>
        </div><!-- /.login-wrapper -->
        <!--
        ===========================================================
        END PAGE
        ===========================================================
        -->

        <!--
        ===========================================================
        Placed at the end of the document so the pages load faster
        ===========================================================
        -->
        <!-- MAIN JAVASRCIPT (REQUIRED ALL PAGE)-->
        <script src="<?php echo base_url(); ?>static/js/admin/jquery.min.js"></script>
        <script src="<?php echo base_url(); ?>static/js/admin/bootstrap.min.js"></script>
        <script src="<?php echo base_url(); ?>static/js/admin/jquery.nicescroll.js"></script>
<!--        <script src="<?php echo base_url(); ?>static/js/admin/jquery.slimscroll.min.js"></script>-->
        <script src="<?php echo base_url(); ?>static/js/admin/jquery.validate.min.js"></script>

        <!-- MAIN APPS JS -->
        <script src="<?php echo base_url(); ?>static/js/admin/apps.js"></script>
    </body>
</html>
