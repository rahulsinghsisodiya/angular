<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Response extends My_Controller
    {

        public function __construct()
        {
            parent::__construct();
            $this->load->helper('language');
            $this->load->helper('url');
            $this->load->model('Request_model');
            $this->load->model('Response_model');
            $this->userdata = GetLoggedinUserData();
            $this->providerdata = GetLoggedinProviderData();
        }

        public function saveResponse()
        {
            $this->load->library('form_validation');
            $providerdata = $this->providerdata;
            $providerid = $providerdata['providerid'];

            if (!empty($providerdata))
            {
                $dataArray = array();
//                $this->form_validation->set_rules('message', lang("message"), 'trim|max_length[255]');
//                $this->form_validation->set_rules('offeredprice', lang("offeredprice"), 'required|trim|max_length[8]|callback_checkofferedprice');
                $this->form_validation->set_rules('requestno', lang("request"), 'trim|required|callback_checkrequest');
//              

                if ($this->form_validation->run() == false)
                {
                    $response_arr['status'] = "error";
                    $response_arr['message'] = validation_errors();
                }
                else
                {
                    
                    $amount = getCustomConfigItem('amount');
                    $message = $this->input->post('message');
                    $offeredprice = $this->input->post('offeredprice');
                    $requestno = $this->input->post('requestno');
                    $requestno = $this->input->post('requestno');
                    $requestrecord = $this->Request_model->getrequestrecord(array('requestno' => $requestno));
                    
                    $requestid = $requestrecord->requestid;

                    $responseno = getResponseNo();
                    $createdat = date("Y-m-d H:i:s");

                    $budget = $requestrecord->budget;
                    
                    $requiredamount = $this->commonlibrary->getrequiredamountforbid($budget);

                  
                    $dataValues = array(
                        'responseno' => $responseno,
                        'requestid' => $requestid,
                        'providerid' => $providerid,
                        'offeredprice' => '0',
                        'message' => $message,
                        'createdat' => $createdat
                    );

                  
                    $responseid = $this->Response_model->saveresponse($dataValues);

                    // Insert Into ledger

                    $ledger_array = array(
                        "transactiontype" => "Lead",
                        "recordid" => $responseid,
                        "transactiondate" => $createdat,
                        "providerid" => $providerid,
                        "amount" => $requiredamount,
                        "status" => 'Minus',
                    );

                    $ledger_id = $this->Payment_model->insertLedger($ledger_array);


                    $this->commonlibrary->sendresponsemail($responseid);

                    $response_arr['status'] = "success";
                    $response_arr['message'] = lang("get_success");
                }
            }
            else
            {
                $response_arr['status'] = "error";
                $response_arr['message'] = lang("login_error_message");
            }
            echo json_encode($response_arr);
        }

        function checkrequest($requestno)
        {
            $this->load->model('Payment_model');
            $providerdata = $this->providerdata;
            $providerid = $providerdata['providerid'];
            $amount = getCustomConfigItem('amount');
            $maxresponse = getCustomConfigItem('maxresponse');

            $requestrecord = $this->Request_model->getrequestrecord(array('requestno' => $requestno));
            $requestid = $requestrecord->requestid;
            $responserecord = $this->Response_model->getresponserecords(array('r.requestid' => $requestid), 'rp.responseid');
            $countresponse = count($responserecord);

            $balance = $this->Payment_model->getproviderbalance($providerid);
            $budget = $requestrecord->budget;

            $requiredamount = $this->commonlibrary->getrequiredamountforbid($budget);

            if (empty($requestrecord))
            {
                $this->form_validation->set_message('checkrequest', lang('request_record_not_found'));
                return FALSE;
            }
            elseif ($countresponse >= $maxresponse)
            {
                $this->form_validation->set_message('checkrequest', lang('request_max_response'));
                return FALSE;
            }
            else
            {
                $projectstart = $requestrecord->projectstart;
                $projectstart = strtotime($projectstart);
                $today = time();
                if ($projectstart < $today)
                {
                    $this->form_validation->set_message('checkrequest', lang('request_close'));
                    return FALSE;
                }
                elseif ($balance < $requiredamount)
                {
                    $this->form_validation->set_message('checkrequest', lang('dont_sufficient_balance'));
                    return FALSE;
                }
            }
        }

        function checkofferedprice($offeredprice)
        {
            $this->load->model('Payment_model');

            $providerdata = $this->providerdata;
            $providerid = $providerdata['providerid'];
            $amount = getCustomConfigItem('amount');
            $maxresponse = getCustomConfigItem('maxresponse');

            $requestno = $this->input->post('requestno');
            $requestrecord = $this->Request_model->getrequestrecord(array('requestno' => $requestno));
            $requestid = $requestrecord->requestid;
            $responserecord = $this->Response_model->getresponserecords(array('r.requestid' => $requestid), 'rp.responseid');
            $countresponse = count($responserecord);

            $balance = $this->Payment_model->getproviderbalance($providerid);
            $budget = $requestrecord->budget;

            $requiredamount = $this->commonlibrary->getrequiredamountforbid($budget);



            if (empty($requestrecord))
            {
                $this->form_validation->set_message('checkofferedprice', lang('request_record_not_found'));
                return FALSE;
            }
            elseif ($countresponse >= $maxresponse)
            {
                $this->form_validation->set_message('checkofferedprice', lang('request_max_response'));
                return FALSE;
            }
            else
            {
                $projectstart = $requestrecord->projectstart;
                $projectstart = strtotime($projectstart);
                $today = time();
                if ($projectstart < $today)
                {
                    $this->form_validation->set_message('checkofferedprice', lang('request_close'));
                    return FALSE;
                }
                elseif ($balance < $requiredamount)
                {
                    $this->form_validation->set_message('checkofferedprice', lang('dont_sufficient_balance'));
                    return FALSE;
                }
                elseif ($offeredprice > $budget)
                {
                    $this->form_validation->set_message('checkofferedprice', lang('offered_price_less'));
                    return FALSE;
                }
            }
        }

    }
    