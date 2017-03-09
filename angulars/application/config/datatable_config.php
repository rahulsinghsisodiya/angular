<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');
    if (!isset($config))
    {
        $config = array();
    }
    $config['datatable_class'] = "dyntable table table-bordered table-hover dataTable";

    $config['gallery_listing_headers'] = array(
        'imagetitle' => array(
            'jsonField' => 'imagetitle',
            'width' => '80%'
        ),
        'edit' => array(
            'isSortable' => FALSE,
            'systemDefaults' => TRUE,
            'type' => 'EDIT_ICON',
            'isLink' => TRUE,
            'linkParams' => array('imageid'),
            'linkTarget' => 'admin/gallery/addgallery/',
            'width' => '5%',
            'align' => 'center'
        ),
        'delete' => array(
            'isSortable' => FALSE,
            'systemDefaults' => TRUE,
            'type' => 'DELETE_ICON',
            'confirmBox' => TRUE,
            'isLink' => TRUE,
            'linkParams' => array('imageid'),
            'linkTarget' => 'admin/gallery/deletegallery/',
            'width' => '5%',
            'align' => 'center'
        )
    );
    $config['slider_listing_headers'] = array(
        'slidetitle' => array(
            'jsonField' => 'slidetitle',
            'width' => '65%'
        ),
        'page' => array(
            'jsonField' => 'pagename',
            'width' => '15%'
        ),
        'edit' => array(
            'isSortable' => FALSE,
            'systemDefaults' => TRUE,
            'type' => 'EDIT_ICON',
            'isLink' => TRUE,
            'linkParams' => array('slideid'),
            'linkTarget' => 'admin/slider/adddslider/',
            'width' => '5%',
            'align' => 'center'
        ),
        'delete' => array(
            'isSortable' => FALSE,
            'systemDefaults' => TRUE,
            'type' => 'DELETE_ICON',
            'confirmBox' => TRUE,
            'isLink' => TRUE,
            'linkParams' => array('slideid'),
            'linkTarget' => 'admin/slider/deleteslider/',
            'width' => '5%',
            'align' => 'center'
        )
    );



    
