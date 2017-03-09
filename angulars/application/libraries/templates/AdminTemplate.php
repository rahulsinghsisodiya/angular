<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');
    /**
     * Default template
     */
    require_once 'template.php';

    /**
     * Default template implementation.
     * 
     * It is the default renderer of all the pages if any other renderer is not used.
     */
    class AdminTemplate extends Template
    {

        public function __construct()
        {
            parent::__construct();

            $this->_CI = & get_instance();
        }

        public function render($view, array $data = array())
        {
            $return_val = $this->CI->load->viewPartial($view, $data);

            $data['template_content'] = $return_val;

            $css_tags = $this->collectCss("admin", isset($data['local_css']) ? $data['local_css'] : array());
            $data['template_css'] = implode("", $css_tags);
            $script_tags = $this->collectJs("admin", isset($data['local_js']) ? $data['local_js'] : array());
            $data['template_js'] = implode("", $script_tags);



            $this->CI->load->library('session');
            $this->CI->load->library('commonlib');
            $this->CI->load->helper('url');
            $this->CI->load->helper('language');

            //Logged In User detail
            $admin_data = GetLoggedinAdminData();

            $data['username'] = $admin_data['username'];
            $data['uri_segment_2'] = $this->CI->uri->segment(2);
            $data['uri_segment_3'] = $this->CI->uri->segment(3);
            $data["sidebar"] = $this->CI->load->viewPartial($this->viewPath . 'sidebar', $data);
            $data['template_header'] = $this->CI->load->viewPartial($this->viewPath . 'header', $data);
            $data['template_footer'] = $this->CI->load->viewPartial($this->viewPath . 'footer', $data);
            if (isset($data['template_title']))
            {
                $data['template_title'] = $data['template_title'];
            }
            
       

            $return_val = $this->CI->load->viewPartial($this->viewPath . $this->masterTemplate, $data);
            return $return_val;
        }

    }
    