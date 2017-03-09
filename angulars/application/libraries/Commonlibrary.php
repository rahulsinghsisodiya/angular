<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');

/**
 * Common library function goes here
 */
class Commonlibrary {

    private $_CI;    // CodeIgniter instance

    public function __construct() {
        $this->_CI = & get_instance();
    }

    public function generatePassword($length = 9, $strength = 0) {
        $vowels = 'aeuy';
        $consonants = 'bdghjmnpqrstvz';
        if ($strength & 1) {
            $consonants .= 'BDGHJLMNPQRSTVWXZ';
        }
        if ($strength & 2) {
            $vowels .= "AEUY";
        }
        if ($strength & 4) {
            $consonants .= '23456789';
        }
        if ($strength & 8) {
            $consonants .= '@#$%';
        }

        $password = '';
        $alt = time() % 2;
        for ($i = 0; $i < $length; $i++) {
            if ($alt == 1) {
                $password .= $consonants[(rand() % strlen($consonants))];
                $alt = 0;
            } else {
                $password .= $vowels[(rand() % strlen($vowels))];
                $alt = 1;
            }
        }
        return $password;
    }

    public function deleteFile($fileName) {
        if (file_exists($fileName))
            unlink($fileName);
    }

    public function CreateDirectory($dirname) {
        if (!file_exists($dirname)) {
            mkdir($dirname, 0755);
        }
    }

    public function parseFilePath($fileType, $fileId, $module) {
        switch ($module) {
            default:
                $fileName = $fileId . '.' . $fileType;
        }

        $this->_CI->load->config('report_config');
        $arr = $this->_CI->config->item($module);

        $fileDir = $arr['upload_path'];

        return $fileDir . $fileName;
    }

    public function is_file_uploaded($user_field = '') {
        $return = FALSE;
        if (!empty($_FILES[$user_field])) {
            if (is_array($_FILES[$user_field]['name'])) {
                foreach ($_FILES[$user_field]['name'] as $key => $file) {
                    if ($_FILES[$user_field]['size'][$key] <= 0) {
                        $return = FALSE;
                        return $return;
                    } else {
                        $return = TRUE;
                    }
                }
            } else {
                if (isset($_FILES[$user_field]) && $_FILES[$user_field]['size'] > 0) {
                    $return = TRUE;
                }
            }
        }

        return $return;
    }

    public function sendmail($to_email, $to_name, $subject, $body, $mailtype = "html", $from_name = EMAIL_FROM_NAME, $from_email = EMAIL_FROM_EMAIL, $bcc = "") {
        $this->_CI->load->library('email');

//            $this->_CI->email->set_protocol('sendmail');
        $this->_CI->email->from($from_email, $from_name);
        $this->_CI->email->to($to_email);

        if (!empty($bcc)) {
            $this->_CI->email->bcc($bcc);
        }

        $this->_CI->email->set_mailtype($mailtype);
        $this->_CI->email->subject($subject);
        $this->_CI->email->message($body);
        $serverList = array('localhost', '127.0.0.1');
        if (!in_array($this->_CI->input->server('HTTP_HOST'), $serverList)) {
            $this->_CI->email->send();
        }

//            $this->email->attach()
//            if ($this->_CI->email->send())
//            {
//                echo 'Your email was sent, successfully.';
//            }
//            else
//            {
//                show_error($this->_CI->email->print_debugger());
//            }
    }

    public function sendsms($recipient = "", $message = "") {
        $this->_CI->load->library('smslib');

        if (!empty($recipient) && !empty($message)) {
            $this->_CI->smslib->sendSms($recipient, $message);
        }
    }

    public function rrmdir($dir) {
        if (is_dir($dir)) {
            $objects = scandir($dir);
            foreach ($objects as $object) {
                if ($object != "." && $object != "..") {
                    if (filetype($dir . "/" . $object) == "dir")
                        rrmdir($dir . "/" . $object);
                    else
                        unlink($dir . "/" . $object);
                }
            }
            reset($objects);
            rmdir($dir);
        }
    }

    public function getpreviewlink($file_name, $base_url = '') {

        $preview_link = '';
        $tmp_base_url = '';

        if (!isset($base_url) || $base_url == '') {
            $tmp_base_url = base_url();
        } else {
            $tmp_base_url = $base_url;
        }
        //p($file_name);
        if (file_exists($file_name)) {
            //  p("hello");
            $preview_link = '<a rel="' . $tmp_base_url . $file_name . '" href="' . $tmp_base_url . $file_name . '" class="preview">Click here to preview <i class="action-icon fa fa-image"></i></a>';
        }

//                $preview_link = '<a href="' . $tmp_base_url . $file_name . '" class="preview"><img src="assets/admin-images/icons/preview.png" alt="Preview" title="Preview" /></a>';
//            p($preview_link);
        return $preview_link;
    }

    public function getimagefilepath($module, $imagename) {
        $arr = getCustomConfigItem($module);
        $fileDir = $arr['upload_path'];
        $filepath = $fileDir . $imagename;
        return $filepath;
    }

    public function unlinkFile($file_name, $file_path) {
        $this->_CI->load->helper('path_helper');
        $path_system = set_realpath($file_path);
        $unlink_file_name = $path_system . $file_name;

        if (file_exists($unlink_file_name)) {
            unlink($unlink_file_name);
        }
    }

    /// $arr : config item of file
    public function upload_files($arr, $file_name, $file_type) {
        $return = array();
        $filename = $file_name;
        $exts = pathinfo($filename, PATHINFO_EXTENSION);
        $randimg = generateRandomString();
        $arr_doc_type = explode("/", $file_type);
        $ext = "." . $arr_doc_type[1];
        $return['location_doc'] = strtolower($randimg . "." . $exts);
        $return['tmp_upload_path'] = $arr['upload_path'];
        return $return;
    }

    public function sendSignupEmail($email, $token, $languageid) {
        $content = null;
        $this->_CI->load->model('Useraccount_model');
        $this->_CI->load->model('Emailtemplate_model');
        $subject = "Welcome To Our Site - Activate Account";
        $userRecord = $this->_CI->Useraccount_model->get_user_byemail($email);
        $fullname = $userRecord->username;
        $email_to = $userRecord->useremail;
        $templateRecord = $this->_CI->Emailtemplate_model->getEmailtemplateContent('Signup Email', $languageid);
        if (!empty($templateRecord)) {
            $content = htmlspecialchars_decode($templateRecord->pagecontent);
            $subject = $templateRecord->subject;
        }
        $activation_url = base_url() . 'activateuser/' . $token;
        $arr_placeholders = array("{{fullname}}", "{{sitename}}", "{{activation_url}}");
        $arr_placeholders_values = array($fullname, SITENAME, $activation_url);
        $content = str_replace($arr_placeholders, $arr_placeholders_values, $content);
        $this->sendmail($email_to, $fullname, $subject, $content, "html");
    }

    public function moduleconfig($modulestatus, $other_detail) {
        if ($modulestatus == "Active") {
            $link = "<a href='' data-module='" . $other_detail->id . "' id='config" . $other_detail->id . "' data-status='Active' class='configurationstatus fa fa-toggle-on fa-2x'></a>";
        } else {
            $link = "<a href='' data-module='" . $other_detail->id . "' id='config" . $other_detail->id . "' data-status='Blocked' class='configurationstatus fa fa-toggle-off fa-2x'></a>";
        }
        return $link;
    }

    public function onpage_edit($appointment_id, $other_detail) {
        $operator_data = GetLoggedinOperatorData();
        $check_access = check_access($other_detail->module, $operator_data['group_id'], "edit");
        $link = "-";
        if (!empty($check_access)) {
            $link = "<a href='' data-id='" . $appointment_id . "' id='onpage_edit_" . $appointment_id . "' class='on_page_edit'><i class='fa fa-pencil action-icon'></i></a>";
        }
        return $link;
    }

    public function session_fees_view($session_fees, $other_detail) {
        $currency_arr = getCustomConfigItem('currency_arr');
        $return = $currency_arr[$other_detail->session_fees_currency_type] . " " . $session_fees;
        return $return;
    }

    public function template_copy_link($template_id, $other_detail) {
        $operator_data = GetLoggedinOperatorData();
        $check_access = check_access($other_detail->module, $operator_data['group_id'], "add");
        $return = "-";
        if (!empty($check_access)) {
            $this->_CI->load->model("Scheduletemplate_model");
            $already_added_days = $this->_CI->Scheduletemplate_model->get_distinct_days_list_by_template_id($template_id);
            if (empty($already_added_days)) {
                $return = lang("blank");
            } else {
                $return = "<a href='' data-client_id='" . $other_detail->client_id . "' data-id='" . $template_id . "' id='copy_template_" . $template_id . "' class='copy_template'><i class='fa fa-copy action-icon'></i></a>";
            }
        }
        return $return;
    }

    public function view_appointment_schedule_link($schedule_date, $other_detail) {
        $return = "-";
        if (!empty($other_detail->client_id)) {
            $return = "<a href='' data-client_id='" . $other_detail->client_id . "' data-schedule_date='" . $schedule_date . "' class='view_schedule'><i class='fa fa-eye action-icon'></i></a>";
        }
        return $return;
    }

    public function get_country_json($query) {
        $this->_CI->load->model('Country_model');
        $arr_city = $this->_CI->Country_model->getallcountryjson($query);
        $arr = array();

        $i = 0;
        foreach ($arr_city as $cityRecord) {
            $arr[$i]["id"] = $cityRecord["country_id"];
            $arr[$i]["text"] = $cityRecord["short_name"];

            $i++;
        }

        return json_encode($arr);
    }

    public function get_user_json($query) {
        $this->_CI->load->model('Useraccount_model');
        $arr_user = $this->_CI->Useraccount_model->getuserjson($query);
        $arr = array();
        if (!empty($arr_user)) {
            $i = 0;
            foreach ($arr_user as $userRecord) {
                $arr[$i]["id"] = $userRecord["userid"];
                $arr[$i]["text"] = $userRecord["name"] . "(" . $userRecord['email'] . ")";

                $i++;
            }
        }

        return json_encode($arr);
    }

    public function get_client_search_data($query, $filterArr = NULL) {
        $arr = array();

        $this->_CI->load->model('Client_model');
        $arr_clients = $this->_CI->Client_model->get_clients_json_record($query, $filterArr);
        $client_image = getCustomConfigItem('client_image');
        if (!empty($arr_clients)) {
            $i = 0;
            foreach ($arr_clients as $clientRecord) {
                $arr[$i]["name"] = $clientRecord['client_title'] . " " . $clientRecord["client_displayed_name"];
                $arr[$i]["data"] = $clientRecord["client_id"];
                $arr[$i]["activity_type_name"] = $clientRecord["activity_type_name"];
                $arr[$i]['url'] = base_url() . $clientRecord['activity_type_slug'] . '/' . $clientRecord['client_slug'];
                if (!empty($clientRecord['client_image'])) {
                    $thumbnail = base_url() . $client_image['upload_path'] . $clientRecord["client_image"];
                } else {
                    $thumbnail = $thumbnail = base_url() . $client_image['upload_path'] . $client_image['default_image'];
                }
                $arr[$i]["thumbnail"] = $thumbnail;
                $i++;
            }
        }

        return ($arr);
    }

    public function get_client_json() {
        $this->_CI->load->model('Client_model');
        $query = $this->_CI->input->post("query");
        $activity_type_id = $this->_CI->input->post("activity_type");
        $arr = array();
        $client_image = getCustomConfigItem('client_image');
        if (!empty($query)) {
            $activity_type_id = empty($activity_type_id) ? "" : $activity_type_id;

            $arr_clients = $this->_CI->Client_model->get_clients_json_record($query, $activity_type_id);


            $i = 0;
            foreach ($arr_clients as $clientRecord) {
                $arr[$i]["value"] = $clientRecord["client_fullname"];
                $arr[$i]["data"] = $clientRecord["client_id"];
                $arr[$i]["address"] = $clientRecord["client_address1"];

                if (!empty($clientRecord['client_image'])) {
                    $thumbnail = base_url() . $client_image['upload_path'] . $clientRecord["client_image"];
                } else {
                    $thumbnail = $thumbnail = base_url() . $client_image['upload_path'] . $client_image['default_image'];
                }
                $arr[$i]["thumbnail"] = $thumbnail;
                $i++;
            }
        }
        return $arr;
    }

    function create_unique_slug($string, $table, $field = 'slug', $key = NULL, $value = NULL) {
        $t = & get_instance();
        $slug = url_title($string);
        $slug = strtolower($slug);
        $slug = strtr(utf8_decode($slug), utf8_decode('àáâãäçèéêëìíîïñòóôõöùúûüýÿÀÁÂÃÄÇÈÉÊËÌÍÎÏÑÒÓÔÕÖÙÚÛÜÝ'), 'aaaaaceeeeiiiinooooouuuuyyAAAAACEEEEIIIINOOOOOUUUUY');

        $i = 0;
        $params = array();
        $params[$field] = $slug;

        if ($key)
            $params["$key !="] = $value;

        while ($t->db->where($params)->get($table)->num_rows()) {
            if (!preg_match('/-{1}[0-9]+$/', $slug))
                $slug .= '-' . ++$i;
            else
                $slug = preg_replace('/[0-9]+$/', ++$i, $slug);

            $params [$field] = $slug;
        }
        return $slug;
    }

    function getLatLong($address) {
        if (!empty($address)) {
            //Formatted address
            $formattedAddr = str_replace(' ', '+', $address);
            //Send request and receive json data by address
            $geocodeFromAddr = file_get_contents('http://maps.googleapis.com/maps/api/geocode/json?address=' . $formattedAddr . '&sensor=false');
            $output = json_decode($geocodeFromAddr);
            //Get latitude and longitute from json data
            $data['latitude'] = $output->results[0]->geometry->location->lat;
            $data['longitude'] = $output->results[0]->geometry->location->lng;
            //Return latitude and longitude of the given address
            if (!empty($data)) {
                return $data;
            } else {
                return false;
            }
        } else {
            return false;
        }
    }

    public function make_excel($heading, $field, $data, $file_name) {
        $this->_CI->load->library('excel');
        $objPHPExcel = new PHPExcel();

        $rowNumberH = 1;
        $colH = 'A';
        foreach ($heading as $h) {
            $objPHPExcel->getActiveSheet()->setCellValue($colH . $rowNumberH, $h[0]);
//                $objPHPExcel->getActiveSheet()->getColumnDimension($colH)->setAutoSize();
            $objPHPExcel->getActiveSheet()->getColumnDimension($colH)->setWidth($h[1] + 0.71);
            $objPHPExcel->getActiveSheet()->getRowDimension($rowNumberH)->setRowHeight(15);

            $colH++;
        }

        $row = 2;

        foreach ($data as $key => $record) {
            foreach ($field as $k => $fieldname) {
                $colno = $k;
                if (!empty($fieldname)) {
                    $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow($colno, $row, $record[$fieldname]);
                    $objPHPExcel->getActiveSheet()->getRowDimension($row)->setRowHeight(20);
                }
            }
            $row = $row + 1;
        }

        $objPHPExcel->getActiveSheet()->setTitle('');

        $objPHPExcel->setActiveSheetIndex(0);
//            $objPHPExcel->getActiveSheet()->getPageSetup()->setOrientation(PHPExcel_Worksheet_PageSetup::ORIENTATION_LANDSCAPE);


        header('Content-Type: application/vnd.ms-excel');
        header('Content-Disposition: attachment;filename="' . $file_name . '"');
        header('Cache-Control: max-age=0');
        header('Cache-Control: max-age=1');
        header('Expires: Mon, 26 Jul 1997 05:00:00 GMT'); // Date in the past
        header('Last-Modified: ' . gmdate('D, d M Y H:i:s') . ' GMT'); // always modified
        header('Cache-Control: cache, must-revalidate'); // HTTP/1.1
        header('Pragma: public'); // HTTP/1.0
        $objWriter = PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');


        $objWriter->save('php://output');
    }

    public function get_city_json($query) {
        $this->_CI->load->model('City_model');
        $arr_city = $this->_CI->City_model->getallcityjson($query);
        $arr = array();

        $i = 0;
        foreach ($arr_city as $cityRecord) {
            $arr[$i]["data"] = $cityRecord["cityid"];
            $arr[$i]["value"] = $cityRecord["cityname"];
            $arr[$i]["make"] = $cityRecord["regionname"];

            $i++;
        }

        return json_encode($arr);
    }

    public function getservicefieldhtmlpublic($servicefieldid, $value = NULL) {
        $html = '';
        if (!empty($servicefieldid)) {
            $this->_CI->load->model('Servicefield_model');
            $filterArr = array(
                'sf.servicefieldid' => $servicefieldid
            );
            $servicefieldrecord = $this->_CI->Servicefield_model->getservicefieldrecord($filterArr);

            $fieldtype = $servicefieldrecord->fieldtype;
            $fieldtitle = $servicefieldrecord->fieldtitle;
            $mandatory = $servicefieldrecord->mandatory;
            $required = (empty($mandatory) || $mandatory == 0) ? FALSE : TRUE;

            $fieldoptiontype = getCustomConfigItem('fieldoptiontype');

            if (in_array($fieldtype, $fieldoptiontype)) {
                $options = $this->getoptions($servicefieldid);


                $data = array(
                    'options' => $options,
                    'fieldtype' => $fieldtype,
                    'name' => 'servicefield[' . $servicefieldid . ']',
                    'id' => 'servicefield_' . $servicefieldid,
                    'value' => $value,
                    'required' => $required
                );

                $html = '<div class="col-sm-6">';
                $html.='<label for="' . $data['id'] . '">';
                $html.=$fieldtitle;
                $html.='</label>';
                $html.='<div class="clearfix"></div>';
                $html.= $this->gethtmlforinput($data);
                $html.='</div>';
            } else {
                $data = array(
                    'fieldtype' => $fieldtype,
                    'name' => 'servicefield[' . $servicefieldid . ']',
                    'value' => $value,
                    'id' => 'servicefield_' . $servicefieldid,
                    'required' => $required
                );
                $html = '<div class="col-sm-6">';
                $html.='<label for="' . $data['id'] . '">';
                $html.=$fieldtitle;
                $html.='</label>';
                $html.= $this->gethtmlforinput($data);
                $html.='</div>';
            }
        }

        return $html;
    }

    public function getservicefieldhtml($servicefieldid, $value = NULL) {
        $html = '';
        if (!empty($servicefieldid)) {
            $this->_CI->load->model('Servicefield_model');
            $filterArr = array(
                'sf.servicefieldid' => $servicefieldid
            );
            $servicefieldrecord = $this->_CI->Servicefield_model->getservicefieldrecord($filterArr);

            $fieldtype = $servicefieldrecord->fieldtype;
            $fieldtitle = $servicefieldrecord->fieldtitle;
            $mandatory = $servicefieldrecord->mandatory;
            $required = empty($mandatory) ? FALSE : TRUE;

            $fieldoptiontype = getCustomConfigItem('fieldoptiontype');

            if (in_array($fieldtype, $fieldoptiontype)) {
                $options = $this->getoptions($servicefieldid);


                $data = array(
                    'options' => $options,
                    'fieldtype' => $fieldtype,
                    'name' => 'servicefield[' . $servicefieldid . ']',
                    'id' => 'servicefield_' . $servicefieldid,
                    'value' => $value,
                    'required' => $required
                );

                $html = '<div class="form-group">';
                $html.='<label class="col-md-3 control-label" for="' . $data['id'] . '">';
                $html.=$fieldtitle;
                $html.='</label>';
                $html.='<div class="col-md-8">';
                $html.= $this->gethtmlforinput($data);
                $html.='</div>';
                $html.='</div>';
            } else {
                $data = array(
                    'fieldtype' => $fieldtype,
                    'name' => 'servicefield[' . $servicefieldid . ']',
                    'value' => $value,
                    'id' => 'servicefield_' . $servicefieldid,
                    'required' => $required
                );
                $html = '<div class="form-group">';
                $html.='<label class="col-md-3 control-label" for="' . $data['id'] . '">';
                $html.=$fieldtitle;
                $html.='</label>';
                $html.='<div class="col-md-8">';
                $html.= $this->gethtmlforinput($data);
                $html.='</div>';
                $html.='</div>';
            }
        }

        return $html;
    }

    public function gethtmlforinput($data) {
        $this->_CI->load->helper('form_helper');
        $fieldtype = $data['fieldtype'];
        $required = $data['required'];

        switch ($fieldtype) {
            case 'Dropdown':
                $data['options'] = add_blank_option($data['options'], '');
                $requiredtext = empty($required) ? '' : 'required=required';
                $html = form_dropdown($data['name'], empty($data['options']) ? array() : $data['options'], empty($data['value']) ? NULL : $data['value'], 'id="' . $data['id'] . '" class="form-control input-block-level" ' . $requiredtext . '');
                break;
            case 'Checkbox':

                $i = 1;
                $html = '';
                foreach ($data['options'] as $key => $val) {
                    $selectedvalue = explode(',', $data['value']);

                    if (in_array($val, $selectedvalue)) {
                        $checked = TRUE;
                    } else {
                        $checked = FALSE;
                    }

                    $html.= '<div class="col-sm-12 padding_0 margin_0">';
                    $html.='<div class="col-sm-1">';
                    $checkbox = array(
                        'name' => $data['name'] . '[]',
                        'id' => $val . '-' . $i,
                        'value' => $val,
                        'checked' => $checked,
                        'class' => '',
                    );

                    if (!empty($required)) {
                        $checkbox['required'] = $required;
                    }

                    $html.=form_checkbox($checkbox);
                    $html.='</div>';
                    $html.='<label class="col-sm-10 control-label" for="' . $val . '-' . $i . '">';
                    $html.=$val;
                    $html.='</label>';
                    $html.='</div>';
                }
                $html.='<p></p>';

                break;
            case 'Radio':
                $i = 1;
                $html = '';
                foreach ($data['options'] as $key => $val) {
                    if ($val == $data['value']) {
                        $checked = TRUE;
                    } else {
                        $checked = FALSE;
                    }

                    $html.= '<div class="col-sm-12 padding_0 margin_0">';
                    $html.='<div class="col-sm-1">';
                    $checkbox = array(
                        'name' => $data['name'],
                        'id' => $val . '-' . $i,
                        'value' => $val,
                        'checked' => $checked,
                        'class' => ''
                    );

                    if (!empty($required)) {
                        $checkbox['required'] = $required;
                    }

                    $html.=form_radio($checkbox);
                    $html.='</div>';
                    $html.='<label class="col-sm-10 control-label" for="' . $val . '-' . $i . '">';
                    $html.=$val;
                    $html.='</label>';
                    $html.='</div>';
                }
                break;
            case 'Text':
                $text = array(
                    'name' => $data['name'],
                    'id' => $data['id'],
                    'value' => set_value($data['name'], empty($data['value']) ? "" : $data['value'], FALSE),
                    'class' => 'form-control',
                );
                if (!empty($required)) {
                    $text['required'] = $required;
                }
                $html = form_input($text);
                break;
            case 'Date':
                $text = array(
                    'name' => $data['name'],
                    'id' => $data['id'],
                    'value' => set_value($data['name'], empty($data['value']) ? "" : $data['value']),
                    'class' => 'form-control datepicker',
                );
                if (!empty($required)) {
                    $text['required'] = $required;
                }
                $html = form_input($text);
                break;
            case 'Textarea':
                $textarea = array(
                    'name' => $data['name'],
                    'id' => $data['id'],
                    'value' => empty($data['value']) ? NULL : $data['value'],
                    'class' => 'form-control',
                    'rows' => '3',
                    'cols' => '50'
                );
                if (!empty($required)) {
                    $textarea['required'] = $required;
                }
                $html = form_textarea($textarea);
                break;
            default:
                $html;
        }

        return $html;
    }

    public function getoptions($servicefieldid) {
        $result = array();

        $filterArr = array(
            'servicefieldid' => $servicefieldid
        );
        $servicefieldoptionrecord = $this->_CI->Servicefield_model->getservicefieldoptionrecord($filterArr);

        if (!empty($servicefieldoptionrecord)) {
            $options = $servicefieldoptionrecord->options;
            $list = explode(',', $options);
            foreach ($list as $key => $val) {
                $result[$val] = $val;
            }
        }

        return $result;
    }

    public function provideremailexists($email) {
        $this->_CI->load->model('Provider_model');
        return $this->_CI->Provider_model->providerEmailExist($email);
    }

    public function getusersidebar() {
        $this->_CI->load->model('Useraccount_model');
        $user_picture_config = getCustomConfigItem('user_picture');
        $userdata = GetLoggedinUserData();
        $userid = $userdata['userid'];
        $filterArr = array(
            'userid' => $userid
        );
        $userrecord = $this->_CI->Useraccount_model->getuserrecord($filterArr);
        $data['name'] = $userrecord->name;

        $data['userimage'] = '';
        $upload_path = base_url() . $user_picture_config['upload_path'];
        $default_image = $user_picture_config['default_image'];
        $user_image = $userrecord->picture;
        if (!empty($user_image)) {
            $data['userimage'] = $upload_path . $user_image;
        } else {
            $data['userimage'] = $upload_path . $default_image;
        }

        $sidebar = $this->_CI->load->viewPartial('user-sidebar', $data);
        return $sidebar;
    }

    public function getprovidersidebar() {

        $this->_CI->load->model('Provider_model');
        $provider_data = GetLoggedinProviderData();
        $providerid = $provider_data['providerid'];
        $filterArr = array(
            'providerid' => $providerid
        );
        $providerrecord = $this->_CI->Provider_model->getproviderrecord($filterArr);
        $data['providername'] = $providerrecord->providername;
        $data['status'] = $providerrecord->status;

        $sidebar = $this->_CI->load->viewPartial('provider-sidebar', $data);
        return $sidebar;
    }

    public function getrequeststatus($status) {
        $return = '<span class="text-red">Inactive<span>';
        if ($status) {
            $return = '<span class="text-green">Active<span>';
        }

        return $return;
    }

    public function getrequestatus1($status, $data) {
        $this->_CI->load->model('Response_model');
        $return = 'Open';
        $requestid = $data->requestid;

        $filterArr3 = array(
            'r.requestid' => $requestid,
        );
        $responserecord = $this->_CI->Response_model->getresponserecords($filterArr3, 'rp.offeredprice');
        $countresponse = count($responserecord);

        $projectstart = str_replace('/', '-', $data->projectstart);
        $start = strtotime($projectstart);
        $today = time();


        $maxresponse = getCustomConfigItem('maxresponse');

        if ($countresponse >= $maxresponse) {
            $return = 'Closed';
        } elseif ($today > $start) {
            $return = 'Closed';
        }

        return $return;
    }

    public function getledgerevent($ledgerid, $data) {
        $return = NULL;
        $this->_CI->load->model('Payment_model');
        $this->_CI->load->model('Response_model');

        $recordid = $data->recordid;
        $transactiontype = $data->transactiontype;

        if ($transactiontype == 'Purchase') {
            $record = $this->_CI->Payment_model->getpaymentbyid($recordid);
            if (!empty($record)) {
                $return = 'Deposit #' . $record['txn_id'];
            }
        } elseif ($transactiontype == 'Lead') {
            $filterArr = array(
                'responseid' => $recordid
            );
            $record = $this->_CI->Response_model->getresponserecord($filterArr);
            if (!empty($record)) {
                $return = '<a href=' . base_url() . 'view-request/' . $record->requestno . '>Buying lead #' . $record->requestno . '</a>';
            }
        }

        return $return;
    }

    public function getremaningbalance($ledgerid, $data) {
        $this->_CI->load->model('Payment_model');

        $providerdata = GetLoggedinProviderData();
        $providerid = $providerdata['providerid'];

        $amount = $data->amount;
        $status = $data->status;

        $filterArr = array(
            'ledgerid' => $ledgerid,
            'providerid' => $providerid
        );

        $record = $this->_CI->Payment_model->getpreviousledger($filterArr);

        if (!empty($record)) {
            $totalamount = 0;
            foreach ($record as $key => $val) {
                if ($val['status'] == 'Plus') {
                    $totalamount = $totalamount + $val['amount'];
                } else {
                    $totalamount = $totalamount - $val['amount'];
                }
            }

            if ($status == 'Plus') {
                $return = $totalamount + $amount;
            } else {
                $return = $totalamount - $amount;
            }
        } else {
            $return = $amount;
        }


        return $return;
    }

    public function sendresponsemail($responseid) {
        $this->_CI->load->model('Response_model');
        $this->_CI->load->model('Emailtemplate_model');
        $filterArr = array(
            'rp.responseid' => $responseid
        );
        $responserecord = $this->_CI->Response_model->getresponserecord($filterArr);
        $templateRecord = $this->_CI->Emailtemplate_model->getemailtemplate('Response');
        if (!empty($templateRecord) && !empty($responserecord)) {
            $content = htmlspecialchars_decode($templateRecord->content);
            $subject = $templateRecord->subject;

            $from_name = EMAIL_FROM_NAME;
            $from_email = EMAIL_FROM_EMAIL;

            $email_to = $responserecord->contactemail;
            $fullname = $responserecord->contactname;

            $contactname = $responserecord->contactname;
            $requestno = $responserecord->requestno;
            $responseno = $responserecord->responseno;
            $providername = $responserecord->providername;
            $offeredprice = $responserecord->offeredprice;
            $useremail = $responserecord->useremail;

            $referenceurl = base_url() . 'my-request/' . $requestno;

            $arr_placeholders = array("{{sitename}}", "{{contactname}}", "{{requestno}}", "{{responseno}}", "{{providername}}", "{{offeredprice}}", "{{referenceurl}}");
            $arr_placeholders_values = array(SITENAME, $contactname, $requestno, $responseno, $providername, $offeredprice, $referenceurl);

            $content = str_replace($arr_placeholders, $arr_placeholders_values, $content);


            $this->sendmail($email_to, $fullname, $subject, $content, "html", $from_name, $from_email, $useremail);
        }
    }

    public function get_zipcode($query) {
        $this->_CI->load->model('City_model');
        $arr_city = $this->_CI->City_model->getallcityjson($query);
        $arr = array();

        $i = 0;
        foreach ($arr_city as $cityRecord) {
            $arr[$i]["data"] = $cityRecord["postalcode"];
            $arr[$i]["value"] = $cityRecord["postalcode"] . '-' . $cityRecord['cityname'];

            $i++;
        }

        return ($arr);
    }

    public function getservicename($servicefieldid) {
        $this->_CI->load->model('Servicefield_model');
        $servicfield_services = $this->_CI->Servicefield_model->getservicefieldservices_record(array('sf.servicefieldid' => $servicefieldid));

        $servicefieldname = array();
        foreach ($servicfield_services as $key => $val) {
            $servicefieldname[$val['subservicename']] = $val['subservicename'];
        }

        $services = implode(', ', $servicefieldname);

        return $services;
    }

    public function getrequiredamountforbid($budget) {
        $this->_CI->load->model('Payment_model');
        $recordamount = $this->_CI->Payment_model->getbidpercentrecord($budget);

        if (!empty($recordamount)) {
            $percent = $recordamount->percent;
        } else {
            $percent = getCustomConfigItem('bidpercent');
        }

        $amount = ($percent * $budget) / 100;

        return $amount;
    }

    public function getonlyprofessionals() {
        $this->_CI->load->model('Cms_model');
        $dataArray = array();
        $language = getLanguage();
        $cmscontent = $this->_CI->Cms_model->getcmscontent('only-professionals', $language);
        if (!empty($cmscontent)) {
            $dataArray['pagetitle'] = $cmscontent->pagetitle;
            $dataArray['pagecontent'] = $cmscontent->pagecontent;
        }
        $result = $this->_CI->load->viewPartial('only-professionals', $dataArray);
        return $result;
    }

    public function sendrequestmail($requestid) {
        $this->_CI->load->model('Provider_model');
        $this->_CI->load->model('Emailtemplate_model');
        $this->_CI->load->model('Request_model');
        if (!empty($requestid)) {
            $requestRecord = $this->_CI->Request_model->getrequestrecord(array('r.requestid' => $requestid));
            $serviceFieldRecord = $this->_CI->Request_model->getrequestservicefield(array('r.requestid' => $requestid));
      
            $serviceid = $requestRecord->serviceid;
            $postalcode = $requestRecord->postalcode;
            $requestno = $requestRecord->requestno;
            $clienttype = $requestRecord->requestconcerns;
            $frequency = '';
            foreach ($serviceFieldRecord as $key => $val) {
                if ($val['servicefieldid'] == 29) { // FIXME : find programmatic way to get "frequency"
                    $frequency = $val['value'];
                }
            }

            $providerArr = $this->_CI->Provider_model->getallprovider_array();
            foreach ($providerArr as $key => $val) {
                $serviceArr = array();
                $cityArr = array();

                $servicedata = $this->_CI->Provider_model->getproviderservices($key);
                $serviceContents = array();
                foreach ($servicedata as $row) {
                    $serviceArr[$row['serviceid']] = $row['serviceid'];
                    $serviceContents[$row['serviceid']] = $row['servicename'];
                }

                $citydata = $this->_CI->Provider_model->getprovidercities($key);
                foreach ($citydata as $row) {
                    $cityArr[$row['postalcode']] = $row['postalcode'];
                }

                // FIXME : ADD HERE FUNCTION TO MAKE MATCH BETWEEN CITY AND THE REST
                if (in_array($serviceid, $serviceArr) && in_array($postalcode, $cityArr)) {
                    $providerRecord = $this->_CI->Provider_model->getproviderrecord(array('providerid' => $key));
                    $requestmail = $providerRecord->request;

                    if (!empty($requestmail)) {
                        $servicename = $serviceContents[$serviceid];
                        $providername = $providerRecord->providername;
                        $email_to = $providerRecord->email;

                        $templateRecord = $this->_CI->Emailtemplate_model->getemailtemplate('Request Notification');

                        if (!empty($templateRecord)) {
                            $content = htmlspecialchars_decode($templateRecord->content);
                            $subject = $templateRecord->subject . ' - ' . $servicename . ' - ' . $postalcode;
                        }

                        $from_name = EMAIL_FROM_NAME;
                        $from_email = EMAIL_FROM_EMAIL;

                        $requesturl = base_url() . 'view-request/'.$requestno;


                        $arr_placeholders = array("{{clienttype}}", "{{frequency}}", "{{servicename}}", "{{postalcode}}", "{{providername}}", "{{sitename}}", "{{requestno}}", "{{requesturl}}");
                        $arr_placeholders_values = array($clienttype, $frequency, $servicename, $postalcode, $providername, SITENAME, $requestno, $requesturl);

                        $content = str_replace($arr_placeholders, $arr_placeholders_values, $content);

                        $this->sendmail($email_to, $providername, $subject, $content, "html", $from_name, $from_email);
                    }
                }
            }
        }
    }

    public function getresponseno($requestid) {
        $this->_CI->load->model('Response_model');
        $return = lang('you_dont_have_responded_yet');
        $providerdata = GetLoggedinProviderData();
        $providerid = $providerdata['providerid'];
        $filterArr2 = array(
            'rp.requestid' => $requestid,
            'rp.providerid' => $providerid
        );

        $responserecord = $this->_CI->Response_model->getresponserecord($filterArr2, 'rp.responseid,rp.responseno');
        if (!empty($responserecord)) {
            $return = '#' . $responserecord->responseno;
        }

        return $return;
    }

        public function GetLocationJson($query)
        {
            $this->_CI->load->model('Location_model');
            $arr_location = $this->_CI->Location_model->getalllocationsJson($query);
            $arr = array();

            $i = 0;
            foreach ($arr_location as $locationRecord)
            {
                $arr[$i]["value"] = $locationRecord["name"];
                $arr[$i]["data"] = $locationRecord["locationid"];
                $i++;
}

            return json_encode($arr);
        }
        public function getlocationtype($type_id,$detail)
        {
            
            $location_type_arr = getCustomConfigItem('location_type');
            $type = $location_type_arr[$type_id];
            return $type;
        }
    }
    
