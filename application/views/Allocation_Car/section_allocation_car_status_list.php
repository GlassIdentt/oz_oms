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
	<div id="section_allocation_car_list_<?php echo $section_allocation_page; ?>" style="height: 600px; max-height: 600px; flex: 1; border: 0px solid #CCC2C2; width: 100%; overflow: hidden; display: flex; flex-direction: row;">
		<div id="csetp_1" class="csetp_1"></div>
		<div id="csetp_2" class="csetp_2"></div>
		<div id="csetp_3" class="csetp_3"></div>
	</div>
	<div id="dropdown" class="dropdown"></div>
</div>

<?php $this->load->view('common/product_select_layer'); ?>
<?php $this->load->view('common/bottom'); ?>
