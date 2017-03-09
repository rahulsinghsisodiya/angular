<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Gallery_model extends My_Model
    {

        public function __construct()
        {
            parent::__construct();
        }

        public function getallgallery($pagingParams = array())
        {
            $this->db->select('SQL_CALC_FOUND_ROWS 1', false);
            $this->db->select('gallery.*');
            $this->db->from('gallery');

            if (!empty($pagingParams['order_by']))
            {
                if (empty($pagingParams['order_direction']))
                {
                    $pagingParams['order_direction'] = '';
                }

                switch ($pagingParams['order_by'])
                {
                    default:
                        $this->db->order_by($pagingParams['order_by'], $pagingParams['order_direction']);
                        break;
                }
            }

            $search = $pagingParams['search'];
            if (!empty($search))
            {
                $this->db->like('imagetitle', $search);
            }

            $return = $this->get_with_count(null, $pagingParams['records_per_page'], $pagingParams['offset']);
            return $return;
        }
        
        public function get_all_gallery_images()
        {
            $data = array();
            $this->db->select('*');
            $this->db->from('gallery');            
            $this->db->order_by('orderid');
            $query = $this->db->get();

            foreach ($query->result_array() as $row)
            {
                $data[] = $row;
            }
    
            return $data;
        }
        
        public function getallgallery_array($filterArr = array())
        {
            $data = array();
            $this->db->select('*');
            $this->db->from('gallery');
            if (!empty($filterArr))
            {
                $this->db->where($filterArr);
            }
            $this->db->order_by('orderid');
            $query = $this->db->get();

            foreach ($query->result_array() as $row)
            {
                $data[$row['imageid']] = $row['imagetitle'];
            }
            return $data;
        }

        public function savegallery($dataValues)
        {
            $return = null;
            if (count($dataValues) > 0)
            {
                if (array_key_exists('imageid', $dataValues))
                {
                    $this->db->where('imageid', $dataValues['imageid']);
                    $this->db->update('gallery', $dataValues);
                    $return = $dataValues['imageid'];
                }
                else
                {
                    $this->db->insert('gallery', $dataValues);
                    $return = $this->db->insert_id();
                }
            }
            return $return;
        }

        public function getgalleryrecord($filterArr)
        {
            $return = NULL;
            if (!empty($filterArr))
            {
                $this->db->select('gallery.*');
                $this->db->from('gallery');
                $this->db->where($filterArr);
                $return = $this->db->get()->row();
            }
            return $return;
        }

        public function deletegallerybyid($id)
        {
            $this->db->delete('gallery', array('imageid' => $id));
        }
        
         public function setorder($id, $order)
        {
            $arr = array(
                "orderid" => $order,
            );
            $this->db->where('imageid', $id);
            $this->db->update('gallery', $arr);
        }

    }
    