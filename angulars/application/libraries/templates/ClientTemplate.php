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
    class ClientTemplate extends Template
    {

        public function __construct()
        {
            parent::__construct();

            $this->_CI = & get_instance();
            $this->viewPath = "templates/client/";
        }

        public function render($view, array $data = array())
        {
            $this->CI->load->library('session');
            $this->CI->load->model('Client_model');
            $return_val = $this->CI->load->viewPartial($view, $data);

            $data['template_content'] = $return_val;


            $arr_clientdata = GetLoggedinClientData();
            if (!empty($arr_clientdata))
            {
                $client_id = $arr_clientdata['client_id'];
                $filterArr = array(
                    'client_id' => $client_id
                );

                $userdetails = $this->CI->Client_model->get_client_record($filterArr);
                $data['loginfirstname'] = $userdetails->client_displayed_name;
                $data['loginmembername'] = $userdetails->client_displayed_name . '- Member';
                $createdat = $userdetails->client_created_at;
                $client_image = $userdetails->client_image;

                $contact_list_link = base64_encode($userdetails->client_fullname . "|" . $userdetails->client_id);
                $data['contact_list_link'] = $contact_list_link;

                $client_image_config = getCustomConfigItem('client_image');
                if (!empty($client_image))
                {
                    $data['clientimage'] = base_url() . $client_image_config['upload_path'] . $client_image;
                }
                else
                {
                    $data['clientimage'] = base_url() . $client_image_config['upload_path'] . $client_image_config['default_image'];
                }



                $data['loginmemberfrom'] = 'Member since ' . date('j F Y', strtotime($createdat));
            }

            $css_tags = $this->collectCss("client", isset($data['local_css']) ? $data['local_css'] : array());
            $data['template_css'] = implode("", $css_tags); //implode("\n", $css_tags);
            $script_tags = $this->collectJs("client", isset($data['local_js']) ? $data['local_js'] : array());
            if (isset($data['template_title']))
            {
                $data['template_title'] = $data['template_title'];
            }
            $data['mini_logo'] = lang("mini_logo");
            $data['long_logo'] = lang("long_logo");



            $data['uri_segment_1'] = $this->CI->uri->segment(1);
            $data['template_js'] = implode("", $script_tags); //implode("\n", $script_tags);
            $data["sidebar"] = $this->CI->load->viewPartial($this->viewPath . 'sidebar', $data);

            $data['template_header'] = $this->CI->load->viewPartial($this->viewPath . 'header', $data);
            $data['template_footer'] = $this->CI->load->viewPartial($this->viewPath . 'footer', $data);
            $return_val = $this->CI->load->viewPartial($this->viewPath . $this->masterTemplate, $data);
            return $return_val;
        }

    }
    