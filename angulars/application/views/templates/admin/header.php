<body class="hold-transition skin-blue sidebar-mini">
    <div class="wrapper">

        <header class="main-header">
            <!-- Logo -->
            <a href="" class="logo">
                <!-- mini logo for sidebar mini 50x50 pixels -->
                <span class="logo-mini">Paint</span>
                <!-- logo for regular state and mobile devices -->
               
                  <img src="<?php echo base_url() ?>assets/images/logo.png" class="img"   alt="User Image" height="50" width= "200px" >
            </a>
            <!-- Header Navbar: style can be found in header.less -->
            <nav class="navbar navbar-static-top" role="navigation">
                <!-- Sidebar toggle button-->
                <a href="#" class="sidebar-toggle" data-toggle="offcanvas" role="button">
                    <span class="sr-only">Toggle navigation</span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                    <span class="icon-bar"></span>
                </a>
                <div class="navbar-custom-menu">
                    <ul class="nav navbar-nav">
                        <li class="dropdown user user-menu">
                            <a href="#" class="dropdown-toggle" data-toggle="dropdown">
                                <img src="<?php echo base_url() ?>assets/img/avatar5.png" class="user-image" alt="User Image">
                                <span class="hidden-xs"><?php echo empty($username) ? '' : $username; ?></span>
                            </a>
                            <ul class="dropdown-menu">
                                <!-- User image -->
                                <li class="user-header">
                                    <img src="<?php echo base_url() ?>assets/img/avatar5.png" class="img-circle" alt="User Image">
                                    <p>
                                        <?php echo empty($username) ? '' : $username; ?>
                                    </p>
                                </li>
                                <li class="user-footer">
                                    <div class="pull-left">
                                        <a href="#" class="btn btn-default btn-flat"><?php echo lang('change_password'); ?></a>
                                    </div>
                                    <div class="pull-right">
                                        <a href="<?php echo base_url() ?>admin/logout" class="btn btn-default btn-flat"><?php echo lang('logout'); ?></a>
                                    </div>
                                </li>
                            </ul>
                        </li>
                    </ul>
                </div>
            </nav>
        </header>

        <?php echo $sidebar; ?>

        <!-- Full Width Column -->
        <div class="content-wrapper">
            <section class="content">
