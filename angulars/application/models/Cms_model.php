<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Cms_model extends My_Model
    {

        /**
         * initializes the class inheriting the methods of the class My_Model 
         */
        public function __construct()
        {
            parent::__construct();
        }

        public function savecms($dataValues)
        {

            $pageid = null;
            if (count($dataValues) > 0)
            {
                $cmsRecord = $this->getCmsContent($dataValues['pagename'],$dataValues['pagelanguage']);

                if (!empty($cmsRecord))
                {
                    $this->db->where('pagename', $dataValues['pagename']);
                    $this->db->where('pagelanguage', $dataValues['pagelanguage']);
                    $this->db->update('cms', $dataValues);

                    $pageid = $cmsRecord->pageid;
                }
                else
                {
                    $this->db->insert('cms', $dataValues);
                    $pageid = $this->db->insert_id();
                }
            }

            return $pageid;
        }

        public function getcmscontent($pagename, $pagelanguage = NULL)
        {
            $return = NULL;

            if (!empty($pagename))
            {
                $this->db->select('*');
                $this->db->where('pagename', $pagename);
                if (!empty($pagelanguage))
                {
                    $this->db->where('pagelanguage', $pagelanguage);
                }
                $return = $this->db->get('cms')->row();
            }
            return $return;
        }

    }
    