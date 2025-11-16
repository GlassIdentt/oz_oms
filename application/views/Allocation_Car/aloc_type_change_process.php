<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// CodeIgniter 인스턴스 가져오기
$CI =& get_instance();
$CI->load->database();

// POST 데이터 받기
$CNT_NO = $CI->input->post('CNT_NO');
$CAR_NO = $CI->input->post('CAR_NO');
$LISENCE_NO = $CI->input->post('LISENCE_NO');
$CAR_POSION = $CI->input->post('CAR_POSION');
$TRAN_CD = $CI->input->post('TRAN_CD');
$TRAN_NM = $CI->input->post('TRAN_NM');

// TRAN_NM 문자열 치환
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
$A_CAR_KEY = $CI->input->post('A_CAR_KEY');
$cancle_key = $CI->input->post('cancle_key');
$ALLOCATE_DV = $CI->input->post('ALLOCATE_DV');
$Allocation_page = $CI->input->post('Allocation_page');
$PAGE_KEY = $CI->input->post('PAGE_KEY');
$ALOC_STAT = $CI->input->post('ALOC_STAT');

// Query 파라미터 가져오기
$Query = $CI->input->post('Query');

// 디버깅: 파라미터 출력
echo "<h3>POST 파라미터 확인</h3>";
echo "<pre>";
echo "CNT_NO: " . print_r($CNT_NO, true) . "\n";
echo "CAR_NO: " . print_r($CAR_NO, true) . "\n";
echo "LISENCE_NO: " . print_r($LISENCE_NO, true) . "\n";
echo "CAR_POSION: " . print_r($CAR_POSION, true) . "\n";
echo "TRAN_CD: " . print_r($TRAN_CD, true) . "\n";
echo "TRAN_NM: " . print_r($TRAN_NM, true) . "\n";
echo "DRV_NM: " . print_r($DRV_NM, true) . "\n";
echo "CAR_TEL: " . print_r($CAR_TEL, true) . "\n";
echo "CAR_TYPE: " . print_r($CAR_TYPE, true) . "\n";
echo "DRV_CD: " . print_r($DRV_CD, true) . "\n";
echo "CAR_HOR_ADD: " . print_r($CAR_HOR_ADD, true) . "\n";
echo "I_CRUD: " . print_r($I_CRUD, true) . "\n";
echo "CHK_COUNT: " . print_r($CHK_COUNT, true) . "\n";
echo "A_CAR_KEY: " . print_r($A_CAR_KEY, true) . "\n";
echo "cancle_key: " . print_r($cancle_key, true) . "\n";
echo "ALLOCATE_DV: " . print_r($ALLOCATE_DV, true) . "\n";
echo "Allocation_page: " . print_r($Allocation_page, true) . "\n";
echo "PAGE_KEY: " . print_r($PAGE_KEY, true) . "\n";
echo "ALOC_STAT: " . print_r($ALOC_STAT, true) . "\n";
echo "Query: " . print_r($Query, true) . "\n";
echo "</pre>";
echo "<hr>";

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

// 기존 Query에 파라미터 추가
$query_string = $Query . '&' . http_build_query($query_params);

// GridData JSON 받기
$GridData = $CI->input->post('GridData');

// 디버깅: GridData 출력
echo "<h3>GridData 확인</h3>";
echo "<pre>";
echo "GridData (원본): " . print_r($GridData, true) . "\n";
echo "</pre>";

// JSON 데이터 파싱
$jsonArray = json_decode($GridData, true);

// 디버깅: 파싱된 JSON 출력
echo "<h3>파싱된 JSON 데이터</h3>";
echo "<pre>";
echo print_r($jsonArray, true);
echo "</pre>";
echo "<hr>";

if (!empty($jsonArray) && is_array($jsonArray)) {
    $CNT = intval($CNT_NO);

    // 각 항목 처리
    foreach ($jsonArray as $item) {
        if (isset($item['SO_NO'])) {
            $SO_NO = trim($item['SO_NO']);

            // 저장 프로시저 호출
            $sql = "CALL Proc_So_Order_Aloc_Type_Update(?, ?)";

            try {
                $CI->db->query($sql, array($SO_NO, trim($ALOC_STAT)));
            } catch (Exception $e) {
                // 에러 로깅
                log_message('error', 'Failed to update ALOC_TYPE for SO_NO: ' . $SO_NO . ' - ' . $e->getMessage());
            }
        }
    }
}

// 이전 페이지 URL 가져오기
$referer = $CI->input->server('HTTP_REFERER');

// Query 문자열 추가
if ($referer) {
    // 기존 URL에 쿼리 파라미터가 있는지 확인
    $separator = (strpos($referer, '?') !== false) ? '&' : '?';
    $redirect_url = $referer . $separator . $query_string;
} else {
    // Referer가 없는 경우 기본 페이지로
    $redirect_url = site_url('allocation_car_list') . '?' . $query_string;
}

// 디버깅: Referer와 리디렉션 URL 출력
echo "<h3>리디렉션 정보</h3>";
echo "<pre>";
echo "Referer: " . print_r($referer, true) . "\n";
echo "Query String: " . print_r($query_string, true) . "\n";
echo "Redirect URL: " . print_r($redirect_url, true) . "\n";
echo "</pre>";
echo "<hr>";

// 공용 함수를 사용하여 메시지 출력 후 페이지 이동
//MsgGo('배차유형이 변경 되었습니다!', $redirect_url);
echo "<p style='color: red; font-weight: bold;'>디버깅 모드: 페이지 이동이 주석처리되어 있습니다.</p>";
