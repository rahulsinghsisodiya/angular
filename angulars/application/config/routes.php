<?php

    defined('BASEPATH') OR exit('No direct script access allowed');



    $route['default_controller'] = 'public/index/index';
// $route['default_controller'] = 'public/request/postRequest';
    $route['home'] = 'public/index/index';

//    $route['residential-commercial'] = 'public/index/residential_commercial';
    $route['exterior'] = 'public/index/exterior';
    $route['interior'] = 'public/index/interior';
    $route['gallery'] = 'public/index/gallery';
    $route['about'] = 'public/index/about';
    $route['lang'] = 'public/index/lang';
    $route['contact'] = 'public/index/contact';
    $route['savecompany_json'] = 'public/index/savecompany_json';
    $route['getclient_json'] = 'public/index/getclient_json';


    $route['admin'] = 'admin/users';
    $route['admin/logout'] = 'admin/users/logout';
    $route['admin/dashboard'] = 'admin/users/dashboard';

    