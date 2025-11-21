<?php $this->load->view('common/header', array('category' => $category)); ?>

<!-- 하단 탭 버튼들 (동적 로딩) -->
<?php
	$submenu_data = array(
		'folder_name' => isset($folder_name) ? $folder_name : 'Allocation_Car',
		'current_file' => isset($current_file) ? $current_file : 'section_allocation_car_status_list'
	);
	$this->load->view('common/submenu', $submenu_data);
	
	// Helper 함수 로드
	$CI =& get_instance();
	$CI->load->helper('common_form');
	
	// ASP Request 파라미터를 PHP로 변환
	// POST 우선, 없으면 GET에서 가져오기
	$s_code = $CI->input->post('S_CODE') ?: $CI->input->get('S_CODE');
	$s_code = !empty($s_code) ? $s_code : '';
	
	$s_code2 = $CI->input->post('S_CODE2') ?: $CI->input->get('S_CODE2');
	$s_code2 = !empty($s_code2) ? $s_code2 : '';
	
	$s_date = $CI->input->post('S_DATE') ?: $CI->input->get('S_DATE');
	$s_date = !empty($s_date) ? $s_date : '';
	
	$seardate = $CI->input->post('SEARDATE') ?: $CI->input->get('SEARDATE');
	if (empty($seardate) || is_null($seardate)) {
		$seardate = 'LOAD_REQ_DT';
	}
	
	if (empty($s_date)) {
		$t_date = str_replace('-', '', date('Y-m-d'));
		$s_date = date('Y-m-d');
	} else {
		$t_date = str_replace('-', '', $s_date);
	}
	
	// Section_Allocation_page 파라미터 처리
	$section_allocation_page_post = $CI->input->post('Section_Allocation_page');
	$section_allocation_page_get = $CI->input->get('Section_Allocation_page');
	$section_allocation_page = !empty($section_allocation_page_post) ? $section_allocation_page_post : (!empty($section_allocation_page_get) ? $section_allocation_page_get : '');
	// Section_Allocation_page가 빈 문자열이면 기본값으로 Ch_1 설정
	if (empty($section_allocation_page)) {
		$section_allocation_page = 'Ch_1';
	}
	
	$so_mode = $CI->input->post('SO_MODE') ?: $CI->input->get('SO_MODE');
	
	// Section_Allocation_page에 따른 SO_MODE 기본값 설정
	if (empty($so_mode)) {
		switch ($section_allocation_page) {
			case 'Ch_1':
				$so_mode = 'LCL,';
				break;
			case 'Ch_2':
				$so_mode = 'AIR,';
				break;
			case 'Ch_3':
				$so_mode = 'FCL,';
				break;
			default:
				$so_mode = 'LCL,';
				break;
		}
	}
	
	$io_type = $CI->input->post('IO_TYPE') ?: $CI->input->get('IO_TYPE');
	$io_type = !empty($io_type) ? $io_type : '';
	
	$aloc_type = $CI->input->post('ALOC_TYPE') ?: $CI->input->get('ALOC_TYPE');
	$aloc_type = !empty($aloc_type) ? $aloc_type : '';
	
	$aloc_type_t = $aloc_type;
	
	$aloc_type_value = '';
	switch ($aloc_type) {
		case 'C01':
			$aloc_type_value = '직송배차';
			break;
		case 'C02':
			$aloc_type_value = '일산집하';
			break;
		case 'C03':
			$aloc_type_value = '안성집하';
			break;
		case 'C04':
			$aloc_type_value = '익일콘솔';
			break;
		case 'C05':
			$aloc_type_value = '당일콘솔';
			break;
	}
	
	$aloc_stat = $CI->input->post('ALOC_STAT') ?: $CI->input->get('ALOC_STAT');
	$aloc_stat = !empty($aloc_stat) ? $aloc_stat : '';
	
	$aloc_stat_c01 = '';
	$aloc_stat_c02 = '';
	$aloc_stat_c03 = '';
	$aloc_stat_c04 = '';
	$aloc_stat_c05 = '';
	
	switch ($aloc_stat) {
		case 'C01':
			$aloc_stat_c01 = 'selected';
			break;
		case 'C02':
			$aloc_stat_c02 = 'selected';
			break;
		case 'C03':
			$aloc_stat_c03 = 'selected';
			break;
		case 'C04':
			$aloc_stat_c04 = 'selected';
			break;
		case 'C05':
			$aloc_stat_c05 = 'selected';
			break;
	}
	
	$n_field = $CI->input->post('N_Field') ?: $CI->input->get('N_Field');
	$n_field = !empty($n_field) ? $n_field : '';
	
	$s_text = $CI->input->post('S_TEXT') ?: $CI->input->get('S_TEXT');
	$s_text = !empty($s_text) ? $s_text : '';
	
	$sort_sql = $CI->input->post('SORT_SQL') ?: $CI->input->get('SORT_SQL');
	$sort_sql = !empty($sort_sql) ? $sort_sql : '';
	
	$office_cd = $CI->input->post('OFFICE_CD') ?: $CI->input->get('OFFICE_CD');
	if (empty($office_cd) || is_null($office_cd)) {
		$office_cd = '01';
	}
	
	$query = '';
	$query .= 'S_CODE=' . urlencode($s_code) . '&';
	$query .= 'S_CODE2=' . urlencode($s_code2) . '&';
	$query .= 'SEARDATE=' . urlencode($seardate) . '&';
	$query .= 'S_DATE=' . urlencode($s_date) . '&';
	$query .= 'IO_TYPE=' . urlencode($io_type) . '&';
	$query .= 'SO_MODE=' . urlencode($so_mode) . '&';
	$query .= 'PAGE_KEY=' . urlencode(isset($page_key) ? $page_key : '') . '&';
	$query .= 'N_Field=' . urlencode($n_field) . '&';
	$query .= 'OFFICE_CD=' . urlencode($office_cd) . '&';
	$query .= 'ALOC_TYPE=' . urlencode($aloc_type) . '&';
	$query .= 'S_TEXT=' . urlencode($s_text);
	
	$s_car_no = $CI->input->post('S_CAR_NO') ?: $CI->input->get('S_CAR_NO');
	$s_car_no = !empty($s_car_no) ? $s_car_no : '';
	
	$lisence_no = $CI->input->post('LISENCE_NO') ?: $CI->input->get('LISENCE_NO');
	$lisence_no = !empty($lisence_no) ? $lisence_no : '';
	
	$car_posion = $CI->input->post('CAR_POSION') ?: $CI->input->get('CAR_POSION');
	$car_posion = !empty($car_posion) ? $car_posion : '';
	
	$tran_nm = $CI->input->post('TRAN_NM') ?: $CI->input->get('TRAN_NM');
	$tran_nm = !empty($tran_nm) ? $tran_nm : '';
	
	$drv_nm = $CI->input->post('DRV_NM') ?: $CI->input->get('DRV_NM');
	$drv_nm = !empty($drv_nm) ? $drv_nm : '';
	
	$car_tel = $CI->input->post('CAR_TEL') ?: $CI->input->get('CAR_TEL');
	$car_tel = !empty($car_tel) ? $car_tel : '';
	
	$car_type = $CI->input->post('CAR_TYPE') ?: $CI->input->get('CAR_TYPE');
	$car_type = !empty($car_type) ? $car_type : '';
	
	$car_ton = $CI->input->post('CAR_TON') ?: $CI->input->get('CAR_TON');
	$car_ton = !empty($car_ton) ? $car_ton : '';
	
	$aloc_gb = $CI->input->post('ALOC_GB') ?: $CI->input->get('ALOC_GB');
	$aloc_gb = !empty($aloc_gb) ? $aloc_gb : '';
	
	$cust_cd = $CI->input->post('CUST_CD') ?: $CI->input->get('CUST_CD');
	$cust_cd = !empty($cust_cd) ? $cust_cd : '';
	
	$drv_cd = $CI->input->post('DRV_CD') ?: $CI->input->get('DRV_CD');
	$drv_cd = !empty($drv_cd) ? $drv_cd : '';
	
	$car_hor_add = $CI->input->post('CAR_HOR_ADD') ?: $CI->input->get('CAR_HOR_ADD');
	$car_hor_add = !empty($car_hor_add) ? $car_hor_add : '';
	
	// 기타 변수 초기화
	$opt_item1 = isset($opt_item1) ? $opt_item1 : '';
	$query_string = isset($query_string) ? $query_string : $query;
	$car_no = isset($car_no) ? $car_no : '';
	$allocate_dv = isset($allocate_dv) ? $allocate_dv : '';
	$work_value = isset($work_value) ? $work_value : '';

	// ============================================
	// 프로시저 실행하여 Grid 데이터 가져오기
	// ============================================
	// $n_field를 $s_code로 사용 (검색 유형)
	$grid_data = get_allocation_car_grid_data($t_date, $office_cd, $io_type, $so_mode, $aloc_type, $n_field, $s_text, $sort_sql, isset($Grid_Data) ? $Grid_Data : null, true);
	$grid_data_json = json_encode($grid_data, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_APOS);
	
	// ============================================
	// Proc_So_Aloc_Section_T_List_Json 프로시저 호출 예제
	// ============================================
	// $section_t_list_data = get_section_allocation_t_list_data($t_date, $aloc_type, $so_mode, true);
	// $section_t_list_json = json_encode($section_t_list_data, JSON_UNESCAPED_UNICODE | JSON_HEX_QUOT | JSON_HEX_APOS);
?>

<?php $this->load->view('common/footer'); ?>

<!-- 배차관리 목록 컨텐츠 -->
<div class="contents_area" id="contentsArea" style="padding: 0 20px; width: 100%; height: 100%; display: flex; flex-direction: column; align-items: flex-start; justify-content: flex-start;">
     <div class="container_top_aloc_list" style="height: 100px; border: 0px solid #000; width: 100%;">
		<form name="Allocation_Car" id="Allocation_Car" method="post" style="display: flex; flex-direction: column; width: 100%; height: 100%;">
		<input type="hidden" name="Form_Id" id="Form_Id" value="Section_Allocation_page_<?php echo htmlspecialchars($section_allocation_page); ?>">	
		<input type="hidden" name="Query" id="Query" value="<?php echo htmlspecialchars($query_string); ?>">
		<input type="hidden" name="Section_Allocation_page" id="Section_Allocation_page" value="<?php echo htmlspecialchars($section_allocation_page); ?>">
		<input type="hidden" name="GridData" id="GridData">
		<input type="hidden" name="CNT_NO" id="CNT_NO">
		<input type="hidden" name="A_CAR_KEY" id="A_CAR_KEY">  				
        <div id="step_1" style="height: 30px; width: 100%; border: 0px solid #000;">
			<nav style="display: flex; align-items: center; height: 100%; gap: 10px; padding: 0 5px;">
				<select name="SEARDATE" class="text-input-style">
				<option value="01" <?php echo ($seardate == 'LOAD_REQ_DT') ? 'selected' : ''; ?>>픽업요청일</option>
				<option value="02" <?php echo ($seardate == 'ORD_DT') ? 'selected' : ''; ?>>오더등록일</option>
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
				<input type="search" name="S_TEXT" class="Reg_Box" style="width:180px;ime-mode:active;" value="<?php echo htmlspecialchars($s_text); ?>" onkeydown="if (window.event.keyCode==13) { search_form('Y','S') }">
				<button class="event-btn select-btn" data-name="검색" onclick="search_form();">
					<span class="event-btn-icon icon-search"></span>
					<span>검색</span>
				</button>
				<div style="width: 100px;"></div>
				<?php
				echo com_section_work_order($work_value, $t_date, $aloc_type);
				?>
				<button id="excelButton" class="event-btn print-btn" data-name="작업지시서출력" onclick="">
					<span class="event-btn-icon icon-print"></span>
					<span>작업지시서출력</span>
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
	<div id="section_allocation_car_list_area" style="height: 600px; max-height: 600px; flex: 1; border: 0px solid #CCC2C2; width: 100%; overflow: hidden; display: flex; flex-direction: row;">
		<div id="section_allocation_car_list_<?php echo $section_allocation_page; ?>" class="csetp_1">
			
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
				//applyFontSize();
				
				let savedOrder = [];
				try {
				    const savedOrderStr = localStorage.getItem('Wonder_Section_Allocation_Column_Header_<?php echo $section_allocation_page; ?>');
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
				    
				    var tableHolder = document.querySelector('#section_allocation_car_list_<?php echo $section_allocation_page; ?> .tabulator-tableholder');
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
				    localStorage.removeItem('Wonder_Section_Allocation_SortState_<?php echo $section_allocation_page; ?>');
				    
				    const allData = table.getData();
				    const normalData = allData.filter(d => !d.isEmpty);
				    const emptyData = allData.filter(d => d.isEmpty);
				    
				    table.setData([...normalData, ...emptyData]);
				    updateSortUI();
				    console.log('모든 정렬이 초기화되었습니다.');
				}
				
				function initializeSortState() {
				    sortState = [];
				    
				    const savedSortState = localStorage.getItem('Wonder_Section_Allocation_SortState_<?php echo $section_allocation_page; ?>');
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
				    localStorage.setItem('Wonder_Section_Allocation_SortState_<?php echo $section_allocation_page; ?>', JSON.stringify(sortState));
				    console.log('정렬 상태 저장:', sortState);
				}
				
				// ========== 8. 컬럼 관련 함수들 ==========
				function loadColumnWidths() {
				    let savedWidths = localStorage.getItem("Wonder_Section_Allocation_ColumnWidths_<?php echo $section_allocation_page; ?>");
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
				    localStorage.setItem('Wonder_Section_Allocation_Column_Header_<?php echo $section_allocation_page; ?>', JSON.stringify(order));
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
				    localStorage.setItem('Wonder_Section_Allocation_car_list_<?php echo $section_allocation_page; ?>_column', JSON.stringify(visibility));
				    console.log('컬럼 가시성이 저장되었습니다!');
				}
				
				function restoreColumnVisibility() {
				    const visibility = JSON.parse(localStorage.getItem('Wonder_Section_Allocation_car_list_<?php echo $section_allocation_page; ?>_column'));
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
				        localStorage.removeItem('Wonder_Section_Allocation_Column_Header_<?php echo $section_allocation_page; ?>');
				        localStorage.removeItem('Wonder_Section_Allocation_ColumnWidths_<?php echo $section_allocation_page; ?>');
				        localStorage.removeItem('Wonder_Section_Allocation_car_list_<?php echo $section_allocation_page; ?>_column');
				        localStorage.removeItem('Wonder_Section_Allocation_SortState_<?php echo $section_allocation_page; ?>');
				        alert('초기화 완료! 페이지를 새로고침합니다.');
				        location.reload();
				    }
				}
				window.resetAllocationTable = resetAllocationTable;
				
				// ========== 11. 컬럼 데이터 정의 ==========
				const columnData = [
				    {
				        title:"담당자", 
						headerFilter: "input",
				        headerFilterPlaceholder: "검색",
						headerFilterParams: {
							elementAttributes: {
						   	style: "text-align: center;" // 입력 텍스트와 Placeholder를 중앙 정렬
							}
						},						
				        titleFormatter: function(cell) {
				            return `<div class='custom-header-wrapper'>
				                        <div class='header-title'>담당자</div>
				                        <span class='custom-sort-button' data-sort-field='OP_NM'>▼</span>
				                    </div>`;
				        },						
				        field:"OP_NM", 
				        width:45, 
				        hozAlign:"center", 
				        headerSort:false,
				        visible: true
				    },
				    {title:"-", field:"ORDER_VIEW",formatter: OrderViewFormatter, width:25,headerSort: false,minWidth: 25,maxWidth: 25,visible: true},
				    {title:"-", field:"LMS_VIEW",formatter: LmsViewFormatter,  width:25,headerSort: false,minWidth: 25,maxWidth: 25,visible: true},
				    {title:"-", field:"CAR_OPERATE",formatter: CarOperateViewFormatter,  width:25,headerSort: false,minWidth: 25,maxWidth: 25,visible: true},
				    {title:"-", field:"SO_PT",formatter: SoPtFormatter, width:25, headerSort: false,minWidth: 25,maxWidth: 25,visible: true},
				    {title:"M", field:"S_ORDER",hozAlign:"center",  width:25,headerSort: false,minWidth: 25,maxWidth: 25,visible: true},
				    {
				        title:"상품", 
				        titleFormatter: function(cell) {
				            return `<div class='custom-header-wrapper'>
				                        <div class='header-title'>상품<br><span class='custom-sort-button' data-sort-field='SO_MODE_H'>▼</span></div>
				                    </div>`;
				        },						
				        field:"SO_MODE_H", 
				        width:40,
				        hozAlign:"center",
				        headerSort:false,
				        visible: true
				    },
				    {
				        title:"구분", 
				        titleFormatter: function(cell) {
				            return `<div class='custom-header-wrapper'>
				                        <div class='header-title'>구분<br><span class='custom-sort-button' data-sort-field='IO_TYPE'>▼</span></div>
				                    </div>`;
				        },						
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
				        title:"일반배차", 
				        headerFilter: "input",
				        headerFilterPlaceholder: "검색",
						headerFilterParams: {
							elementAttributes: {
						   	style: "text-align: center;" // 입력 텍스트와 Placeholder를 중앙 정렬
							}
						},						
				        titleFormatter: function(cell) {
				            return `<div class='custom-header-wrapper'>
				                        <div class='header-title'>일반배차</div>
				                        <span class='custom-sort-button' data-sort-field='CUSTOM_CARNO_NUM'>▼</span>
				                    </div>`;
				        },
				        field:"CUSTOM_CARNO_NUM", 
				        width:70, 
				        tooltip:true,
				        headerSort: false,
				        formatter: Custom_Carno_NumFormatter,
				        visible: true 
				    },
				    {
				        title:"배차업체", 
				        headerFilter: "input",
				        headerFilterPlaceholder: "검색",
						headerFilterParams: {
							elementAttributes: {
						   	style: "text-align: center;" // 입력 텍스트와 Placeholder를 중앙 정렬
							}
						},						
				        titleFormatter: function(cell) {
				            return `<div class='custom-header-wrapper'>
				                        <div class='header-title'>배차업체</div>
				                        <span class='custom-sort-button' data-sort-field='TRAN_NM_H'>▼</span>
				                    </div>`;
				        },
				        field:"TRAN_NM_H", 
				        width:100,
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"차량전화", 
				        headerFilter: "input",
				        headerFilterPlaceholder: "검색",
						headerFilterParams: {
							elementAttributes: {
						   	style: "text-align: center;" // 입력 텍스트와 Placeholder를 중앙 정렬
							}
						},						
				        titleFormatter: function(cell) {
				            return `<div class='custom-header-wrapper'>
				                        <div class='header-title'>차량전화</div>
				                        <span class='custom-sort-button' data-sort-field='CAR_TEL_H'>▼</span>
				                    </div>`;
				        },	
				        field:"CAR_TEL_H", 
				        width:90, 
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"구간배차", 
				        headerFilter: "input",
				        headerFilterPlaceholder: "검색",
						headerFilterParams: {
							elementAttributes: {
						   	style: "text-align: center;" // 입력 텍스트와 Placeholder를 중앙 정렬
							}
						},						
				        titleFormatter: function(cell) {
				            return `<div class='custom-header-wrapper'>
				                        <div class='header-title'>구간배차</div>
				                        <span class='custom-sort-button' data-sort-field='G_CAR_NO'>▼</span>
				                    </div>`;
				        },
				        field:"G_CAR_NO", 
				        width:70, 
				        hozAlign:"left", 
				        headerSort: false,
				        formatter: Section_Carno_NumFormatter,
				        visible: true
				    },
				    {
				        title:"배차유형", 
				        headerFilter: "input",
				        headerFilterPlaceholder: "검색",
						headerFilterParams: {
							elementAttributes: {
						   	style: "text-align: center;" // 입력 텍스트와 Placeholder를 중앙 정렬
							}
						},						
				        titleFormatter: function(cell) {
				            return `<div class='custom-header-wrapper'>
				                        <div class='header-title'>배차유형</div>
				                        <span class='custom-sort-button' data-sort-field='ALOC_TYPE'>▼</span>
				                    </div>`;
				        },	
				        field:"ALOC_TYPE", 
				        width:55, 
				        hozAlign:"center", 
				        formatter: AlocTypeFormatter, 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"발주처", 
				        headerFilter: "input",
				        headerFilterPlaceholder: "검색",
						headerFilterParams: {
							elementAttributes: {
						   	style: "text-align: center;" // 입력 텍스트와 Placeholder를 중앙 정렬
							}
						},						
				        titleFormatter: function(cell) {
				            return `<div class='custom-header-wrapper'>
				                        <div class='header-title'>발주처</div>
				                        <span class='custom-sort-button' data-sort-field='ACT_SHIP_A_NM'>▼</span>
				                    </div>`;
				        },	
				        field:"ACT_SHIP_A_NM", 
				        width:100, 
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"발주처전화", 
				        headerFilter: "input",
				        headerFilterPlaceholder: "검색",
						headerFilterParams: {
							elementAttributes: {
						   	style: "text-align: center;" // 입력 텍스트와 Placeholder를 중앙 정렬
							}
						},						
				        titleFormatter: function(cell) {
				            return `<div class='custom-header-wrapper'>
				                        <div class='header-title'>발주처전화</div>
				                        <span class='custom-sort-button' data-sort-field='ACT_SHIP_TEL'>▼</span>
				                    </div>`;
				        },	
				        field:"ACT_SHIP_TEL", 
				        width:90,
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"발주담당",
				        headerFilter: "input",
				        headerFilterPlaceholder: "검색",
						headerFilterParams: {
							elementAttributes: {
						   	style: "text-align: center;" // 입력 텍스트와 Placeholder를 중앙 정렬
							}
						},						
				        titleFormatter: function(cell) {
				            return `<div class='custom-header-wrapper'>
				                        <div class='header-title'>발주담당</div>
				                        <span class='custom-sort-button' data-sort-field='ACT_SHIP_PIC_NM'>▼</span>
				                    </div>`;
				        },	
				        field:"ACT_SHIP_PIC_NM", 
				        width:70,
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"화주", 
				        headerFilter: "input",
				        headerFilterPlaceholder: "검색",
						headerFilterParams: {
							elementAttributes: {
						   	style: "text-align: center;" // 입력 텍스트와 Placeholder를 중앙 정렬
							}
						},						
				        titleFormatter: function(cell) {
				            return `<div class='custom-header-wrapper'>
				                        <div class='header-title'>화주</div>
				                        <span class='custom-sort-button' data-sort-field='SHIP_NM'>▼</span>
				                    </div>`;
				        },	
				        field:"SHIP_NM", 
				        width:100, 
				        tooltip:true,
				        hozAlign:"left", 
				        formatter: ShipNmFormatter,
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"픽업지", 
				        headerFilter: "input",
				        headerFilterPlaceholder: "검색",
						headerFilterParams: {
							elementAttributes: {
						   	style: "text-align: center;" // 입력 텍스트와 Placeholder를 중앙 정렬
							}
						},						
				        titleFormatter: function(cell) {
				            return `<div class='custom-header-wrapper'>
				                        <div class='header-title'>픽업지</div>
				                        <span class='custom-sort-button' data-sort-field='LOAD_NM'>▼</span>
				                    </div>`;
				        },	
				        field:"LOAD_NM", 
				        width:100,
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"픽업지전화", 
				        headerFilter: "input",
				        headerFilterPlaceholder: "검색",
						headerFilterParams: {
							elementAttributes: {
						   	style: "text-align: center;" // 입력 텍스트와 Placeholder를 중앙 정렬
							}
						},						
				        titleFormatter: function(cell) {
				            return `<div class='custom-header-wrapper'>
				                        <div class='header-title'>픽업지전화</div>
				                        <span class='custom-sort-button' data-sort-field='LOAD_TEL'>▼</span>
				                    </div>`;
				        },	
				        field:"LOAD_TEL", 
				        width:90,
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"픽업담당", 
				        headerFilter: "input",
				        headerFilterPlaceholder: "검색",
						headerFilterParams: {
							elementAttributes: {
						   	style: "text-align: center;" // 입력 텍스트와 Placeholder를 중앙 정렬
							}
						},						
				        titleFormatter: function(cell) {
				            return `<div class='custom-header-wrapper'>
				                        <div class='header-title'>픽업담당</div>
				                        <span class='custom-sort-button' data-sort-field='LOAD_PIC_NM'>▼</span>
				                    </div>`;
				        },	
				        field:"LOAD_PIC_NM", 
				        width:70,
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"픽업지역", 
				        headerFilter: "input",
				        headerFilterPlaceholder: "검색",
						headerFilterParams: {
							elementAttributes: {
						   	style: "text-align: center;" // 입력 텍스트와 Placeholder를 중앙 정렬
							}
						},						
				        titleFormatter: function(cell) {
				            return `<div class='custom-header-wrapper'>
				                        <div class='header-title'>픽업지역</div>
				                        <span class='custom-sort-button' data-sort-field='LOAD_AREA'>▼</span>
				                    </div>`;
				        },	
				        field:"LOAD_AREA", 
				        width:80, 
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"픽업요청", 
				        titleFormatter: function(cell) {
				            return `<div class='custom-header-wrapper'>
				                        <div class='header-title'>픽업<br>요청<br><span class='custom-sort-button' data-sort-field='LOAD_REQ_HM'>▼</span></div>
				                    </div>`;
				        },	 
				        field:"LOAD_REQ_HM", 
				        width:45, 
				        hozAlign:"center", 
				        headerSort: false,
				        visible: true
				    },
				    {title:"픽업<br>예정", field:"ARR_PLAN_HM",hozAlign:"center", width:35,headerSort: false,minWidth: 35,maxWidth: 35,visible: true},
				    {
				        title:"총수량", 
				        titleFormatter: function(cell) {
				            return `<div class='custom-header-wrapper'>
				                        <div class='header-title'>총수량<br><span class='custom-sort-button' data-sort-field='PKG'>▼</span></div>
				                    </div>`;
				        },	
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
				        title:"총부피", 
				        titleFormatter: function(cell) {
				            return `<div class='custom-header-wrapper'>
				                        <div class='header-title'>총부피<br><span class='custom-sort-button' data-sort-field='CBM'>▼</span></div>
				                    </div>`;
				        },	
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
				        title:"총무게", 
				        titleFormatter: function(cell) {
				            return `<div class='custom-header-wrapper'>
				                        <div class='header-title'>총무게<br><span class='custom-sort-button' data-sort-field='WGT'>▼</span></div>
				                    </div>`;
				        },	
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
				        title:"비고",
				        headerFilter: "input",
				        headerFilterPlaceholder: "검색",
						headerFilterParams: {
							elementAttributes: {
						   	style: "text-align: center;" // 입력 텍스트와 Placeholder를 중앙 정렬
							}
						},						
				        titleFormatter: function(cell) {
				            return `<div class='custom-header-wrapper'>
				                        <div class='header-title'>비고</div>
				                        <span class='custom-sort-button' data-sort-field='ORD_ETC'>▼</span>
				                    </div>`;
				        },	
				        field:"ORD_ETC", 
				        width:120, 
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"목적국", 
				        headerFilter: "input",
				        headerFilterPlaceholder: "검색",
						headerFilterParams: {
							elementAttributes: {
						   	style: "text-align: center;" // 입력 텍스트와 Placeholder를 중앙 정렬
							}
						},						
				        titleFormatter: function(cell) {
				            return `<div class='custom-header-wrapper'>
				                        <div class='header-title'>목적국</div>
				                        <span class='custom-sort-button' data-sort-field='FDS_NM'>▼</span>
				                    </div>`;
				        },
				        field:"FDS_NM", 
				        width:70, 
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"하차지", 
				        headerFilter: "input",
				        headerFilterPlaceholder: "검색",
						headerFilterParams: {
							elementAttributes: {
						   	style: "text-align: center;" // 입력 텍스트와 Placeholder를 중앙 정렬
							}
						},						
				        titleFormatter: function(cell) {
				            return `<div class='custom-header-wrapper'>
				                        <div class='header-title'>하차지</div>
				                        <span class='custom-sort-button' data-sort-field='UNLOAD_NM'>▼</span>
				                    </div>`;
				        },	
				        field:"UNLOAD_NM", 
				        width:100, 
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"하차지전화", 
				        headerFilter: "input",
				        headerFilterPlaceholder: "검색",
						headerFilterParams: {
							elementAttributes: {
						   	style: "text-align: center;" // 입력 텍스트와 Placeholder를 중앙 정렬
							}
						},						
				        titleFormatter: function(cell) {
				            return `<div class='custom-header-wrapper'>
				                        <div class='header-title'>하차지전화</div>
				                        <span class='custom-sort-button' data-sort-field='UNLOAD_TEL'>▼</span>
				                    </div>`;
				        },	
				        field:"UNLOAD_TEL", 
				        width:90,
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"하차담당", 
				        headerFilter: "input",
				        headerFilterPlaceholder: "검색",
						headerFilterParams: {
							elementAttributes: {
						   	style: "text-align: center;" // 입력 텍스트와 Placeholder를 중앙 정렬
							}
						},						
				        titleFormatter: function(cell) {
				            return `<div class='custom-header-wrapper'>
				                        <div class='header-title'>하차담당</div>
				                        <span class='custom-sort-button' data-sort-field='UNLOAD_PIC_NM'>▼</span>
				                    </div>`;
				        },
				        field:"UNLOAD_PIC_NM", 
				        width:70,
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"하차요청일", 
				        titleFormatter: function(cell) {
				            return `<div class='custom-header-wrapper'>
				                        <div class='header-title'>하차요청일<br><span class='custom-sort-button' data-sort-field='UNLOAD_REQ_DT'>▼</span></div>
				                        
				                    </div>`;
				        },	
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
				        title:"청구처",
				        headerFilter: "input",
				        headerFilterPlaceholder: "검색",
						headerFilterParams: {
							elementAttributes: {
						   	style: "text-align: center;" // 입력 텍스트와 Placeholder를 중앙 정렬
							}
						},						
				        titleFormatter: function(cell) {
				            return `<div class='custom-header-wrapper'>
				                        <div class='header-title'>청구처</div>
				                        <span class='custom-sort-button' data-sort-field='BILL_NM'>▼</span>
				                    </div>`;
				        },	
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
				        title:"HBL_NO", 
				        headerFilter: "input",
				        headerFilterPlaceholder: "검색",
						headerFilterParams: {
							elementAttributes: {
						   	style: "text-align: center;" // 입력 텍스트와 Placeholder를 중앙 정렬
							}
						},						
				        titleFormatter: function(cell) {
				            return `<div class='custom-header-wrapper'>
				                        <div class='header-title'>HBL_NO</div>
				                        <span class='custom-sort-button' data-sort-field='HBL_NO'>▼</span>
				                    </div>`;
				        },	
				        field:"HBL_NO", 
				        width:90, 
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        formatter: Hbl_No_Formatter,
				        visible: true
				    },
				    {
				        title:"픽업CY", 
				        headerFilter: "input",
				        headerFilterPlaceholder: "검색",
						headerFilterParams: {
							elementAttributes: {
						   	style: "text-align: center;" // 입력 텍스트와 Placeholder를 중앙 정렬
							}
						},						
				        titleFormatter: function(cell) {
				            return `<div class='custom-header-wrapper'>
				                        <div class='header-title'>픽업CY</div>
				                        <span class='custom-sort-button' data-sort-field='LOAD_CY'>▼</span>
				                    </div>`;
				        },	 
				        field:"LOAD_CY", 
				        width:100,
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
					        title:"픽업CY담당", 
				        headerFilter: "input",
				        headerFilterPlaceholder: "검색",
						headerFilterParams: {
							elementAttributes: {
						   	style: "text-align: center;" // 입력 텍스트와 Placeholder를 중앙 정렬
							}
						},						
				        titleFormatter: function(cell) {
				            return `<div class='custom-header-wrapper'>
				                        <div class='header-title'>픽업CY담당</div>
				                        <span class='custom-sort-button' data-sort-field='LOAD_CY_PIC_NM'>▼</span>
				                    </div>`;
				        },
				        field:"LOAD_CY_PIC_NM", 
				        width:80, 
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"픽업CY전화", 
				        headerFilter: "input",
				        headerFilterPlaceholder: "검색",
						headerFilterParams: {
							elementAttributes: {
						   	style: "text-align: center;" // 입력 텍스트와 Placeholder를 중앙 정렬
							}
						},						
				        titleFormatter: function(cell) {
				            return `<div class='custom-header-wrapper'>
				                        <div class='header-title'>픽업CY전화</div>
				                        <span class='custom-sort-button' data-sort-field='LOAD_CY_TEL'>▼</span>
				                    </div>`;
				        },	 
				        field:"LOAD_CY_TEL", 
				        width:80,
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"하차CY담당", 
				        headerFilter: "input",
				        headerFilterPlaceholder: "검색",
						headerFilterParams: {
							elementAttributes: {
						   	style: "text-align: center;" // 입력 텍스트와 Placeholder를 중앙 정렬
							}
						},						
				        titleFormatter: function(cell) {
				            return `<div class='custom-header-wrapper'>
				                        <div class='header-title'>하차CY담당</div>
				                        <span class='custom-sort-button' data-sort-field='UNLOAD_CY_PIC_NM'>▼</span>
				                    </div>`;
				        },	
				        field:"UNLOAD_CY_PIC_NM", 
				        width:80,
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"하차CY", 
				        headerFilter: "input",
				        headerFilterPlaceholder: "검색",
						headerFilterParams: {
							elementAttributes: {
						   	style: "text-align: center;" // 입력 텍스트와 Placeholder를 중앙 정렬
							}
						},						
				        titleFormatter: function(cell) {
				            return `<div class='custom-header-wrapper'>
				                        <div class='header-title'>하차CY</div>
				                        <span class='custom-sort-button' data-sort-field='UNLOAD_CY'>▼</span>
				                    </div>`;
				        },	
				        field:"UNLOAD_CY", 
				        width:100,
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"하차CY전화", 
				        headerFilter: "input",
				        headerFilterPlaceholder: "검색",
						headerFilterParams: {
							elementAttributes: {
						   	style: "text-align: center;" // 입력 텍스트와 Placeholder를 중앙 정렬
							}
						},						
				        titleFormatter: function(cell) {
				            return `<div class='custom-header-wrapper'>
				                        <div class='header-title'>하차CY담당</div>
				                        <span class='custom-sort-button' data-sort-field='UNLOAD_CY_TEL'>▼</span>
				                    </div>`;
				        },
				        field:"UNLOAD_CY_TEL", 
				        width:80,
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"아이템", 
				        headerFilter: "input",
				        headerFilterPlaceholder: "검색",
						headerFilterParams: {
							elementAttributes: {
						   	style: "text-align: center;" // 입력 텍스트와 Placeholder를 중앙 정렬
							}
						},						
				        titleFormatter: function(cell) {
				            return `<div class='custom-header-wrapper'>
				                        <div class='header-title'>아이템</div>
				                        <span class='custom-sort-button' data-sort-field='ITEM_NM'>▼</span>
				                    </div>`;
				        },	
				        field:"ITEM_NM", 
				        width:80,
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"품목", 
				        headerFilter: "input",
				        headerFilterPlaceholder: "검색",
						headerFilterParams: {
							elementAttributes: {
						   	style: "text-align: center;" // 입력 텍스트와 Placeholder를 중앙 정렬
							}
						},						
				        titleFormatter: function(cell) {
				            return `<div class='custom-header-wrapper'>
				                        <div class='header-title'>품목</div>
				                        <span class='custom-sort-button' data-sort-field='GOOD_NM'>▼</span>
				                    </div>`;
				        },	 
				        field:"GOOD_NM", 
				        width:60,
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"CNTR_NO", 
				        headerFilter: "input",
				        headerFilterPlaceholder: "검색",
						headerFilterParams: {
							elementAttributes: {
						   	style: "text-align: center;" // 입력 텍스트와 Placeholder를 중앙 정렬
							}
						},						
				        titleFormatter: function(cell) {
				            return `<div class='custom-header-wrapper'>
				                        <div class='header-title'>CNTR_NO</div>
				                        <span class='custom-sort-button' data-sort-field='CNTR_NO'>▼</span>
				                    </div>`;
				        },		 
				        field:"CNTR_NO", 
				        width:80,
				        tooltip:true,
				        hozAlign:"left", 
				        headerSort: false,
				        visible: true
				    },
				    {
				        title:"SEAL_NO", 
				        headerFilter: "input",
				        headerFilterPlaceholder: "검색",
						headerFilterParams: {
							elementAttributes: {
						   	style: "text-align: center;" // 입력 텍스트와 Placeholder를 중앙 정렬
							}
						},						
				        titleFormatter: function(cell) {
				            return `<div class='custom-header-wrapper'>
				                        <div class='header-title'>SEAL_NO</div>
				                        <span class='custom-sort-button' data-sort-field='SEAL_NO'>▼</span>
				                    </div>`;
				        },	
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
				var table = new Tabulator("#section_allocation_car_list_<?php echo $section_allocation_page; ?>", {
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
				
				// ========== 테이블 이벤트 설정 ==========
				// // 헤더필터를 사용하기 위한 초기화				
				let filterEventRegistered = false;
				let initialLoadComplete = false;	

				table.on("tableBuilt", function() {

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

					//필터 검색 후 로컬 스토리지 저장 
				    // 필터 복원 후 약간의 지연을 두고 이벤트 등록				
					let $Form_Id='';
					$Form_Id=document.querySelector('#Form_Id').value;
				    loadFilterState($Form_Id);
				    
				    // 필터 입력 필드에 이벤트 바인딩 함수
				    function bindFilterEvents() {
				        document.querySelectorAll(".tabulator-header-filter input").forEach(function(input) {
				            // 이미 바인딩된 경우 스킵
				            if (input.dataset.filterBound === 'true') {
				                return;
				            }
				            
				            input.dataset.filterBound = 'true';
				            
				            // 입력 시 실시간 저장
				            input.addEventListener("input", function() {
				                setTimeout(function() {
				                    saveFilterState($Form_Id);
				                    updateFilterHighlight();
				                }, 100);
				            });
				            
				            // 값 변경 시 저장 (clear 버튼 클릭 시에도 감지)
				            input.addEventListener("change", function() {
				                setTimeout(function() {
				                    saveFilterState($Form_Id);
				                    updateFilterHighlight();
				                }, 100);
				            });
				            
				            // 포커스 해제 시 저장 (필터 값이 비워진 경우 감지)
				            input.addEventListener("blur", function() {
				                setTimeout(function() {
				                    saveFilterState($Form_Id);
				                    updateFilterHighlight();
				                }, 100);
				            });
				            
				            // X 버튼 클릭 시 (clear 버튼) - 여러 선택자 시도
				            setTimeout(function() {
				                const filterContainer = input.closest('.tabulator-header-filter');
				                if (filterContainer) {
				                    // Tabulator의 clear 버튼 선택자들
				                    const clearSelectors = [
				                        '.tabulator-header-filter-clear',
				                        '.tabulator-col-filter-clear',
				                        'button[type="button"]',
				                        '.tabulator-header-filter button'
				                    ];
				                    
				                    clearSelectors.forEach(function(selector) {
				                        const clearBtn = filterContainer.querySelector(selector);
				                        if (clearBtn && !clearBtn.dataset.clearBound) {
				                            clearBtn.dataset.clearBound = 'true';
				                            clearBtn.addEventListener("click", function(e) {
				                                e.stopPropagation();
				                                setTimeout(function() {
				                                    saveFilterState($Form_Id);
				                                    updateFilterHighlight();
				                                }, 200);
				                            });
				                        }
				                    });
				                }
				            }, 300);
				        });
				    }
				    
				    setTimeout(function() {
				        if (!filterEventRegistered) {
				            // 필터 변경 시 저장
				            table.on("dataFiltered", function(filters, rows) {
				                // 초기 로딩 시에는 저장하지 않음
				                if (initialLoadComplete) {
				                    saveFilterState($Form_Id);
				                }
				                updateFilterHighlight();
				            });
				            
				            // 초기 필터 입력 필드에 이벤트 바인딩
				            setTimeout(function() {
				                bindFilterEvents();
				                
				                // 동적으로 추가되는 필터 입력 필드를 감지하기 위한 MutationObserver
				                const tableElement = document.querySelector("#section_allocation_car_list_<?php echo $section_allocation_page; ?>");
				                if (tableElement) {
				                    const filterObserver = new MutationObserver(function(mutations) {
				                        bindFilterEvents();
				                    });
				                    filterObserver.observe(tableElement, { childList: true, subtree: true });
				                }
				            }, 500);
				            
				            filterEventRegistered = true;
				        }
				        // 초기 로딩 완료 표시
				        setTimeout(function() {
				            initialLoadComplete = true;
				        }, 500);
				    }, 100);
					//필터 검색 후 로컬 스토리지 저장 	

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
				            var allCells = document.querySelectorAll('#section_allocation_car_list_<?php echo $section_allocation_page; ?> .tabulator-cell');
				            allCells.forEach(function(cell) {
				                var cellText = cell.textContent.trim();
				                var cellHTML = cell.innerHTML.trim();
				                if (cellText === '' || cellText === '&nbsp;' || cellText === '\u00A0' || cellHTML === '' || cellHTML === '&nbsp;') {
				                    cell.classList.add('tabulator-cell-empty');
				                    cell.style.display = 'none';
				                }
				            });
				        }, 100);
				        
				        // 데이터가 없어도 통계 카운터는 실행 (0으로 표시)
				        setTimeout(function() {
				            if (typeof updateOrderCounter === 'function') {
				                console.log('tableBuilt (데이터 없음)에서 updateOrderCounter 호출');
				                updateOrderCounter();
				            } else {
				                console.warn('updateOrderCounter 함수가 아직 정의되지 않았습니다.');
				            }
				        }, 800);
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
				    
				    // 테이블이 완전히 빌드된 후 통계 카운터 실행
				    setTimeout(function() {
				        if (typeof updateOrderCounter === 'function') {
				            console.log('tableBuilt에서 updateOrderCounter 호출');
				            updateOrderCounter();
				        } else {
				            console.warn('updateOrderCounter 함수가 아직 정의되지 않았습니다.');
				        }
				    }, 800);





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
				    localStorage.setItem("Wonder_Section_Allocation_ColumnWidths_<?php echo $section_allocation_page; ?>", JSON.stringify(widths));
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
						updateFilterHighlight();					        
				        // 테이블 렌더링 완료 후 통계 카운터 실행
				        setTimeout(function() {
				            if (typeof updateOrderCounter === 'function') {
				                console.log('window.onload에서 updateOrderCounter 호출');
				                updateOrderCounter();
				            } else {
				                console.warn('updateOrderCounter 함수가 아직 정의되지 않았습니다.');
				            }
				        }, 1000);
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
		</div>
		<div id="csetp_2" class="csetp_2"></div>
		<div id="csetp_3" class="csetp_3"></div>
	</div>
	<div id="dropdown" class="dropdown"></div>
</div>
<?php $this->load->view('common/product_select_layer'); ?>
<?php $this->load->view('common/grid_title_select'); ?>
<?php $this->load->view('common/bottom'); ?>
