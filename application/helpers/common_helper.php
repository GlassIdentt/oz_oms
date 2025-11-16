<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 공용 Helper 함수 모음
 * 클래식 ASP 함수들을 PHP로 변환
 */

/**
 * 통화 형식으로 변환
 * @param mixed $iVal 변환할 값
 * @param int $iLen 소수점 자리수
 * @return string
 */
if (!function_exists('ConvCurr')) {
    function ConvCurr($iVal, $iLen = 0) {
        if (is_null($iVal) || $iVal === '') {
            return '--';
        } else {
            return number_format($iVal, $iLen);
        }
    }
}

/**
 * 캐시 방지 헤더 설정
 */
if (!function_exists('NoCache')) {
    function NoCache() {
        $CI =& get_instance();
        $CI->output->set_header('Expires: 0');
        $CI->output->set_header('Pragma: no-cache');
        $CI->output->set_header('Cache-Control: no-cache, no-store, must-revalidate');
        $CI->output->set_header('Content-Type: text/html; charset=utf-8');
    }
}

/**
 * HTML 텍스트 출력용 문자열 변환
 * @param string $value 변환할 문자열
 * @return string
 */
if (!function_exists('prnTEXT')) {
    function prnTEXT($value) {
        $tempStr = strval($value ?? '');
        $tempStr = str_replace('&', '&amp;', $tempStr);
        $tempStr = str_replace('<', '&lt;', $tempStr);
        $tempStr = str_replace('>', '&gt;', $tempStr);
        $tempStr = str_replace("\r", '<br>', $tempStr);
        $tempStr = str_replace("\n", '', $tempStr);
        return $tempStr;
    }
}

/**
 * 파일명 특수문자 제거
 * @param string $filename 파일명
 * @return string
 */
if (!function_exists('file_name_ch')) {
    function file_name_ch($filename) {
        $tempStr = strval($filename);
        $special_chars = array('!', '@', '#', '$', '%', '^', '&', '*', '(', ')', '-', '+',
                              '|', '\\', '?', '<', '>', '/', ':', ';', "'", '[', ']',
                              '{', '}', ',', ' ');
        $tempStr = str_replace($special_chars, '', $tempStr);
        return $tempStr;
    }
}

/**
 * 페이지 이동
 * @param string $sUrl 이동할 URL
 */
if (!function_exists('LoGo')) {
    function LoGo($sUrl) {
        echo "<script>
            document.location='{$sUrl}';
        </script>";
        exit;
    }
}

/**
 * 부모 창 페이지 이동
 * @param string $sUrl 이동할 URL
 * @param string $Pkey 부모 창 키 (parent, opener 등)
 */
if (!function_exists('P_LoGo')) {
    function P_LoGo($sUrl, $Pkey = 'parent') {
        echo "<script>
            {$Pkey}.document.location='{$sUrl}';
        </script>";
        exit;
    }
}

/**
 * 메시지 출력 후 페이지 이동
 * @param string $sMsg 출력할 메시지
 * @param string $sUrl 이동할 URL
 */
if (!function_exists('MsgGo')) {
    function MsgGo($sMsg, $sUrl) {
        echo "<script>
            alert('{$sMsg}');
            document.location='{$sUrl}';
        </script>";
        exit;
    }
}

/**
 * 메시지 출력 후 현재 창과 부모 창 이동
 * @param string $sMsg 출력할 메시지
 * @param string $sUrl 현재 창 이동 URL
 * @param string $pUrl 부모 창 이동 URL
 */
if (!function_exists('MsgGo_Parent')) {
    function MsgGo_Parent($sMsg, $sUrl, $pUrl) {
        echo "<script>
            alert('{$sMsg}');
            opener.document.location='{$pUrl}';
            document.location='{$sUrl}';
        </script>";
        exit;
    }
}

/**
 * Toastr 메시지 출력 후 부모 창 이동
 * @param string $sMsg 출력할 메시지
 * @param string $sUrl 이동할 URL
 * @param string $Pkey 부모 창 키
 * @param string $Position Toastr 위치
 */
if (!function_exists('P_MsgGo_2')) {
    function P_MsgGo_2($sMsg, $sUrl, $Pkey = 'parent', $Position = 'toast-top-right') {
        echo "<script>
            $(function() {
                toastr.success('OK', '{$sMsg}', {
                    timeOut: 300,
                    preventDuplicates: true,
                    positionClass: '{$Position}',
                    onHidden: function() {
                        {$Pkey}.location.href='{$sUrl}';
                    }
                });
            });
        </script>";
        exit;
    }
}

/**
 * 메시지 출력 후 부모 창 이동
 * @param string $sMsg 출력할 메시지
 * @param string $sUrl 이동할 URL
 * @param string $Pkey 부모 창 키
 */
if (!function_exists('P_MsgGo')) {
    function P_MsgGo($sMsg, $sUrl, $Pkey = 'parent') {
        echo "<script>
            alert('{$sMsg}');
            {$Pkey}.document.location='{$sUrl}';
        </script>";
        exit;
    }
}

/**
 * 메시지 출력 후 창 닫기
 * @param string $sMsg 출력할 메시지
 */
if (!function_exists('MsgClose')) {
    function MsgClose($sMsg) {
        echo "<script>
            alert('{$sMsg}');
            self.close();
        </script>";
        exit;
    }
}

/**
 * 메시지 출력, 부모 창 이동 후 창 닫기
 * @param string $sMsg 출력할 메시지
 * @param string $sUrl 부모 창 이동 URL
 * @param string $Pkey 부모 창 키
 */
if (!function_exists('P_MsgClose')) {
    function P_MsgClose($sMsg, $sUrl, $Pkey = 'parent') {
        echo "<script>
            alert('{$sMsg}');
            {$Pkey}.document.location='{$sUrl}';
            self.close();
        </script>";
        exit;
    }
}

/**
 * 메시지 출력, 부모 창 이동 및 닫기, 자신도 닫기
 * @param string $sMsg 출력할 메시지
 * @param string $sUrl 부모 창 이동 URL
 * @param string $Pkey 부모 창 키
 */
if (!function_exists('OP_MsgClose')) {
    function OP_MsgClose($sMsg, $sUrl, $Pkey = 'opener') {
        echo "<script>
            alert('{$sMsg}');
            {$Pkey}.document.location='{$sUrl}';
            {$Pkey}.window.close();
            self.close();
        </script>";
        exit;
    }
}

/**
 * 메시지 출력, 페이지 이동 후 창 닫기
 * @param string $sMsg 출력할 메시지
 * @param string $sUrl 이동할 URL
 */
if (!function_exists('MsgClose_Lo')) {
    function MsgClose_Lo($sMsg, $sUrl) {
        echo "<script>
            alert('{$sMsg}');
            document.location='{$sUrl}';
            self.close();
        </script>";
        exit;
    }
}

/**
 * 메시지 출력 후 뒤로가기
 * @param string $sMsg 출력할 메시지
 */
if (!function_exists('MsgHistoryback')) {
    function MsgHistoryback($sMsg) {
        echo "<script>
            alert('{$sMsg}');
            history.back();
        </script>";
        exit;
    }
}

/**
 * 메시지만 출력 (페이지 이동 없음)
 * @param string $sMsg 출력할 메시지
 */
if (!function_exists('Msg')) {
    function Msg($sMsg) {
        echo "<script>
            alert('{$sMsg}');
        </script>";
    }
}

/**
 * 창 닫기
 */
if (!function_exists('WinClose')) {
    function WinClose() {
        echo "<script>
            self.close();
        </script>";
        exit;
    }
}

/**
 * NULL 값 변환
 * @param mixed $value 값
 * @param string $default 기본값
 * @return mixed
 */
if (!function_exists('ConvertNull')) {
    function ConvertNull($value, $default = '') {
        return ($value === null || $value === '') ? $default : $value;
    }
}

?>
