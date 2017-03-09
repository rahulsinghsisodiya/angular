<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">
        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="<?php echo base_url() ?>assets/img/avatar5.png" class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <p><?php echo empty($username) ? '' : $username; ?></p>
                <a href="#"><i class="fa fa-circle text-success"></i> Online</a>
            </div>
        </div>
        <!-- /.search form -->
        <!-- sidebar menu: : style can be found in sidebar.less -->
        <ul class="sidebar-menu">
            <li class="header"><?php echo lang('MAIN NAVIGATION'); ?></li>

            <li class="<?php echo $uri_segment_2 == 'cms' ? 'active' : '' ?>">
                <a href="<?php echo base_url() ?>admin/cms/cmsContent">
                    <i class="fa fa-desktop"></i> 
                    <span><?php echo lang('cms'); ?></span>
                </a>
            </li>
            <li class="<?php echo $uri_segment_2 == 'gallery' ? 'active' : '' ?>">
                <a href="<?php echo base_url() ?>admin/gallery/listgallery">
                    <i class="fa fa-desktop"></i> 
                    <span><?php echo lang('gallery'); ?></span>
                </a>
            </li>
            <li class="<?php echo $uri_segment_2 == 'slider' ? 'active' : '' ?>">
                <a href="<?php echo base_url() ?>admin/slider/listslider">
                    <i class="fa fa-desktop"></i> 
                    <span><?php echo lang('slider'); ?></span>
                </a>
            </li>
                    </ul>
    </section>
    <!-- /.sidebar -->
</aside>
