<?php 
$header_data = array(
    'category' => isset($category) ? $category : '배차관리',
    'folder_name' => isset($folder_name) ? $folder_name : 'Allocation_Car',
    'current_file' => isset($current_file) ? $current_file : 'allocation_car_list'
);
$this->load->view('common/header', $header_data); 
?>

<!-- 하단 탭 버튼들 (동적 로딩) -->
<?php
$submenu_data = array(
	'folder_name' => isset($folder_name) ? $folder_name : 'Allocation_Car',
	'current_file' => isset($current_file) ? $current_file : 'allocation_car_list'
);
$this->load->view('common/submenu', $submenu_data);
?>

<?php $this->load->view('common/footer'); ?>

			<!-- 배차관리 목록 컨텐츠 -->
<?php
// URL 파라미터에서 Allocation_page 가져오기 (POST 우선, 없으면 GET)
$CI =& get_instance();
$CI->load->helper('common_form');
$menu_allocation_page_post = $CI->input->post('Allocation_page');
$menu_allocation_page_get = $CI->input->get('Allocation_page');
$menu_allocation_page = !empty($menu_allocation_page_post) ? $menu_allocation_page_post : (!empty($menu_allocation_page_get) ? $menu_allocation_page_get : '');

// ============================================
// POST 파라미터로부터 모든 input/select name 값 받기
// ============================================

// Helper 함수로 생성되는 select 박스들
$office_cd = isset($OFFICE_CD) ? $OFFICE_CD : $CI->input->post('OFFICE_CD');
$office_cd = !empty($office_cd) ? $office_cd : '';

$io_type = isset($IO_TYPE) ? $IO_TYPE : $CI->input->post('IO_TYPE');
$io_type = !empty($io_type) ? $io_type : '';

$aloc_type = isset($ALOC_TYPE) ? $ALOC_TYPE : $CI->input->post('ALOC_TYPE');
$aloc_type = !empty($aloc_type) ? $aloc_type : '';

$n_field = isset($N_Field) ? $N_Field : $CI->input->post('N_Field');
$n_field = !empty($n_field) ? $n_field : '';

// 직접 정의된 input/select 태그들
$seardate = isset($SEARDATE) ? $SEARDATE : $CI->input->post('SEARDATE');
$seardate = !empty($seardate) ? $seardate : 'LOAD_REQ_DT';

$today = date('Y-m-d');
$min_date = date('Y-m-d', strtotime('-3 months', strtotime($today)));
$max_date = date('Y-m-d', strtotime('+3 months', strtotime($today)));

$s_date = isset($S_DATE) ? $S_DATE : $CI->input->post('S_DATE');
$s_date = !empty($s_date) ? $s_date : $today;

// T_DATE 변환 (날짜에서 "-" 제거: 2025-11-16 -> 20251116)		
$t_date = str_replace('-', '', $s_date);

$s_text = isset($S_TEXT) ? $S_TEXT : $CI->input->post('S_TEXT');
$s_text = !empty($s_text) ? $s_text : '';

$so_mode = isset($SO_MODE) ? $SO_MODE : $CI->input->post('SO_MODE');
// menu_allocation_page에 따른 기본값 설정
if (empty($so_mode)) {
    if ($menu_allocation_page == 'ch_1') {
        $so_mode = 'LCL,';
    } elseif ($menu_allocation_page == 'ch_2') {
        $so_mode = 'AIR,';
    } elseif ($menu_allocation_page == 'ch_3') {
        $so_mode = 'FCL,QUK,';
    } else {
        $so_mode = '';
    }
}

$receipt_type = isset($RECEIPT_TYPE) ? $RECEIPT_TYPE : $CI->input->post('RECEIPT_TYPE');
$receipt_type = !empty($receipt_type) ? $receipt_type : '';

$order_delivery = isset($ORDER_DELIVERY) ? $ORDER_DELIVERY : $CI->input->post('ORDER_DELIVERY');
$order_delivery = !empty($order_delivery) ? $order_delivery : '';

$allocate_dv = isset($ALLOCATE_DV) ? $ALLOCATE_DV : $CI->input->post('ALLOCATE_DV');
$allocate_dv = !empty($allocate_dv) ? $allocate_dv : '';

$car_no = isset($CAR_NO) ? $CAR_NO : $CI->input->post('CAR_NO');
$car_no = !empty($car_no) ? $car_no : '';

$car_posion = isset($CAR_POSION) ? $CAR_POSION : $CI->input->post('CAR_POSION');
$car_posion = !empty($car_posion) ? $car_posion : '';

$tran_nm = isset($TRAN_NM) ? $TRAN_NM : $CI->input->post('TRAN_NM');
$tran_nm = !empty($tran_nm) ? $tran_nm : '';

$drv_nm = isset($DRV_NM) ? $DRV_NM : $CI->input->post('DRV_NM');
$drv_nm = !empty($drv_nm) ? $drv_nm : '';

$car_tel = isset($CAR_TEL) ? $CAR_TEL : $CI->input->post('CAR_TEL');
$car_tel = !empty($car_tel) ? $car_tel : '';

$car_type = isset($CAR_TYPE) ? $CAR_TYPE : $CI->input->post('CAR_TYPE');
$car_type = !empty($car_type) ? $car_type : '';

$car_ton = isset($CAR_TON) ? $CAR_TON : $CI->input->post('CAR_TON');
$car_ton = !empty($car_ton) ? $car_ton : '';

// 기타 변수
$opt_item1 = isset($opt_item1) ? $opt_item1 : '';

// S_CODE 파라미터 (검색 코드)
$s_code = isset($S_CODE) ? $S_CODE : $CI->input->post('S_CODE');
$s_code = !empty($s_code) ? $s_code : 'Y'; // 기본값: 'Y'

// SORT_SQL 파라미터 (정렬 SQL)
$sort_sql = isset($SORT_SQL) ? $SORT_SQL : $CI->input->post('SORT_SQL');
$sort_sql = !empty($sort_sql) ? $sort_sql : '';

// ============================================
// Query 변수 생성 (모든 파라미터를 하나의 Query 문자열로 연결)
// ============================================
// $query_string 변수명 사용 (데이터베이스 쿼리 변수 $query와 충돌 방지)
$query_string = "";
if (!empty($s_code)) {
    $query_string = $query_string . "S_CODE=" . urlencode($s_code) . "&";
}
if (!empty($seardate)) {
    $query_string = $query_string . "SEARDATE=" . urlencode($seardate) . "&";
}
if (!empty($s_date)) {
    $query_string = $query_string . "S_DATE=" . urlencode($s_date) . "&";
}
if (!empty($office_cd)) {
    $query_string = $query_string . "OFFICE_CD=" . urlencode($office_cd) . "&";
}
if (!empty($io_type)) {
    $query_string = $query_string . "IO_TYPE=" . urlencode($io_type) . "&";
}
if (!empty($so_mode)) {
    $query_string = $query_string . "SO_MODE=" . urlencode($so_mode) . "&";
}
if (!empty($aloc_type)) {
    $query_string = $query_string . "ALOC_TYPE=" . urlencode($aloc_type) . "&";
}
if (!empty($n_field)) {
    $query_string = $query_string . "N_Field=" . urlencode($n_field) . "&";
}
if (!empty($s_text)) {
    $query_string = $query_string . "S_TEXT=" . urlencode($s_text) . "&";
}
if (!empty($receipt_type)) {
    $query_string = $query_string . "RECEIPT_TYPE=" . urlencode($receipt_type) . "&";
}
if (!empty($order_delivery)) {
    $query_string = $query_string . "ORDER_DELIVERY=" . urlencode($order_delivery) . "&";
}
if (!empty($allocate_dv)) {
    $query_string = $query_string . "ALLOCATE_DV=" . urlencode($allocate_dv) . "&";
}
if (!empty($car_no)) {
    $query_string = $query_string . "CAR_NO=" . urlencode($car_no) . "&";
}
if (!empty($car_posion)) {
    $query_string = $query_string . "CAR_POSION=" . urlencode($car_posion) . "&";
}
if (!empty($tran_nm)) {
    $query_string = $query_string . "TRAN_NM=" . urlencode($tran_nm) . "&";
}
if (!empty($drv_nm)) {
    $query_string = $query_string . "DRV_NM=" . urlencode($drv_nm) . "&";
}
if (!empty($car_tel)) {
    $query_string = $query_string . "CAR_TEL=" . urlencode($car_tel) . "&";
}
if (!empty($car_type)) {
    $query_string = $query_string . "CAR_TYPE=" . urlencode($car_type) . "&";
}
if (!empty($car_ton)) {
    $query_string = $query_string . "CAR_TON=" . urlencode($car_ton) . "&";
}
if (!empty($sort_sql)) {
    $query_string = $query_string . "SORT_SQL=" . urlencode($sort_sql) . "&";
}

// ============================================
// 프로시저 실행하여 Grid 데이터 가져오기
// ============================================
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

// 디버깅: $grid_data 출력
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

$grid_data_json = json_encode($grid_data, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_APOS);
?>

<script language="JavaScript">
    // Site URL for AJAX and form submissions
    var SITE_URL = '<?php echo site_url(); ?>';

    function loadScript(src, callback) {
        var script = document.createElement('script');
        script.onload = callback;
        script.onerror = function() {
            document.getElementById('error-message').style.display = 'block';
            document.getElementById('error-message').textContent = 'Failed to load Tabulator. Please check your internet connection and try again.';
        };
        script.src = src;
        document.head.appendChild(script);
    }

    function showGenderInfo(gender) {
        alert("Gender: " + gender);
    }

    // table 변수를 전역으로 선언
    var table;

    function toggleSelectAll(checkbox) {
        if (checkbox.checked) {
            table.selectRow(); // 모든 행 선택
        } else {
            table.deselectRow(); // 모든 행 선택 해제
        }
    }
	
	function saveFontSize(size) {
        localStorage.setItem('fontSize', size);
    }

    function loadFontSize() {
        return localStorage.getItem('fontSize') || '11px'; // 기본값 설정
    }

    function applyFontSize() {
        var fontSize = loadFontSize();
        document.documentElement.style.setProperty('--tabulator-font-size', fontSize);
        document.querySelectorAll('.tabulator .tabulator-cell').forEach(cell => {
            cell.style.fontSize = fontSize; // 각 셀에 글자 크기 적용
            cell.style.fontFamily = 'Dotum, 돋움, sans-serif'; // 돋움체 적용
        });
    }

    function increaseFontSize() {
        var currentSize = parseInt(loadFontSize());
        var newSize = currentSize + 1 + 'px';
        saveFontSize(newSize);
        applyFontSize();
    }

    function decreaseFontSize() {
        var currentSize = parseInt(loadFontSize());
        if (currentSize > 1) { // 최소 글자 크기 1px
            var newSize = currentSize - 1 + 'px';
            saveFontSize(newSize);
            applyFontSize();
        }
    }	
	
	// Query 변수 생성 함수 (모든 파라미터를 하나의 Query 문자열로 연결)
	function buildQuery() {
		let Query = "";
		
		// S_CODE
		let s_code = document.querySelector('#S_CODE') ? document.querySelector('#S_CODE').value : 'Y';
		if (s_code) {
			Query = Query + "S_CODE=" + encodeURIComponent(s_code) + "&";
		}
		
		// SEARDATE
		let seardate = document.querySelector('select[name="SEARDATE"]') ? document.querySelector('select[name="SEARDATE"]').value : '';
		if (seardate) {
			Query = Query + "SEARDATE=" + encodeURIComponent(seardate) + "&";
		}
		
		// S_DATE
		let s_date = document.querySelector('#S_DATE') ? document.querySelector('#S_DATE').value : '';
		if (s_date) {
			Query = Query + "S_DATE=" + encodeURIComponent(s_date) + "&";
		}
		
		// OFFICE_CD
		let office_cd = document.querySelector('select[name="OFFICE_CD"]') ? document.querySelector('select[name="OFFICE_CD"]').value : '';
		if (office_cd) {
			Query = Query + "OFFICE_CD=" + encodeURIComponent(office_cd) + "&";
		}
		
		// IO_TYPE
		let io_type = document.querySelector('select[name="IO_TYPE"]') ? document.querySelector('select[name="IO_TYPE"]').value : '';
		if (io_type) {
			Query = Query + "IO_TYPE=" + encodeURIComponent(io_type) + "&";
		}
		
		// SO_MODE
		let so_mode = document.querySelector('#SO_MODE') ? document.querySelector('#SO_MODE').value : '';
		if (so_mode) {
			Query = Query + "SO_MODE=" + encodeURIComponent(so_mode) + "&";
		}
		
		// ALOC_TYPE
		let aloc_type = document.querySelector('select[name="ALOC_TYPE"]') ? document.querySelector('select[name="ALOC_TYPE"]').value : '';
		if (aloc_type) {
			Query = Query + "ALOC_TYPE=" + encodeURIComponent(aloc_type) + "&";
		}
		
		// N_Field
		let n_field = document.querySelector('select[name="N_Field"]') ? document.querySelector('select[name="N_Field"]').value : '';
		if (n_field) {
			Query = Query + "N_Field=" + encodeURIComponent(n_field) + "&";
		}
		
		// S_TEXT
		let s_text = document.querySelector('input[name="S_TEXT"]') ? document.querySelector('input[name="S_TEXT"]').value : '';
		if (s_text) {
			Query = Query + "S_TEXT=" + encodeURIComponent(s_text) + "&";
		}
		
		// RECEIPT_TYPE
		let receipt_type = document.querySelector('select[name="RECEIPT_TYPE"]') ? document.querySelector('select[name="RECEIPT_TYPE"]').value : '';
		if (receipt_type) {
			Query = Query + "RECEIPT_TYPE=" + encodeURIComponent(receipt_type) + "&";
		}
		
		// ORDER_DELIVERY
		let order_delivery = document.querySelector('select[name="ORDER_DELIVERY"]') ? document.querySelector('select[name="ORDER_DELIVERY"]').value : '';
		if (order_delivery) {
			Query = Query + "ORDER_DELIVERY=" + encodeURIComponent(order_delivery) + "&";
		}
		
		// ALLOCATE_DV
		let allocate_dv = document.querySelector('#ALLOCATE_DV') ? document.querySelector('#ALLOCATE_DV').value : '';
		if (allocate_dv) {
			Query = Query + "ALLOCATE_DV=" + encodeURIComponent(allocate_dv) + "&";
		}
		
		// CAR_NO
		let car_no = document.querySelector('#CAR_NO') ? document.querySelector('#CAR_NO').value : '';
		if (car_no) {
			Query = Query + "CAR_NO=" + encodeURIComponent(car_no) + "&";
		}
		
		// CAR_POSION
		let car_posion = document.querySelector('#CAR_POSION') ? document.querySelector('#CAR_POSION').value : '';
		if (car_posion) {
			Query = Query + "CAR_POSION=" + encodeURIComponent(car_posion) + "&";
		}
		
		// TRAN_NM
		let tran_nm = document.querySelector('#TRAN_NM') ? document.querySelector('#TRAN_NM').value : '';
		if (tran_nm) {
			Query = Query + "TRAN_NM=" + encodeURIComponent(tran_nm) + "&";
		}
		
		// DRV_NM
		let drv_nm = document.querySelector('#DRV_NM') ? document.querySelector('#DRV_NM').value : '';
		if (drv_nm) {
			Query = Query + "DRV_NM=" + encodeURIComponent(drv_nm) + "&";
		}
		
		// CAR_TEL
		let car_tel = document.querySelector('#CAR_TEL') ? document.querySelector('#CAR_TEL').value : '';
		if (car_tel) {
			Query = Query + "CAR_TEL=" + encodeURIComponent(car_tel) + "&";
		}
		
		// CAR_TYPE
		let car_type = document.querySelector('#CAR_TYPE') ? document.querySelector('#CAR_TYPE').value : '';
		if (car_type) {
			Query = Query + "CAR_TYPE=" + encodeURIComponent(car_type) + "&";
		}
		
		// CAR_TON
		let car_ton = document.querySelector('#CAR_TON') ? document.querySelector('#CAR_TON').value : '';
		if (car_ton) {
			Query = Query + "CAR_TON=" + encodeURIComponent(car_ton) + "&";
		}
		
		// SORT_SQL
		let sort_sql = document.querySelector('#SORT_SQL') ? document.querySelector('#SORT_SQL').value : '';
		if (sort_sql) {
			Query = Query + "SORT_SQL=" + encodeURIComponent(sort_sql) + "&";
		}
		
		// Query 변수를 hidden input에 저장
		let queryInput = document.querySelector('#Query');
		if (queryInput) {
			queryInput.value = Query;
		}
		
		return Query;
	}
	
	function search_form(){
		// Query 변수 업데이트
		buildQuery();

		let $Allocation_page = document.querySelector('#Allocation_page').value;
		let Query = document.querySelector('#Query').value;
		document.querySelector('#Allocation_Car').action = '<?php echo site_url("allocation_car_list"); ?>?Allocation_page=' + $Allocation_page;
		document.querySelector('#Allocation_Car').submit();
    }
	
	// 타이틀항목 추가삭제 레이어 표시 함수
	function showTitleSelectLayer(event) {
		if (event) {
			event.preventDefault();
			event.stopPropagation();
		}
		var layer = document.getElementById('title_item');
		if (layer) {
			layer.style.display = 'block';
			layer.style.opacity = '0';
			layer.style.transition = 'opacity 0.3s';
			setTimeout(function() {
				layer.style.opacity = '1';
			}, 10);
		}
		return false;
	}		
</script>		

<div class="contents_area" id="contentsArea" style="padding: 0 20px; width: 100%; height: 100%; display: flex; flex-direction: column; align-items: flex-start; justify-content: flex-start;">
     <div class="container_top_aloc_list" style="height: 100px; border: 0px solid #000; width: 100%;">
		<form name="Allocation_Car" id="Allocation_Car" method="post" style="display: flex; flex-direction: column; width: 100%; height: 100%;">
		<input type="hidden" name="Query" id="Query" value="<?php echo htmlspecialchars($query_string); ?>">
		<input type="hidden" name="Allocation_page" id="Allocation_page" value="<?php echo htmlspecialchars(is_string($menu_allocation_page) ? $menu_allocation_page : ''); ?>">
		<input type="hidden" name="GridData" id="GridData">
		<input type="hidden" name="CNT_NO" id="CNT_NO">
		<input type="hidden" name="A_CAR_KEY" id="A_CAR_KEY">  				
        <div id="step_1" style="height: 30px; width: 100%; border: 0px solid #000;">
			<nav style="display: flex; align-items: center; height: 100%; gap: 10px; padding: 0 5px;">
				<select name="SEARDATE" class="text-input-style">
				<option value="LOAD_REQ_DT" <?php echo ($seardate == 'LOAD_REQ_DT') ? 'selected' : ''; ?>>픽업요청일</option>
				<option value="ORD_DT" <?php echo ($seardate == 'ORD_DT') ? 'selected' : ''; ?>>오더등록일</option>
				</select>
				<input type="text" id="S_DATE" name="S_DATE" style="width:70px;cursor:pointer;" class="text-input-style datepicker" value="<?php echo htmlspecialchars($s_date); ?>" readonly>
				<span class="font_bold">사업장</span>
				<?php echo com_office_cd($office_cd, $opt_item1); ?>
				<span class="font_bold">구분</span>
				<?php echo com_io_type($io_type, $opt_item1); ?>								
				<span class="font_bold">상품</span>
				<span class="product-input-wrapper" style="position: relative; display: inline-block;">
					<input type="text" id="SO_MODE" name="SO_MODE" readonly class="Reg_Box" style="width:150px !important; min-width:150px !important;" value="<?php echo htmlspecialchars($so_mode); ?>" onclick="openProductLayer();">
				</span>
				<span class="font_bold">배차유형</span>
				<?php echo com_aloc_type($aloc_type); ?>
				<span class="font_bold">업체</span>
				<?php echo com_search_type($n_field, $opt_item1); ?>
				<input type="search" name="S_TEXT" class="Reg_Box" style="width:250px;ime-mode:active;" value="<?php echo htmlspecialchars($s_text); ?>" onkeydown="if (window.event.keyCode==13) { search_form('Y','S') }">
				<button class="event-btn select-btn" data-name="검색" onclick="search_form();">
					<span class="event-btn-icon icon-search"></span>
					<span>배차현황검색</span>
				</button>
				<div style="width: 100px;"></div>
				<button id="excelButton" class="event-btn excel-btn" data-name="엑셀출력" onclick="excelDown();">
					<span class="event-btn-icon icon-excel"></span>
					<span>엑셀출력</span>
				</button>
				<button class="event-btn select-btn" data-name="배차차량 문자발송">
					<span class="event-btn-icon icon-envelope"></span>
					<span>배차차량 문자발송</span>
				</button>
				<button class="event-btn select-btn" data-name="일반 문자발송">
					<span class="event-btn-icon icon-envelope"></span>
					<span>일반 문자발송</span>
				</button>									
			</nav>
		</div>
       	<div id="step_2" style="height: 30px; width: 100%; border: 0px solid #000; overflow: hidden;">
			<nav style="display: flex; align-items: center; height: 100%; gap: 10px; padding: 0 5px; flex-wrap: nowrap; white-space: nowrap;">
				<button class="event-btn select-btn" data-name="배차차량선택">
					<span class="event-btn-icon icon-check"></span>
					<span>배차차량선택</span>
				</button>
				<button type="button" class="event-btn select-btn" data-name="배차차량등록" onclick="return operat_run(event);">
					<span class="event-btn-icon icon-save"></span>
					<span>배차차량등록</span>
				</button>
				<button type="button" class="event-btn cancel-btn" data-name="배차취소" onclick="return operat_cancle('CANCLE', event);">
					<span class="event-btn-icon icon-cancel"></span>
					<span>배차취소</span>
				</button>
				<button class="event-btn select-btn" data-name="스마트오더전송" onclick="return app_order_sand(event);">
					<span class="event-btn-icon icon-message"></span>
					<span>스마트오더전송</span>
				</button>
				<button class="event-btn select-btn" data-name="MMS오더전송" onclick="return sms_order(event);">
				<span class="event-btn-icon icon-envelope"></span>
					<span>MMS오더전송</span>
				</button>
				<button class="event-btn print-btn" data-name="오더장출력">
					<span class="event-btn-icon icon-print"></span>
					<span>오더장출력</span>
				</button>
				<div style="width: 50px;"></div>
				<select name="RECEIPT_TYPE" class="text-input-style">
					<option value="">선택</option>
				</select>
				<button class="event-btn print-btn" data-name="인수증출력">
					<span class="event-btn-icon icon-print"></span>
					<span>인수증출력</span>
				</button>
				<div style="width: 20px;"></div>
				<span class="font_bold">배차유형변경</span>
				<?php echo com_aloc_stat(''); ?>
				<button class="event-btn select-btn" data-name="배차유형변경" onclick="return Aloc_Type_Change(event);">
					<span class="event-btn-icon icon-save"></span>
					<span>배차유형변경</span>
				</button>
				<div style="width: 50px;"></div>
				<div style="width: 20px;"></div>
				<span class="font_bold">오더전달</span>
				<?php echo com_cust_exchange(''); ?>
				<button class="event-btn forward-btn" data-name="오더전달">
					<span class="event-btn-icon icon-arrow"></span>
					<span>오더전달</span>
				</button>
				<button class="event-btn cancel-btn" data-name="오더전달취소">
					<span class="event-btn-icon icon-cancel"></span>
					<span>오더전달취소</span>
				</button>
				<button type="button" class="event-btn select-btn" data-name="타이틀항목 추가삭제" onclick="showTitleSelectLayer(event);">
					<span class="event-btn-icon icon-check"></span>
					<span>타이틀항목 추가삭제</span>
				</button>
				<button class="event-btn cancel-btn" data-name="타이틀항목 초기화">
					<span class="event-btn-icon icon-reset"></span>
					<span>타이틀항목 초기화</span>
				</button>
			</nav>
		</div>
		<div id="step_3" style="height: 40px; width: 100%; border: 0px solid #000;">
			<nav style="display: flex; align-items: center; height: 100%; gap: 5px; padding: 0 5px; flex-wrap: nowrap; white-space: nowrap;">
				<input type="text" id="cnt" style="width:32px;height:25px;text-align:center;font-size:12px;background-color: #FFFF00;border: 1px solid #000000;" readonly />
				<span class="font_bold">차량번호</span>
				<input type="text" name="CAR_NO" id="CAR_NO"  style="width:60px;" class="Reg_Box" onkeydown="if (window.event.keyCode==13) { car_open2(1); }" value="<?php echo htmlspecialchars($car_no); ?>">
				<span class="font_bold">배차차량구분</span>
				<select name="ALLOCATE_DV" id="ALLOCATE_DV" style="width:80px;" class="custom-select">
					<option value="" <?php echo ($allocate_dv == '') ? 'selected' : ''; ?>>선택</option>
					<option value="01" <?php echo ($allocate_dv == '01') ? 'selected' : ''; ?>>계약차량</option>
					<option value="02" <?php echo ($allocate_dv == '02') ? 'selected' : ''; ?>>협력업체</option>
					<option value="03" <?php echo ($allocate_dv == '03') ? 'selected' : ''; ?>>콜</option>
				</select>
				<span class="font_bold">소속</span>
				<input type="text" name="CAR_POSION" id="CAR_POSION" style="width:80px;" class="Reg_Box" readonly value="<?php echo htmlspecialchars($car_posion); ?>">
				<span class="font_bold">업체명</span>
				<input type="text" name="TRAN_NM" id="TRAN_NM" class="Reg_Box" style="width:120px;" readonly value="<?php echo htmlspecialchars($tran_nm); ?>">
				<span class="font_bold">기사명</span>
				<input type="text" name="DRV_NM" id="DRV_NM" class="Reg_Box" style="width:60px;" readonly value="<?php echo htmlspecialchars($drv_nm); ?>">
				<span class="font_bold">전화번호</span>
				<input type="text" name="CAR_TEL" id="CAR_TEL" class="Reg_Box" style="width:100px;" readonly value="<?php echo htmlspecialchars($car_tel); ?>">
				<span class="font_bold">차량구분</span>
				<input type="text" name="CAR_TYPE" id="CAR_TYPE" class="Reg_Box" style="width:60px;" readonly value="<?php echo htmlspecialchars($car_type); ?>">
				<span class="font_bold">차량톤수</span>
				<input type="text" name="CAR_TON" id="CAR_TON" class="Reg_Box" style="width:60px;" readonly value="<?php echo htmlspecialchars($car_ton); ?>">
				<input type="hidden" name="A_CAR_KEY" id="A_CAR_KEY">
			</nav>
		</div>
		</form>					
	</div>
	<div id="allocation_car_list_<?php echo $menu_allocation_page; ?>" style="height: 600px; max-height: 600px; flex: 1; border: 1px solid #CCC2C2; width: 100%; overflow: hidden;"></div>
	<div id="dropdown" class="dropdown"></div>
</div>

		<script>
				// ========== 1. 데이터 및 전역 변수 선언 ==========
				let tabledata = <?php echo $grid_data_json; ?>;
				// tabledata가 undefined이거나 null이거나 배열이 아닌 경우 빈 배열로 초기화
				if (typeof tabledata === 'undefined' || tabledata === null || !Array.isArray(tabledata)) {
				    tabledata = [];
				}
				// undefined나 null 값을 가진 항목 제거
				tabledata = tabledata.filter(function(item) {
				    return item !== null && item !== undefined && typeof item === 'object';
				});
				let isDragging = false;
				let startCell = null;
				let endCell = null;
				let selectedCells = [];
				let selectionOverlay = null;
				
				// ========== 2. 다중 정렬 관련 변수 ==========
				let sortState = []; 
				const MAX_CLICK_COUNT = 3;
				const sortableColumns = [
				    'OP_NM', 'SO_MODE_H', 'IO_TYPE', 'CUSTOM_CARNO_NUM', 'TRAN_NM_H', 
				    'CAR_TEL_H', 'G_CAR_NO', 'ALOC_TYPE', 'ACT_SHIP_A_NM', 'ACT_SHIP_TEL',
				    'ACT_SHIP_PIC_NM', 'SHIP_NM', 'LOAD_NM', 'LOAD_TEL', 'LOAD_PIC_NM',
				    'LOAD_AREA', 'LOAD_REQ_HM', 'PKG', 'CBM', 'WGT', 'GOD_M_SIZE', 
				    'ORD_ETC', 'FDS_NM', 'UNLOAD_NM', 'UNLOAD_TEL', 'UNLOAD_PIC_NM',
				    'UNLOAD_REQ_DT', 'BILL_NM', 'HBL_NO', 'LOAD_CY', 'LOAD_CY_PIC_NM',
				    'LOAD_CY_TEL', 'UNLOAD_CY_PIC_NM', 'UNLOAD_CY', 'UNLOAD_CY_TEL',
				    'ITEM_NM', 'GOOD_NM', 'CNTR_NO', 'SEAL_NO'
				];
				
				// ========== 3. 검색 관련 변수 ==========
				var currentSearchTerm = '';
				var searchIndex = 0;
				var searchResults = [];
				var isFiltered = false;
				
				// ========== 4. 페이지별 설정값 ==========
				applyFontSize();
				
				let savedOrder = [];
				try {
				    const savedOrderStr = localStorage.getItem('Wonder_Allocation_Column_Header_<?php echo $menu_allocation_page; ?>');
				    if (savedOrderStr) {
				        savedOrder = JSON.parse(savedOrderStr);
				        console.log('저장된 컬럼 순서:', savedOrder);
				    }
				} catch (e) {
				    console.error('컬럼 순서 로딩 실패:', e);
				    savedOrder = [];
				}
				
				function applySorting() {
				    console.log('========== applySorting 시작 ==========');
				    console.log('적용할 sortState:', JSON.parse(JSON.stringify(sortState)));
				    
				    var tableHolder = document.querySelector('#allocation_car_list_<?php echo $menu_allocation_page; ?> .tabulator-tableholder');
				    var scrollLeft = tableHolder ? tableHolder.scrollLeft : 0;
				    console.log('현재 수평 스크롤 위치:', scrollLeft);
				    
				    const allData = table.getData();
				    const normalData = allData.filter(d => !d.isEmpty);
				    const emptyData = allData.filter(d => d.isEmpty);
				    
				    console.log(`정렬 전 - 일반 데이터: ${normalData.length}개, 빈 행: ${emptyData.length}개`);
				    
				    if (sortState.length === 0) {
				        table.setData([...normalData, ...emptyData]);
				        console.log('? 정렬 초기화 (원본 순서)');
				        
				        setTimeout(function() {
				            if (tableHolder) {
				                tableHolder.scrollLeft = scrollLeft;
				                console.log('스크롤 위치 복원:', scrollLeft);
				            }
				        }, 50);
				        return;
				    }
				    
				    const sortedData = normalData.sort((a, b) => {
				        for (let i = 0; i < sortState.length; i++) {
				            const sort = sortState[i];
				            const field = sort.field;
				            const dir = sort.dir;
				            
				            const aVal = (a[field] === null || a[field] === undefined || a[field] === '') ? '' : String(a[field]).trim();
				            const bVal = (b[field] === null || b[field] === undefined || b[field] === '') ? '' : String(b[field]).trim();
				            
				            // 공백을 정렬 방향에 따라 배치
				            if (aVal === '' && bVal !== '') {
				                return dir === 'asc' ? -1 : 1;  // 오름차순: 앞, 내림차순: 뒤
				            }
				            if (aVal !== '' && bVal === '') {
				                return dir === 'asc' ? 1 : -1;  // 오름차순: 뒤, 내림차순: 앞
				            }
				            if (aVal === '' && bVal === '') continue;
				            
				            const compareResult = aVal.localeCompare(bVal, 'ko-KR', { 
				                numeric: true, 
				                sensitivity: 'base' 
				            });
				            
				            if (compareResult !== 0) {
				                return dir === 'asc' ? compareResult : -compareResult;
				            }
				        }
				        return 0;
				    });
				    
				    console.log('정렬 완료 - 순서:');
				    sortedData.slice(0, 10).forEach((data, idx) => {
				        console.log(`  ${idx + 1}. 발주처: ${data.ACT_SHIP_A_NM || '(빈값)'}, 발주담당: ${data.ACT_SHIP_PIC_NM || '(빈값)'}`);
				    });
				    
				    table.setData([...sortedData, ...emptyData]);
				    
				    console.log('? 테이블 데이터 업데이트 완료');
				    
				    setTimeout(function() {
				        if (tableHolder) {
				            tableHolder.scrollLeft = scrollLeft;
				            console.log('스크롤 위치 복원:', scrollLeft);
				        }
				    }, 50);
				}
				
				function clearAllSorts() {
				    sortState = [];
				    localStorage.removeItem('Wonder_Allocation_SortState_<?php echo $menu_allocation_page; ?>');
				    
				    const allData = table.getData();
				    const normalData = allData.filter(d => !d.isEmpty);
				    const emptyData = allData.filter(d => d.isEmpty);
				    
				    table.setData([...normalData, ...emptyData]);
				    updateSortUI();
				    console.log('모든 정렬이 초기화되었습니다.');
				}
				
				function initializeSortState() {
				    sortState = [];
				    
				    const savedSortState = localStorage.getItem('Wonder_Allocation_SortState_<?php echo $menu_allocation_page; ?>');
				    if (savedSortState) {
				        try {
				            sortState = JSON.parse(savedSortState);
				            console.log('정렬 상태 복원:', sortState);
				        } catch (e) {
				            console.error('정렬 상태 복원 실패:', e);
				            sortState = [];
				        }
				    }
				    
				    if (sortState.length > 0) {
				        applySorting();
				    }
				    
				    updateSortUI();
				}
				
				function saveSortState() {
				    localStorage.setItem('Wonder_Allocation_SortState_<?php echo $menu_allocation_page; ?>', JSON.stringify(sortState));
				    console.log('정렬 상태 저장:', sortState);
				}
				
				// ========== 8. 컬럼 관련 함수들 ==========
				function loadColumnWidths() {
				    let savedWidths = localStorage.getItem("Wonder_Allocation_ColumnWidths_<?php echo $menu_allocation_page; ?>");
				    if (savedWidths) {
				        let widths = JSON.parse(savedWidths);
				        table.getColumns().forEach((col, index) => {
				            if (widths[index] !== undefined) {
				                col.setWidth(widths[index]);
				            }
				        });
				    }
				}
				
				function saveAllocation_Column_Header() {
				    const columns = table.getColumns();
				    const order = columns.map(col => col.getField());
				    localStorage.setItem('Wonder_Allocation_Column_Header_<?php echo $menu_allocation_page; ?>', JSON.stringify(order));
				    console.log("컬럼 순서 저장됨:", order);
				}
				
				
				function saveColumnVisibility() {
				    const visibility = {};
				    columnData.forEach(column => {
				        if (column.visible !== false && column.field && 
				            !['SO_NO', 'DRV_CD', 'DRV_NM', 'LISENCE_NO_H', 'LOAD_REQ_DT', 
				             'TOSS_ORDER', 'CAR_DIVISION', 'A_GPS_ID', 'B_GPS_ID', 
				             'A_APP_ID', 'B_APP_ID', 'LOAD_IMG', 'UNLOAD_IMG', 
				             'CLAIM', 'SO_STAT', 'SO_OFF', 'LOAD_F_IMG', 'UNLOAD_F_IMG',
				             'TRAN_VEN_YN', 'T_INFO_YN', 'T_INFO_EMAIL'].includes(column.field)) {
				            const checkbox = document.getElementById(column.field);
				            if (checkbox) {
				                visibility[column.field] = checkbox.checked;
				            }
				        }
				    });
				    localStorage.setItem('Wonder_Allocation_car_list_<?php echo $menu_allocation_page; ?>_column', JSON.stringify(visibility));
				    console.log('컬럼 가시성이 저장되었습니다!');
				}
				
				function restoreColumnVisibility() {
				    const visibility = JSON.parse(localStorage.getItem('Wonder_Allocation_car_list_<?php echo $menu_allocation_page; ?>_column'));
				    if (visibility) {
				        for (const [field, isVisible] of Object.entries(visibility)) {
				            const checkbox = document.getElementById(`${field}`);
				            if (checkbox) {
				                checkbox.checked = isVisible;
				                try {
				                    const column = table.getColumn(field);
				                    if (column && !isVisible) {
				                        column.hide();
				                    }
				                } catch (e) {
				                    console.log(`컬럼 ${field}를 찾을 수 없습니다.`);
				                }
				            }
				        }
				    }
				}
				
				
				// ========== 10. 초기화 함수 ==========
				function resetAllocationTable() {
				    if (confirm('테이블 설정을 초기화하시겠습니까?\n(컬럼 순서, 너비, 가시성, 정렬 상태가 초기화됩니다)')) {
				        localStorage.removeItem('Wonder_Allocation_Column_Header_<?php echo $menu_allocation_page; ?>');
				        localStorage.removeItem('Wonder_Allocation_ColumnWidths_<?php echo $menu_allocation_page; ?>');
				        localStorage.removeItem('Wonder_Allocation_car_list_<?php echo $menu_allocation_page; ?>_column');
				        localStorage.removeItem('Wonder_Allocation_SortState_<?php echo $menu_allocation_page; ?>');
				        alert('초기화 완료! 페이지를 새로고침합니다.');
				        location.reload();
				    }
				}
				window.resetAllocationTable = resetAllocationTable;
				
				// ========== 11. 컬럼 데이터 정의 ==========
				const columnData = [
				    {
				        title:"<div class='custom-header'>담당자<br><span class='custom-sort-button' data-sort-field='OP_NM'></span></div>", 
						headerFilter: "input",
				        field:"OP_NM", 
				        width:45, 
				        hozAlign:"center", 
				        headerSort:false,
				        visible: true
				    },
				    {title:" ", field:"ORDER_VIEW",formatter: OrderViewFormatter, width:25,headerSort: false,minWidth: 25,maxWidth: 25,visible: true},
				    {title:" ", field:"LMS_VIEW",formatter: LmsViewFormatter,  width:25,headerSort: false,minWidth: 25,maxWidth: 25,visible: true},
				    {title:" ", field:"CAR_OPERATE",formatter: CarOperateViewFormatter,  width:25,headerSort: false,minWidth: 25,maxWidth: 25,visible: true},
				    {title:" ", field:"SO_PT",formatter: SoPtFormatter, width:25, headerSort: false,minWidth: 25,maxWidth: 25,visible: true},
				    {title:"M", field:"S_ORDER",hozAlign:"center",  width:25,headerSort: false,minWidth: 25,maxWidth: 25,visible: true},
				    {
				        title:"<div class='custom-header'>상품<br><span class='custom-sort-button' data-sort-field='SO_MODE_H'></span></div>", 
				        field:"SO_MODE_H", 
				        width:40,
				        hozAlign:"center",
				        headerSort:false,
				        visible: true
				    },
				    {
				        title:"<div class='custom-header'>구분<br><span class='custom-sort-button' data-sort-field='IO_TYPE'></span></div>", 
				        field:"IO_TYPE", 
				        width:30,
				        hozAlign:"center", 
				        headerSort:false,
				        visible: true
				    },
				    {title:"픽업<br>출발", field:"LOAD_ST_HM",hozAlign:"center",  width:35,headerSort: false,minWidth: 35,maxWidth: 35,visible: true},
				    {title:"픽업<br>도착", field:"LOAD_PLAN_HM",hozAlign:"center",  width:35,headerSort: false,minWidth: 35,maxWidth: 35,visible: true},
				    {title:"픽업<br>완료", field:"LOAD_HM",hozAlign:"center",formatter: LoadHmFormatter,  width:35,headerSort: false,minWidth: 35,maxWidth: 35,visible: true},
				    {title:"하차<br>도착", field:"UNLOAD_HM",hozAlign:"center",formatter: UnLoadHmFormatter,  width:35,headerSort: false,minWidth: 35,maxWidth: 35,visible: true},
				    {
				        title:"<div class='custom-header'>일반배차<br><span class='custom-sort-button' data-sort-field='CUSTOM_CARNO_NUM'></span></div>", 
				        headerFilter: "input",
				        field:"CUSTOM_CARNO_NUM", 
				        width:70, 
				        tooltip:true,
				        headerSort: false,
				        formatter: Custom_Carno_NumFormatter,
				        visible: true 
				    },
				    {
				        title:"<div class='custom-header'>배차업체<br><span class='custom-sort-button' data-sort-field='TRAN_NM_H'></span></div>", 
				        headerFilter: "input",
				        field:"TRAN_NM_H", 
				        width:100,
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"<div class='custom-header'>차량전화<br><span class='custom-sort-button' data-sort-field='CAR_TEL_H'></span></div>", 
				        headerFilter: "input",
				        field:"CAR_TEL_H", 
				        width:90, 
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"<div class='custom-header'>구간배차<br><span class='custom-sort-button' data-sort-field='G_CAR_NO'></span></div>", 
				        headerFilter: "input",
				        field:"G_CAR_NO", 
				        width:70, 
				        hozAlign:"left", 
				        headerSort: false,
				        formatter: Section_Carno_NumFormatter,
				        visible: true
				    },
				    {
				        title:"<div class='custom-header'>배차유형<br><span class='custom-sort-button' data-sort-field='ALOC_TYPE'></span></div>", 
				        headerFilter: "input",
				        field:"ALOC_TYPE", 
				        width:55, 
				        hozAlign:"center", 
				        formatter: AlocTypeFormatter, 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"<div class='custom-header'>발주처<br><span class='custom-sort-button' data-sort-field='ACT_SHIP_A_NM'></span></div>", 
				        headerFilter: "input",
				        field:"ACT_SHIP_A_NM", 
				        width:100, 
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"<div class='custom-header'>발주처전화<br><span class='custom-sort-button' data-sort-field='ACT_SHIP_TEL'></span></div>", 
				        headerFilter: "input",
				        field:"ACT_SHIP_TEL", 
				        width:90,
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"<div class='custom-header'>발주담당<br><span class='custom-sort-button' data-sort-field='ACT_SHIP_PIC_NM'></span></div>",
				        headerFilter: "input",
				        field:"ACT_SHIP_PIC_NM", 
				        width:70,
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"<div class='custom-header'>화주<br><span class='custom-sort-button' data-sort-field='SHIP_NM'></span></div>", 
				        headerFilter: "input",
				        field:"SHIP_NM", 
				        width:100, 
				        tooltip:true,
				        hozAlign:"left", 
				        formatter: ShipNmFormatter,
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"<div class='custom-header'>픽업지<br><span class='custom-sort-button' data-sort-field='LOAD_NM'></span></div>", 
				        headerFilter: "input",
				        field:"LOAD_NM", 
				        width:100,
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"<div class='custom-header'>픽업지전화<br><span class='custom-sort-button' data-sort-field='LOAD_TEL'></span></div>", 
				        headerFilter: "input",
				        field:"LOAD_TEL", 
				        width:90,
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"<div class='custom-header'>픽업담당<br><span class='custom-sort-button' data-sort-field='LOAD_PIC_NM'></span></div>", 
				        headerFilter: "input",
				        field:"LOAD_PIC_NM", 
				        width:70,
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"<div class='custom-header'>픽업지역<br><span class='custom-sort-button' data-sort-field='LOAD_AREA'></span></div>", 
				        headerFilter: "input",
				        field:"LOAD_AREA", 
				        width:80, 
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"<div class='custom-header'>픽업<br>요청<span class='custom-sort-button' data-sort-field='LOAD_REQ_HM'></span></div>", 
				        field:"LOAD_REQ_HM", 
				        width:45, 
				        hozAlign:"center", 
				        headerSort: false,
				        visible: true
				    },
				    {title:"픽업<br>예정", field:"ARR_PLAN_HM",hozAlign:"center", width:35,headerSort: false,minWidth: 35,maxWidth: 35,visible: true},
				    {
				        title:"<div class='custom-header'>총수량<br><span class='custom-sort-button' data-sort-field='PKG'></span></div>", 
				        field:"PKG", 
				        width:50, 
				        hozAlign:"center", 
				        headerSort: false,
				        formatter: function(cell) {
				            const value = cell.getValue();
				            const SO_NO = cell.getRow().getData().SO_NO;
				            const SO_MODE_H = cell.getRow().getData().SO_MODE_H;
				            let PkgFormatted='';
				            if (value === null || value === undefined || value === '') {
				                PkgFormatted='';
				            }else{
				                const PKG = parseFloat(value);
				                PkgFormatted = PKG % 1 === 0 ? PKG.toString() : PKG.toFixed(0);
				            }
				            return `<div class='OrderView' style='position: relative;top: -2px;' onclick="ord_view('${SO_NO}','${SO_MODE_H}','car');">
				                        <span>${PkgFormatted}</span>
				                    </div>`;
				         },
				        visible: true
				    },
				    {
				        title:"<div class='custom-header'>총부피<br><span class='custom-sort-button' data-sort-field='CBM'></span></div>", 
				        field:"CBM", 
				        width:50, 
				        hozAlign:"center", 
				        headerSort: false,
				        formatter: function(cell) {
				            const value = cell.getValue();
				            const SO_NO = cell.getRow().getData().SO_NO;
				            const SO_MODE_H = cell.getRow().getData().SO_MODE_H;
				            let CbmFormatted='';
				            if (value === null || value === undefined || value === '') {
				                CbmFormatted='';
				            }else{
				                const CBM = parseFloat(value);
				                CbmFormatted = CBM % 1 === 0 ? CBM.toString() : CBM.toFixed(2);
				            }
				            return `<div class='OrderView' style='position: relative;top: -2px;' onclick="ord_view('${SO_NO}','${SO_MODE_H}','car');">
				                        <span>${CbmFormatted}</span>
				                    </div>`;
				        },
				        visible: true
				    },
				    {
				        title:"<div class='custom-header'>총무게<br><span class='custom-sort-button' data-sort-field='WGT'></span></div>", 
				        field:"WGT", 
				        width:50, 
				        hozAlign:"center", 
				        headerSort: false,
				        formatter: function(cell) {
				            const value = cell.getValue();
				            const SO_NO = cell.getRow().getData().SO_NO;
				            const SO_MODE_H = cell.getRow().getData().SO_MODE_H;
				            let weightFormatted='';
				            if (value === null || value === undefined || value === '') {
				                weightFormatted='';
				            }else{
				                const WGT = parseFloat(value);
				                weightFormatted = WGT % 1 === 0 ? WGT.toString() : WGT.toFixed(2);
				            }
				            return `<div class='OrderView' style='position: relative;top: -2px;' onclick="ord_view('${SO_NO}','${SO_MODE_H}','car');">
				                        <span>${weightFormatted}</span>
				                    </div>`;
				        },
				        visible: true
				    },
				    {
				        title:"<div class='custom-header'>사이즈<br><span class='custom-sort-button' data-sort-field='GOD_M_SIZE'></span></div>", 
				        field:"GOD_M_SIZE", 
				        width:120, 
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"<div class='custom-header'>비고<br><span class='custom-sort-button' data-sort-field='ORD_ETC'></span></div>",
				        headerFilter: "input",
				        field:"ORD_ETC", 
				        width:120, 
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"<div class='custom-header'>목적국<br><span class='custom-sort-button' data-sort-field='FDS_NM'></span></div>", 
				        headerFilter: "input",
				        field:"FDS_NM", 
				        width:70, 
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"<div class='custom-header'>하차지<br><span class='custom-sort-button' data-sort-field='UNLOAD_NM'></span></div>", 
				        headerFilter: "input",
				        field:"UNLOAD_NM", 
				        width:100, 
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"<div class='custom-header'>하차지전화<br><span class='custom-sort-button' data-sort-field='UNLOAD_TEL'></span></div>", 
				        headerFilter: "input",
				        field:"UNLOAD_TEL", 
				        width:90,
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"<div class='custom-header'>하차담당<br><span class='custom-sort-button' data-sort-field='UNLOAD_PIC_NM'></span></div>", 
				        headerFilter: "input",
				        field:"UNLOAD_PIC_NM", 
				        width:70,
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"<div class='custom-header'>하차요청일<br><span class='custom-sort-button' data-sort-field='UNLOAD_REQ_DT'></span></div>", 
				        field:"UNLOAD_REQ_DT", 
				        width:80, 
				        hozAlign:"center", 
				        headerSort: false,
				        visible: true
				    },
				    {title:"하차<br>시간", field:"UNLOAD_REQ_HM",hozAlign:"center", width:40,headerSort: false,visible: true},
				    {title:"도착<br>보고", field:"ARRIVAL",hozAlign:"center", width:30,headerSort: false,minWidth: 30,maxWidth: 30,visible: true},
				    {title:"서류", field:"DOC", width:30,hozAlign:"center",headerSort: false,minWidth: 30,maxWidth: 30,visible: true},
				    {title:"하차<br>긴급", field:"EM_UNLOAD_REQ", width:30,hozAlign:"center",headerSort: false,minWidth: 30,maxWidth: 30,visible: true},
				    {title:"통관", field:"CUSTOMS", width:30,hozAlign:"center",headerSort: false,minWidth: 30,maxWidth: 30,visible: true},
				    {title:"보세", field:"BOND", width:30,hozAlign:"center",headerSort: false,minWidth: 30,maxWidth: 30,visible: true},
				    {
				        title:"운송매출",
				        field:"U_S_AMT",
				        hozAlign:"right",
				        width:60,
				        headerSort: false,
				        formatter: function(cell) {
				            const value = cell.getValue();
				            return value !== null && value !== undefined && value !== '' ? addComma(parseFloat(value).toFixed(0)) : '';
				        },
				        visible: true
				    },
				    {
				        title:"운송매입",
				        field:"U_B_AMT",
				        hozAlign:"right",
				        width:60,
				        headerSort: false,
				        formatter: function(cell) {
				            const value = cell.getValue();
				            return value !== null && value !== undefined && value !== '' ? addComma(parseFloat(value).toFixed(0)) : '';
				        },
				        visible: true
				    },
				    {
				        title:"기타매출",
				        field:"K_S_AMT",
				        hozAlign:"right",
				        width:60,
				        headerSort: false,
				        formatter: function(cell) {
				            const value = cell.getValue();
				            return value !== null && value !== undefined && value !== '' ? addComma(parseFloat(value).toFixed(0)) : '';
				        },
				        visible: true
				    },
				    {
				        title:"기타매입",
				        field:"K_B_AMT",
				        hozAlign:"right",
				        width:60,
				        headerSort: false,
				        formatter: function(cell) {
				            const value = cell.getValue();
				            return value !== null && value !== undefined && value !== '' ? addComma(parseFloat(value).toFixed(0)) : '';
				        },
				        visible: true
				    },
				    {
				        title:"도착매출",
				        field:"A_S_AMT",
				        hozAlign:"right",
				        width:60,
				        headerSort: false,
				        formatter: function(cell) {
				            const value = cell.getValue();
				            return value !== null && value !== undefined && value !== '' ? addComma(parseFloat(value).toFixed(0)) : '';
				        },
				        visible: true
				    },
				    {
				        title:"도착매입",
				        field:"A_B_AMT",
				        hozAlign:"right",
				        width:60,
				        headerSort: false,
				        formatter: function(cell) {
				            const value = cell.getValue();
				            return value !== null && value !== undefined && value !== '' ? addComma(parseFloat(value).toFixed(0)) : '';
				        },
				        visible: true
				    },
				    {
				        title:"총매출",
				        field:"T_S_AMT",
				        hozAlign:"right",
				        width:60,
				        headerSort: false,
				        formatter: function(cell) {
				            const value = cell.getValue();
				            return value !== null && value !== undefined && value !== '' ? addComma(parseFloat(value).toFixed(0)) : '';
				        },
				        visible: true
				    },
				    {
				        title:"총매입",
				        field:"T_B_AMT",
				        hozAlign:"right",
				        width:60,
				        headerSort: false,
				        formatter: function(cell) {
				            const value = cell.getValue();
				            return value !== null && value !== undefined && value !== '' ? addComma(parseFloat(value).toFixed(0)) : '';
				        },
				        visible: true
				    },
				    {
				        title:"<div class='custom-header'>청구처<br><span class='custom-sort-button' data-sort-field='BILL_NM'></span></div>",
				        headerFilter: "input",
				        field:"BILL_NM", 
				        width:100, 
				        tooltip: function(cell){
				            return  cell.getValue();
				        },
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"<div class='custom-header'>BL_NO<br><span class='custom-sort-button' data-sort-field='HBL_NO'></span></div>", 
				        headerFilter: "input",
				        field:"HBL_NO", 
				        width:90, 
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        formatter: Hbl_No_Formatter,
				        visible: true
				    },
				    {
				        title:"<div class='custom-header'>픽업CY<br><span class='custom-sort-button' data-sort-field='LOAD_CY'></span></div>", 
				        field:"LOAD_CY", 
				        width:100,
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"<div class='custom-header'>픽업CY담당<br><span class='custom-sort-button' data-sort-field='LOAD_CY_PIC_NM'></span></div>", 
				        field:"LOAD_CY_PIC_NM", 
				        width:80, 
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"<div class='custom-header'>픽업CY전화<br><span class='custom-sort-button' data-sort-field='LOAD_CY_TEL'></span></div>", 
				        field:"LOAD_CY_TEL", 
				        width:80,
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"<div class='custom-header'>하차CY담당<br><span class='custom-sort-button' data-sort-field='UNLOAD_CY_PIC_NM'></span></div>", 
				        field:"UNLOAD_CY_PIC_NM", 
				        width:80,
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"<div class='custom-header'>하차CY<br><span class='custom-sort-button' data-sort-field='UNLOAD_CY'></span></div>", 
				        field:"UNLOAD_CY", 
				        width:100,
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"<div class='custom-header'>하차CY담당<br><span class='custom-sort-button' data-sort-field='UNLOAD_CY_TEL'></span></div>", 
				        field:"UNLOAD_CY_TEL", 
				        width:80,
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"<div class='custom-header'>아이템<br><span class='custom-sort-button' data-sort-field='ITEM_NM'></span></div>", 
				        field:"ITEM_NM", 
				        width:80,
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"<div class='custom-header'>품목<br><span class='custom-sort-button' data-sort-field='GOOD_NM'></span></div>", 
				        field:"GOOD_NM", 
				        width:60,
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"<div class='custom-header'>CNTR_NO<br><span class='custom-sort-button' data-sort-field='CNTR_NO'></span></div>", 
				        field:"CNTR_NO", 
				        width:80,
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"<div class='custom-header'>SEAL_NO<br><span class='custom-sort-button' data-sort-field='SEAL_NO'></span></div>", 
				        field:"SEAL_NO",
				        tooltip:true,
				        width:80, 
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {title: "",field: "SO_NO",visible: false},
				    {title: "",field: "DRV_CD",visible: false},
				    {title: "",field: "DRV_NM",visible: false},
				    {title: "",field: "LISENCE_NO_H",visible: false},
				    {title: "",field: "LOAD_REQ_DT",visible: false},
				    {title: "",field: "TOSS_ORDER",visible: false},
				    {title: "",field: "CAR_DIVISION",visible: false},
				    {title: "",field: "A_GPS_ID",visible: false},
				    {title: "",field: "B_GPS_ID",visible: false},
				    {title: "",field: "A_APP_ID",visible: false},
				    {title: "",field: "B_APP_ID",visible: false},
				    {title: "",field: "LOAD_IMG",visible: false},
				    {title: "",field: "UNLOAD_IMG",visible: false},
				    {title: "",field: "CLAIM",visible: false},
				    {title: "",field: "SO_STAT",visible: false},
				    {title: "",field: "SO_OFF",visible: false},
				    {title: "",field: "LOAD_F_IMG",visible: false},
				    {title: "",field: "UNLOAD_F_IMG",visible: false},
				    {title: "",field: "TRAN_VEN_YN",visible: false},
				    {title: "",field: "T_INFO_YN",visible: false},
				    {title: "",field: "T_INFO_EMAIL",visible: false},
				];
				
				// ========== 12. 컬럼 순서 결정 ==========
				let orderedColumns = columnData;
				
				if (savedOrder && savedOrder.length > 0) {
				    const orderedCols = [];
				    savedOrder.forEach(fieldName => {
				        const col = columnData.find(c => c.field === fieldName);
				        if (col) {
				            orderedCols.push(col);
				        }
				    });
				    
				    columnData.forEach(col => {
				        if (!savedOrder.includes(col.field)) {
				            orderedCols.push(col);
				        }
				    });
				    
				    orderedColumns = orderedCols;
				    console.log('정렬된 컬럼 개수:', orderedColumns.length);
				}
				
				// ========== 13. Tabulator 테이블 생성 ==========
				var table = new Tabulator("#allocation_car_list_<?php echo $menu_allocation_page; ?>", {
				    rowHeader: {
				        headerSort: false,
				        resizable: false,
				        frozen: false,
				        headerHozAlign: "center",
				        hozAlign: "center",
				        formatter: "rowSelection",
				        titleFormatter: function(cell, formatterParams, onRendered) {
				            var checkbox = document.createElement("input");
				            checkbox.type = "checkbox";
				            checkbox.id = "header-select-all";            
				            checkbox.addEventListener("change", function() {
				                if (this.checked) {
				                    if (isFiltered) {
				                        var visibleRows = table.getRows("visible");
				                        visibleRows.forEach(function(row) {
				                            if (!row.getData().isEmpty) {
				                                row.select();
				                            }
				                        });
				                    } else {
				                        var allRows = table.getRows();
				                        allRows.forEach(function(row) {
				                            if (!row.getData().isEmpty) {
				                                row.select();
				                            }
				                        });
				                    }
				                } else {
				                    if (isFiltered) {
				                        var visibleRows = table.getRows("visible");
				                        visibleRows.forEach(function(row) {
				                            if (!row.getData().isEmpty) {
				                                row.deselect();
				                            }
				                        });
				                    } else {
				                        table.deselectRow();
				                    }
				                }
				            });
				            
				            return checkbox;
				        },
				        cellClick: function(e, cell) {
				            if (!cell.getRow().getData().isEmpty) {
				                cell.getRow().toggleSelect();
				            }
				        },
				        width: 30,
				    },
				    height:"600px",
				    maxHeight:"600px",
				    rowHeight: 26,
				    layout: "fitDataStretch",
				    virtualDom: true,
				    virtualDomBuffer: 50,
				    scrollToRowPosition: "top",
				    columns: orderedColumns,
				    rowContextMenu: [
				        {
				            label: "기사명 복사",
				            action: function(e, row){
				                const rowData = row.getData();
				                const DRV_NM = rowData.DRV_NM;
				                navigator.clipboard.writeText(DRV_NM).then(function() {
				                }, function() {
				                    alert("클립보드 복사에 실패했습니다.");
				                });
				            }
				        },
				        {
				            label: "차량번호 복사",
				            action: function(e, row){
				                const rowData = row.getData();
				                const LISENCE_NO_H = rowData.LISENCE_NO_H;
				                navigator.clipboard.writeText(LISENCE_NO_H).then(function() {
				                }, function() {
				                    alert("클립보드 복사에 실패했습니다.");
				                });
				            }
				        },
				        {
				            label: "차량전화번호 복사",
				            action: function(e, row){
				                const rowData = row.getData();
				                const CAR_TEL_H = rowData.CAR_TEL_H;
				                navigator.clipboard.writeText(CAR_TEL_H).then(function() {
				                }, function() {
				                    alert("클립보드 복사에 실패했습니다.");
				                });
				            }
				        },
				        {
				            label: "기사명/차량번호/차량휴대폰번호 복사",
				            action: function(e, row){
				                var rowData = row.getData();
				                var dataToCopy = rowData.DRV_NM + " / " + rowData.LISENCE_NO_H + " / " + rowData.CAR_TEL_H;
				                navigator.clipboard.writeText(dataToCopy).then(function() {
				                }, function() {
				                });
				            }
				        }
				    ],
				    data: (tabledata && tabledata.length > 0) ? tabledata : [], // 데이터가 없으면 빈 배열
				    placeholder: (tabledata && tabledata.length > 0) ? undefined : "데이터가 없습니다.", // 데이터가 없을 때 플레이스홀더
				    minHeight: (tabledata && tabledata.length > 0) ? undefined : 0, // 데이터가 없을 때 최소 높이 0
				    selectable: true,
				    headerFilter: true,
				    movableColumns: true,
				    columnResized: function(column) {
				        console.log('컬럼 사이즈 이동');
				    },
				    rowFormatter: function(row) {
				        var datas = row.getData();
				        
				        // datas가 없거나 undefined이거나 null이거나 객체가 아닌 경우 행 숨김
				        if (!datas || datas === null || datas === undefined || typeof datas !== 'object') {
				            var rowElement = row.getElement();
				            if (rowElement) {
				                rowElement.style.display = 'none';
				            }
				            return;
				        }
				        
				        // isEmpty가 true인 경우: 컬럼 병합, 체크박스/아이콘 숨김, 배경색 #ccc
				        if (datas.isEmpty === true) {
				            var rowElement = row.getElement();
				            if (rowElement) {
				                // 배경색 설정
				                rowElement.style.backgroundColor = '#ccc';
				                
				                // 체크박스 숨김
				                var checkboxCell = rowElement.querySelector('.tabulator-row-header');
				                if (checkboxCell) {
				                    checkboxCell.style.display = 'none';
				                }
				                
				                // 모든 셀 가져오기
				                var cells = rowElement.querySelectorAll('.tabulator-cell');
				                if (cells.length > 0) {
				                    // 첫 번째 셀만 표시하고 나머지 숨김
				                    var firstCell = cells[0];
				                    firstCell.style.width = '100%';
				                    firstCell.style.borderRight = 'none';
				                    firstCell.style.textAlign = 'center';
				                    firstCell.style.color = '#000';
				                    firstCell.innerHTML = '';
				                    
				                    // 나머지 셀 숨김
				                    for (let j = 1; j < cells.length; j++) {
				                        cells[j].style.display = 'none';
				                    }
				                }
				            }
				            return;
				        }
				        
				        if (datas['SO_OFF'] === "Y") {
				            row.getElement().style.color = "#7401DF";
				        } else {
				            if (datas['SO_STAT'] === "00") {
				                if (datas['SO_PT'] === "W" || datas['SO_PT'] === "E") {
				                    row.getElement().style.color = "#FE2E2E";
				                } else {
				                    row.getElement().style.color = "#2E2E2E";
				                }
				            }
				        }
				    },
				});
				
				// ========== 14. Tabulator 이벤트 리스너 ==========
				table.on("rowSelectionChanged", function(data, rows) {
				    updateHeaderCheckboxState();
				    var validSelectedCount = data.filter(function(row) {
				        return !row.isEmpty;
				    }).length;
				    document.getElementById("cnt").value = validSelectedCount;
				});
				
				table.on("dataFiltered", function(filters, rows) {
				    isFiltered = (filters && filters.length > 0);
				    updateHeaderCheckboxState();
				    
				    var selectedRows = table.getSelectedRows();
				    if (isFiltered) {
				        var visibleRows = table.getRows("visible");
				        var visibleSelectedCount = selectedRows.filter(function(selectedRow) {
				            var rowData = selectedRow.getData();
				            return visibleRows.includes(selectedRow) && !rowData.isEmpty;
				        }).length;
				        document.getElementById("cnt").value = visibleSelectedCount;
				    } else {
				        var validSelectedCount = selectedRows.filter(function(selectedRow) {
				            return !selectedRow.getData().isEmpty;
				        }).length;
				        document.getElementById("cnt").value = validSelectedCount;
				    }
				});
				
				table.on("tableBuilt", function() {
				    // 데이터가 없으면 모든 빈 행과 빈 셀 숨김
				    if (!tabledata || tabledata.length === 0) {
				        setTimeout(function() {
				            // 모든 행 확인 및 빈 행 제거
				            var allRows = table.getRows();
				            allRows.forEach(function(row) {
				                try {
				                    var rowElement = row.getElement();
				                    if (rowElement) {
				                        // 빈 행인지 확인 (모든 셀이 비어있거나 &nbsp;만 있는 경우)
				                        var cells = rowElement.querySelectorAll('.tabulator-cell');
				                        var isEmptyRow = true;
				                        
				                        cells.forEach(function(cell) {
				                            var cellText = cell.textContent.trim();
				                            var cellHTML = cell.innerHTML.trim();
				                            // 셀이 비어있지 않고 &nbsp;나 공백만 있는 경우가 아닌지 확인
				                            if (cellText !== '' && cellText !== '&nbsp;' && cellText !== '\u00A0' && cellHTML !== '' && cellHTML !== '&nbsp;') {
				                                isEmptyRow = false;
				                            }
				                        });
				                        
				                        if (isEmptyRow) {
				                            rowElement.classList.add('tabulator-row-empty');
				                            rowElement.style.display = 'none';
				                        } else {
				                            row.delete();
				                        }
				                    } else {
				                        row.delete();
				                    }
				                } catch(e) {
				                    // 삭제 실패 시 무시
				                }
				            });
				            
				            // 모든 빈 셀 숨김
				            var allCells = document.querySelectorAll('#allocation_car_list_<?php echo $menu_allocation_page; ?> .tabulator-cell');
				            allCells.forEach(function(cell) {
				                var cellText = cell.textContent.trim();
				                var cellHTML = cell.innerHTML.trim();
				                if (cellText === '' || cellText === '&nbsp;' || cellText === '\u00A0' || cellHTML === '' || cellHTML === '&nbsp;') {
				                    cell.classList.add('tabulator-cell-empty');
				                    cell.style.display = 'none';
				                }
				            });
				        }, 100);
				        return;
				    }
				    
				    updateHeaderCheckboxState();
				    
				    sortableColumns.forEach(field => {
				        let sortButton = document.querySelector(`[data-sort-field="${field}"]`);
				        if (sortButton) {
				            sortButton.style.cursor = 'pointer';
				            sortButton.addEventListener('click', function(e) {
				                e.stopPropagation();
				                handleSortClick(field);
				            });
				        }
				    });
				    
				    setTimeout(() => {
				        initializeSortState();
				    }, 100);
				    
				    var checkbox = document.createElement("input");
				    checkbox.type = "checkbox";
				    checkbox.id = "header-select";
				
				    checkbox.addEventListener("change", function() {
				        if (this.checked) {
				            table.selectRow();
				        } else {
				            table.deselectRow();
				        }
				    });
				
				    var headerCell = document.querySelector(".tabulator-col[data-field='select'] .tabulator-col-content");
				    if (headerCell) {
				        headerCell.appendChild(checkbox);
				    }
				    addDragSelectionEvents();
				});
				
				table.on("cellClick", function(e, cell) {
				    const A_GPS_ID = cell.getRow().getData().A_GPS_ID;
				    const B_GPS_ID = cell.getRow().getData().B_GPS_ID;
				    const A_APP_ID = cell.getRow().getData().A_APP_ID;
				    const B_APP_ID = cell.getRow().getData().B_APP_ID;
				    const LOAD_REQ_DT = cell.getRow().getData().LOAD_REQ_DT;
				    const LOAD_REQ_HM = cell.getRow().getData().LOAD_REQ_HM;
				    const UNLOAD_REQ_DT = cell.getRow().getData().UNLOAD_REQ_DT;
				    const UNLOAD_REQ_HM = cell.getRow().getData().UNLOAD_REQ_HM;
				    const GPS_A_START = LOAD_REQ_DT.replace('-','').replace('-','');
				    const GPS_A_END =  UNLOAD_REQ_DT.replace('-','').replace('-','');
				    
				    let dropdown = document.getElementById("dropdown");
				    dropdown.style.display = "block";
				    dropdown.style.left = e.clientX + "px";
				    dropdown.style.top = e.clientY + "px";
				    
				    dropdown.innerHTML = "<div class='T_menu' data-value='원더로지스'>원더로지스</div><div  class='T_menu5' data-value='모람씨엔티'>모람씨엔티</div>";
				    switch(cell.getField()){
				        case 'CUSTOM_CARNO_NUM':
				            if (A_GPS_ID != '' || A_APP_ID !='') {
				                dropdown.onclick = function(event) {
				                    var value = event.target.getAttribute("data-value");
				                    if (value) {
				                        if (value === "원더로지스" && A_APP_ID !="") {
				                            window.open('trace_location.asp?MOBILE_ID='+A_APP_ID, '_blank', 'scrollbars=yes,width=710,height=710');
				                        } else if (value === "모람씨엔티") {
				                            openTraceMap('0pdTt29OWdFFWPcQEDafjg==', 2,A_GPS_ID,GPS_A_START,GPS_A_END);
				                        }
				                        dropdown.style.display = "none";
				                    }
				                };
				            }else{
				                dropdown.style.display = "none";
				            }
				        break
				        case 'G_CAR_NO':
				            if (B_GPS_ID != '' || B_APP_ID !='') {
				                dropdown.onclick = function(event) {
				                    var value = event.target.getAttribute("data-value");
				                    if (value) {
				                        if (value === "원더로지스" && B_APP_ID !="") {
				                            window.open('trace_location.asp?MOBILE_ID='+B_APP_ID, '_blank', 'scrollbars=yes,width=710,height=710');
				                        } else if (value === "모람씨엔티") {
				                            openTraceMap('0pdTt29OWdFFWPcQEDafjg==', 2,B_GPS_ID,GPS_A_START,GPS_A_END);
				                        }
				                        dropdown.style.display = "none";
				                    }
				                };
				            }else{
				                dropdown.style.display = "none";
				            }
				        break
				        default:
				            dropdown.style.display = "none";
				        break;
				    }
				});
				
				table.on("columnMoved", function(column) {
				    console.log("컬럼이 이동되었습니다:", column.getField());
				    saveAllocation_Column_Header();
				});
				
				table.on("rowClick", function(e, row){
				    if (row.getData().isEmpty) {
				        return;
				    }
				    
				    if (row.getElement().style.background == 'rgb(206, 216, 246)'){
				        row.getElement().style.background = "#ffffff";
				        var checkboxCell = row.getElement().querySelector('.tabulator-row-header');
				        if (checkboxCell) {
				            checkboxCell.style.background = "#ffffff";
				        }
				    } else {
				        row.getElement().style.background = "#CED8F6";
				        var checkboxCell = row.getElement().querySelector('.tabulator-row-header');
				        if (checkboxCell) {
				            checkboxCell.style.background = "#CED8F6";
				        }
				    }
				});
				
				table.on("rowDblClick", function(e, row){
				    let row_datas = row.getData();
				    ord_view(row_datas.SO_NO,row_datas.SO_MODE_H,'car');
				});
				
				table.on("columnResized", function () {
				    let widths = table.getColumns().map(col => col.getWidth());
				    localStorage.setItem("Wonder_Allocation_ColumnWidths_<?php echo $menu_allocation_page; ?>", JSON.stringify(widths));
				});
				
				// ========== 15. 키보드 이벤트 리스너 ==========
				document.addEventListener('keydown', function(e) {
				    if (e.ctrlKey && (e.key === 'f' || e.key === 'F')) {
				        e.preventDefault();
				        var searchTerm = prompt("검색할 내용을 입력하세요:");
				        if (searchTerm) {
				            performSearch(searchTerm);
				        }
				    } 
				    else if (e.ctrlKey && e.key === 'c') {
				        if (selectedCells.length > 0) {
				            e.preventDefault();
				            copySelectedCells();
				        }
				    }
				    else if (e.key === 'Enter' && searchResults.length > 0) {
				        e.preventDefault();
				        nextSearchResult();
				    } else if (e.key === 'F3' && !e.shiftKey) {
				        e.preventDefault();
				        nextSearchResult();
				    } else if (e.key === 'F3' && e.shiftKey) {
				        e.preventDefault();
				        prevSearchResult();
				    } else if (e.key === 'Escape') {
				        clearHighlights();
				        clearCellSelection();
				    }
				});
				
				// ========== 16. window.onload (페이지 초기화) ==========
				window.onload = function() {
				    console.log('=== 페이지 로딩 시작 ===');
				    console.log('테이블 데이터 개수:', tabledata ? tabledata.length : 0);
				    console.log('컬럼 개수:', orderedColumns.length);
				    
				    // 데이터가 없으면 테이블 초기화 중단
				    if (!tabledata || tabledata.length === 0) {
				        console.log('데이터가 없어 테이블 초기화를 건너뜁니다.');
				        return;
				    }
				    
				    clearAllSorts();
				    restoreColumnVisibility();
				    setTimeout(() => {
				        loadColumnWidths();
				        
				        setTimeout(() => {
				            initializeSortState();
				        }, 200);
				        
				        // 데이터가 있는 경우에만 빈 행 추가
				        if (tabledata && tabledata.length > 0) {
				            var emptyRowData = {
				                isEmpty: true,
				                OP_NM: "",
				                SO_MODE_H: "",
				                IO_TYPE: "",
				            };
				            
				            columnData.forEach(function(column) {
				                if (column.field && column.field !== 'isEmpty') {
				                    emptyRowData[column.field] = "";
				                }
				            });
				
				            table.addRow(emptyRowData, false).then(function(row){
				                console.log("빈 행 추가 완료");
				            });
				        }
				        
				        table.redraw(true);
				        console.log('=== 테이블 렌더링 완료 ===');
				    }, 100);
				};
				
				// ========== 17. CSS 스타일 ==========
				if (!document.getElementById('sort-number-style')) {
				    let style = document.createElement('style');
				    style.id = 'sort-number-style';
				    style.textContent = `
				        .custom-sort-button {
				            cursor: pointer;
				            display: inline-block;
				            padding: 0px 3px;
				            margin-left: 2px;
				            user-select: none;
				            font-size: 10px;
				            color: #666;
				        }
				        .custom-sort-button:hover {
				            background-color: rgba(0, 0, 0, 0.1);
				            border-radius: 2px;
				            color: #000;
				        }
				    `;
				    document.head.appendChild(style);
				}
        </script>
<script>
// RECEIPT_TYPE select의 선택값 설정
document.addEventListener('DOMContentLoaded', function() {
    var receiptTypeSelect = document.querySelector('select[name="RECEIPT_TYPE"]');
    var receiptTypeValue = '<?php echo htmlspecialchars($receipt_type, ENT_QUOTES); ?>';
    if (receiptTypeSelect && receiptTypeValue) {
        receiptTypeSelect.value = receiptTypeValue;
    }
    
    // ORDER_DELIVERY select의 선택값 설정
    var orderDeliverySelect = document.querySelector('select[name="ORDER_DELIVERY"]');
    var orderDeliveryValue = '<?php echo htmlspecialchars($order_delivery, ENT_QUOTES); ?>';
    if (orderDeliverySelect && orderDeliveryValue) {
        orderDeliverySelect.value = orderDeliveryValue;
    }
});
</script>

<?php $this->load->view('common/product_select_layer'); ?>
<?php $this->load->view('common/grid_title_select'); ?>
<?php $this->load->view('common/bottom'); ?>

