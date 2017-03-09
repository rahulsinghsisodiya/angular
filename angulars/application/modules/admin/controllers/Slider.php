<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Slider extends My_Controller
    {

        private $_slider_listing_headers = 'slider_listing_headers';

        public function __construct()
        {
            parent::__construct();
            $this->load->helper('language');
            $this->load->helper('url');
            $this->load->model('Slider_model');
        }

        public function addslider($slideid = null)
        {
            $this->load->library('form_validation');
            $this->load->library('Commonlibrary');
            $this->load->library("Upload");

            $this->form_validation->set_rules('slidetitle', lang('slidetitle'), 'required|trim|max_length[255]');

            $slider_slide_config = getCustomConfigItem('slider_slide');

            if ($this->form_validation->run() == FALSE)
            {
                $dataArray = array();

                $dataArray['form_caption'] = lang('add') . ' ' . lang('slide');
                $dataArray['form_action'] = current_url();
                $dataArray['back_link'] = base_url() . 'admin/slider/slidegallery';
                $dataArray['template_title'] = lang('add') . ' ' . lang('slide') . ' | ' . lang('SITENAME');
                $arr_pages = getCustomConfigItem('slide_pages');
                $arr_pages = add_blank_option($arr_pages);
                $dataArray['arr_pages'] = $arr_pages;
                if (!empty($slideid))
                {
                    $filterArr = array(
                        'slideid' => $slideid
                    );
                    $sliderrecord = $this->Slider_model->getsliderrecord($filterArr);
                    $dataArray['form_caption'] = lang('edit') . ' ' . lang('slide');
                    $dataArray['slideid'] = $slideid;
                    $dataArray['slidetitle'] = $sliderrecord->slidetitle;
//                    if (is_file($user_picture_config['upload_path'] . $picture))
//                    {
//                        $dataArray['picture_path'] = base_url() . $user_picture_config['upload_path'] . $picture;
//                    }
                }

                $dataArray['local_js'] = array(
                    'jquery.validate.min'
                );
                $this->load->view('/slider-form', $dataArray);
            }
            else
            {
                $dataValues = array(
                    'slidetitle' => $this->input->post('slidetitle'),
                );

                $slideid = $this->input->post('slideid');

                if (!empty($slideid))
                {
                    $dataValues['slideid'] = $slideid;
                }
                $pagename= $this->input->post('pagename');
                 if (!empty($pagename))
                {
                    $dataValues['pagename'] = $pagename;
                }

                if (!empty($_FILES['slide']['name']))
                {

                    if ($this->commonlibrary->is_file_uploaded('slide'))
                    {
                        $slidename = $this->upload->upload_file("slide", $slider_slide_config['upload_path'], $slider_slide_config);

                        $dataValues['slidename'] = $slidename;
//                        if (!empty($current_picture) && is_file($user_picture_config['upload_path'] . $current_picture))
//                        {
//                            unlink($user_picture_config['upload_path'] . $current_picture);
//                        }
                    }
                }


                $last_id = $this->Slider_model->saveslider($dataValues);

                $this->session->set_flashdata('slider_operation_message', lang('slider') . ' ' . lang('saved'));

                redirect('admin/slider/listslider');
            }
        }

        public function deleteslider($slideid)
        {
            $sliderrecord = $this->Slider_model->getsliderrecord(array('slideid' => $slideid));
            $filename = get_slider_image_path($sliderrecord->slidename);

            if (file_exists($filename))
            {
                unlink($filename);
            }

            $this->Slider_model->deletesliderbyid($slideid);

            $this->session->set_flashdata('gallery_operation_message', lang('slider') . ' ' . lang('deleted'));

            redirect('admin/slider/listslider');
        }

        public function listsliderdata()
        {

            $this->load->library('Datatable');
            $arr = $this->config->config[$this->_slider_listing_headers];
            $cols = array_keys($arr);
            
            $pagingParams = $this->datatable->get_paging_params($cols);

            $resultdata = $this->Slider_model->getallslider($pagingParams);

            $json_output = $this->datatable->get_json_output($resultdata, $this->_slider_listing_headers);
            $this->load->setTemplate('json');
            $this->load->view('json', $json_output);
        }

        public function listslider()
        {
            $this->load->library('Datatable');

            $message = $this->session->flashdata('gallery_operation_message');

            $table_config = array(
                'source' => site_url('admin/slider/listsliderdata'),
                'datatable_class' => $this->config->config["datatable_class"]
            );

            $dataArray = array(
                'table' => $this->datatable->make_table($this->_slider_listing_headers, $table_config),
                'message' => $message
            );

            $dataArray['template_title'] = lang('list') . ' ' . lang('slider') . ' | ' . lang('SITENAME');


            $dataArray['local_css'] = array(
                'jquery.dataTables.bootstrap',
            );

            $dataArray['local_js'] = array(
                'jquery.dataTables',
                'jquery.dataTables.bootstrap',
                'dataTables.fnFilterOnReturn',
            );

            $dataArray['table_heading'] = lang('list') . ' ' . lang('slider');
            $dataArray['new_entry_link'] = base_url() . 'admin/slider/addslider';
            $dataArray['new_entry_caption'] = lang('new') . ' ' . lang('slider');

            $this->load->view('/slider-list', $dataArray);
        }

        public function setorder()
        {
            $arr_gallery = $this->Gallery_model->getallgallery_array();

            $dataArray = array(
                "arr_gallery" => $arr_gallery,
            );

            $dataArray['local_js'] = array(
                'jquery-ui.min',
            );
            $this->load->view('/gallery-set-order', $dataArray);
        }

        public function setordersave()
        {
            $item = $this->input->post('item');

            if (!empty($item))
            {
                foreach ($item as $orderid => $value)
                {
                    $tmp_orderid = $orderid + 1;
                    $slide_id = str_replace("slide_id_", "", $value);
                    $this->Gallery_model->setorder($slide_id, $tmp_orderid);
                }
            }
            $this->load->setTemplate('json');
            $this->load->view('json', array("status" => "success"));
        }

    }
    
