<?php

    if (!defined('BASEPATH'))
        exit('No direct script access allowed');

    class Datatable
    {

        private $_CI;
        private $_systemOperations = array('VIEW', 'VIEW_ICON', 'EDIT', 'EDIT_ICON', 'DELETE', 'DELETE_ICON', 'OTHER_ICON', 'OTHER_TEXT', 'STATUS_ICON');

        public function __construct()
        {
            $this->_CI = & get_instance();
            $this->_CI->load->config('datatable_config');
        }

        public function make_table($configIndex, $table_config)
        {
            $arr = $this->_CI->config->config[$configIndex];
            $columns = array_keys($arr);
            $arrayWidth = $this->get_column_widths($configIndex);
            $headings = array();

            foreach ($columns as $idx => $column)
            {
                $column_heading = $this->_CI->lang->line($column);
                if (empty($column_heading))
                {
                    $column_heading = $column;
                }

                $cell = array('data' => $column_heading, 'width' => $arrayWidth[$idx]);

                $headings[] = $cell;
            }


            $jsonSortable = $this->get_sortable_columns($configIndex);

            $this->_CI->load->library('Table');

            if (empty($table_config['datatable_class']))
            {
                $css_class = 'class="dyntable"';
            }
            else
            {
                $css_class = 'class="dyntable table table-bordered table-hover dataTable"';
            }

            if (empty($table_config['table_id']))
            {
                $tableId = uniqid();
            }
            else
            {
                $tableId = $table_config['table_id'];
            }

            if (empty($table_config['order_by']))
            {
                $order_by = "[0, 'asc']";
            }
            else
            {
                $order_by = $table_config['order_by'];
            }

            if (isset($table_config['source']))
            {
                $open = "<table jsonInfo='$jsonSortable' id='$tableId' $css_class source='{$table_config['source']}' data-order-by = \"{$order_by}\" width='100%'>";
            }
            else
            {
                $open = '<table id="' . $tableId . '" class="dyntable" width="100%">';
            }


            $tmpl = array(
                "table_open" => $open,
                'thead_open' => '<thead>',
                'thead_close' => '</thead>',
                "tfoot_open" => "<tfoot>",
                "footer_row_start" => "<tr>",
                "footer_cell_start" => "<td>",
                "footer_cell_end" => "</td>",
                "footer_row_end" => "</tr>",
                "tfoot_close" => "</tfoot>"
            );

            $this->_CI->table->set_template($tmpl);

//            if (!empty($arrayWidth) && is_array($arrayWidth))
//            {
//                $this->_CI->table->widths = $arrayWidth;
//            }
//            $this->_CI->table->set_heading(array_keys($columns));
            $this->_CI->table->set_heading($headings);
            return $this->_CI->table->generate($tableId);
        }

        /**
         * Get paging parameters from GET/config vars.
         *
         * Creates an array of four elements that we can use to send paging/sorting parameter to BL.
         *
         * @param array $sortColumns is an array of grid columns that can be used for sorting.
         *
         * @return array contaning the following elements.
         *
         * offset
         * records_per_page
         * order_by
         * order_direction
         *
         */
        public function get_paging_params(array $sortColumns)
        {
            $sortColumns = array_values($sortColumns);

            $sortColIndex = $this->_CI->input->get("order[0][column]");
            $sortColIndex = empty($sortColIndex) ? "0" : $sortColIndex;
            if (!empty($sortColumns[$sortColIndex]))
            {
                $sort = $sortColumns[$sortColIndex];
                $order = $this->_CI->input->get("order[0][dir]");
            }
            $pagingParams = array();
            $pagingParams['order_by'] = $sort;
            $pagingParams['order_direction'] = $order;

            // Get start counter of the records to be displayed.
            $offset = $this->_CI->input->get('start');
            $pagingParams['offset'] = intval($offset);

            $search = $this->_CI->input->get('search[value]');
            $pagingParams['search'] = $search;

            //$records_per_page = $this->config->item('records_per_page');
            $records_per_page = $this->_CI->input->get('length');
            $pagingParams['records_per_page'] = $records_per_page;
            return $pagingParams;
        }

        public function get_column_widths($reportName = null)
        {
            $reportColumns = $this->_CI->config->item($reportName);

            if (empty($reportColumns))
            {
                Throw New Exception();
            }

            $widthArr = array();

            foreach ($reportColumns as $key => $arr)
            {
                if (!empty($arr['width']))
                {
                    $widthArr[] = $arr['width'];
                }
            }

            return $widthArr;
        }

        public function get_sortable_columns($reportName = null)
        {
            $reportColumns = $this->_CI->config->item($reportName);

            if (empty($reportColumns))
            {
                Throw New Exception();
            }

            $sortableArr = array();

            foreach ($reportColumns as $key => $arr)
            {
                if (array_key_exists('isSortable', $arr))
                {
                    $flag = $arr['isSortable'] == TRUE ? TRUE : FALSE;
                }
                else
                {
                    $flag = TRUE;
                }

                array_push($sortableArr, array('orderable' => $flag));
            }

            return json_encode($sortableArr);
        }

        public function get_report_columns($reportName = null)
        {
            $reportColumns = $this->_CI->config->item($reportName);

            if (empty($reportColumns))
            {
                Throw New Exception();
            }
            else
            {
                return $reportColumns;
            }
        }

        public function get_json_output(array $resultSetObject, $reportColumnsKey, $obj = null)
        {
            $dataArray = $resultSetObject['resultSet'];

            $tableResponse = array();
            $tableResponse['iTotalRecords'] = empty($resultSetObject['foundRows']) ? 0 : $resultSetObject['foundRows'];
            $tableResponse['iTotalDisplayRecords'] = empty($resultSetObject['foundRows']) ? 0 : $resultSetObject['foundRows'];
            $tableResponse['aaData'] = array();

            $reportColumns = $this->get_report_columns($reportColumnsKey);

            if (count($dataArray) > 0 && count($reportColumns) > 0)
            {
                foreach ($dataArray as $data)
                {
                    $tmp = array();
                    foreach ($reportColumns as $key => $valueArr)
                    {
                        $align_div_start = '';
                        $align_div_end = '';

                        if (array_key_exists('align', $valueArr) && !empty($valueArr['align']))
                        {
                            $align_div_start = '<div align="' . $valueArr['align'] . '">';
                            $align_div_end = '</div>';
                        }

                        $field = empty($valueArr['jsonField']) ? '' : $valueArr['jsonField'];
                        if (array_key_exists('isLink', $valueArr) == TRUE && $valueArr['isLink'] == TRUE)
                        {
                            $paramString = '';
                            $propertyString = '';
                            $success_access = TRUE;
                            if (array_key_exists('moduleName', $valueArr) && !empty($valueArr['moduleName']) && array_key_exists("checkAccess", $valueArr) && !empty($valueArr['checkAccess']))
                            {
                                $module = $valueArr['moduleName'];
                                $module_name = $data->$module;
                                $access_type = $valueArr['accessType'];
                                $operator_data = GetLoggedinOperatorData();
                                $check_access = check_access($module_name, $operator_data['group_id'], $access_type);
                                if (empty($check_access))
                                {
                                    $success_access = FALSE;
                                }
                            }
                            if (!empty($success_access))
                            {
                                if (array_key_exists('linkTarget', $valueArr) && !empty($valueArr['linkTarget']))
                                {
                                    if (!empty($valueArr['linkAtts']))
                                    {
                                        foreach ($valueArr['linkAtts'] as $property => $propertyValue)
                                        {
                                            if (!empty($propertyValue['type']) && $propertyValue['type'] = 'dynamic')
                                            {
                                                $tmpValue = $data->{$propertyValue['field']};
                                            }
                                            else
                                            {
                                                $tmpValue = $propertyValue['value'];
                                            }

                                            $propertyString .= "$property='$tmpValue' ";
                                        }
                                    }

                                    if (array_key_exists('linkParams', $valueArr) && !empty($valueArr['linkParams']))
                                    {
                                        $tmpArray = array();

                                        foreach ($valueArr['linkParams'] as $varName => $varValueField)
                                        {
                                            $tmpArray[] = $data->$varValueField;
                                        }
                                        $paramString = implode('/', $tmpArray);
                                    }

                                    $additionalInlineJs = NULL;
                                    if (array_key_exists('systemDefaults', $valueArr) && !empty($valueArr['systemDefaults']))
                                    {
                                        if (!empty($valueArr['type']) && in_array(strtoupper($valueArr['type']), $this->_systemOperations))
                                        {
                                            if ($valueArr['type'] == 'DELETE' || $valueArr['type'] == 'DELETE_ICON' && !empty($valueArr['confirmBox']))
                                            {
                                                $additionalInlineJs = "onClick=\"javascript:return confirm('Are you sure you want to delete?')\"";
                                            }
                                            $linkCaption = $this->_parse_link_caption($valueArr);
                                        }
                                        else
                                        {
                                            Throw New Exception('Something weird happened.');
                                        }
                                    }
                                    else
                                    {
                                        $linkCaption = $align_div_start . $data->$field . $align_div_end;
                                    }

                                    if (!empty($valueArr['linkClass']))
                                    {

                                        $class = "class=" . $valueArr['linkClass'];
                                    }
                                    else
                                    {
                                        $class = "";
                                    }

                                    $tmp[] = $align_div_start . "<a $additionalInlineJs $class id='" . $paramString . "' href=\"" . site_url($valueArr['linkTarget'] . $paramString) . "\" $propertyString>" . $linkCaption . "</a>" . $align_div_end;
                                }
                                else
                                {
                                    $tmp[] = $align_div_start . $data->$field . $align_div_end;
                                }
                            }
                            else
                            {
                                $tmp[] = "-";
                            }
                        }
                        elseif (array_key_exists('isCheckBox', $valueArr) == TRUE && $valueArr['isCheckBox'] == TRUE)
                        {
                            $varValueField = $valueArr['CheckBoxParams'][0];
                            $tmpValue = $data->$varValueField;

                            $chk_name = $valueArr["CheckBoxName"] . "[]";
                            $chk_id = $valueArr["CheckBoxName"] . "_" . $tmpValue;

                            $tmp[] = $align_div_start . "<input type='checkbox' name='$chk_name' id='$chk_id' value='$tmpValue'>" . $align_div_end;
                        }
                        else
                        {
                            $tmp[] = $align_div_start . $this->_parse_field($valueArr, $data, $field) . $align_div_end;
                        }
                    }
                    $tableResponse['aaData'][] = $tmp;
                }
            }

            return $tableResponse;
        }

        private function _parse_field($configArr, $objectRow, $fieldName)
        {

            $fieldValue = $objectRow->$fieldName;
            $return = $fieldValue;

            if (!empty($configArr['callBack']) && !empty($configArr['callBackType']) && !empty($configArr['callBackClass']) && !empty($configArr['callBackFunction']))
            {

                $triggerCallback = true;
                $class = strtolower($configArr['callBackClass']);
                $function = $configArr['callBackFunction'];

                switch ($configArr['callBackType'])
                {
                    case 'library':
                    case 'model':
                        $callBackType = $configArr['callBackType'];
                        $this->_CI->load->$callBackType($class);
                        $return = $this->_CI->$class->$function($fieldValue, $objectRow);
                        break;
                    case 'helper':
                        $callBackType = $configArr['callBackType'];
                        $this->_CI->load->$callBackType($class);
                        $return = $function($fieldValue, $objectRow);
                    default:
                        $triggerCallback = false;
                }

                //@TODO - Static methods ? is_callable implementation is to be done                
//                $return = $triggerCallback === true ? $this->_CI->$class->$function($fieldValue, $objectRow) : $fieldValue;
            }

            return $return;
        }

        private function _parse_link_caption($valueArr)
        {
            $operation = $valueArr['type'];

            switch ($operation)
            {
                case 'VIEW':
                    $return = 'VIEW'; // later we will get values from lang
                    break;
                case 'VIEW_ICON':
                    $return = '<i class="fa fa-eye action-icon"></i>';
                    break;
                case 'EDIT':
                    $return = 'EDIT'; // later we will get values from lang
                    break;
                case 'EDIT_ICON':
                    $return = '<i class="fa fa-pencil action-icon"></i>';
                    break;
                case 'STATUS_ICON':
                    $return = '<i class="fa fa-toggle-on  action-icon"></i>';
                    break;
                case 'DELETE':
                    $return = 'DELETE'; // later we will get values from lang
                    break;
                case 'DELETE_ICON':
                    $return = '<i class="fa fa-trash-o action-icon"></i>';
                    break;
                case 'OTHER_ICON':
                    if (empty($valueArr['iconName']))
                    {
                        Throw New Exception('Icon path was not found');
                    }
                    $return = '<i class="fa ' . $valueArr['iconName'] . ' action-icon"></i>';
//                    $title = empty($valueArr['titleText']) ? '' : "title = '{$valueArr['titleText']}'";
//                    $return = "<img src='" . base_url() . "{$valueArr['iconPath']}' $title>";
                    break;
                case 'OTHER_TEXT':
                    if (empty($valueArr['otherText']))
                    {
                        Throw New Exception('Link text missing');
                    }

                    $return = $valueArr['otherText'];
                    break;
            }

            return $return;
        }

    }
    