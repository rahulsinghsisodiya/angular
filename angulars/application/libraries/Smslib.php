<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    /**
     * Common library function goes here
     */
    class SMSLib
    {

        private $_CI;    // CodeIgniter instance

        public function __construct()
        {
            $this->_CI = & get_instance();
        }

        public function sendSms($recipient = "", $message = "")
        {
            $return = false;
            if (!empty($recipient) || !empty($message))
            {
                $sms_config = get_sms_config();

                if (!empty($sms_config))
                {
                    switch ($sms_config['provider'])
                    {
                        CASE "CMSMS" :
                            $this->_CI->load->library('CMLib');
                            $objSms = new CMSMS();
                            break;
                        default :
                            return false;
                    }

                    $message_status = $objSms->sendMessage($recipient, $message, $sms_config);
                    $return = $message_status;
                }
            }

            return $return;
        }

    }
    