<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Slider_model extends My_Model
    {

        public function __construct()
        {
            parent::__construct();
        }

        public function getallslider($pagingParams = array())
        {
            $this->db->select('SQL_CALC_FOUND_ROWS 1', false);
            $this->db->select('slider.*');
            $this->db->from('slider');

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
                $this->db->like('slidetitle', $search);
            }

            $return = $this->get_with_count(null, $pagingParams['records_per_page'], $pagingParams['offset']);
            return $return;
        }

        public function get_all_slider_slides()
        {
            $data = array();
            $this->db->select('*');
            $this->db->from('slider');
            $this->db->order_by('orderid');
            $query = $this->db->get();

            foreach ($query->result_array() as $row)
            {
                $data[] = $row;
            }

            return $data;
        }

        public function getallslider_array($filterArr = array())
        {
            $data = array();
            $this->db->select('*');
            $this->db->from('slider');
            if (!empty($filterArr))
            {
                $this->db->where($filterArr);
            }
            $this->db->order_by('orderid');
            $query = $this->db->get();

            foreach ($query->result_array() as $row)
            {
                $data[$row['slideid']] = $row['slidetitle'];
            }
            return $data;
        }

        public function saveslider($dataValues)
        {
            $return = null;
            if (count($dataValues) > 0)
            {
                if (array_key_exists('slideid', $dataValues))
                {
                    $this->db->where('slideid', $dataValues['slideid']);
                    $this->db->update('slider', $dataValues);
                    $return = $dataValues['slideid'];
                }
                else
                {
                    $this->db->insert('slider', $dataValues);
                    $return = $this->db->insert_id();
                }
            }
            return $return;
        }
        public function saveclient($dataValues)
        {
            $return = null;
            if (count($dataValues) > 0)
            {
                if (array_key_exists('slideid', $dataValues))
                {
                    $this->db->where('slideid', $dataValues['slideid']);
                    $this->db->update('slider', $dataValues);
                    $return = $dataValues['slideid'];
                }
                else
                {
                    $this->db->insert('client', $dataValues);
                    $return = $this->db->insert_id();
                }
            }
            return $return;
        }

        public function getsliderrecord($filterArr)
        {
            $return = NULL;
            if (!empty($filterArr))
            {
                $this->db->select('slider.*');
                $this->db->from('slider');
                $this->db->where($filterArr);
                $return = $this->db->get()->row();
            }
            return $return;
        }
        
        public function getsliderpage($filterArr)
        {
            $return = NULL;
            if (!empty($filterArr))
            {
                $this->db->select('slider.slidename');
                $this->db->from('slider');
                $this->db->where($filterArr[0],$filterArr[1]);
                $return = $this->db->get()->result_array();
            }
            return $return;
        }
        
        public function deletesliderbyid($slideid)
        {
            $this->db->delete('slider', array('slideid' => $slideid));
        }

    }
    