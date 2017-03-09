<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class User extends My_Controller
    {

        public function __construct()
        {
            parent::__construct();
            $this->load->helper('language');
            $this->load->helper('url');
            $this->userdata = GetLoggedinUserData();
            $this->load->model('Useraccount_model');
        }

        public function userLogin()
        {
            $this->load->library('form_validation');
            $this->form_validation->set_rules('login_email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('login_password', 'Password', 'required');

            $dataArray = array();

            if ($this->form_validation->run() == false)
            {

                $dataArray['form_action'] = current_url();
                $dataArray['error_message'] = $this->session->flashdata('user_operation_message_error');
                $dataArray['message'] = $this->session->flashdata('user_operation_message');
                $dataArray['form_caption'] = lang('login');
                $dataArray['template_title'] = lang('user') . '  ' . lang('login') . ' | ' . SITENAME;

                $dataArray['local_js'] = array(
                    'jquery.validate',
                );
                $this->load->view('/user-login', $dataArray);
            }
            else
            {

                $email = $this->input->post('login_email');

                $userRecord = $this->Useraccount_model->validateuser($this->input->post('login_email'), $this->input->post('login_password'));
                if (!empty($userRecord))
                {
                    $status = $userRecord->status;

                    if ($status == "Active" || $status == '')
                    {
                        $data = $userRecord;
                        $arr_userdata = array(
                            'my_userdata' => $data
                        );
                        $this->session->set_userdata($arr_userdata);

                        $login_referrer = getuserlogin_referrer();
                        redirect($login_referrer);
                    }
                    else if ($status == "Blocked")
                    {
                        $login_error_message_2 = lang('login_error_message_2');
                        $this->session->set_flashdata('user_operation_message_error', $login_error_message_2);
                        redirect(base_url() . 'user-login');
                    }
                }
                else
                {
                    $login_error_message_3 = lang('login_error_message_3');
                    $this->session->set_flashdata('user_operation_message_error', $login_error_message_3);
                    redirect(base_url() . 'user-login');
                }
            }
        }

        public function userLogout()
        {
            $this->session->unset_userdata('my_userdata');
            $this->session->unset_userdata('user_login_referrer');
            $this->session->set_flashdata('user_operation_message', lang('logged_out_successfully'));
            redirect('/login');
        }

        public function userDashboard()
        {
            $data = array();
            $this->load->model('Cms_model');
            $dataArray['sidebar'] = $this->commonlibrary->getusersidebar();

            $language = getLanguage();

            $professionalcontent = $this->Cms_model->getcmscontent('user-profile-data-sidebar', $language);
            if (!empty($professionalcontent))
            {
                $dataArray['only_professionals'] = $professionalcontent->pagecontent;
            }

            //$dataArray['only_professionals'] = $this->commonlibrary->getonlyprofessionals();
            $dataArray['message'] = $this->session->flashdata('user_operation_message');
            $dataArray['error_message'] = $this->session->flashdata('user_operation_message_error');
            $this->load->view('user-dashboard', $dataArray);
        }

        public function userProfileData()
        {
            $this->load->library('form_validation');
            $this->load->library('Commonlibrary');
            $this->load->model('Useraccount_model');
            $this->load->model('Cms_model');
            $this->load->library("Upload");
            $userdata = $this->userdata;
            $userid = $userdata['userid'];
            $dataArray = array();

            $this->form_validation->set_rules('name', lang('name'), 'required|min_length[3]|max_length[100]');
            $this->form_validation->set_rules('email', lang('email'), 'required|trim|min_length[6]|max_length[50]|unique[users.email.userid.' . $userid . ']');
            $this->form_validation->set_rules('mainphone', lang('mainphone'), 'required|min_length[8]|max_length[20]');
            $this->form_validation->set_rules('surname', lang('surname'), 'required|min_length[3]|max_length[100]');
            $this->form_validation->set_rules('user_picture', lang("picture"), 'callback_checkfile[user_picture]');

            $user_picture_config = getCustomConfigItem('user_picture');

            if ($this->form_validation->run() == FALSE)
            {
                $dataArray['form_action'] = current_url();
                $dataArray['template_title'] = lang('profile_data') . ' | ' . lang('SITENAME');
                $dataArray['sidebar'] = $this->commonlibrary->getusersidebar();


                $language = getLanguage();
                $professionalcontent = $this->Cms_model->getcmscontent('user-profile-data-sidebar', $language);
                if (!empty($professionalcontent))
                {
                    $dataArray['only_professionals'] = $professionalcontent->pagecontent;
                }

                //$dataArray['only_professionals'] = $this->commonlibrary->getonlyprofessionals();
                $dataArray['message'] = $this->session->flashdata('user_operation_message');
                $filterArr = array(
                    'userid' => $userid
                );
                $userrecord = $this->Useraccount_model->getuserrecord($filterArr);

                $dataArray['name'] = $userrecord->name;
                $dataArray['email'] = $userrecord->email;
                $dataArray['mainphone'] = $userrecord->mainphone;
                $dataArray['surname'] = $userrecord->surname;
                $picture = $userrecord->picture;

                if (is_file($user_picture_config['upload_path'] . $picture))
                {
                    $dataArray['picture_path'] = base_url() . $user_picture_config['upload_path'] . $picture;
                }


                $dataArray['local_css'] = array(
                    'select2',
                    'dropify',
                );

                $dataArray['local_js'] = array(
                    'jquery.validate.min',
                    'select2',
                    'dropify',
                );


                $this->load->view('user-profile-data', $dataArray);
            }
            else
            {
                $image_record = $this->Useraccount_model->getuserrecord(array("userid" => $userid), "picture");
                $current_picture = $image_record->picture;
                $password = $this->input->post('password');

                $dataValues = array(
                    'userid' => $userid,
                    'name' => $this->input->post('name'),
                    'email' => $this->input->post('email'),
                    'mainphone' => $this->input->post('mainphone'),
                    'surname' => $this->input->post('surname'),
                    'updatedat' => date('Y-m-d H:i:s')
                );

                if (!empty($password))
                {
                    $dataValues['password'] = md5($password);
                }

                if (!empty($_FILES['user_picture']['name']))
                {

                    if ($this->commonlibrary->is_file_uploaded('user_picture'))
                    {
                        $new_client_image = $this->upload->upload_file("user_picture", $user_picture_config['upload_path'], $user_picture_config);

                        $dataValues['picture'] = $new_client_image;
                        if (!empty($current_picture) && is_file($user_picture_config['upload_path'] . $current_picture))
                        {
                            unlink($user_picture_config['upload_path'] . $current_picture);
                        }
                    }
                }

                $last_id = $this->Useraccount_model->saveuser($dataValues);
                $this->session->set_flashdata('user_operation_message', lang('data') . ' ' . lang('saved'));
                redirect('profile-data');
            }
        }

        function checkfile($field, $module)
        {
            $file_config = getCustomConfigItem($module);
            $this->upload->init_config($file_config);
            if ($this->upload->validate_upload("checkfile", $module) == false)
            {
                return FALSE;
            }
            else
            {
                return TRUE;
            }
        }

        public function userForgotPassword()
        {

            $dataArray = array();
            $this->load->library('form_validation');
            $this->load->model('Emailtemplate_model');

            $this->form_validation->set_rules('email', lang("email"), 'required|trim|valid_email|max_length[50]|callback_checkvalidemail');

            if ($this->form_validation->run() == false)
            {
                $dataArray['form_action'] = current_url();
                $message = $this->session->flashdata('password_operation_message');
                $dataArray['message'] = $message;
                $dataArray['form_caption'] = lang('user_forgot_password');
                $dataArray['template_title'] = lang('user_forgot_password') . ' | ' . SITENAME;

                $dataArray['local_js'] = array(
                    'jquery.validate',
                );
                $this->load->view('/user-forgot-password', $dataArray);
            }
            else
            {
                $email = $this->input->post('email');
                $filterArr = array(
                    'email' => $email
                );
                $userRecord = $this->Useraccount_model->getuserrecord($filterArr);

                $userid = $userRecord->userid;
                $fullname = $userRecord->name;
                $email = $userRecord->email;
                $password = $this->commonlibrary->generatePassword(9, 2);

                $dataValues = array(
                    'userid' => $userid,
                    'password' => md5($password)
                );

                $this->Useraccount_model->saveuser($dataValues);

                $templateRecord = $this->Emailtemplate_model->getEmailTemplate('Forgot Password Email');

                if (!empty($templateRecord))
                {
                    $content = htmlspecialchars_decode($templateRecord->content);
                    $subject = $templateRecord->subject;
                }

                $from_name = EMAIL_FROM_NAME;
                $from_email = EMAIL_FROM_EMAIL;

                $arr_placeholders = array("{{fullname}}", "{{sitename}}", "{{email}}", "{{password}}");
                $arr_placeholders_values = array($fullname, SITENAME, $email, $password);
                $content = str_replace($arr_placeholders, $arr_placeholders_values, $content);

                $this->commonlibrary->sendmail($email, null, $subject, $content, "html", $from_name, $from_email);


                $this->session->set_flashdata('password_operation_message', lang('password_operation_message'));

                redirect('/forgot-password');
            }
        }

        public function getUserDetails()
        {
            $userid = $this->input->post('userid');

            if (!empty($userid) && is_numeric($userid))
            {
                $this->load->model('Useraccount_model');
                $filterArr = array(
                    'userid' => $userid
                );
                $userrecord = $this->Useraccount_model->getuserrecord($filterArr, $fields = "name,email,mainphone,surname");
                $data = array(
                    'userrecord' => $userrecord
                );
            }
            else
            {
                $data = array(
                    'userrecord' => array()
                );
            }

            echo json_encode($data);
        }

        function checkvalidemail($field)
        {
            $filterArr = array(
                'email' => $field
            );
            $userRecord = $this->Useraccount_model->getuserrecord($filterArr);
            if (empty($userRecord))
            {
                $this->form_validation->set_message('checkvalidemail', lang('forgot_password_operation_error_message'));
                return FALSE;
            }
            else
            {
                return TRUE;
            }
        }

    }
    