<?php 
$header_data = array(
    'category' => isset($category) ? $category : '오더등록록',
    'folder_name' => isset($folder_name) ? $folder_name : 'Orders',
    'current_file' => isset($current_file) ? $current_file : 'order_status_list'
);
$this->load->view('common/header', $header_data); 
?>

<!-- 하단 탭 버튼들 (동적 로딩) -->
<?php
$submenu_data = array(
	'folder_name' => isset($folder_name) ? $folder_name : 'Orders',
	'current_file' => isset($current_file) ? $current_file : 'order_status_list'
);
$this->load->view('common/submenu', $submenu_data);
?>

<?php $this->load->view('common/footer'); ?>

<!-- 오더등록 목록 컨텐츠 -->
<?php
// Helper 함수 로드
$CI =& get_instance();
$CI->load->helper('common_form');

// POST 파라미터로부터 모든 input/select name 값 받기
$office_cd = isset($OFFICE_CD) ? $OFFICE_CD : $CI->input->post('OFFICE_CD');
$office_cd = !empty($office_cd) ? $office_cd : '';

$io_type = isset($IO_TYPE) ? $IO_TYPE : $CI->input->post('IO_TYPE');
$io_type = !empty($io_type) ? $io_type : '';

$aloc_type = isset($ALOC_TYPE) ? $ALOC_TYPE : $CI->input->post('ALOC_TYPE');
$aloc_type = !empty($aloc_type) ? $aloc_type : '';

$n_field = isset($N_Field) ? $N_Field : $CI->input->post('N_Field');
$n_field = !empty($n_field) ? $n_field : '';

$seardate = isset($SEARDATE) ? $SEARDATE : $CI->input->post('SEARDATE');
$seardate = !empty($seardate) ? $seardate : 'LOAD_REQ_DT';

$today = date('Y-m-d');
$s_date = isset($S_DATE) ? $S_DATE : $CI->input->post('S_DATE');
$s_date = !empty($s_date) ? $s_date : $today;

$s_text = isset($S_TEXT) ? $S_TEXT : $CI->input->post('S_TEXT');
$s_text = !empty($s_text) ? $s_text : '';

$so_mode = isset($SO_MODE) ? $SO_MODE : $CI->input->post('SO_MODE');
$so_mode = !empty($so_mode) ? $so_mode : '';

$HBL_NO = isset($HBL_NO) ? $HBL_NO : $CI->input->post('HBL_NO');
$HBL_NO = !empty($HBL_NO) ? $HBL_NO : '';

$opt_item1 = isset($opt_item1) ? $opt_item1 : '';
?>
<div class="contents_area" id="contentsArea" style="padding: 0 20px; width: 100%; height: 100%; display: flex; flex-direction: column; align-items: flex-start; justify-content: flex-start;">
	<div class="container_top_order_list" style="height: 00px; border: 1px solid #000; width: 100%;"> 				
		<div id="step_1" style="height: 50px; width: 80%; border: 1px solid #000;">
			<nav style="display: flex; align-items: center; height: 100%; gap: 10px; padding: 0 5px;">
				<select name="SEARDATE" class="text-input-style">
				<option value="LOAD_REQ_DT" <?php echo ($seardate == 'LOAD_REQ_DT') ? 'selected' : ''; ?>>픽업요청일</option>
				<option value="ORD_DT" <?php echo ($seardate == 'ORD_DT') ? 'selected' : ''; ?>>오더등록일</option>
				</select>
				<input type="text" id="S_DATE" name="S_DATE" readonly style="width:70px;cursor:pointer;" class="text-input-style datepicker" value="<?php echo htmlspecialchars($s_date); ?>">
				<span class="font_bold">사업장</span>
				<?php echo com_office_cd($office_cd, $opt_item1); ?>
				<span class="font_bold">구분</span>
				<?php echo com_io_type($io_type, $opt_item1); ?>								
				<span class="font_bold">상품</span>
				<span class="product-input-wrapper" style="position: relative; display: inline-block;">
					<input type="text" id="SO_MODE" name="SO_MODE" readonly class="Reg_Box" style="width:150px !important; min-width:150px !important;" value="<?php echo htmlspecialchars($so_mode); ?>" onclick="openProductLayer();">
				</span>
				<span class="font_bold">HBL_NO</span>
				<input type="search" name="HBL_NO" class="Reg_Box" style="width:120px;" value="<?php echo htmlspecialchars($HBL_NO); ?>">
				<span class="font_bold">업체</span>
				<?php echo com_search_type($n_field, $opt_item1); ?>
				<input type="search" name="S_TEXT" class="Reg_Box" style="width:250px;ime-mode:active;" value="<?php echo htmlspecialchars($s_text); ?>" onkeydown="if (window.event.keyCode==13) { search_form('Y','S') }">
				<button class="event-btn select-btn" data-name="검색" onclick="search_form();">
					<span class="event-btn-icon icon-search"></span>
					<span>검색</span>
				</button>
				<div style="width: 100px;"></div>
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
	</div>
	<div id="Order_status_list" style="height: 650px; max-height: 650px; flex: 1; border: 1px solid #CCC2C2; width: 100%; overflow: hidden;"></div>
</div>
<?php $this->load->view('common/product_select_layer'); ?>
<?php $this->load->view('common/grid_title_select'); ?>
<?php $this->load->view('common/bottom'); ?>
