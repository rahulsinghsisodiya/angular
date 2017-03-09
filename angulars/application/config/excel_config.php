<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    if (!isset($config))
    {
        $config = array();
    }

    $config["excel_config"] = array(
        'client_contacts_headings' => array(
            'First Name'=>array(
                'field_name'=>'contact_first_name',
                'size'=>'20',
            ),
            'Last Name'=>array(
                'field_name'=>'contact_last_name',
                'size'=>'20',
            ),
            'Address First'=>array(
                'field_name'=>'contact_address_1',
                'size'=>'40',
            ),
            'Address Second'=>array(
                'field_name'=>'contact_address_2',
                'size'=>'40',
            ),
            'Zip'=>array(
                'field_name'=>'contact_zip',
                'size'=>'10',
            ),
            'City'=>array(
                'field_name'=>'contact_city',
                'size'=>'20',
            ),
            'Phone First'=>array(
                'field_name'=>'contact_phone_home_1',
                'size'=>'20',
            ),
            'Phone Second'=>array(
                'field_name'=>'contact_phone_home_2',
                'size'=>'20',
            ),
            'Mobile First'=>array(
                'field_name'=>'contact_phone_mobile_1',
                'size'=>'20',
            ),
            'Mobile Second'=>array(
                'field_name'=>'contact_phone_mobile_2',
                'size'=>'20',
            ),
            'Office Phone'=>array(
                'field_name'=>'contact_phone_office',
                'size'=>'20',
            ),
            'Fax'=>array(
                'field_name'=>'contact_fax',
                'size'=>'20',
            ),
            'Email First'=>array(
                'field_name'=>'contact_email_1',
                'size'=>'20',
            ),
            'Email Second'=>array(
                'field_name'=>'contact_email_2',
                'size'=>'20',
            ),
            'Date of Birth'=>array(
                'field_name'=>'contact_dob',
                'size'=>'20',
            ),
            'Messenger First'=>array(
                'field_name'=>'contact_messenger_1',
                'size'=>'20',
            ),
            'Messenger Second'=>array(
                'field_name'=>'contact_messenger_2',
                'size'=>'20',
            ),
        ),
      );

//End of the file report_config.php 
