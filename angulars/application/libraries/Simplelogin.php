<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    /**
     * Simplelogin Class
     *
     * Makes authentication simple
     *
     * Simplelogin is released to the public domain
     * (use it however you want to)
     * 
     * Simplelogin expects this database setup
     * (if you are not using this setup you may
     * need to do some tweaking)
     * 

      #This is for a MySQL table
      CREATE TABLE `users` (
      `id` INT( 11 ) NOT NULL AUTO_INCREMENT PRIMARY KEY ,
      `username` VARCHAR( 64 ) NOT NULL ,
      `password` VARCHAR( 64 ) NOT NULL ,
      UNIQUE (
      `username`
      )
      );

     * 
     */
    class Simplelogin
    {

        var $CI;
        //var $user_table = 'admin_users';
        var $user_table = 'admin';
        var $username_field = 'username';
        var $email_field = 'operator_email';
        var $password_field = 'password';

        function Simplelogin()
        {
            // get_instance does not work well in PHP 4
            // you end up with two instances
            // of the CI object and missing data
            // when you call get_instance in the constructor
            //$this->CI =& get_instance();
        }

        public function setLoginParams(array $params)
        {
            $this->user_table = !empty($params['table']) ? $params['table'] : $this->user_table;
            $this->username_field = !empty($params['username_field']) ? $params['username_field'] : $this->username_field;
            $this->email_field = !empty($params['email_field']) ? $params['email_field'] : $this->username_field;
            $this->password_field = !empty($params['password_field']) ? $params['password_field'] : $this->password_field;
        }

        /**
         * Create a user account
         *
         * @access	public
         * @param	string
         * @param	string
         * @param	bool
         * @return	bool
         */
        function create($user = '', $password = '', $auto_login = true)
        {
            //Put here for PHP 4 users
            $this->CI = & get_instance();

            //Make sure account info was sent
            if ($user == '' OR $password == '')
            {
                return false;
            }

            //Check against user table
            $this->CI->db->where('username', $user);
            $query = $this->CI->db->getwhere($this->user_table);

            if ($query->num_rows() > 0)
            {
                //username already exists
                return false;
            }
            else
            {

                //Encrypt password
                $password = md5($password);

                //Insert account into the database
                $data = array(
                    'username' => $user,
                    'password' => $password
                );
                $this->CI->db->set($data);
                if (!$this->CI->db->insert($this->user_table))
                {
                    //There was a problem!
                    return false;
                }
                $user_id = $this->CI->db->insert_id();

                //Automatically login to created account
                if ($auto_login)
                {
                    //Destroy old session
                    $this->CI->session->sess_destroy();

                    //Create a fresh, brand new session
                    $this->CI->session->sess_create();

                    //Set session data
                    $this->CI->session->set_userdata(array('id' => $user_id, 'username' => $user));

                    //Set logged_in to true
                    $this->CI->session->set_userdata(array('logged_in' => true));
                }

                //Login was successful			
                return true;
            }
        }

        /**
         * Delete user
         *
         * @access	public
         * @param integer
         * @return	bool
         */
        function delete($user_id)
        {
            //Put here for PHP 4 users
            $this->CI = & get_instance();

            if (!is_numeric($user_id))
            {
                //There was a problem
                return false;
            }

            if ($this->CI->db->delete($this->user_table, array('id' => $user_id)))
            {
                //Database call was successful, user is deleted
                return true;
            }
            else
            {
                //There was a problem
                return false;
            }
        }

        /**
         * Login and sets session variables
         *
         * @access	public
         * @param	string
         * @param	string
         * @return	bool
         */
        function login($user = '', $password = '')
        {

            //Put here for PHP 4 users
            $this->CI = & get_instance();

            //Make sure login info was sent
            if ($user == '' OR $password == '')
            {
                return false;
            }

            $this->CI->load->library('session');

            //Check if already logged in
            if ($this->CI->session->userdata('username') == $user)
            {
                //User is already logged in.
                return true;
//                return false;
            }

            //Check against user table
            $this->CI->db->where($this->username_field, $user);
            $query = $this->CI->db->get($this->user_table);
//            p($this->db->last_query());
            if ($query->num_rows() > 0)
            {

                $row = $query->row_array();

                //Check against password
                if (md5($password) != $row[$this->password_field])
                {
                    return false;
                }

//                Destroy old session
//                $this->CI->session->sess_destroy();
//                Create a fresh, brand new session
//                $this->CI->session->sess_create();
                //Remove the password field
                $password_field = $this->password_field;
                unset($row[$password_field]);

                //Set session data
//                $data = $row;
//                        unset($data->password);                        
//                        $arr_userdata = array(
//                            'my_userdata' => $data
//                        );
//
//                        $this->CI->session->set_userdata($arr_userdata);
                $this->CI->session->set_userdata($row);

                //Set logged_in to true
                $this->CI->session->set_userdata(array('logged_in' => true));

                //Login was successful			
                return true;
            }
            else
            {

                //No database result found
                return false;
            }
        }

        /**
         * Logout user
         *
         * @access	public
         * @return	void
         */
        function logout()
        {
            //Put here for PHP 4 users
            $this->CI = & get_instance();

            //Destroy session
            $this->CI->load->library('Session');
            $this->CI->session->sess_destroy();
        }

    }

?>