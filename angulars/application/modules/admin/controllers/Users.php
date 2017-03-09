<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Users extends MY_Controller
    {

        public function __construct()
        {
            parent::__construct();

            $this->load->setTemplate('blank');
            $this->load->library('commonlib');
            $this->load->library('commonlib');
            $this->load->library('session');
            $this->load->helper('language');
        }

        public function index()
        {
            redirect('/admin/users/validate/');
        }

        public function dashboard()
        {
            $dataArray = array();
            $this->load->setTemplate('admin');
            $this->load->helper('url');
            $errors = $this->session->flashdata('access_error_message');
            $dataArray['errors'] = $errors;
            $dataArray['template_title'] = lang('dashboard') . ' | ' . lang('SITENAME');
            $this->load->view('dashboard', $dataArray);
        }

        public function login()
        {
            $this->load->library('form_validation');
            $message = $this->session->flashdata('login_operation_message');
            $this->load->view('login-form', array("message" => $message));
        }

        public function logout()
        {
            $this->load->library('Simplelogin');
            $this->load->helper('url');
            $this->simplelogin->logout();
            redirect('admin/users/login', 'refresh');
        }

        public function validate()
        {
            $this->load->helper('url');
            $this->load->library('form_validation');
            $dataArray[] = array();

            $this->form_validation->set_rules('username', 'Username', 'required|min_length[4]|max_length[32]');
            $this->form_validation->set_rules('password', 'Password', 'required|min_length[4]|max_length[32]');

            if ($this->form_validation->run() == false)
            {

                $this->session->set_flashdata('login_operation_message', lang('login_error_message_3'));
                $this->load->view('login-form', $dataArray);
            }
            else
            {
                $this->load->library('Simplelogin');


                if ($this->simplelogin->login($this->input->post('username'), $this->input->post('password')))
                {
                    redirect('/admin/dashboard');
                }
                else
                {

                    $this->session->set_flashdata('login_operation_message', lang('login_error_message_3'));
                    //$message = $this->session->flashdata('login_operation_message');
                    $this->load->view('login-form');
                    //redirect('admin/users/login', 'refresh');
                }
            }
        }

        public function login_validate()
        {
            $username = $this->input->post('user_name');
            $password = $this->input->post('user_password');
            $session_id = $this->input->post('session_id');
            $data = array();


            $data['total_results'] = $isUserValid ? 1 : 0;
            $this->load->view(false, $data, false, 'json');
        }

    }

    /* End of file users.php */