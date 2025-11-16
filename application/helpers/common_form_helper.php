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
        $html = '<input type="text" id="S_DATE" name="S_DATE" size="10" class="text-input-style" value="' . htmlspecialchars($s_date) . '"> - ';
        $html .= '<input type="text" id="S_DATE2" name="S_DATE2" size="10" class="text-input-style" value="' . htmlspecialchars($s_date2) . '">';
        return $html;
    }
}

/**
 * 정렬 키/아이템 히든 필드 생성
 * @param int $cnt 개수
 * @param array $sort_keys 정렬 키 배열
 * @return string HTML
 */
if (!function_exists('com_sort')) {
    function com_sort($cnt, $sort_keys = array())
    {
        $html = '';
        
        // SORT_KEY 히든 필드
        for ($k = 1; $k <= $cnt; $k++) {
            $value = isset($sort_keys[$k - 1]) ? $sort_keys[$k - 1] : '';
            $html .= '<input type="hidden" name="SORT_KEY_' . $k . '" id="SORT_KEY_' . $k . '" value="' . htmlspecialchars($value) . '">' . "\n";
        }
        
        // SORT_ITEM 히든 필드
        for ($p = 1; $p <= $cnt; $p++) {
            $html .= '<input type="hidden" name="SORT_ITEM_' . $p . '" id="SORT_ITEM_' . $p . '">' . "\n";
        }
        
        return $html;
    }
}

/**
 * 사업장 코드 선택 박스 생성
 * @param string $office_cd 선택된 사업장 코드
 * @param string $opt_item1 옵션 아이템1
 * @return string HTML
 */
if (!function_exists('com_office_cd')) {
    function com_office_cd($office_cd = '', $opt_item1 = '')
    {
        $CI =& get_instance();
        $CI->load->model('Common_model');
        
        // 디버깅: 전달된 값 확인
        $debug_info = "<!-- DEBUG com_office_cd: office_cd='" . htmlspecialchars($office_cd) . "', opt_item1='" . htmlspecialchars($opt_item1) . "' -->\n";
        $debug_info .= "<!-- DEBUG: office_cd empty check = " . (empty($office_cd) ? 'true' : 'false') . " -->\n";
        $debug_info .= "<!-- DEBUG: office_cd value length = " . strlen($office_cd) . " -->\n";
        
        // T02 그룹 코드 조회
        $t02_list = array();
        try {
            $sql = "EXEC [dbo].[Proc_Com_Code_List] @GRP_CD = ?, @OPT_ITEM1 = ?";
            $query = $CI->db->query($sql, array('T02', $opt_item1));
            $result = $query->result();
            
            $debug_info .= "<!-- DEBUG: Query result count = " . count($result) . " -->\n";
            
            if (!empty($result)) {
                foreach ($result as $row) {
                    $cd = trim($row->CD ?? $row->COM_CD ?? '');
                    $nm = trim($row->CD_NM ?? $row->COM_NM ?? '');
                    $t02_list[] = array(
                        'cd' => $cd,
                        'nm' => $nm
                    );
                    $debug_info .= "<!-- DEBUG: Code item - cd='" . htmlspecialchars($cd) . "', nm='" . htmlspecialchars($nm) . "', match=" . ($office_cd == $cd ? 'true' : 'false') . " -->\n";
                }
            }
        } catch (Exception $e) {
            log_message('error', 'Failed to load office code list: ' . $e->getMessage());
            $debug_info .= "<!-- DEBUG: Exception = " . htmlspecialchars($e->getMessage()) . " -->\n";
        }
        
        $html = $debug_info;
        $html .= '<select name="OFFICE_CD" style="height:25px;" class="custom-select">' . "\n";
        $selected_all = (empty($office_cd)) ? ' selected' : '';
        $html .= '<option value=""' . $selected_all . '>전체</option>' . "\n";
        
        if (!empty($t02_list)) {
            foreach ($t02_list as $item) {
                $com_cd = $item['cd'];
                $com_nm = $item['nm'];
                // $office_cd가 빈값이 아닐 때만 selected 체크
                $selected = (!empty($office_cd) && $office_cd == $com_cd) ? ' selected' : '';
                $html .= '<option value="' . htmlspecialchars($com_cd) . '"' . $selected . '>' . htmlspecialchars($com_nm) . '</option>' . "\n";
            }
        }
        
        $html .= '</select>' . "\n";
        return $html;
    }
}

/**
 * 구분 코드 선택 박스 생성
 * @param string $io_type 선택된 구분 코드
 * @param string $opt_item1 옵션 아이템1
 * @return string HTML
 */
if (!function_exists('com_io_type')) {
    function com_io_type($io_type = '', $opt_item1 = '')
    {
        $CI =& get_instance();
        $CI->load->model('Common_model');
        
        // T38 그룹 코드 조회
        $t38_list = array();
        try {
            $sql = "EXEC [dbo].[Proc_Com_Code_List] @GRP_CD = ?, @OPT_ITEM1 = ?";
            $query = $CI->db->query($sql, array('T38', $opt_item1));
            $result = $query->result();
            
            if (!empty($result)) {
                foreach ($result as $row) {
                    $t38_list[] = array(
                        'cd' => trim($row->CD ?? $row->COM_CD ?? ''),
                        'nm' => trim($row->CD_NM ?? $row->COM_NM ?? '')
                    );
                }
            }
        } catch (Exception $e) {
            log_message('error', 'Failed to load IO type code list: ' . $e->getMessage());
        }
        
        $html = '<select name="IO_TYPE" id="IO_TYPE"  class="custom-select">' . "\n";
        $selected_all = (empty($io_type)) ? ' selected' : '';
        $html .= '<option value=""' . $selected_all . '>전체</option>' . "\n";
        
        if (!empty($t38_list)) {
            foreach ($t38_list as $item) {
                $com_cd = $item['cd'];
                $com_nm = $item['nm'];
                // $io_type이 빈값이 아닐 때만 selected 체크
                $selected = (!empty($io_type) && $io_type == $com_cd) ? ' selected' : '';
                $html .= '<option value="' . htmlspecialchars($com_cd) . '"' . $selected . '>' . htmlspecialchars($com_nm) . '</option>' . "\n";
            }
        }
        
        $html .= '</select>' . "\n";
        return $html;
    }
}

/**
 * 마감일별 코드 선택 박스 생성
 * @param string $mt_field 선택된 마감일별 코드
 * @return string HTML
 */
if (!function_exists('com_close_type')) {
    function com_close_type($mt_field = '')
    {
        $CI =& get_instance();
        $CI->load->model('Common_model');
        
        // T21 그룹 코드 조회
        $t21_list = array();
        try {
            $sql = "EXEC [dbo].[Proc_Com_Code_List] @GRP_CD = ?, @OPT_ITEM1 = ?";
            $query = $CI->db->query($sql, array('T21', ''));
            $result = $query->result();
            
            if (!empty($result)) {
                foreach ($result as $row) {
                    $t21_list[] = array(
                        'cd' => trim($row->CD ?? $row->COM_CD ?? ''),
                        'nm' => trim($row->CD_NM ?? $row->COM_NM ?? '')
                    );
                }
            }
        } catch (Exception $e) {
            log_message('error', 'Failed to load close type code list: ' . $e->getMessage());
        }
        
        $html = '<select name="MT_Field" style="width:50px;" class="Reg_Box">' . "\n";
        $html .= '<option value="">전체</option>' . "\n";
        
        if (!empty($t21_list)) {
            foreach ($t21_list as $item) {
                $com_cd = $item['cd'];
                $com_nm = $item['nm'];
                $selected = ($mt_field == $com_cd) ? ' selected' : '';
                $html .= '<option value="' . htmlspecialchars($com_cd) . '"' . $selected . '>' . htmlspecialchars($com_nm) . '</option>' . "\n";
            }
        }
        
        $html .= '</select>' . "\n";
        return $html;
    }
}

/**
 * 검색항목 코드 선택 박스 생성
 * @param string $n_field 선택된 검색항목 코드
 * @param string $opt_item1 옵션 아이템1
 * @return string HTML
 */
if (!function_exists('com_search_type')) {
    function com_search_type($n_field = '', $opt_item1 = 'A,B,C,')
    {
        $CI =& get_instance();
        $CI->load->model('Common_model');
        
        // T35 그룹 코드 조회
        $t35_list = array();
        try {
            $sql = "EXEC [dbo].[Proc_Com_Code_List] @GRP_CD = ?, @OPT_ITEM1 = ?";
            $query = $CI->db->query($sql, array('T35', $opt_item1));
            $result = $query->result();
            
            if (!empty($result)) {
                foreach ($result as $row) {
                    $t35_list[] = array(
                        'cd' => trim($row->CD ?? $row->COM_CD ?? ''),
                        'nm' => trim($row->CD_NM ?? $row->COM_NM ?? '')
                    );
                }
            }
        } catch (Exception $e) {
            log_message('error', 'Failed to load search type code list: ' . $e->getMessage());
        }
        
        $html = '<select name="N_Field" style="width:90px;height:25px;" class="custom-select">' . "\n";
        $html .= '<option value="">전체</option>' . "\n";
        
        if (!empty($t35_list)) {
            foreach ($t35_list as $item) {
                $com_cd = $item['cd'];
                $com_nm = $item['nm'];
                // $n_field이 빈값이 아닐 때만 selected 체크
                $selected = (!empty($n_field) && $n_field == $com_cd) ? ' selected' : '';
                $html .= '<option value="' . htmlspecialchars($com_cd) . '"' . $selected . '>' . htmlspecialchars($com_nm) . '</option>' . "\n";
            }
        }
        
        $html .= '</select>' . "\n";
        return $html;
    }
}

/**
 * 배차유형 코드 선택 박스 생성
 * @param string $aloc_type 선택된 배차유형 코드
 * @return string HTML
 */
if (!function_exists('com_aloc_type')) {
    function com_aloc_type($aloc_type = '')
    {
        $CI =& get_instance();
        $CI->load->model('Common_model');
        
        // T08 그룹 코드 조회
        $t08_list = array();
        try {
            $sql = "EXEC [dbo].[Proc_Com_Code_List] @GRP_CD = ?, @OPT_ITEM1 = ?";
            $query = $CI->db->query($sql, array('T08', ''));
            $result = $query->result();
            
            if (!empty($result)) {
                foreach ($result as $row) {
                    $t08_list[] = array(
                        'cd' => trim($row->CD ?? $row->FARE_CD ?? $row->COM_CD ?? ''),
                        'nm' => trim($row->CD_NM ?? $row->FARE_NM ?? $row->COM_NM ?? '')
                    );
                }
            }
        } catch (Exception $e) {
            log_message('error', 'Failed to load allocation type code list: ' . $e->getMessage());
        }
        
        $html = '<select name="ALOC_TYPE" style="width:70px;height:25px;" class="custom-select">' . "\n";
        $html .= '<option value="">전체</option>' . "\n";
        
        if (!empty($t08_list)) {
            foreach ($t08_list as $item) {
                $fare_cd = $item['cd'];
                $fare_nm = $item['nm'];
                $selected = (!empty($aloc_type) && $aloc_type == $fare_cd) ? ' selected' : '';
                $html .= '<option value="' . htmlspecialchars($fare_cd) . '"' . $selected . '>' . htmlspecialchars($fare_nm) . '</option>' . "\n";
            }
        }
        
        $html .= '</select>' . "\n";
        return $html;
    }
}

/**
 * 배차상태 코드 선택 박스 생성
 * @param string $aloc_stat 선택된 배차상태 코드
 * @return string HTML
 */
if (!function_exists('com_aloc_stat')) {
    function com_aloc_stat($aloc_stat = '')
    {
        $CI =& get_instance();
        $CI->load->model('Common_model');
        
        // T08 그룹 코드 조회
        $t08_list = array();
        try {
            $sql = "EXEC [dbo].[Proc_Com_Code_List] @GRP_CD = ?, @OPT_ITEM1 = ?";
            $query = $CI->db->query($sql, array('T08', ''));
            $result = $query->result();
            
            if (!empty($result)) {
                foreach ($result as $row) {
                    $t08_list[] = array(
                        'cd' => trim($row->CD ?? $row->FARE_CD ?? $row->COM_CD ?? ''),
                        'nm' => trim($row->CD_NM ?? $row->FARE_NM ?? $row->COM_NM ?? '')
                    );
                }
            }
        } catch (Exception $e) {
            log_message('error', 'Failed to load allocation stat code list: ' . $e->getMessage());
        }
        
        $html = '<select name="ALOC_STAT" style="width:70px;height:25px;" class="Reg_Box">' . "\n";
        $html .= '<option value="">전체</option>' . "\n";
        
        if (!empty($t08_list)) {
            foreach ($t08_list as $item) {
                $fare_cd = $item['cd'];
                $fare_nm = $item['nm'];
                $selected = (!empty($aloc_stat) && $aloc_stat == $fare_cd) ? ' selected' : '';
                $html .= '<option value="' . htmlspecialchars($fare_cd) . '"' . $selected . '>' . htmlspecialchars($fare_nm) . '</option>' . "\n";
            }
        }
        
        $html .= '</select>' . "\n";
        return $html;
    }
}

/**
 * 업체 특이사항 텍스트 영역 생성
 * @param string $cust_cd 업체 코드
 * @return string HTML
 */
if (!function_exists('com_cust_etc')) {
    function com_cust_etc($cust_cd = '')
    {
        $CI =& get_instance();
        $task_special = '';
        
        if (!empty($cust_cd)) {
            try {
                $sql = "SELECT TASK_SPECIAL FROM OMS_MDM_CUST_OTH WHERE CUST_CD = ?";
                $query = $CI->db->query($sql, array($cust_cd));
                $result = $query->row();
                
                if (!empty($result)) {
                    $task_special = trim($result->TASK_SPECIAL ?? '');
                }
            } catch (Exception $e) {
                log_message('error', 'Failed to load customer etc: ' . $e->getMessage());
            }
        }
        
        $html = '<table border="0" width="430" cellspacing="0" cellpadding="0">' . "\n";
        $html .= '	<tr><td class="Font_Bold T_menu">업체 특이사항</td></tr>' . "\n";
        $html .= '	<tr>' . "\n";
        $html .= '		<td>' . "\n";
        $html .= '			<textarea rows="4" cols="65" name="TASK_SPECIAL" style="ime-mode:active;">' . htmlspecialchars($task_special) . '</textarea>' . "\n";
        $html .= '		</td>' . "\n";
        $html .= '	</tr>' . "\n";
        $html .= '</table>' . "\n";
        return $html;
    }
}

/**
 * 은행 계좌 정보 생성
 * @param string $cust_cd 업체 코드
 * @return string HTML
 */
if (!function_exists('com_bank_line')) {
    function com_bank_line($cust_cd = '')
    {
        $CI =& get_instance();
        $CI->load->model('Common_model');
        
        // T19 그룹 코드 조회 (은행명)
        $code_list_b = array();
        try {
            $sql = "EXEC [dbo].[Proc_Com_Code_List] @GRP_CD = ?, @OPT_ITEM1 = ?";
            $query = $CI->db->query($sql, array('T19', ''));
            $result = $query->result();
            
            if (!empty($result)) {
                foreach ($result as $row) {
                    $code_list_b[] = array(
                        'cd' => trim($row->CD ?? $row->COM_CD ?? ''),
                        'nm' => trim($row->CD_NM ?? $row->COM_NM ?? '')
                    );
                }
            }
        } catch (Exception $e) {
            log_message('error', 'Failed to load bank code list: ' . $e->getMessage());
        }
        
        // 업체 계좌 정보 조회
        $a_no_idx = '';
        $a_bank_nm = '';
        $a_acct_no = '';
        $a_sav_nm = '';
        
        if (!empty($cust_cd)) {
            try {
                $sql = "SELECT NO_IDX, BANK_NM, ACCT_NO, SAV_NM FROM OMS_MDM_CUST_BANK WHERE CUST_CD = ? ORDER BY NO_IDX DESC";
                $query = $CI->db->query($sql, array($cust_cd));
                $result = $query->row();
                
                if (!empty($result)) {
                    $a_no_idx = $result->NO_IDX ?? '';
                    $a_bank_nm = $result->BANK_NM ?? '';
                    $a_acct_no = $result->ACCT_NO ?? '';
                    $a_sav_nm = $result->SAV_NM ?? '';
                }
            } catch (Exception $e) {
                log_message('error', 'Failed to load bank line: ' . $e->getMessage());
            }
        }
        
        $html = '<table width="600" border="0">' . "\n";
        $html .= '	<tr>' . "\n";
        $html .= '	    <td width="30">&nbsp;</td>' . "\n";
        $html .= '	    <td width="100" style="font-size:14px;font-color:#f5000f;">' . "\n";
        
        if (!empty($cust_cd)) {
            $html .= '			<b>은행명:</b>' . "\n";
            $html .= '			<select name="C_BANK_NM" style="width:90px;">' . "\n";
            $html .= '			<option value="">선택</option>' . "\n";
            
            if (!empty($code_list_b)) {
                foreach ($code_list_b as $item) {
                    $c_bank_cd = $item['cd'];
                    $c_bank_nm = $item['nm'];
                    $selected = (!empty($a_bank_nm) && $a_bank_nm == $c_bank_nm) ? ' selected' : '';
                    $html .= '			<option value="' . htmlspecialchars($c_bank_nm) . '"' . $selected . '>' . htmlspecialchars($c_bank_nm) . '</option>' . "\n";
                }
            }
            
            $html .= '	</td>' . "\n";
            $html .= '	<td width="250" style="font-size:14px;font-color:#f5000f;">' . "\n";
            $html .= '		<input type="text" name="ACCT_NO" value="' . htmlspecialchars($a_acct_no) . '" class="Reg_Box" size="25" style="ime-mode:active;" placeholder="계좌번호">' . "\n";
            $html .= '		<input type="text" name="SAV_NM" value="' . htmlspecialchars($a_sav_nm) . '" class="Reg_Box" size="15" style="ime-mode:active" placeholder="예금주">' . "\n";
            $html .= '		<a href="javascript:car_com_bank();"><img src="../../web_order/images/btn_good_edit.gif" border="0" align="absmiddle"></a>' . "\n";
        }
        
        $html .= '		</td>' . "\n";
        $html .= '	</tr>' . "\n";
        $html .= '</table>' . "\n";
        return $html;
    }
}

/**
 * 차량소속 코드 선택 박스 생성
 * @param string $car_position 선택된 차량소속 코드
 * @return string HTML
 */
if (!function_exists('com_car_position')) {
    function com_car_position($car_position = '')
    {
        $CI =& get_instance();
        $CI->load->model('Common_model');
        
        // T09 그룹 코드 조회
        $t09_list = array();
        try {
            $sql = "EXEC [dbo].[Proc_Com_Code_List] @GRP_CD = ?, @OPT_ITEM1 = ?";
            $query = $CI->db->query($sql, array('T09', ''));
            $result = $query->result();
            
            if (!empty($result)) {
                foreach ($result as $row) {
                    $t09_list[] = array(
                        'cd' => trim($row->CD ?? $row->COM_CD ?? ''),
                        'nm' => trim($row->CD_NM ?? $row->COM_NM ?? '')
                    );
                }
            }
        } catch (Exception $e) {
            log_message('error', 'Failed to load car position code list: ' . $e->getMessage());
        }
        
        $html = '<select name="CAR_POSITION" style="width:70px;" class="Reg_Box">' . "\n";
        $html .= '<option value="">전체</option>' . "\n";
        
        if (!empty($t09_list)) {
            foreach ($t09_list as $item) {
                $com_cd = $item['cd'];
                $com_nm = $item['nm'];
                $selected = (!empty($car_position) && $car_position == $com_cd) ? ' selected' : '';
                $html .= '<option value="' . htmlspecialchars($com_cd) . '"' . $selected . '>' . htmlspecialchars($com_nm) . '</option>' . "\n";
            }
        }
        
        $html .= '</select>' . "\n";
        return $html;
    }
}

/**
 * 비용항목 코드 선택 박스 생성
 * @param string $fare_cd 선택된 비용항목 코드
 * @return string HTML
 */
if (!function_exists('com_cost_item')) {
    function com_cost_item($fare_cd = '')
    {
        $CI =& get_instance();
        $CI->load->model('Common_model');
        
        // T06 그룹 코드 조회
        $t06_list = array();
        try {
            $sql = "EXEC [dbo].[Proc_Com_Code_List] @GRP_CD = ?, @OPT_ITEM1 = ?";
            $query = $CI->db->query($sql, array('T06', ''));
            $result = $query->result();
            
            if (!empty($result)) {
                foreach ($result as $row) {
                    $t06_list[] = array(
                        'cd' => trim($row->CD ?? $row->COM_CD ?? ''),
                        'nm' => trim($row->CD_NM ?? $row->COM_NM ?? '')
                    );
                }
            }
        } catch (Exception $e) {
            log_message('error', 'Failed to load cost item code list: ' . $e->getMessage());
        }
        
        $html = '<select name="FARE_CD" style="width:70px;" class="Reg_Box">' . "\n";
        $html .= '<option value="">전체</option>' . "\n";
        
        if (!empty($t06_list)) {
            foreach ($t06_list as $item) {
                $com_cd = $item['cd'];
                $com_nm = $item['nm'];
                $selected = (!empty($fare_cd) && $fare_cd == $com_cd) ? ' selected' : '';
                $html .= '<option value="' . htmlspecialchars($com_cd) . '"' . $selected . '>' . htmlspecialchars($com_nm) . '</option>' . "\n";
            }
        }
        
        $html .= '</select>' . "\n";
        return $html;
    }
}

/**
 * 고객 검색 필드 코드 선택 박스 생성
 * @param string $st_filed 선택된 검색 필드 코드
 * @return string HTML
 */
if (!function_exists('com_cust_st_filed')) {
    function com_cust_st_filed($st_filed = '')
    {
        $CI =& get_instance();
        $CI->load->model('Common_model');
        
        // T35 그룹 코드 조회
        $t35_list = array();
        try {
            $sql = "EXEC [dbo].[Proc_Com_Code_List] @GRP_CD = ?, @OPT_ITEM1 = ?";
            $query = $CI->db->query($sql, array('T35', ''));
            $result = $query->result();
            
            if (!empty($result)) {
                foreach ($result as $row) {
                    $t35_list[] = array(
                        'cd' => trim($row->CD ?? $row->FARE_CD ?? $row->COM_CD ?? ''),
                        'nm' => trim($row->CD_NM ?? $row->FARE_NM ?? $row->COM_NM ?? '')
                    );
                }
            }
        } catch (Exception $e) {
            log_message('error', 'Failed to load cust st filed code list: ' . $e->getMessage());
        }
        
        $html = '<select name="FARE_CD" style="width:70px;" class="Reg_Box">' . "\n";
        $html .= '<option value="">전체</option>' . "\n";
        
        if (!empty($t35_list)) {
            foreach ($t35_list as $item) {
                $fare_cd = $item['cd'];
                $fare_nm = $item['nm'];
                $selected = (!empty($st_filed) && $st_filed == $fare_cd) ? ' selected' : '';
                $html .= '<option value="' . htmlspecialchars($fare_cd) . '"' . $selected . '>' . htmlspecialchars($fare_nm) . '</option>' . "\n";
            }
        }
        
        $html .= '</select>' . "\n";
        return $html;
    }
}

