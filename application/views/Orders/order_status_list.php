<?php $this->load->view('common/header', array('category' => $category)); ?>

하단 탭 버튼들 (동적 로딩)
<?php
$submenu_data = array(
'folder_name' => isset($folder_name) ? $folder_name : 'Orders',
'current_file' => isset($current_file) ? $current_file : 'order_status_list'
);
$this->load->view('common/submenu', $submenu_data);
?>

<?php $this->load->view('common/footer'); ?>

			<!-- 오더등록 목록 컨텐츠 -->
<div class="contents_area" id="contentsArea" style="padding: 0 20px; width: 100%; height: 100%; display: flex; flex-direction: column; align-items: flex-start; justify-content: flex-start;">
     <div class="container_top_aloc_list" style="height: 100px; border: 0px solid #000; width: 100%;">
		<form name="Allocation_Car" id="Allocation_Car" method="post" style="display: flex; flex-direction: column; width: 100%; height: 100%;">
		<input type="hidden" name="Form_Id" id="Form_Id" value="Allocation_page_<?php echo htmlspecialchars(is_string($menu_allocation_page) ? $menu_allocation_page : ''); ?>">	
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

<?php $this->load->view('common/bottom'); ?>
