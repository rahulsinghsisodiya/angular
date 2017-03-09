<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Cms extends My_Controller
    {

        public function __construct()
        {
            parent::__construct();
            $this->load->helper('language');
            $this->load->helper('url');
            $this->load->model('Cms_model');
            $this->load->library('form_validation');
            $this->load->library('Commonlibrary');
            $this->load->library("Upload");
        }

        public function cmscontent($pagename = null, $pagelanguage = null)
        {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('pagename', lang('page'), 'required');
            $this->form_validation->set_rules('pagelanguage', lang('langauage'), 'required');
            $this->form_validation->set_rules('pagetitle', lang('page_title'), 'required');
            $this->form_validation->set_rules('pagecontent', lang('page_content'), 'required');
            $this->form_validation->set_rules('leftcontent', lang('left_content'), 'required');
            $this->form_validation->set_rules('about_image', lang('About Image'));
            $dataArray = array();

            $dataArray['form_caption'] = "CMS";
            if (empty($pagelanguage))
            {
                $pagelanguage = getDefaultlanguage();
            }

            $dataArray['pagelanguage'] = $pagelanguage;
            $arr_pagelanguage = getCustomConfigItem('language');
            $dataArray['arr_pagelanguage'] = $arr_pagelanguage;

            $arr_pages = getCustomConfigItem('pages');
            $arr_pages = add_blank_option($arr_pages);

            $message = $this->session->flashdata('cms_operation_message');
            if (!empty($pagename))
            {
                $pagename = urldecode($pagename);
            }

            $dataArray['arr_pages'] = $arr_pages;
            $dataArray['pagename'] = $pagename;
            $dataArray['message'] = $message;



            if ($this->form_validation->run() == false)
            {
                if (!empty($pagename))
                {
                    $cmsRecord = $this->Cms_model->getCmsContent($pagename, $pagelanguage);

                    if (!empty($cmsRecord))
                    {
                        $dataArray['pagename'] = $cmsRecord->pagename;
                        $dataArray['pagelanguage'] = $cmsRecord->pagelanguage;
                        $dataArray['pagetitle'] = $cmsRecord->pagetitle;
                        $dataArray['pagecontent'] = html_entity_decode($cmsRecord->pagecontent);
                        $dataArray['leftcontent'] = html_entity_decode($cmsRecord->leftcontent);
                        /* image variable for about page */
                        $dataArray['about_image'] = $cmsRecord->about_image;
                    }
                }

                $message = $this->session->flashdata('cms_operation_message');
                $dataArray['message'] = $message;
                $dataArray['local_js'] = array(
                    'jquery.validate.min',
                    'ckeditor'
                );

                $this->load->view('/cms-form', $dataArray);
            }
        }

        public function savecms()
        {

            $dataValues = array(
                'pagename' => $this->input->post('pagename'),
                'pagetitle' => $this->input->post('pagetitle'),
                'pagecontent' => $this->input->post('pagecontent'),
                'leftcontent' => $this->input->post('leftcontent'),
                'pagelanguage' => $this->input->post('pagelanguage'),
            );
            $oldimage = $this->input->post('oldimage');


            if (!empty($_FILES['about_image']['name']))
            {

                $about_image_config = getCustomConfigItem("about_image");

                if ($this->commonlibrary->is_file_uploaded('about_image'))
                {

                    $slidename = $this->upload->upload_file("about_image", $about_image_config['upload_path'], $about_image_config);

                    $dataValues['about_image'] = $slidename;

                    unlink($about_image_config['upload_path'] . $oldimage);
                }
            }
            $this->Cms_model->saveCms($dataValues);

            $this->session->set_flashdata('cms_operation_message', 'Saved successfully.');
            redirect('admin/cms/cmsContent');
        }

        public function getCmsBylanguage()
        {
            $pagelanguage = $this->input->post('pagelanguage');
            $pagename = $this->input->post('pagename');

            $datavalue = $this->Cms_model->getCmsContent($pagename, $pagelanguage);
            if (empty($datavalue))
            {
                $output = array("success" => "noo", "pagetitle" => "", "pagecontent" => "");
            }
            else
            {
                $output = array("success" => "yo", "pagetitle" => $datavalue->pagetitle, "pagecontent" => $datavalue->pagecontent);
            }
            echo json_encode($output);
        }

    }
    