<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Request extends My_Controller
    {

        private $_public_request_listing_headers = 'public_request_listing_headers';
        private $_public_provider_request_listing_headers = 'public_provider_request_listing_headers';

        public function __construct()
        {
            parent::__construct();
            $this->load->helper('language');
            $this->load->helper('url');
            $this->load->model('Request_model');
            $this->load->model('Response_model');
            $this->load->model('Subservice_model');
            $this->userdata = GetLoggedinUserData();
            $this->providerdata = GetLoggedinProviderData();
        }

        /**
         * Activates the request token and calls "requestActivated" state
         * 
         * @param type $token - Actvation token (request_token.token)
         */
        public function activateRequest($token)
        {
            $this->load->model('Useraccount_model');
            $dataValues = array(
                'token' => $token,
            );

            $result = $this->Request_model->activateRequest($dataValues);
            if (!empty($result))
            {
                //$this->session->set_flashdata('request_operation_message', lang('activation_success_message'));
                // TODO : transpose this in admin (or cron) $this->commonlibrary->sendrequestmail($result);
                $this->commonlibrary->sendrequestmail($result);
                redirect(base_url() . 'request-activated/' . $token);
            }
            else
            {
                $this->session->set_flashdata('user_operation_message_error', lang('request_activation_error_message'));
                redirect(base_url() . 'my-requests');
            }
        }

        /**
         * Request activated state : changes user password if user.updatedat 
         * 
         * @param type $token - Actvation token (request_token.token)
         */
        public function requestActivated($token)
        {
            $this->load->library('form_validation');
            $this->load->library('Commonlibrary');
            $this->load->model('Useraccount_model');


            // Load user from request if not available
            $tmpDataValues = array(
                'token' => $token,
            );
            $userDataFromToken = $this->Request_model->getUserFromRequestToken($tmpDataValues);
            // Log users with request token
            if (isset($userDataFromToken->email))
            {
                $arrUserFilter = array();
                $arrUserFilter['email'] = $userDataFromToken->email;
                $userRecord = $this->Useraccount_model->getuserrecord($arrUserFilter);
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
            else
            {
                // Token does not match with any email. Display error message
                $login_error_message_3 = lang('login_error_message_3');
                $this->session->set_flashdata('user_operation_message_error', $login_error_message_3);
                redirect(base_url() . 'user-login');
            }
            $this->userdata = GetLoggedinUserData();

            $user_data = $this->userdata;
            $userid = $user_data['userid'];
            $this->form_validation->set_rules('password', lang("password"), 'trim|required|matches[confirm_password]');
            $this->form_validation->set_rules('confirm_password', lang("confirm_password"), 'trim|required');

            // FIXME : create real password control, not only updatedat function
            // If user password not set, allows user to change it's password
            if ($user_data['updatedat'] == null)
            {
                // Request activation
                if ($this->form_validation->run() == FALSE)
                {
                    $dataArray = array();
                    $dataArray['form_caption'] = lang('request_activated');
                    $dataArray['form_action'] = current_url();
                    $dataArray['template_title'] = lang('request_activated') . ' | ' . lang('SITENAME');
                    $dataArray['message'] = $this->session->flashdata('request_operation_message');
                    $dataArray['error_message'] = $this->session->flashdata('request_operation_message_error');
                    $dataArray['sidebar'] = $this->commonlibrary->getusersidebar();
                    $dataArray['only_professionals'] = $this->commonlibrary->getonlyprofessionals();

                    $dataArray['local_js'] = array(
                        'jquery.validate.min',
                        'select2'
                    );
                    $dataArray['local_css'] = array(
                        'select2'
                    );
                    $this->load->view('/request-activated', $dataArray);
                }
                else
                {

                    $password = $this->input->post('password');
                    $dataValues['password'] = md5($password);
                    $dataValues['userid'] = $userid;
                    $dataValues['updatedat'] = date('Y-m-d H:i:s');

                    $last_id = $this->Useraccount_model->saveuser($dataValues);
                    $this->session->set_flashdata('user_operation_message', lang('password') . ' ' . lang('saved'));
                    redirect('my-requests');
                }
            }
            else
            {
                $this->session->set_flashdata('user_operation_message', 'YOUR REQUEST IS ACTIVATED ;)');
                redirect('my-requests');
            }
        }

        public function sendRegistrationEmail($email, $password)
        {

            $this->load->model('Emailtemplate_model');
            $this->load->model('Useraccount_model');

            $filterArr = array(
                'email' => $email
            );
            $userRecord = $this->Useraccount_model->getuserrecord($filterArr);
            $fullname = $userRecord->name;
            $email_to = $email;

            $templateRecord = $this->Emailtemplate_model->getemailtemplate('Signup Email User');
            if (!empty($templateRecord))
            {
                $content = htmlspecialchars_decode($templateRecord->content);
                $subject = $templateRecord->subject;
            }

            $from_name = EMAIL_FROM_NAME;
            $from_email = EMAIL_FROM_EMAIL;


            $arr_placeholders = array("{{fullname}}", "{{sitename}}", "{{password}}");
            $arr_placeholders_values = array($fullname, SITENAME, $password);
            $content = str_replace($arr_placeholders, $arr_placeholders_values, $content);
            $this->commonlibrary->sendmail($email_to, $fullname, $subject, $content, "html", $from_name, $from_email);
        }

        public function sendRequestActivationEmail($requestid, $token)
        {
            $this->load->model('Emailtemplate_model');

            $filterArr = array(
                'r.requestid' => $requestid
            );
            $select = 'r.requestno,r.name as contact_name,r.email as contact_email,r.mainphone,r.surname,u.name as user_name,u.email as user_email';

            $requestRecord = $this->Request_model->getrequestrecord($filterArr, $select);
            $fullname = $requestRecord->user_name;
            $email_to = $requestRecord->user_email;
            $templateRecord = $this->Emailtemplate_model->getemailtemplate('Request Activation');

            $requestno = $requestRecord->requestno;
            $contactname = $requestRecord->contact_name;
            $contactemail = $requestRecord->contact_email;
            $mainphone = $requestRecord->mainphone;
            $surname = $requestRecord->surname;


            if (!empty($templateRecord))
            {
                $content = htmlspecialchars_decode($templateRecord->content);
                $subject = $templateRecord->subject;
            }

            $from_name = EMAIL_FROM_NAME;
            $from_email = EMAIL_FROM_EMAIL;

            $activation_url = base_url() . 'activate-request/' . $token;

            $arr_placeholders = array("{{fullname}}", "{{sitename}}", "{{activationurl}}", "{{requestno}}", "{{contactname}}", "{{contactemail}}",
                "{{mainphone}}", "{{surname}}");
            $arr_placeholders_values = array($fullname, SITENAME, $activation_url, $requestno, $contactname, $contactemail, $mainphone, $surname);

            $content = str_replace($arr_placeholders, $arr_placeholders_values, $content);
            $this->commonlibrary->sendmail($email_to, $fullname, $subject, $content, "html", $from_name, $from_email, $contactemail);
        }

        public function postRequest($slug='')
        {
            $this->load->library('form_validation');
            $this->load->library('Commonlibrary');
            $this->load->model('Service_model');
            $this->load->model('Useraccount_model');
            $this->load->model('Cms_model');

            $userdata = $this->userdata;
            $userid = empty($userdata) ? '' : $userdata['userid'];


            $this->form_validation->set_rules('serviceid', lang('service'), 'required');
            $this->form_validation->set_rules('zipcode', lang('zipcode'), 'required|max_length[5]');
            $this->form_validation->set_rules('projectstart', lang('projectstart'), 'required');
            //$this->form_validation->set_rules('budget', lang('budget'), 'required|max_length[8]');
            $this->form_validation->set_rules('requestconcerns', lang('requestconcerns'), 'required|max_length[255]');
//          $this->form_validation->set_rules('projectdetail', lang('projectdetail'), 'required');
            $this->form_validation->set_rules('name', lang('name'), 'required|min_length[3]|max_length[100]');
            if (!empty($userid))
            {
                $this->form_validation->set_rules('email', lang('email'), 'required|trim|min_length[6]|max_length[50]');
            }
            else
            {
                $this->form_validation->set_rules('email', lang('email'), 'required|trim|min_length[6]|max_length[50]|unique[users.email.userid.' . NULL . ']');
            }
            $this->form_validation->set_rules('mainphone', lang('mainphone'), 'required|min_length[10]|max_length[20]');
            $this->form_validation->set_rules('surname', lang('surname'), 'required|min_length[3]|max_length[100]');


            if ($this->form_validation->run() == FALSE)
            {

                $dataArray = array();

                $dataArray['form_action'] = current_url();
                $dataArray['template_title'] = lang('post_a_request') . ' | ' . lang('SITENAME');

                $arr_service = $this->Service_model->getallservice_array();
                $dataArray['arr_service'] = add_blank_option($arr_service, '');

                $serviceslug = $this->session->flashdata('servicslug');

                if (!empty($serviceslug))
                {
                    $filterArr = array(
                        'serviceslug' => $serviceslug
                    );
                    $servicerecord = $this->Service_model->getservicerecord($filterArr);
                    $dataArray['serviceid'] = $servicerecord->serviceid;
                }



                $requestconcerns = getCustomConfigItem('requestconcerns');
                $dataArray['arr_requestconcerns'] = add_blank_option($requestconcerns, '');


                $dataArray['arr_requestconcerns'] = add_blank_option($requestconcerns, '');

                $projectstart = getCustomConfigItem('projectstart');
                $dataArray['arr_projectstart'] = add_blank_option($projectstart, '');
                $language = getLanguage();

                $cmscontent = $this->Cms_model->getcmscontent('general-terms-and-conditions', $language);
                if (!empty($cmscontent))
                {
                    $dataArray['pagetitle'] = $cmscontent->pagetitle;
                    $dataArray['pagecontent'] = $cmscontent->pagecontent;
                }

                $cmscontent = $this->Cms_model->getcmscontent('read-more-client', $language);
                if (!empty($cmscontent))
                {
                    $dataArray['readmore_client_title'] = $cmscontent->pagetitle;
                    $dataArray['readmore_client_content'] = $cmscontent->pagecontent;
                }

                $professionalcontent = $this->Cms_model->getcmscontent('post-a-request-sidebar', $language);

                if (!empty($professionalcontent))
                {
                    $dataArray['only_qualified_professionals'] = $professionalcontent->pagecontent;
                }

                if (!empty($userid))
                {
                    $filterArr = array(
                        'userid' => $userid
                    );
                    $userrecord = $this->Useraccount_model->getuserrecord($filterArr);
                    $dataArray['name'] = $userrecord->name;
                    $dataArray['email'] = $userrecord->email;
                    $dataArray['mainphone'] = $userrecord->mainphone;
                    $dataArray['surname'] = $userrecord->surname;
                }

                $tooltiprecord = $this->Request_model->getrequestfieldtooltips($language);
                $dataArray['tooltiprecord'] = $tooltiprecord;
                if(!empty($slug)){
                    $subservice_arr = $this->Subservice_model->getallsubserviveBylug($slug);
                    
                    if(!empty($subservice_arr)){
                        $dataArray['serviceid'] = $subservice_arr->serviceid;
                        $dataArray['subserviceid'] = $subservice_arr->subserviceid;
                       
                    }else{
                        redirect('post-request');
                    }
                
                }

                $dataArray['local_css'] = array(
                    'datepicker',
                    'select2',
                    'jquery.datetimepicker',
                    'jquery.tipsy'
                );

                $dataArray['local_js'] = array(
                    'jquery.validate.min',
                    'datepicker',
                    'ckeditor',
                    'select2',
                    'jquery.datetimepicker',
                    'jQuery-Autocomplete-master',
                    'jquery.tipsy',
                    'blockUI'
                );
               
                $this->load->view('post-request', $dataArray);
            }
            else
            {
                $projectstartdays = $this->input->post('projectstart');
                $projectstart = date('Y-m-d', strtotime("+" . $projectstartdays . " days"));

                $requestno = getRequestNo();
                $name = $this->input->post('name');
                $email = $this->input->post('email');
                $mainphone = $this->input->post('mainphone');
                $surname = $this->input->post('surname');

                $budget = getCustomConfigItem('default_budget');

                $dataValues = array(
                    'serviceid' => $this->input->post('serviceid'),
                    'subserviceid' => $this->input->post('subservice'),
                    'zipcode' => $this->input->post('zipcode'),
                    'projectstart' => $projectstart,
                    'projectstartdays' => $projectstartdays,
                    'budget' => $budget,
                    'requestconcerns' => $this->input->post('requestconcerns'),
                    'projectdetail' => $this->input->post('projectdetail'),
                    'requestno' => $requestno,
                    'name' => $name,
                    'email' => $email,
                    'mainphone' => $mainphone,
                    'surname' => $surname,
                    'status' => '0'
                );
                if (!empty($userid))
                {
                    $dataValues['userid'] = $userid;
                }
                else
                {
                    $password_string = $this->commonlibrary->generatePassword(9, 2);
                    $password = md5($password_string);
                    $userArr = array(
                        'name' => $name,
                        'email' => $email,
                        'mainphone' => $mainphone,
                        'surname' => $surname,
                        'status' => 'Active',
                        'password' => $password,
                        'createdat' => date('Y-m-d H:i:s')
                    );
                    $new_userid = $this->Useraccount_model->saveuser($userArr);

                    //FIXME : $this->sendRegistrationEmail($email, $password);
                    $dataValues['userid'] = $new_userid;
                    $userRecord = $this->Useraccount_model->getuserrecord(array('userid' => $new_userid));
                    $data = $userRecord;
                    $arr_userdata = array(
                        'my_userdata' => $data
                    );
                    $this->session->set_userdata($arr_userdata);
                }

                $dataValues['createdat'] = date('Y-m-d H:i:s');

                $last_id = $this->Request_model->saverequest($dataValues);
                $arr_servicefield = $this->input->post('servicefield');
                $this->Request_model->saverequestservicefield($arr_servicefield, $last_id);

                $token = $this->Request_model->getrequesttoken();
                $dataToken = array(
                    'requestid' => $last_id,
                    'token' => $token,
                );
                $this->Request_model->saverequesttoken($dataToken);

                $this->sendRequestActivationEmail($last_id, $token);

                $this->session->set_flashdata('request_operation_message', lang('request') . ' ' . lang('saved'));
                redirect('my-requests');
            }
        }

        /**
         * Choose professional to do the matching
         */
        public function chooseMatching()
        {
            $matchingid = $this->input->post('matchingid');
            $requestid = $this->input->post('requestid');

            $result = $this->Response_model->choosematchingprovider($matchingid, $requestid);
            echo json_encode($result);
        }

        public function getServiceFields()
        {
            $this->load->model('Servicefield_model');
            $serviceid = $this->input->post('serviceid');
            
            $data = array();
            $html = '';
            $servicesidebar = '';
            if (!empty($serviceid))
            {
              
                $servicfield_services = $this->Servicefield_model->getservicefieldservices_record(array('sst.serviceid' => $serviceid));
                
                $bNoMandatoryExists = false;
                foreach ($servicfield_services as $key => $val)
                {
                    $servicefieldid = $val['servicefieldid'];
                    $servicesidebar = $val['subservicedesc'];
                    $is_mandatory = $val['mandatory'];
                    $servicefieldrecord = $this->Servicefield_model->getservicefieldrecord(array('sf.servicefieldid' => $servicefieldid));
                    if ($is_mandatory == 1)
                    {
                        $html.= $this->commonlibrary->getservicefieldhtmlpublic($servicefieldid);
                    }
                    else
                    {
                        $bNoMandatoryExists = true;
                    }
                }
                // Parse again for displaying non mandatory fields
                if ($bNoMandatoryExists)
                {
                    $html .= '<div id="no-mandatory-fields-header" class="col-sm-12">';
                    $html .= '      <h4 class="highlight">' . lang('no_mandatory_fields') . '</h4>';
                    $html .= '      <hr/>';
                    $html .= '</div>';
                    $html .= '<div id="no-mandatory-fields-contents" class="col-sm-12" style="display:none;">';
                    foreach ($servicfield_services as $key => $val)
                    {
                        $servicefieldid = $val['servicefieldid'];
                        $is_mandatory = $val['mandatory'];
                        $servicefieldrecord = $this->Servicefield_model->getservicefieldrecord(array('sf.servicefieldid' => $servicefieldid));
                        if ($is_mandatory == 0)
                        {
                            $html.= $this->commonlibrary->getservicefieldhtmlpublic($servicefieldid);
                        }
                    }
                    $html.= '</div>';
                }
            }


            $script = $this->load->viewPartial('service-field-script');

            $data = array(
                'html' => $html,
                'script' => $script,
                'sidebar' => $servicesidebar
            );
            echo json_encode($data);
        }

        public function requestData()
        {
            $userdata = $this->userdata;
            $userid = $userdata['userid'];
            $this->load->model('Request_model');
            $this->load->library('Datatable');
            $arr = $this->config->config[$this->_public_request_listing_headers];
            $cols = array_keys($arr);
            $pagingParams = $this->datatable->get_paging_params($cols);

            $filterArr = array(
                'r.userid' => $userid
            );
            $resultdata = $this->Request_model->getallrequest($pagingParams, $filterArr);
            $json_output = $this->datatable->get_json_output($resultdata, $this->_public_request_listing_headers);
            $this->load->setTemplate('json');
            $this->load->view('json', $json_output);
        }

        public function myRequests()
        {
            $this->load->model('Useraccount_model');
            $this->load->library('Datatable');
            $dataArray['sidebar'] = $this->commonlibrary->getusersidebar();
            $dataArray['only_professionals'] = $this->commonlibrary->getonlyprofessionals();
            $dataArray['message'] = $this->session->flashdata('request_operation_message');
            $table_config = array(
                'source' => site_url('public/request/requestdata'),
                'datatable_class' => $this->config->config["datatable_class"]
            );
            $dataArray['table'] = $this->datatable->make_table($this->_public_request_listing_headers, $table_config);

            $dataArray['local_css'] = array(
                'jquery.dataTables.bootstrap',
            );

            $dataArray['local_js'] = array(
                'jquery.dataTables',
                'jquery.dataTables.bootstrap',
                'dataTables.fnFilterOnReturn',
            );

            $dataArray['error_message'] = $this->session->flashdata('user_operation_message_error');
            $dataArray['message'] = $this->session->flashdata('user_operation_message');
            $this->load->view('my-requests', $dataArray);
        }

        public function listRequestsData()
        {
            $this->load->model('Request_model');
            $this->load->model('Provider_model');
            $this->load->library('Datatable');
            $arr = $this->config->config[$this->_public_provider_request_listing_headers];
            $cols = array_keys($arr);
            $pagingParams = $this->datatable->get_paging_params($cols);

            $serviceid = $this->input->get('serviceid');
            $cityArr1 = $this->input->get('city');

            $providerdata = $this->providerdata;
            $providerid = $providerdata['providerid'];

            $serviceArr = array();
            $cityArr = array();
           
            $servicedata = $this->Provider_model->getproviderservices($providerid);
            foreach ($servicedata as $row)
            {
                $serviceArr[$row['serviceid']] = $row['serviceid'];
            }
           
            $citydata = $this->Provider_model->getprovidercities($providerid);
            foreach ($citydata as $row)
            {
                $cityArr[$row['postalcode']] = $row['postalcode'];
            }
            $filterArr = array(
                'r.status' => '1',
                'r.validated' => '1'
            );
            if (!empty($serviceid))
            {
                $filterArr['r.subserviceid'] = $serviceid;
            }

            $resultdata = $this->Request_model->getallrequest($pagingParams, $filterArr, $serviceArr, $cityArr, $cityArr1);
            $json_output = $this->datatable->get_json_output($resultdata, $this->_public_provider_request_listing_headers);
            $this->load->setTemplate('json');
            $this->load->view('json', $json_output);
        }

        public function listRequests()
        {
            $this->load->library('form_validation');
            $this->load->model('Provider_model');
            $this->load->library('Datatable');

            $providerdata = $this->providerdata;
            $providerid = $providerdata['providerid'];
            $accountstatus = $providerdata['status'];
            if ($accountstatus != 'Active')
            {
                $this->session->set_flashdata('provider_operation_message_error', lang('please_activate_account'));
                redirect('/provider-dashboard');
            }

            $dataArray['template_title'] = lang('requests') . ' | ' . SITENAME;
            $dataArray['sidebar'] = $this->commonlibrary->getprovidersidebar();
            $dataArray['latest_request'] = $this->load->viewPartial('latest-request', array());
            
            $servicedata = $this->Provider_model->getproviderservices($providerid);
            
            foreach ($servicedata as $row)
            {
                $arr_service[$row['serviceid']] = $row['servicename'];
            }
           
            $dataArray['arr_service'] = add_blank_option($arr_service, '');

            $citydata = $this->Provider_model->getprovidercities($providerid);
            foreach ($citydata as $row)
            {
                $arr_city[$row['postalcode']] = $row['cityname'] . ' (' . $row['postalcode'] . ')';
            }
            $dataArray['arr_city'] = $arr_city;

            $table_config = array(
                'source' => site_url('public/request/listRequestsData'),
                'datatable_class' => $this->config->config["datatable_class"],
                'table_id' => 'request_table'
            );
            $dataArray['table'] = $this->datatable->make_table($this->_public_provider_request_listing_headers, $table_config);
            $dataArray['table_heading'] = lang('requests');

            $dataArray['local_css'] = array(
                'jquery.dataTables.bootstrap',
                'multiple-select'
            );

            $dataArray['local_js'] = array(
                'jquery.dataTables',
                'jquery.dataTables.bootstrap',
                'dataTables.fnFilterOnReturn',
                'multiple-select'
            );

            $this->load->view('list-requests', $dataArray);
        }

        public function viewRequest($requestno)
        {
            $this->load->model('Servicefield_model');
            $this->load->library('form_validation');
            $providerdata = $this->providerdata;
            $providerid = $providerdata['providerid'];
            $accountstatus = $providerdata['status'];

            if ($accountstatus != 'Active')
            {
                $this->session->set_flashdata('provider_operation_message_error', lang('please_activate_account'));
                redirect('/provider-dashboard');
            }

            $filterArr = array(
                'r.requestno' => $requestno
            );

            $requestrecord = $this->Request_model->getrequestrecord($filterArr);
            
            if (!empty($requestrecord))
            {
                $dataArray = array();
                $dataArray['message'] = $this->session->flashdata('payment_operation_message');
                $dataArray['error_message'] = $this->session->flashdata('payment_operation_message_error');
                $dataArray['template_title'] = lang('request') . ' #' . $requestno . ' | ' . SITENAME;


                $requestid = $requestrecord->requestid;
                $dataArray['request'] = $requestid;
                $dataArray['servicename'] = $requestrecord->servicename;
                $dataArray['requestconcerns'] = $requestrecord->requestconcerns;
                $dataArray['zipcode'] = $requestrecord->zipcode;
                $dataArray['requestno'] = $requestno;
                $projectstart = $requestrecord->projectstart;
                $projectstart = empty($projectstart) ? '' : date('d/m/Y', strtotime($projectstart));
                $dataArray['projectstart'] = $projectstart;

                $cityname = $requestrecord->cityname;
                $regionname = $requestrecord->regionname;
                $dataArray['location'] = $cityname . ' , ' . $regionname;
                $dataArray['projectdetail'] = $requestrecord->projectdetail;
                $budget = $requestrecord->budget;
                $dataArray['budget'] = $budget;
                $dataArray['subservicename'] = $requestrecord->subservicename;

                $dataArray['amount'] = getCustomConfigItem('amount');


                $dataArray['sidebar'] = $this->commonlibrary->getprovidersidebar();
                $dataArray['latest_request'] = $this->load->viewPartial('latest-request', array());


                $filterArr1 = array(
                    'rst.requestid' => $requestid
                );
                $requestservicefieldrecord = $this->Servicefield_model->getrequestservicefields_record($filterArr1);

                $dataArray['requestservicefieldrecord'] = $requestservicefieldrecord;


                $filterArr2 = array(
                    'rp.requestid' => $requestid,
                    'rp.providerid' => $providerid
                );
                $responserecord = $this->Response_model->getresponserecords($filterArr2, 'rp.responseid,rp.message,rp.offeredprice,rp.responseno,rp.providerid');
                $dataArray['responserecord'] = $responserecord;

                $dataArray['lowestmemberprice'] = $this->lowestmemberprice($requestid);


                $filterArr3 = array(
                    'rp.requestid' => $requestid,
                );

                $leadbtnvisible = TRUE;

                $responserecord = $this->Response_model->getresponserecords($filterArr3, 'rp.offeredprice');
                $countresponse = count($responserecord);

                $projectstart = $requestrecord->projectstart;
                $start = strtotime($requestrecord->projectstart);
                $today = time();


                $maxresponse = getCustomConfigItem('maxresponse');

                if ($countresponse >= $maxresponse)
                {
                    $leadbtnvisible = FALSE;
                }
                elseif ($today > $start)
                {
                    $leadbtnvisible = FALSE;
                }


                $dataArray['leadbtnvisible'] = $leadbtnvisible;
                $dataArray['countserviceprovider'] = $countresponse;
                $dataArray['maxresponse'] = $maxresponse;


                $requiredamount = $this->commonlibrary->getrequiredamountforbid($budget);

                $dataArray['requiredamount'] = $requiredamount;


                $dataArray['contactname'] = $requestrecord->contactname;
                $dataArray['contactemail'] = $requestrecord->contactemail;
                $dataArray['contactmainphone'] = $requestrecord->contactmainphone;
                $dataArray['contactsurname'] = $requestrecord->contactsurname;



                $dataArray['local_css'] = array(
                    'toastr'
                );

                $dataArray['local_js'] = array(
                    'jquery.validate.min',
                    'toastr'
                );
                $this->load->view('view-request', $dataArray);
            }
            else
            {
                show_404();
            }
        }

        public function myRequest($requestno)
        {
            $this->load->model('Servicefield_model');
            $dataArray['requestno'] = $requestno;

            $filterArr = array(
                'r.requestno' => $requestno
            );

            $requestrecord = $this->Request_model->getrequestrecord($filterArr);
           
            if (!empty($requestrecord))
            {
                $requestid = $requestrecord->requestid;
                $dataArray['request'] = $requestid;
                $dataArray['servicename'] = $requestrecord->servicename;
                $dataArray['requestconcerns'] = $requestrecord->requestconcerns;
                $dataArray['zipcode'] = $requestrecord->zipcode;
                $dataArray['requestno'] = $requestno;
                $projectstart = $requestrecord->projectstart;
                $projectstart = empty($projectstart) ? '' : date('d/m/Y', strtotime($projectstart));
                $dataArray['projectstart'] = $projectstart;
                $cityname = $requestrecord->cityname;
                $regionname = $requestrecord->regionname;
                $dataArray['location'] = $cityname . ' , ' . $regionname;
                $dataArray['projectdetail'] = $requestrecord->projectdetail;
                $dataArray['budget'] = $requestrecord->budget;

                $dataArray['contactname'] = $requestrecord->contactname;
                $dataArray['contactemail'] = $requestrecord->contactemail;
                $dataArray['contactmainphone'] = $requestrecord->contactmainphone;
                $dataArray['subservicename'] = $requestrecord->subservicename;
                // $dataArray['contactsurname'] = $requestrecord->contactsurname;


                $filterArr1 = array(
                    'requestid' => $requestid
                );
                $requestservicefieldrecord = $this->Servicefield_model->getrequestservicefields_record($filterArr1);
                $dataArray['requestservicefieldrecord'] = $requestservicefieldrecord;


                $filterArr2 = array(
                    'rp.requestid' => $requestid,
                );
                $ratingrecords = $this->Response_model->getratingrecords($requestid);
                $responserecord = $this->Response_model->getresponserecords($filterArr2, 'rp.responseid,rp.message,rp.offeredprice,rp.responseno,rp.providerid,p.email,p.phone');
                foreach ($responserecord as $key => $response)
                {
                    foreach ($ratingrecords as $rating)
                    {
                        if ($responserecord[$key]['providerid'] == $rating['providerid'])
                        {
                            $responserecord[$key]['avg_rating'] = isset($rating['avg_rating']) ? $rating['avg_rating'] : 0;
                            $responserecord[$key]['clientmatching'] = $rating['clientmatching'];
                            $responserecord[$key]['matchingid'] = $rating['matchingid'];
                        }
                    }
                }
                $dataArray['responserecord'] = $responserecord;
                $dataArray['countserviceprovider'] = count($responserecord);
                $dataArray['ratingrecords'] = $ratingrecords;


                $dataArray['maxresponse'] = getCustomConfigItem('maxresponse');


                $dataArray['sidebar'] = $this->commonlibrary->getusersidebar();
                $dataArray['only_professionals'] = $this->commonlibrary->getonlyprofessionals();
                $dataArray['message'] = $this->session->flashdata('request_operation_message');


                $this->load->view('my-request', $dataArray);
            }
            else
            {
                show_404();
            }
        }

        function lowestmemberprice($requestid)
        {
            $return = NULL;
            $filterArr2 = array(
                'rp.requestid' => $requestid,
            );

            $responserecord = $this->Response_model->getresponserecords($filterArr2, 'rp.offeredprice');

            if (!empty($responserecord))
            {
                $return = min(array_column($responserecord, 'offeredprice'));
            }

            return $return;
        }

        public function getLatestRequest()
        {

            $this->load->model('Provider_model');
            $serviceArr = array();
            $cityArr = array();
            $providerdata = $this->providerdata;
            if (!empty($providerdata))
            {
                $providerid = $providerdata['providerid'];
                $servicedata = $this->Provider_model->getproviderservices($providerid);
                foreach ($servicedata as $row)
                {
                    $serviceArr[$row['serviceid']] = $row['serviceid'];
                }
                $citydata = $this->Provider_model->getprovidercities($providerid);
                foreach ($citydata as $row)
                {
                    $cityArr[$row['postalcode']] = $row['postalcode'];
                }
            }

            $filterArr = array(
                'r.status' => '1',
            );
            $select = 'r.*,u.*,s.*,c.*,re.*,r.name as contactname,r.email as contactemail,'
                . 'r.mainphone as contactmainphone,r.surname as contactsurname,r.createdat as projectcreated';
            $requestrecord = $this->Request_model->getrequestrecords($filterArr, $select, $serviceArr, $cityArr, '3');

            $dataArray = array(
                'requestrecord' => $requestrecord
            );
            $content = $this->load->viewPartial('partial-request-content', $dataArray);

            $data = array(
                'content' => $content
            );

            echo json_encode($data);
        }

        public function service($slug)
        {
            $this->session->set_flashdata('servicslug', $slug);

            redirect('post-request');
        }

        public function getsubservice()
        {
            $this->load->library('form_validation');
            $this->load->library('Commonlibrary');

            $service = $this->input->post('service_id');
            $subservice_id = $this->input->post('subserviceid');
         
            $data = array();
            if (!empty($service))
            {
                $subservice = $this->Subservice_model->getallsubserviveByserviceid($service);
                $subservice = add_blank_option($subservice, 'Select Service');
                $dataValues['arr_subservice'] = $subservice;
                $dataValues['subserviceid'] = $subservice_id;
              
                $htmlview = $this->load->viewPartial('public/subservice_partial', $dataValues);
                $data['html'] = $htmlview;
                echo json_encode($data);
            }
        }

    }
    