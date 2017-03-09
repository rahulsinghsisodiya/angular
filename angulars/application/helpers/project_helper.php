<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    function get_slider_image_path($filename)
    {
        $slider_slide_config = getCustomConfigItem('slider_slide');
        $filename = $slider_slide_config['upload_path'] . $filename;
        return $filename;
    }
    
    function get_about_image_path($filename)
    {
        $about_image_config = getCustomConfigItem('about_image');
        $filename = $about_image_config['upload_path'] . $filename;
        return $filename;
    }
    