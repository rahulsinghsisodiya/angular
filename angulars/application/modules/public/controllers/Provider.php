<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Provider extends My_Controller
    {

        private $_provider_listing_headers = 'provider_listing_headers';

        public function __construct()
        {
            parent::__construct();
            $this->load->helper('language');
            $this->load->helper('url');
            $this->load->model('Provider_model');
            $this->load->library("Upload");
            $this->provider_data = GetLoggedinProviderData();
        }

        public function sendActivationEmail($email, $token)
        {

            $this->load->model('Emailtemplate_model');

            $filterArr = array(
                'providers.email' => $email
            );
            $providerRecord = $this->Provider_model->getproviderrecord($filterArr);
            $fullname = $providerRecord->providername;
            $email_to = $email;

            $templateRecord = $this->Emailtemplate_model->getemailtemplate('Signup Email');
            if (!empty($templateRecord))
            {
                $content = htmlspecialchars_decode($templateRecord->content);
                $subject = $templateRecord->subject;
            }

            $from_name = EMAIL_FROM_NAME;
            $from_email = EMAIL_FROM_EMAIL;

            $activation_url = base_url() . 'activate-provider/' . $token;

            $arr_placeholders = array("{{fullname}}", "{{sitename}}", "{{activation_url}}");
            $arr_placeholders_values = array($fullname, SITENAME, $activation_url);
            $content = str_replace($arr_placeholders, $arr_placeholders_values, $content);
            $this->commonlibrary->sendmail($email_to, $fullname, $subject, $content, "html", $from_name, $from_email);
        }

        public function providerRegister()
        {
            $this->load->library('form_validation');
            $this->load->library('Commonlibrary');
            $this->load->model('Service_model');
            $this->load->model('Provider_model');
            $this->load->model('Location_model');
            $this->load->model('City_model');
            $this->load->model('Region_model');
            $this->load->model('Cms_model');
            $this->load->library("Upload");

            $this->form_validation->set_rules('providername', lang('providername'), 'required|min_length[3]|max_length[100]');
            $this->form_validation->set_rules('email', lang('email'), 'required|trim|min_length[6]|max_length[50]|unique[providers.email.providerid.' . $this->input->post('providerid') . ']');
            $this->form_validation->set_rules('password', lang("password"), 'required|trim|matches[confirm_password]');
            $this->form_validation->set_rules('confirm_password', lang("confirm_password"), 'trim');
            $this->form_validation->set_rules('companyname', lang('companyname'), 'required|min_length[3]|max_length[100]');
            $this->form_validation->set_rules('companytype', lang('companytype'), 'required');
            $this->form_validation->set_rules('phone', lang('phone'), 'required|min_length[8]|max_length[20]');

            $provider_picture_config = getCustomConfigItem('provider_picture');

            if ($this->form_validation->run() == FALSE)
            {
                $dataArray = array();
                $dataArray['form_caption'] = lang('provider') . ' ' . lang('register');
                $dataArray['form_action'] = current_url();
                $dataArray['template_title'] = lang('provider') . ' ' . lang('register') . ' | ' . lang('SITENAME');
                $dataArray['arr_companytype'] = getCustomConfigItem('companytype');
                $dataArray['arr_status'] = add_blank_option(getCustomConfigItem('status'), '');
                $arrservice = $this->Service_model->getallservice_subservice_array();
                $dataArray['arr_service'] = $arrservice;
                
                $arr_region = $this->Region_model->getallregion_array(array());
                $arr_city = array();

                $location_arr = $this->Location_model->getalllocation_array(array());
                $dataArray['arr_location'] = array(); // $location_arr;
                foreach ($arr_region as $key => $val)
                {
                    $filterArr = array(
                        'regionid' => $key
                    );
                    $results = $this->City_model->getallcity_array($filterArr);
                    if (!empty($results))
                    {
                        $arr_city[$val] = $results;
                    }
                }
                $dataArray['arr_city'] = $arr_city;



                $cmscontent = $this->Cms_model->getcmscontent('general-terms-and-conditions');


                if (!empty($cmscontent))
                {
                    $dataArray['pagetitle'] = $cmscontent->pagetitle;
                    $dataArray['pagecontent'] = $cmscontent->pagecontent;
                }

                $dataArray['latest_request'] = $this->load->viewPartial('latest-request', array());


                $dataArray['local_js'] = array(
                    'jquery.validate.min',
                    'dropify',
                    'selectize',
                    'multiple-select',
                    'select2'
                );
                $dataArray['local_css'] = array(
                    'dropify',
                    'selectize',
                    'multiple-select',
                    'select2'
                );
                $this->load->view('/provider-register', $dataArray);
            }
            else
            {
                $providerid = NULL;
                $password = $this->input->post('password');
                $email = $this->input->post('email');

                $deductilibityx = $this->input->post('deductilibity');
                $deductilibity = empty($deductilibityx) ? '0' : $deductilibityx;

                $requestx = $this->input->post('request');
                $request = empty($requestx) ? '0' : $requestx;


                $key = empty($providerid) ? NULL : 'providerid';
                $value = empty($providerid) ? NULL : $providerid;
                $providername = $this->input->post('providername');
                $table = 'providers';
                $slug = $this->commonlibrary->create_unique_slug($providername, $table, $field = 'providerslug', $key, $value);
                $providerslug = $slug;



                $dataValues = array(
                    'providername' => $providername,
                    'providerslug' => $providerslug,
                    'email' => $email,
                    'companyname' => $this->input->post('companyname'),
                    'companytype' => $this->input->post('companytype'),
                    'siren' => $this->input->post('siren'),
                    'phone' => $this->input->post('phone'),
                    'address' => $this->input->post('address'),
                    'zipcode' => $this->input->post('zipcode'),
                    // 'cesunumber' => $this->input->post('cesunumber'),
                    'deductilibity ' => $deductilibity,
                    'request' => $request,
                    'status' => $this->input->post('status')
                );

                if (!empty($password))
                {
                    $dataValues['password'] = md5($password);
                }

                if (!empty($providerid))
                {
                    $dataValues['providerid'] = $providerid;
                    $dataValues['updatedat'] = date('Y-m-d H:i:s');
                    $image_record = $this->Provider_model->getproviderrecord(array("providerid" => $providerid), "picture");
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
                        $new_client_image = $this->upload->upload_file("picture", $provider_picture_config['upload_path'], $provider_picture_config);
                        $dataValues['picture'] = $new_client_image;
                        if (!empty($current_picture) && is_file($provider_picture_config['upload_path'] . $current_picture))
                        {
                            unlink($provider_picture_config['upload_path'] . $current_picture);
                        }
                    }
                }

                $last_id = $this->Provider_model->saveprovider($dataValues);

                $arr_service = $this->input->post('service');
                $this->Provider_model->saveproviderservices($arr_service, $last_id);

                $arr_city = $this->input->post('city');
                $this->Provider_model->saveprovidercities($arr_city, $last_id);

                $arr_location = $this->input->post('location');
                $this->Provider_model->saveproviderlocation($arr_location, $last_id);

                $token = $this->Provider_model->getprovidertoken();
                $dataToken = array(
                    'email' => $email,
                    'token' => $token,
                );
                $this->Provider_model->saveprovidertoken($dataToken);

                $this->sendActivationEmail($email, $token);

                $providerRecord = $this->Provider_model->getproviderrecord(array('providerid' => $last_id));
                $data = $providerRecord;
                $arr_providerdata = array(
                    'my_providerdata' => $data
                );
                $this->session->set_userdata($arr_providerdata);

                $this->session->set_flashdata('provider_operation_message', lang('provider') . ' ' . lang('saved'));

                redirect('provider-activation');
            }
        }

        public function providerLogout()
        {
            $this->session->unset_userdata('my_providerdata');
            $this->session->unset_userdata('provider_login_referrer');
            $this->session->set_flashdata('activation_success_message', lang('logged_out_successfully'));
            redirect('/login');
        }

        public function providerLogin()
        { 
            $this->load->library('form_validation');
            $this->form_validation->set_rules('login_email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('login_password', 'Password', 'required');

            $dataArray = array();

            if ($this->form_validation->run() == false)
            {

                $dataArray['form_action'] = current_url();
                $login_error_message = $this->session->flashdata('provider_operation_message_error');
                $login_message = $this->session->flashdata('login_error_message');
                $dataArray['form_caption'] = lang('login');
                $dataArray['template_title'] = lang('provider') . '  ' . lang('login') . ' | ' . SITENAME;

                $errors = validation_errors();
                $dataArray['login_message'] = $login_message;
                $dataArray['login_error_message'] = $login_error_message;
                $dataArray['success_message'] = $this->session->flashdata('provider_operation_message');
                $dataArray['login_errors'] = $errors;

                $dataArray['local_js'] = array(
                    'jquery.validate',
                );
                $this->load->view('/provider-login', $dataArray);
            }
            else
            {

                $email = $this->input->post('login_email');

                $userRecord = $this->Provider_model->validateprovider($this->input->post('login_email'), $this->input->post('login_password'));
                if (!empty($userRecord))
                {
                    $status = $userRecord->status;

                    if ($status == "Active" || $status == '')
                    {
                        $data = $userRecord;
                        $arr_userdata = array(
                            'my_providerdata' => $data
                        );
                        $this->session->set_userdata($arr_userdata);

                        $login_referrer = getproviderlogin_referrer();
                        redirect($login_referrer);
                    }
                    else if ($status == "Blocked")
                    {
                        $login_error_message_2 = lang('login_error_message_2');
                        $this->session->set_flashdata('login_error_message', $login_error_message_2);
                        redirect(base_url() . 'provider-login');
                    }
                }
                else
                {
                    $login_error_message_3 = lang('login_error_message_3');
                    $this->session->set_flashdata('login_error_message', $login_error_message_3);
                    redirect(base_url() . 'provider-login');
                }
            }
        }

        public function combinedLoginx()
        {
            exit(); 
            $this->load->model('Useraccount_model');
            $this->load->library('form_validation');
            $this->form_validation->set_rules('login_email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('login_password', 'Password', 'required');

            $dataArray = array();

            if ($this->form_validation->run() == false)
            {
                $dataArray['form_action'] = current_url();
                $login_error_message = $this->session->flashdata('provider_operation_message_error');
                $login_message = $this->session->flashdata('login_error_message');
                $dataArray['form_caption'] = lang('login');
                $dataArray['template_title'] = lang('login') . ' | ' . SITENAME;

                $errors = validation_errors();
                $dataArray['login_message'] = $login_message;
                $dataArray['login_error_message'] = $login_error_message;
                $dataArray['success_message'] = $this->session->flashdata('provider_operation_message');
                $dataArray['login_errors'] = $errors;

                $dataArray['only_professionals'] = $this->commonlibrary->getonlyprofessionals();


                $dataArray['local_js'] = array(
                    'jquery.validate',
                );

                $this->load->view('/combined-login', $dataArray);
            }
            else
            {
                $email = $this->input->post('login_email');

                $providerRecord = $this->Provider_model->validateprovider($this->input->post('login_email'), $this->input->post('login_password'));
                $userRecord = $this->Useraccount_model->validateuser($this->input->post('login_email'), $this->input->post('login_password'));

                if (!empty($providerRecord) && empty($userRecord))
                {
                    $status = $providerRecord->status;

                    if ($status == "Active" || $status == '')
                    {
                        $data = $providerRecord;
                        $arr_userdata = array(
                            'my_providerdata' => $data
                        );
                        $this->session->set_userdata($arr_userdata);

                        $login_referrer = getproviderlogin_referrer();
                        redirect($login_referrer);
                    }
                    else if ($status == "Blocked")
                    {
                        $login_error_message_2 = lang('login_error_message_2');
                        $this->session->set_flashdata('login_error_message', $login_error_message_2);
                        redirect(base_url() . 'login');
                    }
                }
                else if (empty($providerRecord) && !empty($userRecord))
                {
                    $status = $userRecord->status;
                    $userRecord = $this->Useraccount_model->validateuser($this->input->post('login_email'), $this->input->post('login_password'));

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
                        $this->session->set_flashdata('login_error_message', $login_error_message_2);
                        redirect(base_url() . 'login');
                    }
                }
                else if (!empty($providerData) && !empty($userData))
                {
                    $login_email = $this->input->post('login_email');
                    $login_password = $this->input->post('login_password');


                    $this->session->set_userdata(array(
                        'login_email' => $login_email,
                        'login_password' => $login_password
                    ));

                    $this->load->view('login-type');
                }
                else
                {
                    $login_error_message_3 = lang('login_error_message_3');
                    $this->session->set_flashdata('login_error_message', $login_error_message_3);
                    redirect(base_url() . 'login');
                }
            }
        }

        public function combinedLogin()
        {
            $this->load->model('Useraccount_model');
            $this->load->model('Cms_model');
            $this->load->library('form_validation');
            $this->form_validation->set_rules('login_email', 'Email', 'required|valid_email');
            $this->form_validation->set_rules('login_password', 'Password', 'required');

            $dataArray = array();

            if ($this->form_validation->run() == false)
            {
                $dataArray['form_action'] = current_url();
                $login_error_message = $this->session->flashdata('provider_operation_message_error');
                $login_message = $this->session->flashdata('login_error_message');
                $dataArray['form_caption'] = lang('login');
                $dataArray['template_title'] = lang('login') . ' | ' . SITENAME;

                $errors = validation_errors();
                $dataArray['login_message'] = $login_message;
                $dataArray['login_error_message'] = $login_error_message;
                $dataArray['success_message'] = $this->session->flashdata('provider_operation_message');
                $dataArray['login_errors'] = $errors;

                $language = getLanguage();
                $professionalcontent = $this->Cms_model->getcmscontent('login-sidebar', $language);
                if (!empty($professionalcontent))
                {
                    $dataArray['only_professionals'] = $professionalcontent->pagecontent;
                }




                $dataArray['local_js'] = array(
                    'jquery.validate',
                );

                $this->load->view('/combined-login', $dataArray);
            }
            else
            {
                $email = $this->input->post('login_email');
                $password = $this->input->post('login_password');

                $providerData = $this->Provider_model->getproviderrecord(array('email' => $email));
                $userData = $this->Useraccount_model->getuserrecord(array('email' => $email));

                if (!empty($providerData) && empty($userData))
                {
                    $providerRecord = $this->Provider_model->validateprovider($email, $password);

                    if (!empty($providerRecord))
                    {
                        $status = $providerRecord->status;
                        if ($status == "Active" || $status == '')
                        {
                            $data = $providerRecord;
                            $arr_userdata = array(
                                'my_providerdata' => $data
                            );
                            $this->session->set_userdata($arr_userdata);

                            $login_referrer = getproviderlogin_referrer();
                            redirect($login_referrer);
                        }
                        else if ($status == "Blocked")
                        {
                            $login_error_message_2 = lang('login_error_message_2');
                            $this->session->set_flashdata('login_error_message', $login_error_message_2);
                            redirect(base_url() . 'login');
                        }
                    }
                    else
                    {
                        $login_error_message_3 = lang('login_error_message_3');
                        $this->session->set_flashdata('login_error_message', $login_error_message_3);
                        redirect(base_url() . 'login');
                    }
                }
                else if (empty($providerData) && !empty($userData))
                {
                    $userRecord = $this->Useraccount_model->validateuser($email, $password);
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
                            $this->session->set_flashdata('login_error_message', $login_error_message_2);
                            redirect(base_url() . 'login');
                        }
                    }
                    else
                    {
                        $login_error_message_3 = lang('login_error_message_3');
                        $this->session->set_flashdata('login_error_message', $login_error_message_3);
                        redirect(base_url() . 'login');
                    }
                }
                else if (!empty($providerData) && !empty($userData))
                {
                    $userRecord = $this->Useraccount_model->validateuser($email, $password);
                    $providerRecord = $this->Provider_model->validateprovider($email, $password);

                    if (!empty($providerRecord))
                    {
                        $status = $providerRecord->status;
                        if ($status == "Active" || $status == '')
                        {

                            $this->session->set_userdata(array(
                                'login_email' => $email,
                                'login_password' => $password
                            ));
                            $this->load->view('login-type');
                        }
                        elseif ($status == "Blocked")
                        {
                            $login_error_message_2 = lang('login_error_message_2');
                            $this->session->set_flashdata('login_error_message', $login_error_message_2);
                            redirect(base_url() . 'login');
                        }
                    }
                    elseif (!empty($userRecord))
                    {
                        $status = $userRecord->status;
                        if ($status == "Active" || $status == '')
                        {
                            $this->session->set_userdata(array(
                                'login_email' => $email,
                                'login_password' => $password
                            ));
                            $this->load->view('login-type');
                        }
                        else if ($status == "Blocked")
                        {
                            $login_error_message_2 = lang('login_error_message_2');
                            $this->session->set_flashdata('login_error_message', $login_error_message_2);
                            redirect(base_url() . 'login');
                        }
                    }
                    else
                    {

                        $login_error_message_3 = lang('login_error_message_3');
                        $this->session->set_flashdata('login_error_message', $login_error_message_3);
                        redirect(base_url() . 'login');
                    }
                }
                else
                {
                    $login_error_message_3 = lang('login_error_message_3');
                    $this->session->set_flashdata('login_error_message', $login_error_message_3);
                    redirect(base_url() . 'login');
                }
            }
        }

        public function loginProvider()
        {
            $email = $this->session->userdata('login_email');
            $password = $this->session->userdata('login_password');
//            $userRecord = $this->Provider_model->validateprovider($email, $password);
            $userRecord = $this->Provider_model->getproviderrecord(array('email' => $email));
            $this->session->unset_userdata('login_email');
            $this->session->unset_userdata('login_password');

            if (!empty($userRecord))
            {
                $status = $userRecord->status;

                if ($status == "Active" || $status == '')
                {

                    $data = $userRecord;
                    $arr_userdata = array(
                        'my_providerdata' => $data
                    );
                    $this->session->set_userdata($arr_userdata);

                    $login_referrer = getproviderlogin_referrer();
                    redirect($login_referrer);
                }
                else if ($status == "Blocked")
                {
                    $login_error_message_2 = lang('login_error_message_2');
                    $this->session->set_flashdata('login_error_message', $login_error_message_2);
                    redirect(base_url() . 'login');
                }
            }
            else
            {
                $login_error_message_3 = lang('login_error_message_3');
                $this->session->set_flashdata('login_error_message', $login_error_message_3);
                redirect(base_url() . 'login');
            }
        }

        public function loginUser()
        {
            $this->load->model('Useraccount_model');
            $login_email = $this->session->userdata('login_email');
            $login_password = $this->session->userdata('login_password');
//            $userRecord = $this->Useraccount_model->validateuser($login_email, $login_password);
            $userRecord = $this->Useraccount_model->getuserrecord(array('email' => $login_email));
            $this->session->unset_userdata('login_email');
            $this->session->unset_userdata('login_password');

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
                    $this->session->set_flashdata('login_error_message', $login_error_message_2);
                    redirect(base_url() . 'login');
                }
            }
            else
            {
                $login_error_message_3 = lang('login_error_message_3');
                $this->session->set_flashdata('login_error_message', $login_error_message_3);
                redirect(base_url() . 'login');
            }
        }

        public function encryption($string)
        {
            $key = 'rumors';
            $iv = mcrypt_create_iv(
                mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC), MCRYPT_DEV_URANDOM
            );

            $encrypted = base64_encode(
                $iv .
                mcrypt_encrypt(
                    MCRYPT_RIJNDAEL_128, hash('sha256', $key, true), $string, MCRYPT_MODE_CBC, $iv
                )
            );

            return $encrypted;
        }

        public function decryption($encrypted)
        {
            $key = 'rumors';
            $data = base64_decode($encrypted);
            $iv = substr($data, 0, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC));

            $decrypted = rtrim(
                mcrypt_decrypt(
                    MCRYPT_RIJNDAEL_128, hash('sha256', $key, true), substr($data, mcrypt_get_iv_size(MCRYPT_RIJNDAEL_128, MCRYPT_MODE_CBC)), MCRYPT_MODE_CBC, $iv
                ), "\0"
            );

            return $decrypted;
        }

        public function providerDashboard()
        {
            $dataArray = array();
            $provider_data = $this->provider_data;
            $providerid = $provider_data['providerid'];
            $filterArr = array(
                'providerid' => $providerid
            );
            $providerrecord = $this->Provider_model->getproviderrecord($filterArr);
            $data['providername'] = $providerrecord->providername;
            $data['status'] = $providerrecord->status;

            $dataArray['sidebar'] = $this->commonlibrary->getprovidersidebar();
            $dataArray['latest_request'] = $this->load->viewPartial('latest-request', $data);
            $dataArray['message'] = $this->session->flashdata('provider_operation_message');
            $dataArray['error_message'] = $this->session->flashdata('provider_operation_message_error');
            $this->load->view('provider-dashboard', $dataArray);
        }

        public function servicesOffer()
        {
            $this->load->library('form_validation');
            $this->load->library('Commonlibrary');
            $this->load->model('Service_model');
            $this->load->model('Location_model');
            $this->load->model('Provider_model');
            $this->load->model('Cms_model');
            $this->load->model('City_model');
            $this->load->model('Region_model');
            $this->load->library("Upload");

            $provider_data = $this->provider_data;
            $providerid = $provider_data['providerid'];

            $this->form_validation->set_rules('service[]', lang('service'), 'required');
            $this->form_validation->set_rules('city[]', lang('area'), 'required');
           // $this->form_validation->set_rules('location[]', lang('location'), 'required');


            if ($this->form_validation->run() == FALSE)
            {
                $dataArray = array();
                $dataArray['form_caption'] = lang('service_i_offer');
                $dataArray['form_action'] = current_url();
                $dataArray['template_title'] = lang('service_i_offer') . ' | ' . lang('SITENAME');
                $arrservice = $this->Service_model->getallservice_subservice_array();
                $dataArray['arr_service'] = $arrservice;
                $dataArray['message'] = $this->session->flashdata('provider_operation_message');
                $location_arr = $this->Location_model->getalllocation_array(array());
                $dataArray['arr_location'] = $location_arr;
                $dataArray['location'] = $this->Provider_model->getselectedlocation_array($providerid);
                $arr_region = $this->Region_model->getallregion_array(array());
                $arr_city = array();
                foreach ($arr_region as $key => $val)
                {
                    $filterArr = array(
                        'regionid' => $key
                    );
                    $results = $this->City_model->getallcity_array($filterArr);
                    if (!empty($results))
                    {
                        $arr_city[$val] = $results;
                    }
                }
                $dataArray['arr_city'] = $arr_city;



                $dataArray['arr_selected_service'] = $this->Provider_model->getselectedservices_array($providerid);
                $citydata = $this->Provider_model->getselectedcities_array($providerid);
                $dataArray['arr_selected_city'] = $citydata['data2'];


                $filterArr = array(
                    'providerid' => $providerid
                );
                $providerrecord = $this->Provider_model->getproviderrecord($filterArr);
                $data['providername'] = $providerrecord->providername;
                $data['status'] = $providerrecord->status;

                $dataArray['sidebar'] = $this->commonlibrary->getprovidersidebar();


                $language=  getLanguage();
                $professionalcontent = $this->Cms_model->getcmscontent('service-i-offer-sidebar', $language);

                if (!empty($professionalcontent))
                {
                    $dataArray['latest_request'] = $professionalcontent->pagecontent;
                }

                //$dataArray['latest_request'] = $this->load->viewPartial('latest-request', $data);



                $dataArray['local_js'] = array(
                    'jquery.validate.min',
                    'dropify',
                    'selectize',
                    'multiple-select',
                    'select2'
                );
                $dataArray['local_css'] = array(
                    'dropify',
                    'selectize',
                    'multiple-select',
                    'select2'
                );
                
                $this->load->view('/services-offer', $dataArray);
            }
            else
            {
                $arr_service = $this->input->post('service');
                $this->Provider_model->saveproviderservices($arr_service, $providerid);

                $arr_city = $this->input->post('city');
                $this->Provider_model->saveprovidercities($arr_city, $providerid);

                
                $arr_location = $this->input->post('location');
                $this->Provider_model->saveproviderlocation($arr_location, $providerid);
                
                
                $this->session->set_flashdata('provider_operation_message', lang('data') . ' ' . lang('saved'));

                redirect('services-offer');
            }
        }

        public function providerUserData()
        {
            $provider_data = $this->provider_data;
            $providerid = $provider_data['providerid'];
            $this->load->library('form_validation');
            $this->form_validation->set_rules('provider_picture', lang("picture"), 'callback_checkfile[provider_picture]');
            $provider_picture_config = getCustomConfigItem('provider_picture');

            if ($this->form_validation->run() == FALSE)
            {

                $dataArray['form_caption'] = lang('user_data');
                $dataArray['form_action'] = current_url();
                $dataArray['template_title'] = lang('user_data') . ' | ' . lang('SITENAME');
                $dataArray['arr_companytype'] = getCustomConfigItem('companytype');
                $dataArray['message'] = $this->session->flashdata('provider_operation_message');


                $filterArr = array(
                    'providerid' => $providerid
                );
                $providerrecord = $this->Provider_model->getproviderrecord($filterArr);
                $data['providername'] = $providerrecord->providername;
                $data['status'] = $providerrecord->status;
                $dataArray['sidebar'] = $this->commonlibrary->getprovidersidebar();
                $dataArray['latest_request'] = $this->load->viewPartial('latest-request', $data);

                $dataArray['providerrecord'] = $providerrecord;
                $dataArray['providerimage'] = '';
                $upload_path = base_url() . $provider_picture_config['upload_path'];
                $default_image = $provider_picture_config['default_image'];
                $provider_image = $providerrecord->picture;
                if (!empty($provider_image))
                {
                    $dataArray['providerimage'] = $upload_path . $provider_image;
                }
                else
                {
                    $dataArray['providerimage'] = $upload_path . $default_image;
                }

                $dataArray['local_js'] = array(
                    'jquery.validate.min',
                    'select2',
                    'jquery-ui-1.12.1.custom',
                    'blockUI'
                );
                $dataArray['local_css'] = array(
                    'select2',
                    'jquery-ui-1.12.1.custom',
                    'blockUI'
                );
                $this->load->view('/provider-user-data', $dataArray);
            }
            else
            {

                $dataValues['providerid'] = $providerid;
                $dataValues['updatedat'] = date('Y-m-d H:i:s');
                $image_record = $this->Provider_model->getproviderrecord(array("providerid" => $providerid), "picture");
                $current_picture = $image_record->picture;

                if (!empty($_FILES['provider_picture']['name']))
                {

                    if ($this->commonlibrary->is_file_uploaded('provider_picture'))
                    {
                        $new_client_image = $this->upload->upload_file("provider_picture", $provider_picture_config['upload_path'], $provider_picture_config);
                        $dataValues['picture'] = $new_client_image;
                        if (!empty($current_picture) && is_file($provider_picture_config['upload_path'] . $current_picture))
                        {
                            unlink($provider_picture_config['upload_path'] . $current_picture);
                        }
                    }
                }
                $last_id = $this->Provider_model->saveprovider($dataValues);
                $this->session->set_flashdata('provider_operation_message', lang('picture') . ' ' . lang('saved'));
                redirect('my-user-data');
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

        public function providerProfileData()
        {
            $this->load->library('form_validation');
            $this->load->library('Commonlibrary');
            $this->load->model('Service_model');
            $this->load->model('City_model');
            $this->load->library("Upload");
            $provider_data = $this->provider_data;
            $providerid = $provider_data['providerid'];


            $this->form_validation->set_rules('providername', lang('providername'), 'required|min_length[3]|max_length[100]');
            $this->form_validation->set_rules('email', lang('email'), 'required|trim|min_length[6]|max_length[50]|unique[providers.email.providerid.' . $providerid . ']');
            $this->form_validation->set_rules('password', lang("password"), 'trim|matches[confirm_password]');
            $this->form_validation->set_rules('confirm_password', lang("confirm_password"), 'trim');
            $this->form_validation->set_rules('companyname', lang('companyname'), 'required|min_length[3]|max_length[100]');
            $this->form_validation->set_rules('companytype', lang('companytype'), 'required');
            $this->form_validation->set_rules('phone', lang('phone'), 'required|min_length[8]|max_length[20]');
            $this->form_validation->set_rules('agreementnumber', lang('agreement_number'), 'trim|max_length[100]');


            if ($this->form_validation->run() == FALSE)
            {
                $dataArray = array();
                $dataArray['form_caption'] = lang('profile_data');
                $dataArray['form_action'] = current_url();
                $dataArray['template_title'] = lang('profile_data') . ' | ' . lang('SITENAME');
                $dataArray['arr_companytype'] = getCustomConfigItem('companytype');
                $dataArray['message'] = $this->session->flashdata('provider_operation_message');

                if (!empty($providerid))
                {

                    $filterArr = array(
                        'providerid' => $providerid
                    );
                    $providerrecord = $this->Provider_model->getproviderrecord($filterArr);
                    $dataArray['providername'] = $providerrecord->providername;
                    $dataArray['email'] = $providerrecord->email;
                    $dataArray['companyname'] = $providerrecord->companyname;
                    $dataArray['companytype'] = $providerrecord->companytype;
                    $dataArray['siren'] = $providerrecord->siren;
                    $dataArray['phone'] = $providerrecord->phone;
                    $dataArray['address'] = $providerrecord->address;
                    $dataArray['zipcode'] = $providerrecord->zipcode;
                    $dataArray['cesunumber'] = $providerrecord->cesunumber;
                    $dataArray['deductilibity'] = $providerrecord->deductilibity;
                    $dataArray['request'] = $providerrecord->request;
                    $dataArray['agreementnumber'] = $providerrecord->agreementnumber;
                }


                $filterArr = array(
                    'providerid' => $providerid
                );
                $providerrecord = $this->Provider_model->getproviderrecord($filterArr);
                $data['providername'] = $providerrecord->providername;
                $data['status'] = $providerrecord->status;
                $dataArray['sidebar'] = $this->commonlibrary->getprovidersidebar();
                $dataArray['latest_request'] = $this->load->viewPartial('latest-request', $data);

                $dataArray['local_js'] = array(
                    'jquery.validate.min',
                    'select2',
                    'selectize'
                );
                $dataArray['local_css'] = array(
                    'select2',
                    'selectize'
                );
                $this->load->view('/provider-profile-data', $dataArray);
            }
            else
            {

                $password = $this->input->post('password');

                $deductilibityx = $this->input->post('deductilibity');
                $deductilibity = empty($deductilibityx) ? '0' : $deductilibityx;

                $requestx = $this->input->post('request');
                $request = empty($requestx) ? '0' : $requestx;


                $key = empty($providerid) ? NULL : 'providerid';
                $value = empty($providerid) ? NULL : $providerid;
                $providername = $this->input->post('providername');
                $table = 'providers';
                $slug = $this->commonlibrary->create_unique_slug($providername, $table, $field = 'providerslug', $key, $value);
                $providerslug = $slug;



                $dataValues = array(
                    'providername' => $providername,
                    'providerslug' => $providerslug,
                    'email' => $this->input->post('email'),
                    'companyname' => $this->input->post('companyname'),
                    'companytype' => $this->input->post('companytype'),
                    'siren' => $this->input->post('siren'),
                    'phone' => $this->input->post('phone'),
                    'address' => $this->input->post('address'),
                    'zipcode' => $this->input->post('zipcode'),
                    'cesunumber' => $this->input->post('cesunumber'),
                    'deductilibity ' => $deductilibity,
                    'agreementnumber' => $this->input->post('agreementnumber'),
                    'request' => $request,
                    'updatedat' => date('Y-m-d H:i:s'),
                    'providerid' => $providerid
                );

                if (!empty($password))
                {
                    $dataValues['password'] = md5($password);
                }

                $last_id = $this->Provider_model->saveprovider($dataValues);


                $this->session->set_flashdata('provider_operation_message', lang('data') . ' ' . lang('saved'));

                redirect('my-profile-data');
            }
        }

        public function providerForgotPassword()
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
                $dataArray['form_caption'] = lang('provider_forgot_password');
                $dataArray['template_title'] = lang('provider_forgot_password') . ' | ' . SITENAME;

                $dataArray['local_js'] = array(
                    'jquery.validate',
                );
                $this->load->view('/provider-forgot-password', $dataArray);
            }
            else
            {
                $email = $this->input->post('email');
                $filterArr = array(
                    'email' => $email
                );
                $providerRecord = $this->Provider_model->getproviderrecord($filterArr);
               
                $providerid = $providerRecord->providerid;
                $fullname = $providerRecord->providername;
                $email = $providerRecord->email;
                $password = $this->commonlibrary->generatePassword(9, 2);
                p($password);
                $dataValues = array(
                    'providerid' => $providerid,
                    'password' => md5($password)
                );

                $this->Provider_model->saveprovider($dataValues);

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


                $this->session->set_flashdata('password_operation_message', lang('forgot_password_operation_message'));

                redirect('/provider-forgot-password');
            }
        }

        function checkvalidemail($field)
        {
            $filterArr = array(
                'email' => $field
            );
            $providerRecord = $this->Provider_model->getproviderrecord($filterArr);
            if (empty($providerRecord))
            {
                $this->form_validation->set_message('checkvalidemail', lang('forgot_password_operation_error_message'));
                return FALSE;
            }
            else
            {
                return TRUE;
            }
        }

        public function providerActivation()
        {
            $dataArray = array();
            $dataArray['message'] = $this->session->flashdata('provider_operation_message');
            $dataArray['latest_request'] = $this->load->viewPartial('latest-request', array());

            $this->load->view('provider-activation', $dataArray);
        }

        public function providerEmailExists()
        {


            if (array_key_exists('email', $_POST))
            {
                if ($this->commonlibrary->provideremailexists($this->input->post('email')) == TRUE)
                {
                    echo json_encode(FALSE);
                }
                else
                {
                    echo json_encode(TRUE);
                }
            }
        }

        public function activateProvider($token)
        {
            $dataValues = array(
                'token' => $token,
            );

            $result = $this->Provider_model->activateProvider($dataValues);


            if ($result == true)
            {
                $this->session->set_flashdata('provider_operation_message', lang('activation_success_message'));
            }
            else
            {
                $this->session->set_flashdata('provider_operation_message_error', lang('activation_error_message'));
            }

            redirect(base_url() . 'provider-login');
        }
        function getLocationJson($query)
        {
            $query = urldecode($query);
            echo $this->commonlibrary->GetLocationJson($query);
        }

    }
    