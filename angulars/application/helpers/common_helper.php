<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    function generateRandomString($length = 10)
    {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++)
        {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }

    function getDefaultlanguage()
    {
        $langauage = getCustomConfigItem('default_language');
        return $langauage;
    }

    function getLanguage()
    {
        $CI = & get_instance();
        $site_lang = $CI->session->userdata('site_lang');
        
        if (!empty($site_lang))
        {
            $language = $site_lang;
        }
        else
        {
            $language = getCustomConfigItem('default_language');
        }
        return $language;
    }

    function object_to_array($obj)
    {
        if (is_object($obj))
            $obj = (array) $obj;
        if (is_array($obj))
        {
            $new = array();
            foreach ($obj as $key => $val)
            {
                $new[$key] = object_to_array($val);
            }
        }
        else
            $new = $obj;
        return $new;
    }

    function addhttp($url)
    {
        if (!preg_match("~^(?:f|ht)tps?://~i", $url))
        {
            $url = "http://" . $url;
        }
        return $url;
    }

    function add_blank_option($options, $blank_option = '')
    {
        if (is_array($options) && is_string($blank_option))
        {
            if (empty($blank_option))
            {
                $blank_option = array('' => '');
            }
            else
            {
                $blank_option = array('' => $blank_option);
            }
            $options = $blank_option + $options;
            //p($options);
            return $options;
        }
        else
        {
            show_error("Wrong options array passed");
        }
    }

    function getCustomConfigItem($key)
    {
        $CI = & get_instance();
        $arr_custom_config = $CI->config->item('custom');
        $config_item = $arr_custom_config[$key];
        return $config_item;
    }

    function acitivites_checkbox($activitiesid, $acitivites_detail)
    {
        $chk_id = 'riderlevel' . "_" . $activitiesid;
        if ($acitivites_detail->riderlevel_ride === "No")
        {

            $return = "<input class='riderlevels' type='checkbox' name='riderlevel[]' id='$chk_id' value='$activitiesid'>";
        }
        else
        {

            $return = "<input class='riderlevels' type='checkbox' name='riderlevel[]' id='$chk_id' value='$activitiesid' checked>";
        }
        return $return;
    }

    function acitivites_rating($rating_id, $rating_details)
    {
        if ($rating_details->publish === 'no')
        {
            $url = base_url() . 'admin/rating/ratingactivate/' . $rating_details->rating_id;
            $return = '<a href="' . $url . '"> <i class="fa fa-toggle-off  action-icon"></i></a>';
        }
        else
        {
            $url = base_url() . 'admin/rating/ratingdeactivate/' . $rating_details->rating_id;
            $return = '<a href="' . $url . '"><i style="color:green" class="fa fa-toggle-on  action-icon"></i></a>';
        }
        return $return;
    }

    function GetLoggedinUserData()
    {
        $CI = & get_instance();

        if (!empty($CI->session->userdata['my_userdata']))
        {
            $userdata = (array) $CI->session->userdata["my_userdata"];
        }
        else
        {
            $userdata = array();
        }

        return $userdata;
    }

    function GetLoggedinAdminData()
    {
        $CI = & get_instance();
        $userdata = (array) $CI->session->userdata;
        return $userdata;
    }

    function getsessionid()
    {
        $sessionid = PHPSESSID;  //$CI->session->userdata('sessionid');
        return $sessionid;
    }

    function GetLoggedinContactData()
    {
        $CI = & get_instance();
        if (!empty($CI->session->userdata['my_contactdata']))
        {
            $userdata = (array) $CI->session->userdata["my_contactdata"];
        }
        else
        {
            $userdata = array();
        }
        // p($userdata);
        return $userdata;
    }

    function GetLoggedinProviderData()
    {
        $CI = & get_instance();
        if (!empty($CI->session->userdata['my_providerdata']))
        {
            $userdata = (array) $CI->session->userdata["my_providerdata"];
        }
        else
        {
            $userdata = array();
        }
        // p($userdata);
        return $userdata;
    }

    function getuserlogin_referrer()
    {
        $CI = & get_instance();

        if (empty($CI->session->userdata['user_login_referrer']))
        {
            $redirectto = base_url() . 'my-requests';
        }
        else
        {
            $login_referrer = $CI->session->userdata['user_login_referrer'];

            $redirectto = $login_referrer;
        }
        return $redirectto;
    }

    function getproviderlogin_referrer()
    {
        $CI = & get_instance();
        if (empty($CI->session->userdata['provider_login_referrer']))
        {
            $redirectto = base_url() . 'requests';
        }
        else
        {
            $login_referrer = $CI->session->userdata['provider_login_referrer'];

            $redirectto = $login_referrer;
        }
        return $redirectto;
    }

    function my_currency_format($value)
    {
        $my_currency_format = "$" . number_format($value, 2, '.', '');
        return $my_currency_format;
    }

    function base64url_encode($data)
    {

        return rtrim(strtr(base64_encode($data), '+/', '-_'), '=');
    }

    function base64url_decode($data)
    {

        return base64_decode(str_pad(strtr($data, '-_', '+/'), strlen($data) % 4, '=', STR_PAD_RIGHT));
    }

    function mydateformat($date, $from_format = "d-m-Y", $to_format = "Y-m-d")
    {
        if ($date == "")
        {
            $return_date = "0000-00-00";
        }
        else
        {
            $date = DateTime::createFromFormat($from_format, $date);
            $return_date = $date->format($to_format);
        }

        return $return_date;
    }

    function myPublicDate($date, $from_format = "Y-m-d", $to_format = "d-m-Y")
    {
        if ($date == "0000-00-00")
        {
            $return_date = "";
        }
        else
        {
            $date = DateTime::createFromFormat($from_format, $date);

            $return_date = $date->format($to_format);
        }

        return $return_date;
    }

    function create_captcha_common()
    {
        $CI = & get_instance();
        $CI->load->helper('captcha');

        $vals = array(
            'word' => randomPassword(6),
            'img_path' => APPPATH . 'uploads/captcha/images/',
            'img_url' => base_url() . 'application/uploads/captcha/images/',
            'font_path' => APPPATH . 'uploads/captcha/OpenSans-Regular.ttf',
            'img_width' => 150,
            'img_height' => 60,
            'expiration' => 7200
        );
        $cap = create_captcha($vals);

        $_SESSION['thetopupstore']['captcha'] = $cap;
        return $cap;
    }

    function randomPassword($len = 16)
    {
        $alphabet = "abcdefghijklmnopqrstuwxyzABCDEFGHIJKLMNOPQRSTUWXYZ0123456789";
        $pass = array(); //remember to declare $pass as an array
        $alphaLength = strlen($alphabet) - 1; //put the length -1 in cache
        for ($i = 0; $i < $len; $i++)
        {
            $n = rand(0, $alphaLength);
            $pass[] = $alphabet[$n];
        }
        return implode($pass); //turn the array into a string
    }

    function getclientip()
    {
        if (!empty($_SERVER['HTTP_CLIENT_IP']))
        {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        }
        elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        {
            $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
        }
        else
        {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        return $ip;
    }

    function getlocationfromip($ipAddr)
    {
        $url = "http://api.ipinfodb.com/v3/ip-city/?key=5cfaab6c5af420b7b0f88d289571b990763e37b66761b2f053246f9db07ca913&ip=$ipAddr&format=json";
        $d = file_get_contents($url);
        $arr = json_decode($d);
        $array['country'] = $arr->countryName;
        $array['state'] = $arr->regionName;
        $array['city'] = $arr->cityName;
        return $array;
    }

    function languagefilebyid($languageid)
    {
        $CI = & get_instance();
        $CI->load->model("Language_model");
        $language_files_arr = getCustomConfigItem("language_files_arr");
        $language_detail = $CI->Language_model->getlanguagebyid($languageid);
        if (empty($language_detail))
        {
            $language = "English";
        }
        else
        {
            $language = $language_detail->languagename;
        }
        $language_file = $language_files_arr[$language];
        return $language_file;
    }

    function get_operator_image($data, $data_type = "image_name")
    {
        $image_config = getCustomConfigItem("operator_image");
        if ($data_type == "image_name")
        {
            $image_path = $image_config['upload_path'] . $data;
            if (is_file($image_path))
            {
                $image_full_path = base_url() . $image_path;
            }
            else
            {
                $image_full_path = base_url() . $image_config['upload_path'] . $image_config['default_image'];
            }
        }
        else
        {
            $image_full_path = base_url() . $image_config['upload_path'] . $image_config['default_image'];
        }
        return$image_full_path;
    }

    function check_access($module_name, $group_id, $access_type, $redirect_status = false, $redirect_url = "admin/dashboard", $message = "unauthorized_access", $flash_variable = "access_error_message")
    {
        $CI = & get_instance();
        $CI->load->model("Manageaccess_model");
        $access_return = $CI->Manageaccess_model->check_access($module_name, $group_id, $access_type);
        if (empty($access_return) && !empty($redirect_status))
        {
            $CI->session->set_flashdata($flash_variable, lang($message));
            redirect($redirect_url);
        }
        $return = empty($access_return) ? false : true;
        return $return;
    }

    function relativeTime($timestamp)
    {
        if (!is_numeric($timestamp))
        {
            $timestamp = strtotime($timestamp);
            if (!is_numeric($timestamp))
            {
                return "";
            }
        }

        $difference = time() - $timestamp;
        // Customize in your own language.
        $periods = array("sec", "min", "hour", "day", "week", "month", "years", "decade");
        $lengths = array("60", "60", "24", "7", "4.35", "12", "10");

        if ($difference > 0)
        { // this was in the past
            $ending = "ago";
        }
        else
        { // this was in the future
            $difference = -$difference;
            $ending = "to go";
        }
        for ($j = 0; $difference >= $lengths[$j] and $j < 7; $j++)
            $difference /= $lengths[$j];
        $difference = round($difference);
        if ($difference != 1)
        {
            // Also change this if needed for an other language
            $periods[$j].= "s";
        }
        $text = "$difference $periods[$j] $ending";
        return $text;
    }

    function get_sms_config()
    {
        $CI = & get_instance();
        $arr_sms_config = $CI->config->item('sms_config');
        $sms_api = $arr_sms_config['sms_api'];
        $current_config = @$arr_sms_config[$sms_api];
        return $current_config;
    }

    function get_notification_status($user_id, $user_type, $notification_type, $notification)
    {
        $return = false;
        if (!empty($user_id) && !empty($user_type) && !empty($notification_type) && !empty($notification))
        {
            $CI = & get_instance();
            $CI->load->model("General_model");
            $return = $CI->General_model->get_notification_status($user_id, $user_type, $notification_type, $notification);
        }
        return $return;
    }

    function truncate($string, $length, $dots = "...")
    {
        return (strlen($string) > $length) ? substr($string, 0, $length - strlen($dots)) . $dots : $string;
    }

    function genrateRandomNumber()
    {
        //$rand = random_string('numeric', 8); 
        $rand = mt_rand(10000000, 99999999);
        return $rand;
    }

    function getRequestNo()
    {

        $CI = & get_instance();
        while (true)
        {
            $giftid = genrateRandomNumber();
            $gifid_exist = isRequestIdExist($giftid);
            if ($gifid_exist == false)
            {
                break;
            }
        }
        return $giftid;
    }

    function isRequestIdExist($giftid)
    {
        $CI = & get_instance();
        $query = $CI->db->query("SELECT requestno  FROM `requests` WHERE  requestno='" . $giftid . "'");
        $num = $query->num_rows();
        if ($num == 0)
        {
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }

    function getResponseNo()
    {

        $CI = & get_instance();
        while (true)
        {
            $giftid = genrateRandomNumber();
            $gifid_exist = isResponseIdExist($giftid);
            if ($gifid_exist == false)
            {
                break;
            }
        }
        return $giftid;
    }

    function isResponseIdExist($giftid)
    {
        $CI = & get_instance();
        $query = $CI->db->query("SELECT responseno  FROM `response` WHERE  responseno='" . $giftid . "'");
        $num = $query->num_rows();
        if ($num == 0)
        {
            return FALSE;
        }
        else
        {
            return TRUE;
        }
    }
    