<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Useraccount extends My_Controller
    {

        private $_user_listing_headers = 'user_listing_headers';

        public function __construct()
        {
            parent::__construct();
            $this->load->helper('language');
            $this->load->helper('url');
            $this->load->model('Useraccount_model');
        }

        public function adduser($userid = null)
        {
            $this->load->library('form_validation');
            $this->load->library('Commonlibrary');
            $this->load->model('Service_model');
            $this->load->model('City_model');
            $this->load->library("Upload");

            $this->form_validation->set_rules('name', lang('name'), 'required|min_length[3]|max_length[100]');
            $this->form_validation->set_rules('email', lang('email'), 'required|trim|min_length[6]|max_length[50]|unique[users.email.userid.' . $this->input->post('userid') . ']');
            $required_if = empty($userid) ? '|required' : '';
            $this->form_validation->set_rules('password', lang("password"), 'trim' . $required_if . '|matches[confirm_password]');
            $this->form_validation->set_rules('confirm_password', lang("confirm_password"), 'trim');
            $this->form_validation->set_rules('mainphone', lang('mainphone'), 'required|min_length[8]|max_length[20]');
            $this->form_validation->set_rules('surname', lang('surname'), 'required|min_length[3]|max_length[100]');

            $user_picture_config = getCustomConfigItem('user_picture');

            

            if ($this->form_validation->run() == FALSE)
            {
                $dataArray = array();
                $dataArray['form_caption'] = lang('add') . ' ' . lang('user');
                $dataArray['form_action'] = current_url();
                $dataArray['back_link'] = base_url() . 'admin/useraccount/listuser';
                $dataArray['template_title'] = lang('add') . ' ' . lang('user') . ' | ' . lang('SITENAME');
                $dataArray['arr_status'] = add_blank_option(getCustomConfigItem('status'), '');



                if (!empty($userid))
                {
                    $dataArray['form_caption'] = lang('edit') . ' ' . lang('user');

                    $filterArr = array(
                        'userid' => $userid
                    );
                    $userrecord = $this->Useraccount_model->getuserrecord($filterArr);
                    
                    if(empty($userrecord))
                    {
                        show_404();
                    }
                    
                    $dataArray['userid'] = $userid;
                    $dataArray['name'] = $userrecord->name;
                    $dataArray['email'] = $userrecord->email;
                    $dataArray['mainphone'] = $userrecord->mainphone;
                    $dataArray['surname'] = $userrecord->surname;
                    $dataArray['status'] = $userrecord->status;
                    $picture = $userrecord->picture;

                    if (is_file($user_picture_config['upload_path'] . $picture))
                    {
                        $dataArray['picture_path'] = base_url() . $user_picture_config['upload_path'] . $picture;
                    }
                }


                $dataArray['local_js'] = array(
                    'jquery.validate.min',
                    'dropify',
                    'selectize'
                );
                $dataArray['local_css'] = array(
                    'dropify',
                    'selectize'
                );
                $this->load->view('/user-form', $dataArray);
            }
            else
            {
                $userid = $this->input->post('userid');
                $password = $this->input->post('password');


                $dataValues = array(
                    'name' => $this->input->post('name'),
                    'email' => $this->input->post('email'),
                    'mainphone' => $this->input->post('mainphone'),
                    'surname' => $this->input->post('surname'),
                    'status' => $this->input->post('status')
                );

                if (!empty($password))
                {
                    $dataValues['password'] = md5($password);
                }

                if (!empty($userid))
                {
                    $dataValues['userid'] = $userid;
                    $dataValues['updatedat'] = date('Y-m-d H:i:s');
                    $image_record = $this->Useraccount_model->getuserrecord(array("userid" => $userid), "picture");
                    $current_picture = $image_record->picture;
                }
                else
                {
                    $dataValues['createdat'] = date('Y-m-d H:i:s');
                }


                if (!empty($_FILES['picture']['name']))
                {

                    if ($this->commonlibrary->is_file_uploaded('picture'))
                    {
                        $new_client_image = $this->upload->upload_file("picture", $user_picture_config['upload_path'], $user_picture_config);
                        
                        $dataValues['picture'] = $new_client_image;
                        if (!empty($current_picture) && is_file($user_picture_config['upload_path'] . $current_picture))
                        {
                            unlink($user_picture_config['upload_path'] . $current_picture);
                        }
                    }
                }
                
                //p($dataValues);



                $last_id = $this->Useraccount_model->saveuser($dataValues);

                $this->session->set_flashdata('user_operation_message', lang('user') . ' ' . lang('saved'));

                redirect('admin/useraccount/listuser');
            }
        }

        public function deleteuser($userid)
        {

            $image_record = $this->Useraccount_model->getuserrecord(array("userid" => $userid), "picture");
            $current_picture = $image_record->picture;

            $user_picture_config = getCustomConfigItem('user_picture');

            if (!empty($current_picture) && is_file($user_picture_config['upload_path'] . $current_picture))
            {
                unlink($user_picture_config['upload_path'] . $current_picture);
            }

            $this->Useraccount_model->deleteuserbyid($userid);

            $this->session->set_flashdata('user_operation_message', lang('user') . ' ' . lang('deleted'));

            redirect('admin/useraccount/listuser');
        }

        public function listuserdata()
        {

            $this->load->library('Datatable');
            $arr = $this->config->config[$this->_user_listing_headers];
            $cols = array_keys($arr);

            $pagingParams = $this->datatable->get_paging_params($cols);
            $resultdata = $this->Useraccount_model->getalluser($pagingParams);

            $json_output = $this->datatable->get_json_output($resultdata, $this->_user_listing_headers);
            $this->load->setTemplate('json');
            $this->load->view('json', $json_output);
        }

        public function listuser()
        {

            $this->load->library('Datatable');

            $message = $this->session->flashdata('user_operation_message');



            $table_config = array(
                'source' => site_url('admin/useraccount/listuserdata'),
                'datatable_class' => $this->config->config["datatable_class"]
            );

            $dataArray = array(
                'table' => $this->datatable->make_table($this->_user_listing_headers, $table_config),
                'message' => $message
            );

            $dataArray['template_title'] = lang('list') . ' ' . lang('user') . ' | ' . lang('SITENAME');


            $dataArray['local_css'] = array(
                'jquery.dataTables.bootstrap',
            );

            $dataArray['local_js'] = array(
                'jquery.dataTables',
                'jquery.dataTables.bootstrap',
                'dataTables.fnFilterOnReturn',
            );

            $dataArray['table_heading'] = lang('list') . ' ' . lang('user');
            $dataArray['new_entry_link'] = base_url() . 'admin/useraccount/adduser';
            $dataArray['new_entry_caption'] = lang('new') . ' ' . lang('user');

            $this->load->view('/user-list', $dataArray);
        }

    }
    