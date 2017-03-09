<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Commonlib
    {
        private $_CI;

        public function __construct()
        {

            $this->_CI = & get_instance();
        }
        //// Useraccount_model
        public function emailexists($user_email)
        {
            $this->_CI->load->model('Contact_model');
            return $this->_CI->Contact_model->contactEmailExist($user_email);
        }
        public function get_total_users()
        {
            $this->_CI->load->model('Useraccount_model');
            return $this->_CI->Useraccount_model->get_total_users();
        }
         public function usernamexists($username)
        {
            $this->_CI->load->model('Useraccount_model');
            return $this->_CI->Useraccount_model->user_name_exist($username);
        }
        public function get_user_byuserid($userid)
        {
            $this->_CI->load->model('Useraccount_model');
            return $this->_CI->Useraccount_model->getuserbyid($userid);
        }
       
    }
    
