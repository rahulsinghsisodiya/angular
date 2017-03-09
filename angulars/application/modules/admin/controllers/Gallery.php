<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Gallery extends My_Controller
    {

        private $_gallery_listing_headers = 'gallery_listing_headers';

        public function __construct()
        {
            parent::__construct();
            $this->load->helper('language');
            $this->load->helper('url');
            $this->load->model('Gallery_model');
        }

        public function addgallery($imageid = null)
        {
            $this->load->library('form_validation');
            $this->load->library('Commonlibrary');
            $this->load->library("Upload");

            $this->form_validation->set_rules('imagetitle', lang('imagetitle'), 'required|trim|max_length[255]');

            $gallery_image_config = getCustomConfigItem('gallery_image');

            if ($this->form_validation->run() == FALSE)
            {
                $dataArray = array();

                $dataArray['form_caption'] = lang('add') . ' ' . lang('gallery');
                $dataArray['form_action'] = current_url();
                $dataArray['back_link'] = base_url() . 'admin/gallery/listgallery';
                $dataArray['template_title'] = lang('add') . ' ' . lang('gallery') . ' | ' . lang('SITENAME');

                if (!empty($imageid))
                {
                    $filterArr = array(
                        'imageid' => $imageid
                    );
                    $galleryrecord = $this->Gallery_model->getgalleryrecord($filterArr);
                    $dataArray['form_caption'] = lang('edit') . ' ' . lang('gallery');

                    $dataArray['imageid'] = $imageid;
                    $dataArray['imagetitle'] = $galleryrecord->imagetitle;
//                    if (is_file($user_picture_config['upload_path'] . $picture))
//                    {
//                        $dataArray['picture_path'] = base_url() . $user_picture_config['upload_path'] . $picture;
//                    }
                }

                $dataArray['local_js'] = array(
                    'jquery.validate.min'
                );
                $this->load->view('/gallery-form', $dataArray);
            }
            else
            {
                $dataValues = array(
                    'imagetitle' => $this->input->post('imagetitle'),
                );

                $imageid = $this->input->post('imageid');

                if (!empty($imageid))
                {
                    $dataValues['imageid'] = $imageid;
                }

                if (!empty($_FILES['image']['name']))
                {

                    if ($this->commonlibrary->is_file_uploaded('image'))
                    {
                        $imagename = $this->upload->upload_file("image", $gallery_image_config['upload_path'], $gallery_image_config);

                        $dataValues['imagename'] = $imagename;
//                        if (!empty($current_picture) && is_file($user_picture_config['upload_path'] . $current_picture))
//                        {
//                            unlink($user_picture_config['upload_path'] . $current_picture);
//                        }
                    }
                }

                if (!empty($_FILES['thumbimage']['name']))
                {

                    if ($this->commonlibrary->is_file_uploaded('thumbimage'))
                    {
                        $imagename = $this->upload->upload_file("thumbimage", $gallery_image_config['upload_path'], $gallery_image_config);

                        $dataValues['imagethumbname'] = $imagename;
//                        if (!empty($current_picture) && is_file($user_picture_config['upload_path'] . $current_picture))
//                        {
//                            unlink($user_picture_config['upload_path'] . $current_picture);
//                        }
                    }
                }

                $last_id = $this->Gallery_model->savegallery($dataValues);

                $this->session->set_flashdata('gallery_operation_message', lang('gallery') . ' ' . lang('saved'));

                redirect('admin/gallery/listgallery');
            }
        }

        public function deletegallery($imageid)
        {
            $this->Gallery_model->deletegallerybyid($imageid);

            $this->session->set_flashdata('gallery_operation_message', lang('gallery') . ' ' . lang('deleted'));

            redirect('admin/gallery/listgallery');
        }

        public function listgallerydata()
        {

            $this->load->library('Datatable');
            $arr = $this->config->config[$this->_gallery_listing_headers];
            $cols = array_keys($arr);

            $pagingParams = $this->datatable->get_paging_params($cols);
            $resultdata = $this->Gallery_model->getallgallery($pagingParams);

            $json_output = $this->datatable->get_json_output($resultdata, $this->_gallery_listing_headers);
            $this->load->setTemplate('json');
            $this->load->view('json', $json_output);
        }

        public function listgallery()
        {
            $this->load->library('Datatable');

            $message = $this->session->flashdata('gallery_operation_message');

            $table_config = array(
                'source' => site_url('admin/gallery/listgallerydata'),
                'datatable_class' => $this->config->config["datatable_class"]
            );

            $dataArray = array(
                'table' => $this->datatable->make_table($this->_gallery_listing_headers, $table_config),
                'message' => $message
            );

            $dataArray['template_title'] = lang('list') . ' ' . lang('gallery') . ' | ' . lang('SITENAME');


            $dataArray['local_css'] = array(
                'jquery.dataTables.bootstrap',
            );

            $dataArray['local_js'] = array(
                'jquery.dataTables',
                'jquery.dataTables.bootstrap',
                'dataTables.fnFilterOnReturn',
            );

            $dataArray['table_heading'] = lang('list') . ' ' . lang('gallery');
            $dataArray['new_entry_link'] = base_url() . 'admin/gallery/addgallery';
            $dataArray['setorder'] = base_url() . 'admin/gallery/setorder';
            $dataArray['new_entry_caption'] = lang('new') . ' ' . lang('gallery');
            $dataArray['set_order_cap'] =  lang('setorder');

            $this->load->view('/gallery-list', $dataArray);
        }

        public function setorder()
        {
            $dataArray['back_link'] = base_url() . 'admin/gallery/listgallery';
            $arr_gallery = $this->Gallery_model->getallgallery_array();

            $dataArray = array(
                "arr_gallery" => $arr_gallery,
            );

            $dataArray['local_js'] = array(
                'jquery-ui',
            );
            $dataArray['local_css'] = array(
                'jquery-ui',
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
                    $image_id = str_replace("image_id_", "", $value);
                    $this->Gallery_model->setorder($image_id, $tmp_orderid);
                }
            }
            $this->load->setTemplate('json');
            $this->load->view('json', array("status" => "success"));
//            $this->load->view('/gallery-set-order', $dataArray);
        }

    }
    