<?php

    defined('BASEPATH') OR exit('No direct script access allowed');

    class Cron extends CI_Controller
    {

        function __construct()
        {
            parent::__construct();
            $this->load->library('commonlibrary');
            //Check if is called from CLI
            if (!$this->input->is_cli_request())
            {
                //Not called from CLI
                //Log or report (Optional)
                //Error message or 404 (Optional)
                //echo "No access!";
                show_404();

                //Exit (Recommended)
                exit;
            }
        }

        public function backup($database)
        {
            //Process
            echo "Now backing database: " . $database . "\n";
        }

        public function test_auto()
        {
            $to_email = 'hanwant0@gmail.com';
            $to_name = 'hanwant';
            $subject = 'hye';
            $body = 'hye';
            $this->commonlibrary->sendmail($to_email, $to_name, $subject, $body, $mailtype = "html", EMAIL_FROM_NAME, EMAIL_FROM_EMAIL);
            echo 'hye';

            echo BASEPATH;
        }

    }

?>