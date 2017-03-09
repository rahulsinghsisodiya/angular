<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$config['custom']["email_config"] = array(
    'protocol' => 'smtp',
    'smtp_host' => '',
    'smtp_user' => '',
    'smtp_pass' => '',
    'smtp_port' => 25,
    'smtp_timeout' => 10,
    'smtp_crypto' => 'tls',
    'charset' => 'utf-8',
    'mailtype' => 'html',
    'newline' => "\r\n",
    'crlf' => "\r\n",
);

$config['custom']["pages"] = array(
    'about' => 'About',
    'contact' => 'Contact',
    'quality-chart' => 'Charte de qualité',
    'how-it-works-customer' => 'How it works customer',
    'how-it-works-pro' => 'How it works pro',
    'service-cost' => 'Service cost',
    'fiscal-deduction' => 'Fiscal deduction',
    'legal-status' => 'Legal status',
    'how-aidomenage-works' => 'Home - How aidomenage works',
    'why-use-aidomenage' => 'Why use aidomenage ?',
    'general-terms-and-conditions' => 'General Terms and Conditions',
    'post-a-request-sidebar' => 'Post a request - sidebar',
    'login-sidebar' => 'login - sidebar',
    'user-profile-data-sidebar' => 'User Profile data - sidebar',
    'service-i-offer-sidebar' => 'Services I offer - sidebar',
    'history-and-payment-sidebar' => 'History and payment - sidebar',
    'only-professionals' => 'Only professionals',
);

$config['custom']['permissions'] = array(
    "add" => "add",
    "edit" => "edit",
    "delete" => "delete",
    "view" => "view",
);
$config['custom']['status'] = array(
    "Active" => "Active",
    "Blocked" => "Blocked",
);
$config['custom']['appointment_status'] = array(
    "Active" => "Active",
    "Cancel" => "Cancel",
);
$config['custom']['allow_status'] = array(
    "Yes" => "Yes",
    "No" => "No",
);
$config['custom']['default_status'] = array(
    "Yes" => "Yes",
    "No" => "No",
);
$config['custom']['client_type_arr'] = array(
    "Business" => "Business",
    "Solo" => "Solo",
);
$config['custom']["emailtemplates"] = array(
    'Signup Email' => 'Signup Email',
    'Forgot Password Email' => 'Forgot Password Email',
    'Signup Email User' => 'Signup Email User',
    'Request Activation' => 'Request Activation',
    'Response' => 'Response',
    'Request Notification' => 'Request Notification',
);
$config['custom']["pagetemplates"] = array(
    'aboutus' => 'aboutus',
);
$config['custom']["gender"] = array(
    "Male" => "Male",
    "Female" => "Female",
    "Other" => "Other",
);
$config['custom']["title_arr"] = array(
    "Mr" => "Mr",
    "Mrs" => "Mrs",
    "Miss" => "Miss",
    "Dr" => "Dr",
);
$config['custom']["operator_image"] = array(
    'upload_path' => 'assets/images/operators/',
    'allowed_types' => 'jpg|jpeg|png',
    'default_image' => 'default.png',
    'overwrite' => TRUE
);
$config['custom']["provider_picture"] = array(
    'upload_path' => 'assets/images/provider/',
    'allowed_types' => 'jpg|jpeg|png',
    'default_image' => 'default.png',
    'overwrite' => TRUE
);
$config['custom']["user_picture"] = array(
    'upload_path' => 'assets/images/user/',
    'allowed_types' => 'jpg|jpeg|png',
    'default_image' => 'default.png',
    'overwrite' => TRUE
);

$config['custom']["contact_image"] = array(
    'upload_path' => 'assets/images/contacts/',
    'allowed_types' => 'jpg|jpeg|png',
    'default_image' => 'default.png',
    'overwrite' => TRUE
);

$config['custom']["days_arr"] = array(
    "SUNDAY" => "SUNDAY",
    "MONDAY" => "MONDAY",
    "TUESDAY" => "TUESDAY",
    "WEDNESDAY" => "WEDNESDAY",
    "THURSDAY" => "THURSDAY",
    "FRIDAY" => "FRIDAY",
    "SATURDAY" => "SATURDAY",
);

$config['custom']['currency_arr'] = Array
    (
    'USD' => "$",
    'EUR' => "€",
    'GBP' => "£",
    'JPY' => "¥",
);



$config['custom']['notifications'] = array(
    'Email' => 'Email',
//        'Chat' => 'Chat',
    'Sms' => 'Sms'
);

$config['custom']['notification_type'] = array(
    "Appointment Booking" => "Appointment Booking",
    "New Contact Booking" => "New Contact Booking",
    "Appointment Cancel" => "Appointment Cancel",
    "Appointment Release" => "Appointment Release",
    "Reminder" => "Reminder",
);

$config['custom']['notification_access'] = array(
    'yes' => 'yes',
    'no' => 'no'
);


$config['custom']['appointment_type_color_code'] = '#318ccc';
$config['custom']['booked_color_code'] = '#0000ff';
$config['custom']['urgency_color_code'] = '#ff0000';
$config['custom']['missed_color_code'] = '#F3566D';

$config['custom']['feedback'] = array(
    "did not come" => "did not come",
    "late coming" => "late coming",
    "not paid" => "not paid",
    "not good one" => "not good one",
    "blacklisted" => "blacklisted"
);

$config['custom']['icon'] = array(
    "did not come" => "<i class='fa fa-fighter-jet'></i>",
    "web booking" => "<i class='fa fa-globe'></i>",
    "new patient" => "<i class='fa fa-circle'></i>",
);

$config['custom']['time_step'] = 5;

$config['custom']['mark'] = array(
    'new patient' => 'new patient',
    'urgent' => 'urgent'
);

$config['custom']['live_feed_limit'] = 8;

$config['custom']['client_feed_icon'] = array(
    "add" => "fa fa-plus bg-blue",
    "edit" => "fa fa-pencil bg-yellow",
    "delete" => "fa fa-trash bg-red",
);

$config['custom']['contact_feed_title'] = array(
    'add' => '<a href="#">Contact</a> booked an appointment',
    'edit' => '<a href="#">Contact</a> Edit an appointment',
    'delete' => '<a href="#">Contact</a> delete an appointment',
);

$config['custom']['contact_feed_content'] = array(
    'add' => '<a href="#">Contact</a> booked an appointment of date {{schedule_date}} & time {{start_time}} for {{contact_full_name}}',
    'edit' => '<a href="#">Contact</a> Edit an appointment of date {{schedule_date}} & time {{start_time}} for {{contact_full_name}}',
    'delete' => '<a href="#">Contact</a> delete an appointment of date {{schedule_date}} & time {{start_time}} for {{contact_full_name}}',
);

$config['custom']['client_feed_content'] = array(
    'add' => '<a href="#">You</a> booked an appointment of date {{schedule_date}} & time {{start_time}} for {{contact_full_name}}',
    'edit' => '<a href="#">You</a> Edit an appointment of date {{schedule_date}} & time {{start_time}} for {{contact_full_name}}',
    'delete' => '<a href="#">You</a> delete an appointment of date {{schedule_date}} & time {{start_time}} for {{contact_full_name}}',
);

$config['custom']['operator_feed_content'] = array(
    'add' => '<a href="#">Operator</a> booked an appointment of date {{schedule_date}} & time {{start_time}} for {{contact_full_name}}',
    'edit' => '<a href="#">Operator</a> Edit an appointment of date {{schedule_date}} & time {{start_time}} for {{contact_full_name}}',
    'delete' => '<a href="#">Operator</a> delete an appointment of date {{schedule_date}} & time {{start_time}} for {{contact_full_name}}',
);



$config['custom']['client_feed_title'] = array(
    'add' => '<a href="#">You</a> booked an appointment',
    'edit' => '<a href="#">You</a> Edit an appointment',
    'delete' => '<a href="#">You</a> delete an appointment',
);

$config['custom']['operator_feed_title'] = array(
    'add' => '<a href="#">Operator</a> booked an appointment',
    'edit' => '<a href="#">Operator</a> Edit an appointment',
    'delete' => '<a href="#">Operator</a> delete an appointment',
);

$config['custom']['clients_per_page'] = 2;
$config['custom']['radius'] = 50;

$config['custom']['booked_pagination_config'] = array(
    'per_page' => 5,
    'uri_segment' => 2,
    'num_links' => 2,
    'full_tag_open' => '<ul class="pagination pagination-lg" id="pagination_ul">',
    'full_tag_close' => '</ul>',
    'first_link' => FALSE,
    'last_link' => FALSE,
    'first_tag_open' => '<li>',
    'first_tag_close' => '</li>',
    'prev_link' => '&laquo',
    'prev_tag_open' => '<li class="prev">',
    'prev_tag_close' => '</li>',
    'next_link' => '&raquo',
    'next_tag_open' => '<li>',
    'next_tag_close' => '<li>',
    'last_tag_open' => '<li>',
    'last_tag_close' => '<li>',
    'cur_tag_open' => '<li class="active"><a href="#">',
    'cur_tag_close' => '</a></li>',
    'num_tag_open' => '<li>',
    'num_tag_close' => '</li>',
    'use_page_number' => TRUE
);

$config['custom']['joined_waitlist_pagination_config'] = array(
    'per_page' => 5,
    'uri_segment' => 2,
    'num_links' => 2,
    'full_tag_open' => '<ul class="pagination pagination-lg" id="pagination_ul">',
    'full_tag_close' => '</ul>',
    'first_link' => FALSE,
    'last_link' => FALSE,
    'first_tag_open' => '<li>',
    'first_tag_close' => '</li>',
    'prev_link' => '&laquo',
    'prev_tag_open' => '<li class="prev">',
    'prev_tag_close' => '</li>',
    'next_link' => '&raquo',
    'next_tag_open' => '<li>',
    'next_tag_close' => '<li>',
    'last_tag_open' => '<li>',
    'last_tag_close' => '<li>',
    'cur_tag_open' => '<li class="active"><a href="#">',
    'cur_tag_close' => '</a></li>',
    'num_tag_open' => '<li>',
    'num_tag_close' => '</li>',
    'use_page_number' => TRUE
);


$config['custom']['default_calendar_view'] = array(
    'basicWeek' => 'week',
    'month' => 'month',
    'basicDay' => 'day'
);

$config['custom']['default_calendar'] = 'basicWeek';



$config['custom']["clients_profile_image"] = array(
    'upload_path' => 'assets/images/clients-profile/',
    'allowed_types' => 'jpg|jpeg|png',
    'overwrite' => TRUE,
    'max_size' => 1000
);

$config['custom']['max_file_count_clients_profile'] = 10;

$config['custom']['reminder_frequency'] = array(
    "24" => "24 hours",
    "12" => "12 hours",
    "6" => "6 hours",
    "3" => "3 hours",
);

$config['custom']['reminder_type'] = array(
    "Email" => "Email",
    "Sms" => "Sms",
);

$config['custom']['default_calendar'] = 'basicWeek';

$config['custom']['message_status'] = array(
    'Urgent' => 'Urgent',
    'Medium urgent' => 'Medium urgent',
    'Ask for contact' => 'Ask for contact',
    'Waiting advice' => 'Waiting advice',
    'Waiting analysis feedback' => 'Waiting analysis feedback',
    'Waiting call' => 'Waiting call',
    'Need duplicated doc' => 'Need duplicated doc'
);

$config['custom']['message_status_icon'] = array(
    'Urgent' => '<i class="fa fa-star text-red"></i>',
    'Medium urgent' => '<i class="fa fa-star text-yellow"></i>',
    'Ask for contact' => '<i class="fa fa-question"></i>',
    'Waiting advice' => '<i class="fa fa-comments-o"></i>',
    'Waiting analysis feedback' => '<i class="fa fa-pencil-square-o"></i>',
    'Waiting call' => '<i class="fa fa-phone-square"></i>',
    'Need duplicated doc' => '<i class="fa fa-file-text"></i>'
);


$config['custom']['sent_message_admin_pagination_config'] = array(
    'per_page' => 10,
    'uri_segment' => 4,
    'first_link' => FALSE,
    'last_link' => FALSE,
    'use_page_number' => TRUE,
    'display_pages' => FALSE,
    'num_link' => 1,
    'full_tag_open' => '<div class="btn-group">',
    'full_tag_close' => '</div>',
    'prev_link' => '<i class="fa fa-chevron-left"></i>',
    'next_link' => '<i class="fa fa-chevron-right"></i>',
    'cur_tag_open' => '',
    'cur_tag_close' => '',
    'anchor_class' => 'class="btn btn-default btn-sm"'
);

$config['custom']['sent_message_client_pagination_config'] = array(
    'per_page' => 10,
    'uri_segment' => 2,
    'first_link' => FALSE,
    'last_link' => FALSE,
    'use_page_number' => TRUE,
    'display_pages' => FALSE,
    'num_link' => 1,
    'full_tag_open' => '<div class="btn-group">',
    'full_tag_close' => '</div>',
    'prev_link' => '<i class="fa fa-chevron-left"></i>',
    'next_link' => '<i class="fa fa-chevron-right"></i>',
    'cur_tag_open' => '',
    'cur_tag_close' => '',
    'anchor_class' => 'class="btn btn-default btn-sm"'
);

$config['custom']['inbox_message_admin_pagination_config'] = array(
    'per_page' => 10,
    'uri_segment' => 4,
    'first_link' => FALSE,
    'last_link' => FALSE,
    'use_page_number' => TRUE,
    'display_pages' => FALSE,
    'num_link' => 1,
    'full_tag_open' => '<div class="btn-group">',
    'full_tag_close' => '</div>',
    'prev_link' => '<i class="fa fa-chevron-left"></i>',
    'next_link' => '<i class="fa fa-chevron-right"></i>',
    'cur_tag_open' => '',
    'cur_tag_close' => '',
    'anchor_class' => 'class="btn btn-default btn-sm"'
);


$config['custom']['archive_message_admin_pagination_config'] = array(
    'per_page' => 10,
    'uri_segment' => 4,
    'first_link' => FALSE,
    'last_link' => FALSE,
    'use_page_number' => TRUE,
    'display_pages' => FALSE,
    'num_link' => 1,
    'full_tag_open' => '<div class="btn-group">',
    'full_tag_close' => '</div>',
    'prev_link' => '<i class="fa fa-chevron-left"></i>',
    'next_link' => '<i class="fa fa-chevron-right"></i>',
    'cur_tag_open' => '',
    'cur_tag_close' => '',
    'anchor_class' => 'class="btn btn-default btn-sm"'
);

$config['custom']['inbox_message_client_pagination_config'] = array(
    'per_page' => 10,
    'uri_segment' => 2,
    'first_link' => FALSE,
    'last_link' => FALSE,
    'use_page_number' => TRUE,
    'display_pages' => FALSE,
    'num_link' => 1,
    'full_tag_open' => '<div class="btn-group">',
    'full_tag_close' => '</div>',
    'prev_link' => '<i class="fa fa-chevron-left"></i>',
    'next_link' => '<i class="fa fa-chevron-right"></i>',
    'cur_tag_open' => '',
    'cur_tag_close' => '',
    'anchor_class' => 'class="btn btn-default btn-sm"'
);

$config['custom']['client_accepatable_payment'] = array(
    'Species accepted' => 'Species accepted',
    'Cheques accepted' => 'Cheques accepted',
    'Credit Card accepted' => 'Credit Card accepted'
);

$config['custom']['client_accepatable_payment_icons'] = array(
    'Species accepted' => '<i class="fa fa-money fa-2x"></i>',
    'Cheques accepted' => '<i class="fa fa-money fa-2x"></i>',
    'Credit Card accepted' => '<i class="fa fa-credit-card fa-2x"></i>'
);



$config['custom']['client_other_information'] = array(
    'Vitale Card accepted' => 'Vitale Card accepted',
    'Third-party payment' => 'Third-party payment',
);

$config['custom']['companytype'] = array(
    'type1' => 'type1',
    'type2' => 'type2',
    'type3' => 'type3',
    'type4' => 'type4',
);

$config['custom']['fieldtype'] = array(
    'Text' => 'Text',
    'Date' => 'Date',
    'Textarea' => 'Textarea',
    'Dropdown' => 'Dropdown',
    'Checkbox' => 'Checkbox',
    'Radio' => 'Radio'
);

$config['custom']['fieldoptiontype'] = array(
    'Dropdown' => 'Dropdown',
    'Checkbox' => 'Checkbox',
    'Radio' => 'Radio'
);

$config['custom']['credit'] = array(
    '10' => '10‎ €',
    '20' => '20‎ €',
    '30' => '30‎ €',
    '40' => '40‎ €',
    '50' => '50‎ €',
    '60' => '60‎ €',
    '70' => '70‎ €',
    '80' => '80‎ €',
    '90' => '90‎ €',
);


$config['custom']['paypal'] = array(
    "url" => "https://www.sandbox.paypal.com/cgi-bin/webscr",
    // "id" => "gajendrabang-facilitator@yahoo.com",
    "id" => "hanwant0-business@gmail.com",
    "test_mode" => "on",
);


$config['custom']['amount'] = 2;
$config['custom']['maxresponse'] = 3;

$config['custom']['requestconcerns'] = array(
    'Individual' => 'Individual',
    'Enterprise' => 'Enterprise',
    'Developer' => 'Developer',
    'Coproprieté' => 'Coproprieté',
    'Association' => 'Association',
    'Public organization' => 'Public organization'
);

$config['custom']['bidpercent'] = 1;

$config['custom']['projectstart'] = array(
    '6' => 'As soon as posssible',
    '7' => '1 Week',
    '14' => '2 Weeks',
    '30' => '1 Months',
    '60' => '2 Months',
    '90' => '3 Months',
    '180' => '6 Months'
);

$config['custom']['default_language'] = 'french';


$config['custom']['language'] = array(
    'english' => 'English',
    'french' => 'Français'
);

$config['custom']['service_icon'] = array(
    'service-fer-a-repasser' => 'Fer à repasser',
    'service-poubelle' => 'Poubelle',
    'service-aspirateur' => 'Aspirateur',
    'service-machine-a-laver' => 'Machine à laver',
    'service-autres' => 'Autres',
    'service-vitres' => 'Vitres',
    'service-immeuble' => 'Immeuble',
    'service-bureaux' => 'Bureaux',
    'service-chantier' => 'Chantier',
    'service-maison' => 'Maison',
    'service-commercial' => 'Local commercial',
    'service-demenagement' => 'Demenagement'
);


$config['custom']['default_service_icon'] = 'service-washing';
$config['custom']['default_budget'] = 100;

$config['custom']['verified'] = array(
    'no' => 'No',
    'yes' => 'Yes'
);

$config['custom']['validated'] = array(
    'no' => 'No',
    'yes' => 'Yes'
);




