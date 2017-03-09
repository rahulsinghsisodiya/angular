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
    class PublicTemplate extends Template
    {

        public function __construct()
        {
            parent::__construct();

            $this->_CI = & get_instance();
            $this->viewPath = "templates/public/";
        }

        public function render($view, array $data = array())
        {
            $this->CI->load->library('session');
            $this->CI->load->helper('url');
            $this->CI->load->helper('form');

            $session_data = $this->CI->session->userdata('my_userdata');
            $return_val = $this->CI->load->viewPartial($view, $data);


            $data['template_content'] = $return_val;
            if (isset($data['template_title']))
            {
                $data['template_name'] = $data['template_title'];
            }
            $data['uri_segment_1'] = $this->CI->uri->segment(1);

            $arr_site_language = getCustomConfigItem('language');
            $data['arr_site_language'] = $arr_site_language;
            $data['site_language'] = getLanguage();


            $css_tags = $this->collectCss("public", isset($data['local_css']) ? $data['local_css'] : array());
            $data['template_css'] = implode("", $css_tags); //implode("\n", $css_tags);
            $script_tags = $this->collectJs("public", isset($data['local_js']) ? $data['local_js'] : array());
            $data['template_title'] = "";
            $data['template_js'] = implode("", $script_tags); //implode("\n", $script_tags);
            $data['template_header'] = $this->CI->load->viewPartial($this->viewPath . 'header', $data);
            $data['template_footer'] = $this->CI->load->viewPartial($this->viewPath . 'footer', $data);

            $return_val = $this->CI->load->viewPartial($this->viewPath . $this->masterTemplate, $data);
            return $return_val;
        }

    }
    