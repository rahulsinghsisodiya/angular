<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    $config['custom']["pages"] = array(
        'Home' => 'Home',
//        'Residential_Commercial' => 'Residential/Commercial',
        'interior' => 'Interior',
        'exterior' => 'Exterior',
        'Gallery' => 'Gallery',
        'About' => 'About',
        'Contact' => 'Contact',
    );

    $config['custom']['default_language'] = 'english';

    $config['custom']['language'] = array(
        'english' => 'English',
//    'french' => 'Français'
    );

    $config['custom']["gallery_image"] = array(
        'upload_path' => 'assets/gallery/',
        'allowed_types' => 'jpg|jpeg|png',
        'default_image' => 'default.png',
        'overwrite' => TRUE
    );

    $config['custom']["slider_slide"] = array(
        'upload_path' => 'assets/slider/',
        'allowed_types' => 'jpg|jpeg|png',
        'default_image' => 'default.png',
        'overwrite' => TRUE
    );
    $config['custom']["interior_exterior"] = array(
        'upload_path' => 'assets/slider/',
        'allowed_types' => 'jpg|jpeg|png',
        'default_image' => 'default.png',
        'overwrite' => TRUE,
        'max_width' => '350'
    );
    $config['custom']["pages"] = array(
        'Home' => 'Home',
//        'Residential_Commercial' => 'Residential/Commercial',
        'interior' => 'Interior',
        'exterior' => 'Exterior',
        'Gallery' => 'Gallery',
        'About' => 'About',
        'Contact' => 'Contact',
    );

    $config['custom']["slide_pages"] = array(
        'Home' => 'Home',
//        'Residential_Commercial' => 'Residential/Commercial',
        'Interior' => 'Interior',
        'Exterior' => 'Exterior'
    );

    $config['custom']["about_image"] = array(
        'upload_path' => 'assets/about_image/',
        'allowed_types' => 'jpg|jpeg|png',
        'default_image' => 'default.png',
        'overwrite' => TRUE,
//         'max_width' => '350'
    );
    