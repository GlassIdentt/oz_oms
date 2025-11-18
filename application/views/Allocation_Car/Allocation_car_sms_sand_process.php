<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// CodeIgniter 인스턴스 가져오기
$CI =& get_instance();
$CI->load->database();
$CI->load->helper('common_helper');

// POST 데이터 받기
$CAR_NO = $CI->input->post('CAR_NO');
$LISENCE_NO = $CI->input->post('LISENCE_NO');
$CAR_POSION = $CI->input->post('CAR_POSION');
$TRAN_CD = $CI->input->post('TRAN_CD');
$TRAN_NM = $CI->input->post('TRAN_NM');
$TRAN_NM = str_replace(' ', '', $TRAN_NM);
$TRAN_NM = str_replace('(주)', '', $TRAN_NM);
$TRAN_NM = str_replace('주식회사', '', $TRAN_NM);
$TRAN_NM = str_replace('㈜', '', $TRAN_NM);

$DRV_NM = $CI->input->post('DRV_NM');
$CAR_TEL = $CI->input->post('CAR_TEL');
$CAR_TYPE = $CI->input->post('CAR_TYPE');
$DRV_CD = $CI->input->post('DRV_CD');
$CAR_HOR_ADD = $CI->input->post('CAR_HOR_ADD');
$I_CRUD = $CI->input->post('I_CRUD');
$CHK_COUNT = $CI->input->post('CHK_COUNT');
$CNT_NO = $CI->input->post('CNT_NO');
$A_CAR_KEY = $CI->input->post('A_CAR_KEY');
$cancle_key = $CI->input->post('cancle_key');
$ALLOCATE_DV = $CI->input->post('ALLOCATE_DV');
$Allocation_page = $CI->input->post('Allocation_page');
$PAGE_KEY = $CI->input->post('PAGE_KEY');
$ALOC_STAT = $CI->input->post('ALOC_STAT');

// Query 파라미터 가져오기
$Query = $CI->input->post('Query');

// Query 문자열 구성
$query_params = array(
    'CAR_NO' => $CAR_NO,
    'LISENCE_NO' => $LISENCE_NO,
    'CAR_POSION' => $CAR_POSION,
    'TRAN_NM' => $TRAN_NM,
    'DRV_NM' => $DRV_NM,
    'CAR_TEL' => $CAR_TEL,
    'CAR_TYPE' => $CAR_TYPE,
    'DRV_CD' => $DRV_CD,
    'Allocation_page' => $Allocation_page
);

$Query = $Query . '&' . http_build_query($query_params);

// POST 방식으로 전송된 JSON 데이터 받기
$GridData = $CI->input->post('GridData');

// JSON 데이터 파싱
$jsonArray = json_decode($GridData, true);

if (!empty($jsonArray) && is_array($jsonArray)) {
    $CNT = intval($CNT_NO);
    
    foreach ($jsonArray as $i => $item) {
        if (isset($item['SO_NO'])) {
            $SO_NO[$i] = trim($item['SO_NO']);
        } else {
            continue;
        }
        
        // 저장 프로시저 호출 - OUTPUT 파라미터를 처리하기 위해 직접 sqlsrv 사용
        require_once(APPPATH . 'config/database.php');
        $db_config = $db['default'];
        
        $conn = sqlsrv_connect(
            $db_config['hostname'],
            array(
                'Database' => $db_config['database'],
                'UID' => $db_config['username'],
                'PWD' => $db_config['password'],
                'CharacterSet' => 'UTF-8'
            )
        );
        
        if (!$conn) {
            log_message('error', 'Failed to connect to database: ' . print_r(sqlsrv_errors(), true));
            continue;
        }
        
        try {
            // OUTPUT 파라미터를 위한 변수 초기화
            $SO_MODE = '';
            $IO_TYPE = '';
            $ACT_SHIP_NM = '';
            $SHIP_NM = '';
            $FDS_NM = '';
            $PKG = '';
            $WGT = '';
            $CBM = '';
            $GOD_SIZE = '';
            $LOAD_NM = '';
            $LOAD_TEL = '';
            $LOAD_HP = '';
            $LOAD_PIC_NM = '';
            $LOAD_AREA = '';
            $LOAD_ADDR = '';
            $LOAD_REQ_DT = '';
            $LOAD_REQ_HM = '';
            $UNLOAD_REQ_DT = '';
            $UNLOAD_REQ_HM = '';
            $UNLOAD_NM = '';
            $UNLOAD_ADDR = '';
            $UNLOAD_PIC_NM = '';
            $UNLOAD_TEL = '';
            $UNLOAD_HP = '';
            $GOD_HBL_NO = '';
            $ARRIVAL = '';
            $GR_NO = '';
            $BK_NO = '';
            $MOBILE_RMK = '';
            $proc_DRV_NM = '';
            $proc_CAR_TEL = '';
            $proc_LISENCE_NO = '';
            
            // 저장 프로시저 호출 - OUTPUT 파라미터 바인딩
            $sql = "EXEC Proc_Order_Print @SO_NO = ?, 
                    @SO_MODE = ? OUTPUT, 
                    @IO_TYPE = ? OUTPUT, 
                    @ACT_SHIP_NM = ? OUTPUT, 
                    @SHIP_NM = ? OUTPUT, 
                    @FDS_NM = ? OUTPUT, 
                    @PKG = ? OUTPUT, 
                    @WGT = ? OUTPUT, 
                    @CBM = ? OUTPUT, 
                    @GOD_SIZE = ? OUTPUT, 
                    @LOAD_NM = ? OUTPUT, 
                    @LOAD_TEL = ? OUTPUT, 
                    @LOAD_HP = ? OUTPUT, 
                    @LOAD_PIC_NM = ? OUTPUT, 
                    @LOAD_AREA = ? OUTPUT, 
                    @LOAD_ADDR = ? OUTPUT, 
                    @LOAD_REQ_DT = ? OUTPUT, 
                    @LOAD_REQ_HM = ? OUTPUT, 
                    @UNLOAD_REQ_DT = ? OUTPUT, 
                    @UNLOAD_REQ_HM = ? OUTPUT, 
                    @UNLOAD_NM = ? OUTPUT, 
                    @UNLOAD_ADDR = ? OUTPUT, 
                    @UNLOAD_PIC_NM = ? OUTPUT, 
                    @UNLOAD_TEL = ? OUTPUT, 
                    @UNLOAD_HP = ? OUTPUT, 
                    @HBL_NO = ? OUTPUT, 
                    @ARRIVAL = ? OUTPUT, 
                    @GR_NO = ? OUTPUT, 
                    @BK_NO = ? OUTPUT, 
                    @MOBILE_RMK = ? OUTPUT, 
                    @DRV_NM = ? OUTPUT, 
                    @CAR_TEL = ? OUTPUT, 
                    @LISENCE_NO = ? OUTPUT";
            
            $params = array(
                array($SO_NO[$i], SQLSRV_PARAM_IN),
                array(&$SO_MODE, SQLSRV_PARAM_OUT),
                array(&$IO_TYPE, SQLSRV_PARAM_OUT),
                array(&$ACT_SHIP_NM, SQLSRV_PARAM_OUT),
                array(&$SHIP_NM, SQLSRV_PARAM_OUT),
                array(&$FDS_NM, SQLSRV_PARAM_OUT),
                array(&$PKG, SQLSRV_PARAM_OUT),
                array(&$WGT, SQLSRV_PARAM_OUT),
                array(&$CBM, SQLSRV_PARAM_OUT),
                array(&$GOD_SIZE, SQLSRV_PARAM_OUT),
                array(&$LOAD_NM, SQLSRV_PARAM_OUT),
                array(&$LOAD_TEL, SQLSRV_PARAM_OUT),
                array(&$LOAD_HP, SQLSRV_PARAM_OUT),
                array(&$LOAD_PIC_NM, SQLSRV_PARAM_OUT),
                array(&$LOAD_AREA, SQLSRV_PARAM_OUT),
                array(&$LOAD_ADDR, SQLSRV_PARAM_OUT),
                array(&$LOAD_REQ_DT, SQLSRV_PARAM_OUT),
                array(&$LOAD_REQ_HM, SQLSRV_PARAM_OUT),
                array(&$UNLOAD_REQ_DT, SQLSRV_PARAM_OUT),
                array(&$UNLOAD_REQ_HM, SQLSRV_PARAM_OUT),
                array(&$UNLOAD_NM, SQLSRV_PARAM_OUT),
                array(&$UNLOAD_ADDR, SQLSRV_PARAM_OUT),
                array(&$UNLOAD_PIC_NM, SQLSRV_PARAM_OUT),
                array(&$UNLOAD_TEL, SQLSRV_PARAM_OUT),
                array(&$UNLOAD_HP, SQLSRV_PARAM_OUT),
                array(&$GOD_HBL_NO, SQLSRV_PARAM_OUT),
                array(&$ARRIVAL, SQLSRV_PARAM_OUT),
                array(&$GR_NO, SQLSRV_PARAM_OUT),
                array(&$BK_NO, SQLSRV_PARAM_OUT),
                array(&$MOBILE_RMK, SQLSRV_PARAM_OUT),
                array(&$proc_DRV_NM, SQLSRV_PARAM_OUT),
                array(&$proc_CAR_TEL, SQLSRV_PARAM_OUT),
                array(&$proc_LISENCE_NO, SQLSRV_PARAM_OUT)
            );
            
            $stmt = sqlsrv_prepare($conn, $sql, $params);
            
            if (!$stmt) {
                log_message('error', 'Failed to prepare statement: ' . print_r(sqlsrv_errors(), true));
                sqlsrv_close($conn);
                continue;
            }
            
            if (sqlsrv_execute($stmt)) {
                // OUTPUT 파라미터 값 가져오기
                $SO_MODE = trim($SO_MODE);
                $IO_TYPE = trim($IO_TYPE);
                $ACT_SHIP_NM = trim($ACT_SHIP_NM);
                $SHIP_NM = trim($SHIP_NM);
                $FDS_NM = trim($FDS_NM);
                $PKG = trim($PKG);
                $WGT = trim($WGT);
                $CBM = trim($CBM);
                $GOD_SIZE = trim($GOD_SIZE);
                $LOAD_NM = trim($LOAD_NM);
                $LOAD_TEL = trim($LOAD_TEL);
                $LOAD_HP = trim($LOAD_HP);
                $LOAD_PIC_NM = trim($LOAD_PIC_NM);
                $LOAD_AREA = trim($LOAD_AREA);
                $LOAD_ADDR = trim($LOAD_ADDR);
                $LOAD_REQ_DT = trim($LOAD_REQ_DT);
                $LOAD_REQ_HM = trim($LOAD_REQ_HM);
                $UNLOAD_REQ_DT = trim($UNLOAD_REQ_DT);
                $UNLOAD_REQ_HM = trim($UNLOAD_REQ_HM);
                $UNLOAD_NM = trim($UNLOAD_NM);
                $UNLOAD_ADDR = trim($UNLOAD_ADDR);
                $UNLOAD_PIC_NM = trim($UNLOAD_PIC_NM);
                $UNLOAD_TEL = trim($UNLOAD_TEL);
                $UNLOAD_HP = trim($UNLOAD_HP);
                $GOD_HBL_NO = trim($GOD_HBL_NO);
                $ARRIVAL = trim($ARRIVAL);
                $GR_NO = trim($GR_NO);
                $BK_NO = trim($BK_NO);
                $MOBILE_RMK = trim($MOBILE_RMK);
                
                // 프로시저에서 반환된 값이 있으면 사용, 없으면 POST에서 받은 값 사용
                $DRV_NM = !empty($proc_DRV_NM) ? trim($proc_DRV_NM) : $DRV_NM;
                $CAR_TEL = !empty($proc_CAR_TEL) ? trim($proc_CAR_TEL) : $CAR_TEL;
                $LISENCE_NO = !empty($proc_LISENCE_NO) ? trim($proc_LISENCE_NO) : $LISENCE_NO;
                
                sqlsrv_free_stmt($stmt);
            } else {
                log_message('error', 'Failed to execute stored procedure: ' . print_r(sqlsrv_errors(), true));
                sqlsrv_free_stmt($stmt);
                sqlsrv_close($conn);
                continue;
            }
            
            sqlsrv_close($conn);
        } catch (Exception $e) {
            log_message('error', 'Failed to execute Proc_Order_Print for SO_NO: ' . $SO_NO[$i] . ' - ' . $e->getMessage());
            if (isset($conn)) {
                sqlsrv_close($conn);
            }
            continue;
        }
        
        // ARRIVAL 값 변환
        if ($ARRIVAL == "Y") {
            $ARRIVAL = "유";
        } elseif ($ARRIVAL == "" || is_null($ARRIVAL) || $ARRIVAL == "N") {
            $ARRIVAL = "무";
        }
        
        // 사이즈 정보 조회
        $sTSql = "SELECT GOOD_CD, MSIZE FROM OMS_SO_DIM WHERE SO_NO = ?";
        $size_query = $CI->db->query($sTSql, array($SO_NO[$i]));
        $T_MSIZE = "";
        
        if ($size_query && $size_query->num_rows() > 0) {
            $size_list = $size_query->result_array();
            foreach ($size_list as $j => $size_row) {
                $GOOD_CD = $size_row['GOOD_CD'];
                $MSIZE = $size_row['MSIZE'];
                
                if ($j > 0) {
                    $T_MSIZE .= ",<br>[" . $GOOD_CD . "]-" . $MSIZE;
                } else {
                    $T_MSIZE = "[" . $GOOD_CD . "]-" . $MSIZE;
                }
            }
        }
        
        // 메시지 본문 구성
        $M_BODY = "";
        $M_BODY .= "[원더로지스] [02-2668-9900] \r\n";
        $M_BODY .= "절대 화주에게 운송료를 오픈하지 마세요!!!\r\n";
        $M_BODY .= "※운송료 및 계산서는 콜 진행하신 곳에 문의 및 운송료 수령하시기 바랍니다\r\n";
        $M_BODY .= "\r\n";
        $M_BODY .= "파손, 수량변경, 시간조정 등 특이 사항 발생할 경우\r\n";
        $M_BODY .= "반드시 원더로지스로 연락주세요!!\r\n";
        $M_BODY .= "\r\n";
        $M_BODY .= "픽업일:" . $LOAD_REQ_DT . "\r\n";
        $M_BODY .= "픽업요청시간:" . $LOAD_REQ_HM . "\r\n";
        
        if ($SO_MODE == "AIR") {
            $M_BODY .= "포워딩:" . $ACT_SHIP_NM . "\r\n";
        }
        
        $M_BODY .= "화주:" . $SHIP_NM . "\r\n";
        $M_BODY .= "픽업지명:" . $LOAD_NM . "\r\n";
        $M_BODY .= "픽업주소:" . $LOAD_ADDR . "\r\n";
        $M_BODY .= "픽업전화:" . $LOAD_TEL . "\r\n";
        $M_BODY .= "휴대폰:" . $LOAD_HP . "\r\n";
        $M_BODY .= "픽업지담당자:" . $LOAD_PIC_NM . "\r\n";
        $M_BODY .= "총수량:" . $PKG . " EA\r\n";
        $M_BODY .= "총무게:" . $WGT . " KG\r\n";
        $M_BODY .= "총부피:" . $CBM . " CBM\r\n";
        $M_BODY .= "사이즈:" . $T_MSIZE . "\r\n";
        $M_BODY .= "B/L:" . $GOD_HBL_NO . "\r\n";
        $M_BODY .= "하차일:" . $UNLOAD_REQ_DT . "\r\n";
        $M_BODY .= "하차요청시간:" . substr($UNLOAD_REQ_HM, 0, 2) . ":" . substr($UNLOAD_REQ_HM, -2) . "\r\n";
        $M_BODY .= "하차지명:" . $UNLOAD_NM . "\r\n";
        $M_BODY .= "하차지주소:" . $UNLOAD_ADDR . "\r\n";
        $M_BODY .= "하차지전화:" . $UNLOAD_TEL . "\r\n";
        $M_BODY .= "휴대폰:" . $UNLOAD_HP . "\r\n";
        $M_BODY .= "하차지담당자:" . $UNLOAD_PIC_NM . "\r\n";
        $M_BODY .= "목적국:" . $FDS_NM . "\r\n";
        $transport_GUBUN2 = $CI->input->post('transport_GUBUN2');
        $M_BODY .= "도착보고:" . $ARRIVAL . "  하차긴급:" . ($transport_GUBUN2 ? $transport_GUBUN2 : "") . "\r\n";
        $M_BODY .= "\r\n";
        $M_BODY .= "비고:" . $MOBILE_RMK . "\r\n";
        $M_BODY .= "\r\n";
        $M_BODY .= "위탁자 사업자 번호:522-81-00528\\r\n";
        $M_BODY .= "위탁자 사업자 주소:서울특별시 강서구 공항대로 247 퀸즈파크나인 C동 933~936호\r\n";
        $M_BODY .= "\r\n";
        $M_BODY .= "수탁자 :" . $DRV_NM . "\r\n";
        $M_BODY .= "성명:" . $CAR_TEL . "\r\n";
        $M_BODY .= "차량번호:" . $LISENCE_NO . "\r\n";
        $M_BODY .= "최대적재량:\r\n";
        $M_BODY .= "차종:\r\n";
        $M_BODY .= "운임지급방법:[]선불,[●]후불\r\n";
        $M_BODY .= "화물자동자 운수 사업법 제 11조제12항, 제 28조 및 제33조와 같은 법 시행규칙 제 39조에 따라\r\n";
        $M_BODY .= "화물위탁증을 교부 하며, 위 기재 내용은 사실과 다르지 아니함을 확인합니다\r\n";
        $M_BODY .= date('Y') . "년 " . date('m') . "월 " . date('d') . "일\r\n";
        
        // 전화번호 정리
        $CAR_TEL = str_replace('-', '', $CAR_TEL);
        $CAR_TEL = str_replace('*', '', $CAR_TEL);
        $CAR_TEL = str_replace(' ', '', $CAR_TEL);
        
        if (strlen($SHIP_NM) > 5) {
            $SHIP_NM = substr($SHIP_NM, 0, 5);
        }
        
        $M_SUBJECT = "원더로지스-" . trim($SHIP_NM) . " 화물위탁증";
        
        // 특수문자 제거
        $M_BODY = str_replace('?', ' ', $M_BODY);
        $M_BODY = str_replace('"', '', $M_BODY);
        $M_BODY = str_replace("'", '', $M_BODY);
        
        $title = $M_SUBJECT;
        $message = $M_BODY;
        $sender = "0226689900";
        $username = "wonderlogis";
        $key = "O1wHqDMZX2ZFs2G";
        
        // JSON 데이터 구성
        $receiver = array(
            array(
                'name' => $DRV_NM,
                'mobile' => $CAR_TEL,
                'note1' => ''
            )
        );
        
        $data = array(
            'title' => $title,
            'message' => $message,
            'sender' => $sender,
            'username' => $username,
            'receiver' => $receiver,
            'key' => $key,
            'type' => 'asp'
        );
        
        $json_data = json_encode($data, JSON_UNESCAPED_UNICODE);
        
        // cURL을 사용하여 HTTP 요청 전송
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://directsend.co.kr/index.php/api_v2/sms_change_word");
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $json_data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($json_data)
        ));
        
        $postResponse = curl_exec($ch);
        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        
        if (curl_errno($ch)) {
            log_message('error', 'cURL Error: ' . curl_error($ch));
        }
        
        curl_close($ch);
        
        echo $postResponse;
    }
}

// 리디렉션 URL 구성
$redirect_url = site_url('allocation_car_list') . '?' . $Query;

MsgGo('SMS 오더 전송 완료!!', $redirect_url);
?>
