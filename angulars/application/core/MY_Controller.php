<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class My_Controller extends CI_Controller
    {

        /**
         * $ajaxRequest : this is the variable which contains the requested page is via ajax call or not. by default it is false and will be set as false and will be set as true in constructor after validating the request type.
         *
         */
        public $ajaxRequest = false;
        public $template = NULL;

        public function __construct()
        {
            parent::__construct();

            /**
             * validating the request type is ajax or not and setting up the $ajaxRequest variable as true/false.
             *
             */
            $requestType = $this->input->server('HTTP_X_REQUESTED_WITH');
            $this->ajaxRequest = strtolower($requestType) == 'xmlhttprequest';

            /**
             * set the default template as blank when the request type is ajax
             */
            if ($this->ajaxRequest === true)
            {
                $this->load->setTemplate('blank');
            }

            $module = $this->router->fetch_module();
            $method = $this->router->fetch_method();



            switch ($module)
            {
                case 'public':
                    $provider_method_arr = array(
                        //'providerDashboard',
                    );
                    if (array_search($method, $provider_method_arr) !== FALSE)
                    {
                        $this->load->setTemplate('provider');
                    }
                    else
                    {

                        $this->load->setTemplate('public');
                    }
                    break;
            }
        }

        public function _remap($method, $params = array())
        {
            $this->load->library('session');
            $this->load->helper('url');

            $module = $this->router->fetch_module();

            if ($module == 'admin')
            {
                $redirectToLogin = true;

                if (($method == 'login' && is_array($params) && count($params) == 1 && $params[0] == 'redirectForcefully') ||
                    ($method == 'validate' && is_array($params) && count($params) == 1 && $params[0] == 'redirectForcefully')
                )
                {
                    $redirectToLogin = false;
                }

                if ($redirectToLogin == true)
                {
                    $loggedin = $this->session->userdata('logged_in');

                    if (empty($loggedin))
                    {
                        redirect('admin/users/login/redirectForcefully/');
                    }
                }
            }
            elseif ($module == 'public')
            {

               
            }

            if (method_exists($this, $method))
            {
                call_user_func_array(array($this, $method), $params);
            }
            else
            {
                show_404();
            }
        }

    }
    