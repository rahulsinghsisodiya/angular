<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Index extends My_Controller
    {

        public function __construct()
        {
            parent::__construct();
            $this->load->helper('language');
            $this->load->helper('url');
            $this->load->helper('array');
            $this->load->model('Slider_model');
        }

        public function gallery()
        {
            $this->load->model('Gallery_model');
            $dataArray = array();

            // $language = getLanguage();
            $gallery_image_config = getCustomConfigItem('gallery_image');

            $arr_images = $this->Gallery_model->get_all_gallery_images();

            $dataArray['upload_path'] = $gallery_image_config['upload_path'];
            $dataArray['arr_images'] = $arr_images;

            $dataArray['template_title'] = lang('Gallery') . ' | ' . SITENAME;


            $dataArray['local_css'] = array(
                'jquery.fancybox',
            );

            $dataArray['local_js'] = array(
                'jquery.fancybox',
            );

            $this->load->view('gallery', $dataArray);
        }

        public function index()
        {
            $this->load->model('Cms_model');
            $this->load->model('Slider_model');
            $dataArray = array();
            $language = getLanguage();

            $page_record = $this->Cms_model->getcmscontent('Home', $language);

            if (!empty($page_record))
            {
                $dataArray['pagecontent'] = $page_record->pagecontent;
                $dataArray['pagetitle'] = $page_record->pagetitle;
            }

            $dataArray['template_title'] = lang('Home') . ' | ' . SITENAME;
            $condition = array('pagename', 'Home');
            $dataArray['slides'] = $this->Slider_model->getsliderpage($condition);
            $dataArray['local_css'] = array(
                'owl.carousel',
                'bootstrap.min',
            );

            $dataArray['local_js'] = array(
                'owl.carousel',
                'bootstrap',
            );
            $this->load->view('index', $dataArray);
        }

        public function lang()
        {
            $this->load->model('Cms_model');
            $this->load->model('Slider_model');
            $dataArray = array();
            $language = getLanguage();

            $page_record = $this->Cms_model->getcmscontent('Home', $language);

            if (!empty($page_record))
            {
                $dataArray['pagecontent'] = $page_record->pagecontent;
                $dataArray['pagetitle'] = $page_record->pagetitle;
            }

            $dataArray['template_title'] = lang('Home') . ' | ' . SITENAME;
            $condition = array('pagename', 'Home');
            $dataArray['slides'] = $this->Slider_model->getsliderpage($condition);
            $dataArray['local_css'] = array(
                'owl.carousel',
                'bootstrap.min',
            );

            $dataArray['local_js'] = array(
                'owl.carousel',
                'bootstrap',
            );
            $this->load->view('lang', $dataArray);
        }

        public function about()
        {
            $this->load->model('Cms_model');
            $dataArray = array();
            $language = getLanguage();

            $page_record = $this->Cms_model->getcmscontent('About', $language);

            if (!empty($page_record))
            {
                $dataArray['pagecontent'] = $page_record->pagecontent;
                $dataArray['pagetitle'] = $page_record->pagetitle;
//                if (!empty($page_record->about_image))
//                {
                $dataArray['about_image'] = $page_record->about_image;
//                }
            }

            $dataArray['template_title'] = lang('about') . ' | ' . SITENAME;

            $this->load->view('about', $dataArray);
        }

        public function contact()
        {
            $this->load->model('Cms_model');
            $dataArray = array();
            $language = getLanguage();

            $page_record = $this->Cms_model->getcmscontent('Contact', $language);

            if (!empty($page_record))
            {
                $dataArray['pagecontent'] = $page_record->pagecontent;
                $dataArray['leftcontent'] = $page_record->leftcontent;
                $dataArray['pagetitle'] = $page_record->pagetitle;
            }

            $dataArray['template_title'] = lang('contact') . ' | ' . SITENAME;

            $dataArray['local_js'] = array(
                'google-map',
            );

            $this->load->view('contact', $dataArray);
        }

        public function exterior()
        {
            $this->load->model('Cms_model');
            $dataArray = array();
            $language = getLanguage();

            $page_record = $this->Cms_model->getcmscontent('Exterior', $language);

            if (!empty($page_record))
            {

                $dataArray['pagecontent'] = $page_record->pagecontent;
                $dataArray['pagetitle'] = $page_record->pagetitle;
            }
            $dataArray['local_css'] = array(
                'owl.carousel',
                'bootstrap.min',
            );
            $condition = array('pagename', 'Exterior');
            $dataArray['slides'] = $this->Slider_model->getsliderpage($condition);
            $dataArray['local_js'] = array(
                'owl.carousel',
                'bootstrap',
            );

            $dataArray['template_title'] = lang('residential-commercial') . ' | ' . SITENAME;

            $this->load->view('exterior', $dataArray);
        }

        public function interior()
        {
            $this->load->model('Cms_model');
            $dataArray = array();
            $language = getLanguage();

            $page_record = $this->Cms_model->getcmscontent('Interior', $language);

            if (!empty($page_record))
            {

//                $dataArray['image'] = $page_record->image;
                $dataArray['pagecontent'] = $page_record->pagecontent;
                $dataArray['pagetitle'] = $page_record->pagetitle;
            }
            $condition = array('pagename', 'Interior');
            $dataArray['slides'] = $this->Slider_model->getsliderpage($condition);
            $dataArray['local_css'] = array(
                'owl.carousel',
                'bootstrap.min',
            );

            $dataArray['local_js'] = array(
                'owl.carousel',
                'bootstrap',
            );

            $dataArray['template_title'] = lang('Interior') . ' | ' . SITENAME;

            $this->load->view('interior', $dataArray);
        }

        public function savecompany_json()
        {
            $this->load->model('Slider_model');
            $dataValues = Array();
            $dataValues = json_decode(file_get_contents('php://input'), true);
           
            $last_id = $this->Slider_model->saveclient($dataValues);
            $data = 'Suss';
            echo json_encode($data);
            
        }

    }
    