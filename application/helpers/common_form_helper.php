<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 공통 폼 요소 생성 Helper
 * 클래식 ASP 코드를 PHP CodeIgniter로 변환
 */

/**
 * 날짜 입력 필드 생성 (단일)
 * @param string $s_date 날짜 값
 * @return string HTML
 */
if (!function_exists('com_date_1')) {
    function com_date_1($s_date = '')
    {
        $html = '<input type="text" id="S_DATE" name="S_DATE" style="width:70px;height:25px;" class="text-input-style" value="' . htmlspecialchars($s_date) . '">';
        return $html;
    }
}

/**
 * 날짜 입력 필드 생성 (범위)
 * @param string $s_date 시작 날짜
 * @param string $s_date2 종료 날짜
 * @return string HTML
 */
if (!function_exists('com_date_2')) {
    function com_date_2($s_date = '', $s_date2 = '')
    {
        $html = '<input type="text" id="S_DATE" name="S_DATE" style="width:70px;height:25px;" class="text-input-style" value="' . htmlspecialchars($s_date) . '">';
        $html .= ' ~ ';
        $html .= '<input type="text" id="S_DATE2" name="S_DATE2" style="width:70px;height:25px;" class="text-input-style" value="' . htmlspecialchars($s_date2) . '">';
        return $html;
    }
}

/**
 * 사업장 선택 박스 생성
 * @param string $office_cd 선택된 사업장 코드
 * @param string $opt_item1 옵션 아이템1
 * @return string HTML
 */
if (!function_exists('com_office_cd')) {
    function com_office_cd($office_cd = '', $opt_item1 = '')
    {
        $CI =& get_instance();
        $CI->load->model('Common_model');
        
        $office_list = array();
        try {
            $result = $CI->Common_model->get_common_code_list('T01');
            if (!empty($result)) {
                foreach ($result as $row) {
                    $office_list[] = array(
                        'cd' => trim($row->COMN_CD ?? ''),
                        'nm' => trim($row->CD_NM ?? '')
                    );
                }
            }
        } catch (Exception $e) {
            log_message('error', 'Failed to load office list: ' . $e->getMessage());
        }
        
        $html = '<select name="OFFICE_CD" id="OFFICE_CD" style="width:80px;" class="custom-select">' . "\n";
        $html .= '<option value="">전체</option>' . "\n";
        
        if (!empty($office_list)) {
            foreach ($office_list as $item) {
                $com_cd = $item['cd'];
                $com_nm = $item['nm'];
                if (empty($com_cd)) continue;
                $selected = (!empty($office_cd) && $office_cd == $com_cd) ? ' selected' : '';
                $html .= '<option value="' . htmlspecialchars($com_cd) . '"' . $selected . '>' . htmlspecialchars($com_nm) . '</option>' . "\n";
            }
        }
        
        $html .= '</select>' . "\n";
        return $html;
    }
}

/**
 * 입출고 구분 선택 박스 생성
 * @param string $io_type 선택된 입출고 구분
 * @param string $opt_item1 옵션 아이템1
 * @return string HTML
 */
if (!function_exists('com_io_type')) {
    function com_io_type($io_type = '', $opt_item1 = '')
    {
        $CI =& get_instance();
        $CI->load->model('Common_model');
        
        $io_list = array();
        try {
            $result = $CI->Common_model->get_common_code_list('T02');
            if (!empty($result)) {
                foreach ($result as $row) {
                    $io_list[] = array(
                        'cd' => trim($row->COMN_CD ?? ''),
                        'nm' => trim($row->CD_NM ?? '')
                    );
                }
            }
        } catch (Exception $e) {
            log_message('error', 'Failed to load IO type list: ' . $e->getMessage());
        }
        
        $html = '<select name="IO_TYPE" id="IO_TYPE" style="width:80px;" class="custom-select">' . "\n";
        $html .= '<option value="">전체</option>' . "\n";
        
        if (!empty($io_list)) {
            foreach ($io_list as $item) {
                $com_cd = $item['cd'];
                $com_nm = $item['nm'];
                if (empty($com_cd)) continue;
                $selected = (!empty($io_type) && $io_type == $com_cd) ? ' selected' : '';
                $html .= '<option value="' . htmlspecialchars($com_cd) . '"' . $selected . '>' . htmlspecialchars($com_nm) . '</option>' . "\n";
            }
        }
        
        $html .= '</select>' . "\n";
        return $html;
    }
}

/**
 * 배차 유형 선택 박스 생성
 * @param string $aloc_type 선택된 배차 유형
 * @return string HTML
 */
if (!function_exists('com_aloc_type')) {
    function com_aloc_type($aloc_type = '')
    {
        $CI =& get_instance();
        $CI->load->model('Common_model');
        
        $aloc_list = array();
        try {
            $result = $CI->Common_model->get_common_code_list('T03');
            if (!empty($result)) {
                foreach ($result as $row) {
                    $aloc_list[] = array(
                        'cd' => trim($row->COMN_CD ?? ''),
                        'nm' => trim($row->CD_NM ?? '')
                    );
                }
            }
        } catch (Exception $e) {
            log_message('error', 'Failed to load allocation type list: ' . $e->getMessage());
        }
        
        $html = '<select name="ALOC_TYPE" id="ALOC_TYPE" style="width:100px;" class="custom-select">' . "\n";
        $html .= '<option value="">전체</option>' . "\n";
        
        if (!empty($aloc_list)) {
            foreach ($aloc_list as $item) {
                $com_cd = $item['cd'];
                $com_nm = $item['nm'];
                if (empty($com_cd)) continue;
                $selected = (!empty($aloc_type) && $aloc_type == $com_cd) ? ' selected' : '';
                $html .= '<option value="' . htmlspecialchars($com_cd) . '"' . $selected . '>' . htmlspecialchars($com_nm) . '</option>' . "\n";
            }
        }
        
        $html .= '</select>' . "\n";
        return $html;
    }
}

/**
 * 검색 유형 선택 박스 생성
 * @param string $n_field 선택된 검색 유형
 * @param string $opt_item1 옵션 아이템1
 * @return string HTML
 */
if (!function_exists('com_search_type')) {
    function com_search_type($n_field = '', $opt_item1 = '')
    {
        $CI =& get_instance();
        $CI->load->model('Common_model');
        
        $search_list = array();
        try {
            $result = $CI->Common_model->get_common_code_list('T04');
            if (!empty($result)) {
                foreach ($result as $row) {
                    $search_list[] = array(
                        'cd' => trim($row->COMN_CD ?? ''),
                        'nm' => trim($row->CD_NM ?? '')
                    );
                }
            }
        } catch (Exception $e) {
            log_message('error', 'Failed to load search type list: ' . $e->getMessage());
        }
        
        $html = '<select name="N_Field" id="N_Field" style="width:80px;" class="custom-select">' . "\n";
        $html .= '<option value="">전체</option>' . "\n";
        
        if (!empty($search_list)) {
            foreach ($search_list as $item) {
                $com_cd = $item['cd'];
                $com_nm = $item['nm'];
                if (empty($com_cd)) continue;
                $selected = (!empty($n_field) && $n_field == $com_cd) ? ' selected' : '';
                $html .= '<option value="' . htmlspecialchars($com_cd) . '"' . $selected . '>' . htmlspecialchars($com_nm) . '</option>' . "\n";
            }
        }
        
        $html .= '</select>' . "\n";
        return $html;
    }
}

/**
 * 배차 상태 선택 박스 생성
 * @param string $aloc_stat 선택된 배차 상태
 * @return string HTML
 */
if (!function_exists('com_aloc_stat')) {
    function com_aloc_stat($aloc_stat = '')
    {
        $CI =& get_instance();
        $CI->load->model('Common_model');
        
        $stat_list = array();
        try {
            $result = $CI->Common_model->get_common_code_list('T05');
            if (!empty($result)) {
                foreach ($result as $row) {
                    $stat_list[] = array(
                        'cd' => trim($row->COMN_CD ?? ''),
                        'nm' => trim($row->CD_NM ?? '')
                    );
                }
            }
        } catch (Exception $e) {
            log_message('error', 'Failed to load allocation status list: ' . $e->getMessage());
        }
        
        $html = '<select name="ALOC_STAT" id="ALOC_STAT" style="width:100px;" class="custom-select">' . "\n";
        $html .= '<option value="">전체</option>' . "\n";
        
        if (!empty($stat_list)) {
            foreach ($stat_list as $item) {
                $com_cd = $item['cd'];
                $com_nm = $item['nm'];
                if (empty($com_cd)) continue;
                $selected = (!empty($aloc_stat) && $aloc_stat == $com_cd) ? ' selected' : '';
                $html .= '<option value="' . htmlspecialchars($com_cd) . '"' . $selected . '>' . htmlspecialchars($com_nm) . '</option>' . "\n";
            }
        }
        
        $html .= '</select>' . "\n";
        return $html;
    }
}

/**
 * 고객 교환 선택 박스 생성
 * @param string $cust_exchange 선택된 고객 교환
 * @return string HTML
 */
if (!function_exists('com_cust_exchange')) {
    function com_cust_exchange($cust_exchange = '')
    {
        $CI =& get_instance();
        $CI->load->model('Common_model');
        
        $exchange_list = array();
        try {
            $result = $CI->Common_model->get_common_code_list('T06');
            if (!empty($result)) {
                foreach ($result as $row) {
                    $exchange_list[] = array(
                        'cd' => trim($row->COMN_CD ?? ''),
                        'nm' => trim($row->CD_NM ?? '')
                    );
                }
            }
        } catch (Exception $e) {
            log_message('error', 'Failed to load customer exchange list: ' . $e->getMessage());
        }
        
        $html = '<select name="CUST_EXCHANGE" id="CUST_EXCHANGE" style="width:100px;" class="custom-select">' . "\n";
        $html .= '<option value="">선택</option>' . "\n";
        
        if (!empty($exchange_list)) {
            foreach ($exchange_list as $item) {
                $com_cd = $item['cd'];
                $com_nm = $item['nm'];
                if (empty($com_cd)) continue;
                $selected = (!empty($cust_exchange) && $cust_exchange == $com_cd) ? ' selected' : '';
                $html .= '<option value="' . htmlspecialchars($com_cd) . '"' . $selected . '>' . htmlspecialchars($com_nm) . '</option>' . "\n";
            }
        }
        
        $html .= '</select>' . "\n";
        return $html;
    }
}

/**
 * 구간배차 작업 오더 선택 박스 생성
 * @param string $work_value 선택된 작업 값
 * @param string $t_date 날짜 (YYYYMMDD 형식)
 * @param string $aloc_type 배차유형
 * @return string HTML
 */
if (!function_exists('com_section_work_order')) {
    function com_section_work_order($work_value = '', $t_date = '', $aloc_type = '')
    {
        $CI =& get_instance();
        $CI->load->model('Common_model');

        // T_DATE가 비어있으면 오늘 날짜 사용
        if (empty($t_date)) {
            $t_date = date('Ymd');
        } else {
            // YYYY-MM-DD 형식이면 YYYYMMDD로 변환
            if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $t_date)) {
                $t_date = str_replace('-', '', $t_date);
            }
        }

        // Proc_So_Aloc_Section_Work_Order 프로시저 호출
        $work_list = array();
        try {
            $sql = "EXEC [dbo].[Proc_So_Aloc_Section_Work_Order] @T_DATE = ?, @ALOC_TYPE = ?";
            $query = $CI->db->query($sql, array($t_date, $aloc_type));

            if ($query) {
                $result = $query->result();

                if (!empty($result)) {
                    foreach ($result as $row) {
                        // 가능한 모든 컬럼명 시도
                        $cd = '';
                        $nm = '';

                        // CD 값 찾기
                        if (isset($row->WORK_VALUE)) $cd = trim($row->WORK_VALUE);
                        elseif (isset($row->CD)) $cd = trim($row->CD);
                        elseif (isset($row->COMN_CD)) $cd = trim($row->COMN_CD);
                        elseif (isset($row->COM_CD)) $cd = trim($row->COM_CD);

                        // NM 값 찾기
                        if (isset($row->WORK_NM)) $nm = trim($row->WORK_NM);
                        elseif (isset($row->CD_NM)) $nm = trim($row->CD_NM);
                        elseif (isset($row->COM_NM)) $nm = trim($row->COM_NM);
                        elseif (isset($row->NM)) $nm = trim($row->NM);

                        // 빈 값인 경우 건너뛰기
                        if (empty($cd)) continue;

                        $work_list[] = array(
                            'cd' => $cd,
                            'nm' => $nm
                        );
                    }
                }
            }
        } catch (Exception $e) {
            log_message('error', 'Failed to load section work order list: ' . $e->getMessage());
        }
        
        $html = '<select name="WORK_VALUE" id="WORK_VALUE" style="width:100px;" class="custom-select">' . "\n";
        $html .= '<option value="">선택</option>' . "\n";
        
        if (!empty($work_list)) {
            foreach ($work_list as $item) {
                $work_cd = $item['cd'];
                $work_nm = $item['nm'];
                // 빈 값인 경우 옵션 출력하지 않음
                if (empty($work_cd)) continue;
                $selected = (!empty($work_value) && $work_value == $work_cd) ? ' selected' : '';
                $html .= '<option value="' . htmlspecialchars($work_cd) . '"' . $selected . '>' . htmlspecialchars($work_nm) . '</option>' . "\n";
            }
        }

        $html .= '</select>' . "\n";
        return $html;
    }
}

/**
 * 배차관리 Grid 데이터 가져오기
 * @param string $t_date 날짜 (YYYYMMDD)
 * @param string $office_cd 사업장 코드
 * @param string $io_type 입출고 구분
 * @param string $so_mode 상품 모드
 * @param string $aloc_type 배차 유형
 * @param string $s_code 검색 코드
 * @param string $s_text 검색 텍스트
 * @param string $sort_sql 정렬 SQL
 * @param array $Grid_Data 이미 설정된 Grid 데이터 (선택적)
 * @param bool $debug 디버그 출력 여부
 * @return array Grid 데이터 배열
 */
if (!function_exists('get_allocation_car_grid_data')) {
    function get_allocation_car_grid_data($t_date = '', $office_cd = '', $io_type = '', $so_mode = '', $aloc_type = '', $s_code = '', $s_text = '', $sort_sql = '', $Grid_Data = null, $debug = true)
    {
        $CI =& get_instance();
        $grid_data = array();
        $query_result = null; // 디버깅용

        // Grid_Data가 이미 설정되어 있으면 사용, 없으면 프로시저 실행
        if (isset($Grid_Data) && !empty($Grid_Data)) {
            $grid_data = $Grid_Data;
        } else {
            try {
                // 프로시저 실행
                $sql = "EXEC [dbo].[Proc_So_Aloc_List_7_Json] @T_DATE = ?, @OFFICE_CD = ?, @IO_TYPE = ?, @SO_MODE = ?, @ALOC_TYPE = ?, @S_CODE = ?, @S_TEXT = ?, @SORT_SQL = ?";
                
                $query = $CI->db->query($sql, array(
                    $t_date,        // @T_DATE VARCHAR(10)
                    $office_cd,     // @OFFICE_CD VARCHAR(2)
                    $io_type,       // @IO_TYPE VARCHAR(10)
                    $so_mode,       // @SO_MODE VARCHAR(50)
                    $aloc_type,     // @ALOC_TYPE VARCHAR(10)
                    $s_code,        // @S_CODE VARCHAR(2)
                    $s_text,        // @S_TEXT VARCHAR(50)
                    $sort_sql       // @SORT_SQL VARCHAR(100)
                ));
                
                if ($query) {
                    // 프로시저가 JSON 문자열을 반환하는 경우 처리
                    $result = $query->result();
                    $query_result = $result; // 디버깅용 저장
                    
                    if (!empty($result)) {
                        // 첫 번째 행 확인
                        $first_row = $result[0];
                        $row_vars = null;
                        
                        if (is_object($first_row)) {
                            $row_vars = get_object_vars($first_row);
                        } elseif (is_array($first_row)) {
                            $row_vars = $first_row;
                        }
                        
                        if ($row_vars !== null) {
                            // JsonData 컬럼이 있는지 확인
                            if (isset($row_vars['JsonData'])) {
                                $json_string = $row_vars['JsonData'];
                                
                                // NULL 체크
                                if (is_null($json_string)) {
                                    log_message('error', 'JsonData가 NULL입니다.');
                                    $grid_data = array();
                                } 
                                // 빈 문자열 체크 (NULL이 아닌 빈 문자열도 체크)
                                elseif ($json_string === '' || $json_string === false || empty($json_string)) {
                                    log_message('error', 'JsonData가 빈 문자열입니다.');
                                    $grid_data = array();
                                } 
                                // JSON 문자열 디코딩
                                else {
                                    $decoded = json_decode($json_string, true);
                                    if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                        $grid_data = $decoded;
                                    } else {
                                        // JSON 디코딩 실패 시 빈 배열
                                        log_message('error', 'JSON 디코딩 실패: ' . json_last_error_msg() . ' | JsonData 값: ' . substr($json_string, 0, 200));
                                        $grid_data = array();
                                    }
                                }
                            } 
                            // JsonData가 없으면 첫 번째 컬럼 확인
                            else if (!empty($row_vars)) {
                                $first_value = reset($row_vars);
                                // JSON 문자열인지 확인
                                $decoded = json_decode($first_value, true);
                                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                                    $grid_data = $decoded;
                                } else {
                                    // 일반 결과셋인 경우 - 하지만 JsonData 컬럼이 없으면 빈 배열로 설정
                                    // (프로시저가 JsonData를 반환해야 하는데 없으면 데이터가 없는 것으로 간주)
                                    $grid_data = array();
                                }
                            } else {
                                $grid_data = array();
                            }
                        } else {
                            // 첫 번째 행을 파싱할 수 없으면 빈 배열로 설정
                            $grid_data = array();
                        }
                    }
                }
            } catch (Exception $e) {
                // 에러 발생 시 로그 기록 및 빈 배열 반환
                log_message('error', '프로시저 실행 실패: ' . $e->getMessage());
                $grid_data = array();
            }
        }

        // Grid 데이터를 JSON으로 인코딩 (빈값이거나 null일 경우 빈 배열 "[]"로 설정)
        // JsonData가 NULL이거나 빈 값인 경우 빈 배열로 설정
        if (empty($grid_data) || is_null($grid_data)) {
            $grid_data = array();
        } else {
            // grid_data가 프로시저 결과 객체를 포함하고 있는지 확인
            if (is_array($grid_data) && count($grid_data) > 0) {
                $first_item = $grid_data[0];
                if (is_array($first_item) && isset($first_item['JsonData'])) {
                    // JsonData가 NULL이거나 빈 값이면 빈 배열로 설정
                    $json_data_val = $first_item['JsonData'];
                    if (is_null($json_data_val) || $json_data_val === '' || $json_data_val === false || empty($json_data_val)) {
                        $grid_data = array();
                    }
                } elseif (is_object($first_item)) {
                    $first_item_vars = get_object_vars($first_item);
                    if (isset($first_item_vars['JsonData'])) {
                        $json_data_val = $first_item_vars['JsonData'];
                        if (is_null($json_data_val) || $json_data_val === '' || $json_data_val === false || empty($json_data_val)) {
                            $grid_data = array();
                        }
                    }
                }
            }
        }

        // 디버깅 출력
        if ($debug) {
            echo "<!-- DEBUG: grid_data 출력 시작 -->\n";
            echo "<!-- grid_data 타입: " . gettype($grid_data) . " -->\n";
            echo "<!-- grid_data 개수: " . (is_array($grid_data) ? count($grid_data) : 'N/A') . " -->\n";
            if (is_array($grid_data) && count($grid_data) > 0) {
                echo "<!-- grid_data 첫 번째 항목: " . htmlspecialchars(print_r($grid_data[0], true)) . " -->\n";
            } else {
                echo "<!-- grid_data: 빈 배열 또는 데이터 없음 -->\n";
                // 프로시저 결과 확인
                if (!empty($query_result)) {
                    echo "<!-- 프로시저 결과 첫 번째 행: " . htmlspecialchars(print_r($query_result[0], true)) . " -->\n";
                    $row_vars = get_object_vars($query_result[0]);
                    if (isset($row_vars['JsonData'])) {
                        $json_data_value = $row_vars['JsonData'];
                        echo "<!-- JsonData 존재 여부: 있음 -->\n";
                        echo "<!-- JsonData 타입: " . gettype($json_data_value) . " -->\n";
                        echo "<!-- JsonData 값 (처음 1000자): " . htmlspecialchars(substr($json_data_value, 0, 1000)) . " -->\n";
                        if (is_null($json_data_value)) {
                            echo "<!-- JsonData가 NULL입니다 -->\n";
                        } elseif (empty($json_data_value)) {
                            echo "<!-- JsonData가 빈 값입니다 -->\n";
                        } else {
                            $test_decode = json_decode($json_data_value, true);
                            echo "<!-- JSON 디코딩 테스트 결과: " . (json_last_error() === JSON_ERROR_NONE ? '성공' : '실패 - ' . json_last_error_msg()) . " -->\n";
                        }
                    } else {
                        echo "<!-- JsonData 컬럼이 없습니다. 사용 가능한 컬럼: " . htmlspecialchars(implode(', ', array_keys($row_vars))) . " -->\n";
                    }
                }
            }
            echo "<!-- DEBUG: grid_data 출력 끝 -->\n";
        }

        return $grid_data;
    }
}
