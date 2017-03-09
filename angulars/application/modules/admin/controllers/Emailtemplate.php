<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class EmailTemplate extends My_Controller
    {

        public function __construct()
        {
            parent::__construct();
            $this->load->helper('language');
            $this->load->helper('url');
            $this->load->model('Emailtemplate_model');
        }

        public function templateContent($templatename = null)
        {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('templatename', 'Select Page', 'required');
            $this->form_validation->set_rules('subject', 'Page Title', 'required');
            $this->form_validation->set_rules('content', 'Page Content', 'required');

            $dataArray = array();
            $dataArray['form_caption'] = "Email Template";

            $arr_templates = getCustomConfigItem('emailtemplates');
            $arr_templates = add_blank_option($arr_templates);

            $message = $this->session->flashdata('emailtemplate_operation_message');

            if (!empty($templatename))
            {
                $templatename = urldecode($templatename);
            }

            $dataArray['arr_templates'] = $arr_templates;
            $dataArray['templatename'] = $templatename;

            if ($this->form_validation->run() == false)
            {
                if (!empty($templatename))
                {
                    $templateRecord = $this->Emailtemplate_model->getEmailTemplate($templatename);
                    if (!empty($templateRecord))
                    {
                        $dataArray['templatename'] = $templateRecord->templatename;
                        $dataArray['subject'] = $templateRecord->subject;
                        $dataArray['content'] = $templateRecord->content;
                        $dataArray['keys'] = $templateRecord->keys;
                    }
                }

                $message = $this->session->flashdata('emailtemplate_operation_message');
                $dataArray['message'] = $message;

                $dataArray['local_js'] = array(
                    'jquery.validate.min',
                    'ckeditor',
                );
                $this->load->view('/email-template-form', $dataArray);
            }
        }

        public function saveEmailTemplate()
        {
            $templatename = $this->input->post('templatename');
            $dataValues = array(
                'templatename' => $templatename,
                'subject' => $this->input->post('subject'),
                'content' => $this->input->post('content')
            );

            $action = "";
            $templateRecord = $this->Emailtemplate_model->getEmailTemplate($templatename);
            if (!empty($templateRecord))
            {
                $action = 'update';
            }

            $this->Emailtemplate_model->saveEmailTemplate($dataValues, $action);

            $this->session->set_flashdata('emailtemplate_operation_message', 'Template saved successfully.');
            redirect('admin/emailtemplate/templateContent');
        }

    }
    