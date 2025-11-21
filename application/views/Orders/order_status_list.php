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

// ASP Request 파라미터를 PHP로 변환
// POST 우선, 없으면 GET에서 가져오기

// Order_Page 파라미터 처리 (allocation_page -> Order_Page로 변경)
$order_page_post = $CI->input->post('Order_Page');
$order_page_get = $CI->input->get('Order_Page');
$order_page = !empty($order_page_post) ? $order_page_post : (!empty($order_page_get) ? $order_page_get : '');
// Order_Page가 빈 문자열이면 기본값으로 Ch_1 설정
if (empty($order_page)) {
    $order_page = 'Ch_1';
}

$s_code = $CI->input->post('S_CODE') ?: $CI->input->get('S_CODE');
$s_code = !empty($s_code) ? $s_code : '';

$s_code2 = $CI->input->post('S_CODE2') ?: $CI->input->get('S_CODE2');
$s_code2 = (!empty($s_code2) && !is_null($s_code2)) ? $s_code2 : 'S';

$seardate = $CI->input->post('SEARDATE') ?: $CI->input->get('SEARDATE');
if (empty($seardate) || is_null($seardate)) {
    $seardate = '01';
}

// 프로시저 호출용 원본 값 저장 (01 또는 02)
$seardate_for_proc = $seardate;

// SEARDATE 값 변환 (01 -> LOAD_REQ_DT, 02 -> ORD_REGDATE) - 화면 표시용
$seardate_01 = '';
$seardate_02 = '';
switch ($seardate) {
    case '01':
        $seardate = 'LOAD_REQ_DT';
        $seardate_01 = 'selected';
        break;
    case '02':
        $seardate = 'ORD_REGDATE';
        $seardate_02 = 'selected';
        break;
    case 'LOAD_REQ_DT':
        $seardate_for_proc = '01';
        $seardate_01 = 'selected';
        break;
    case 'ORD_REGDATE':
        $seardate_for_proc = '02';
        $seardate_02 = 'selected';
        break;
    case 'ORD_DT':
        $seardate = 'ORD_REGDATE';
        $seardate_for_proc = '02';
        $seardate_02 = 'selected';
        break;
}

$s_date = $CI->input->post('S_DATE') ?: $CI->input->get('S_DATE');
$today = date('Y-m-d');
if (empty($s_date)) {
    $t_date = str_replace('-', '', $today);
    $s_date = $today;
} else {
    // S_DATE가 YYYY-MM-DD 형식이면 YYYYMMDD로 변환
    if (preg_match('/^\d{4}-\d{2}-\d{2}$/', $s_date)) {
        $t_date = str_replace('-', '', $s_date);
    } 
    // S_DATE가 이미 YYYYMMDD 형식인 경우
    elseif (preg_match('/^\d{8}$/', $s_date)) {
        $t_date = $s_date;
        // S_DATE는 YYYY-MM-DD 형식으로 유지
        $s_date = substr($s_date, 0, 4) . '-' . substr($s_date, 4, 2) . '-' . substr($s_date, 6, 2);
    }
    // 다른 형식이거나 잘못된 형식인 경우 오늘 날짜 사용
    else {
        $t_date = str_replace('-', '', $today);
        $s_date = $today;
    }
}

// T_DATE가 8자리 숫자인지 최종 확인 (프로시저 호출 전 검증)
if (empty($t_date) || strlen($t_date) != 8 || !is_numeric($t_date)) {
    $t_date = str_replace('-', '', $today);
    $s_date = $today;
}

$s_date2 = $CI->input->post('S_DATE2') ?: $CI->input->get('S_DATE2');
if (empty($s_date2)) {
    $t_date2 = str_replace('-', '', $today);
    $s_date2 = $today;
} else {
    $t_date2 = str_replace('-', '', $s_date2);
}

$so_mode = $CI->input->post('SO_MODE') ?: $CI->input->get('SO_MODE');
$so_mode = str_replace('@', "'", $so_mode);

// Order_Page에 따른 SO_MODE 기본값 설정
if (empty($so_mode)) {
    if ($order_page == 'Ch_2') {
        $so_mode = 'AIR,';
    } else {
        // Ch_1 또는 기타의 경우 기본값은 빈 문자열 유지
        $so_mode = '';
    }
}

$so_mode_gubun = $CI->input->post('SO_MODE_GUBUN') ?: $CI->input->get('SO_MODE_GUBUN');
$so_mode_gubun = !empty($so_mode_gubun) ? $so_mode_gubun : '';

$io_type = $CI->input->post('IO_TYPE') ?: $CI->input->get('IO_TYPE');
$io_type = !empty($io_type) ? $io_type : '';

$n_field = $CI->input->post('N_Field') ?: $CI->input->get('N_Field');
$n_field = !empty($n_field) ? $n_field : '';

$s_text = $CI->input->post('S_TEXT') ?: $CI->input->get('S_TEXT');
$s_text = !empty($s_text) ? $s_text : '';

$g_hbl_no = $CI->input->post('G_HBL_NO') ?: $CI->input->get('G_HBL_NO');
$g_hbl_no = !empty($g_hbl_no) ? $g_hbl_no : '';

$aloc_type_num = $CI->input->post('ALOC_TYPE_NUM') ?: $CI->input->get('ALOC_TYPE_NUM');
$aloc_type_num = !empty($aloc_type_num) ? $aloc_type_num : '';

$office_cd = $CI->input->post('OFFICE_CD') ?: $CI->input->get('OFFICE_CD');
$office_cd = !empty($office_cd) ? $office_cd : '';

$aloc_type = $CI->input->post('ALOC_TYPE') ?: $CI->input->get('ALOC_TYPE');
$aloc_type = !empty($aloc_type) ? $aloc_type : '';

$hbl_no = $CI->input->post('HBL_NO') ?: $CI->input->get('HBL_NO');
$hbl_no = !empty($hbl_no) ? $hbl_no : '';

$sort_sql = $CI->input->post('SORT_SQL') ?: $CI->input->get('SORT_SQL');
$sort_sql = !empty($sort_sql) ? $sort_sql : '';

$opt_item1 = isset($opt_item1) ? $opt_item1 : '';

// SEARDATE에 따른 한글 값 설정
$seardate_value = '';
switch ($seardate) {
    case 'LOAD_REQ_DT':
        $seardate_value = '픽업요청일';
        break;
    case 'ORD_REGDATE':
    case 'ORD_DT':
        $seardate_value = '오더등록일';
        break;
}

// Query 문자열 생성 (필요시 사용)
$query = '';
$query .= 'S_CODE=' . urlencode($s_code) . '&';
$query .= 'S_CODE2=' . urlencode($s_code2) . '&';
$query .= 'SEARDATE=' . urlencode($seardate) . '&';
$query .= 'S_DATE=' . urlencode($s_date) . '&';
$query .= 'IO_TYPE=' . urlencode($io_type) . '&';
$query .= 'OFFICE_CD=' . urlencode($office_cd) . '&';
$query .= 'SO_MODE=' . urlencode($so_mode) . '&';
$query .= 'N_Field=' . urlencode($n_field) . '&';
$query .= 'SORT_SQL=' . urlencode($sort_sql) . '&';
$query .= 'SO_MODE_GUBUN=' . urlencode($so_mode_gubun) . '&';
$query .= 'ALOC_TYPE_NUM=' . urlencode($aloc_type_num) . '&';
$query .= 'S_TEXT=' . urlencode($s_text);

// ASP TOLS_L 파라미터를 PHP로 변환하여 저장 프로시저 호출
// Proc_So_Order_List_5_Json 프로시저 실행
$json_data = array();
try {
    // TOLS_L(0) = @SEARDATE VARCHAR(10)
    // TOLS_L(1) = @T_DATE VARCHAR(10) - 날짜에서 "-" 제거
    // TOLS_L(2) = @IO_TYPE VARCHAR(10)
    // TOLS_L(3) = @OFFICE_CD VARCHAR(2)
    // TOLS_L(4) = @SO_MODE VARCHAR(50)
    // TOLS_L(5) = @G_HBL_NO VARCHAR(20) - G_HBL_NO 사용
    // TOLS_L(6) = @S_CODE VARCHAR(2) - N_Field 사용
    // TOLS_L(7) = @S_TEXT VARCHAR(50)
    // TOLS_L(8) = @SORT_SQL VARCHAR(500)
    
    // T_DATE 파라미터 최종 검증 (8자리 숫자 형식)
    if (empty($t_date) || strlen($t_date) != 8 || !is_numeric($t_date)) {
        $t_date = str_replace('-', '', date('Y-m-d'));
    }
    
    $sql = "EXEC [dbo].[Proc_So_Order_List_5_Json] @SEARDATE = ?, @T_DATE = ?, @IO_TYPE = ?, @OFFICE_CD = ?, @SO_MODE = ?, @G_HBL_NO = ?, @S_CODE = ?, @S_TEXT = ?, @SORT_SQL = ?";
    
    $proc_query = $CI->db->query($sql, array(
        $seardate_for_proc,  // @SEARDATE VARCHAR(10) - 프로시저 호출용 (01 또는 02)
        $t_date,             // @T_DATE VARCHAR(10) - 날짜에서 "-" 제거된 값 (YYYYMMDD 형식)
        $io_type,            // @IO_TYPE VARCHAR(10)
        $office_cd,          // @OFFICE_CD VARCHAR(2)
        $so_mode,            // @SO_MODE VARCHAR(50)
        $g_hbl_no,           // @G_HBL_NO VARCHAR(20) - G_HBL_NO 사용
        $n_field,            // @S_CODE VARCHAR(2) - N_Field 사용
        $s_text,             // @S_TEXT VARCHAR(50)
        $sort_sql            // @SORT_SQL VARCHAR(500)
    ));
    
    if ($proc_query) {
        // 프로시저가 JSON 문자열을 반환하는 경우 처리
        $proc_result = $proc_query->result();
        
        if (!empty($proc_result)) {
            // 첫 번째 행 확인
            $first_row = $proc_result[0];
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
                    if (!is_null($json_string) && $json_string !== '' && $json_string !== false) {
                        $decoded = json_decode($json_string, true);
                        if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                            $json_data = $decoded;
                        } else {
                            log_message('error', 'JSON 디코딩 실패: ' . json_last_error_msg());
                            $json_data = array();
                        }
                    } else {
                        $json_data = array();
                    }
                } else {
                    // JsonData 컬럼이 없는 경우 전체 결과를 배열로 변환
                    $json_data = $proc_result;
                }
            }
        }
    } else {
        log_message('error', 'Proc_So_Order_List_5_Json 프로시저 실행 실패');
    }
} catch (Exception $e) {
    log_message('error', 'Proc_So_Order_List_5_Json 프로시저 실행 중 오류: ' . $e->getMessage());
    $json_data = array();
}
?>
<div class="contents_area" id="contentsArea" style="padding: 0 20px; width: 100%; height: 100%; display: flex; flex-direction: column; align-items: flex-start; justify-content: flex-start;">
	<div class="container_top_order_list" style="height: 50px; border: 0px solid #000; width: 100%; margin-bottom: 0;"> 				
		<div id="step_1" style="height: 50px; width: 100%; border: 0px solid #000;">
			<nav style="display: flex; align-items: center; justify-content: space-between; height: 100%; background-color: #cccccc;">
				<div style="width: 10px; height: 100%;"></div>
				<select name="SEARDATE" class="text-input-style">
				<option value="01" <?php echo ($seardate == '01' || $seardate == 'LOAD_REQ_DT' || $seardate_01 == 'selected') ? 'selected' : ''; ?>>픽업요청일</option>
				<option value="02" <?php echo ($seardate == '02' || $seardate == 'ORD_REGDATE' || $seardate == 'ORD_DT' || $seardate_02 == 'selected') ? 'selected' : ''; ?>>오더등록일</option>
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
				<input type="search" name="HBL_NO" class="Reg_Box" style="width:120px;" value="<?php echo htmlspecialchars($hbl_no); ?>">
				<span class="font_bold"></span>
				<?php echo com_search_type($n_field, $opt_item1); ?>
				<input type="search" name="S_TEXT" class="Reg_Box" style="width:180px;ime-mode:active;" value="<?php echo htmlspecialchars($s_text); ?>" onkeydown="if (window.event.keyCode==13) { search_form('Y','S') }">
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
				<div style="width: 300px;"></div>							
			</nav>			
		</div>			
	</div>
	<div id="step_2" style="height: 5px;"></div>
	<div id="Order_status_list" style="height: calc(100vh - 200px); max-height: 650px; flex: 1; border: 1px solid #CCC2C2; width: 100%; overflow: hidden; margin-top: 0;"></div>
</div>
<script>
			var tabledata = <?php echo json_encode($json_data); ?>;
			let isDragging = false;
			let startCell = null;
			let endCell = null;
			let selectedCells = [];
			let selectionOverlay = null;
			
			// ========== 2. 다중 정렬 관련 변수 ==========
			let sortState = []; 
			const MAX_CLICK_COUNT = 3;
			const sortableColumns = [
			    'OP_NM', 'SO_MODE_H', 'IO_TYPE', 'ACT_SHIP_A_NM', 'ACT_SHIP_TEL',
			    'ACT_SHIP_PIC_NM', 'SHIP_NM', 'LOAD_NM', 'LOAD_TEL', 'LOAD_PIC_NM',
			    'LOAD_AREA', 'LOAD_REQ_HM', 'PKG', 'CBM', 'WGT', 'GOD_M_SIZE', 
			    'ORD_ETC', 'FDS_NM', 'UNLOAD_NM', 'UNLOAD_TEL', 'UNLOAD_PIC_NM',
			    'UNLOAD_REQ_DT', 'BILL_NM', 'HBL_NO'
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
			    const savedOrderStr = localStorage.getItem('Jelogis_OrderList_Column_Header_Ch1');
			    if (savedOrderStr) {
			        savedOrder = JSON.parse(savedOrderStr);
			       // console.log('저장된 컬럼 순서:', savedOrder);
			    }
			} catch (e) {
			  //  console.error('컬럼 순서 로딩 실패:', e);
			    savedOrder = [];
			}
			
			// ========== 정렬 함수들 ==========
			function applySorting() {
			   //  console.log('========== applySorting 시작 ==========');
			   //  console.log('적용할 sortState:', JSON.parse(JSON.stringify(sortState)));
			    
			    var tableHolder = document.querySelector('#Order_status_list .tabulator-tableholder');
			    var scrollLeft = tableHolder ? tableHolder.scrollLeft : 0;
			    
			    const allData = table.getData();
			    const normalData = allData.filter(d => !d.isEmpty);
			    const emptyData = allData.filter(d => d.isEmpty);
			    
			    //console.log(`정렬 전 - 일반 데이터: ${normalData.length}개, 빈 행: ${emptyData.length}개`);
			    
			    if (sortState.length === 0) {
			        table.setData([...normalData, ...emptyData]);
			        //console.log('? 정렬 초기화 (원본 순서)');
			        
			        setTimeout(function() {
			            if (tableHolder) {
			                tableHolder.scrollLeft = scrollLeft;
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
			            
			            if (aVal === '' && bVal !== '') return 1;
			            if (aVal !== '' && bVal === '') return -1;
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
			    
			    console.log('정렬 완료 - 순서 (처음 5개):');
			    sortedData.slice(0, 5).forEach((data, idx) => {
			        //console.log(`  ${idx + 1}. 발주처: ${data.ACT_SHIP_A_NM || '(빈값)'}, 발주담당: ${data.ACT_SHIP_PIC_NM || '(빈값)'}`);
			    });
			    
			    table.setData([...sortedData, ...emptyData]);
			    
			    setTimeout(function() {
			        if (tableHolder) {
			            tableHolder.scrollLeft = scrollLeft;
			        }
			    }, 50);
			}
			
			function handleSortClick(field) {
			    //console.log(`========== 정렬 클릭: ${field} ==========`);
			    
			    let sortIndex = sortState.findIndex(s => s.field === field);
			    
			    if (sortIndex === -1) {
			        sortState.push({
			            field: field,
			            dir: 'asc',
			            clickCount: 1
			        });
			        //console.log(`? ${field} 오름차순 정렬 추가`);
			    } else {
			        let currentSort = sortState[sortIndex];
			        currentSort.clickCount++;
			        
			        if (currentSort.clickCount === 2) {
			            currentSort.dir = 'desc';
			            //console.log(`? ${field} 내림차순으로 변경`);
			        } else if (currentSort.clickCount >= MAX_CLICK_COUNT) {
			            sortState.splice(sortIndex, 1);
			            //console.log(`? ${field} 정렬 제거`);
			        }
			    }
			    
			    applySorting();
			    updateSortUI();
			    saveSortState();
			}
			
			function updateSortUI() {
			    sortableColumns.forEach(field => {
			        let sortButton = document.querySelector(`[data-sort-field="${field}"]`);
			        if (sortButton) {
			            sortButton.innerHTML = '';
			            
			            let sortIndex = sortState.findIndex(s => s.field === field);
			            
			            if (sortIndex === -1) {
			                sortButton.textContent = '▼';
			                sortButton.style.color = '#666';
			                sortButton.style.fontWeight = 'normal';
			            } else {
			                let sort = sortState[sortIndex];
			                let arrow = sort.dir === 'asc' ? '▲' : '▼';
			                sortButton.textContent = `${arrow}${sortIndex + 1}`;
			                sortButton.style.color = '#0066cc';
			                sortButton.style.fontWeight = 'bold';
			            }
			        }
			    });
			}
			
			function clearAllSorts() {
			    sortState = [];
			    localStorage.removeItem('Jelogis_Allocation_SortState_ch_1');
			    
			    const allData = table.getData();
			    const normalData = allData.filter(d => !d.isEmpty);
			    const emptyData = allData.filter(d => d.isEmpty);
			    
			    table.setData([...normalData, ...emptyData]);
			    updateSortUI();
			}
			
			function initializeSortState() {
			    sortState = [];
			    
			    const savedSortState = localStorage.getItem('Jelogis_Allocation_SortState_ch_1');
			    if (savedSortState) {
			        try {
			            sortState = JSON.parse(savedSortState);
			           // console.log('정렬 상태 복원:', sortState);
			        } catch (e) {
			            sortState = [];
			        }
			    }
			    
			    if (sortState.length > 0) {
			        applySorting();
			    }
			    
			    updateSortUI();
			}
			
			function saveSortState() {
			    localStorage.setItem('Jelogis_Allocation_SortState_ch_1', JSON.stringify(sortState));
			}
			
			// ========== 컬럼 데이터 설정 (헤더 구조 개선) ==========
			const columnData = [
			     {
			        title:"담당자", 
			        field:"OP_NM", 
			        width:45, 
			        hozAlign:"center", 
			        headerSort:false,
			        headerFilter:false,
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
			        visible: true
			    },
			    {title:"", field:"ORDER_VIEW",formatter: OrderViewFormatter, width:25,headerSort: false,minWidth: 25,maxWidth: 25},
			    {title:"", field:"CAR_OPERATE",formatter: CarOperateViewFormatter,  width:25,headerSort: false,minWidth: 25,maxWidth: 25},
			    {
			        title:"PT", 
			        field:"SO_PT",
			        width:35, 
			        hozAlign:"center", 
			        headerSort: false,
			        headerFilter:false,
			        titleFormatter: function(cell) {
			            return `<div class='custom-header-wrapper'>
			                        <div class='header-title'>PT<br><span class='custom-sort-button' data-sort-field='SO_PT'>▼</span></div>
			                    </div>`;
			        },
			        visible: true,
			        minWidth: 35,
			        maxWidth: 35
			    },
			    {
			        title:"상품", 
			        field:"SO_MODE_H", 
			        width:40,
			        hozAlign:"center", 
			        headerSort:false,
			        headerFilter:false,
			        titleFormatter: function(cell) {
			            return `<div class='custom-header-wrapper'>
			                        <div class='header-title'>상품<br><span class='custom-sort-button' data-sort-field='SO_MODE_H'>▼</span></div>
			                    </div>`;
			        },
			        visible: true
			    },
			    {
			        title:"구분", 
			        field:"IO_TYPE", 
			        width:30,
			        hozAlign:"center", 
			        headerSort:false,
			        headerFilter:false,
			        titleFormatter: function(cell) {
			            return `<div class='custom-header-wrapper'>
			                        <div class='header-title'>구분<br><span class='custom-sort-button' data-sort-field='IO_TYPE'>▼</span></div>
			                    </div>`;
			        },
			        visible: true
			    },
			    {
			        title:"발주처",
			        field:"ACT_SHIP_A_NM",
			        width:100,
			        tooltip:true,
			        hozAlign:"left", 
			        headerSort: false,
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
			        visible: true
			    },
			    {
			        title:"발주처전화",
			        field:"ACT_SHIP_TEL", 
			        width:90, 
			        tooltip:true,
			        hozAlign:"left", 
			        headerSort: false,
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
			        visible: true
			    },
			    {
			        title:"발주담당",
			        field:"ACT_SHIP_PIC_NM", 
			        width:70,
			        tooltip:true,
			        hozAlign:"left", 
			        headerSort: false,
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
			        visible: true
			    },
			    {
			        title:"청구처",
			        field:"BILL_NM", 
			        width:100,
			        tooltip:true,
			        hozAlign:"left",
			        headerSort: false,
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
			        visible: true
			    },
			    {
			        title:"화주",
			        field:"SHIP_NM", 
			        width:100,
			        tooltip:true,
			        hozAlign:"left", 
			        headerSort: false,
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
			        visible: true
			    },
			    {
			        title:"픽업지",
			        field:"LOAD_NM", 
			        width:100,
			        tooltip:true,
			        hozAlign:"left", 
			        headerSort: false,
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
			        visible: true
			    },
			    {
			        title:"픽업지전화",
			        field:"LOAD_TEL", 
			        width:90,
			        tooltip:true,
			        hozAlign:"left", 
			        headerSort: false,
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
			        visible: true
			    },
			    {
			        title:"픽업담당",
			        field:"LOAD_PIC_NM", 
			        width:70,
			        tooltip:true,
			        hozAlign:"left", 
			        headerSort: false,
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
			        visible: true
			    },
			    {
			        title:"픽업지역",
			        field:"LOAD_AREA", 
			        width:80,
			        tooltip:true,
			        hozAlign:"center", 
			        headerSort: false,
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
			        visible: true
			    },
			    {
			        title:"픽업요청",
			        field:"LOAD_REQ_HM", 
			        width:45,
			        tooltip:true,
			        hozAlign:"center", 
			        headerSort: false,
			        headerFilter:false,
			        titleFormatter: function(cell) {
			            return `<div class='custom-header-wrapper'>
			                        <div class='header-title'>픽업<br>요청<br><span class='custom-sort-button' data-sort-field='LOAD_REQ_HM'>▼</span></div>
			                    </div>`;
			        },
			        visible: true
			    },
			    {
			        title:"총수량",
			        field:"PKG", 
			        width:50, 
			        hozAlign:"center", 
			        headerSort: false,
			        headerFilter:false,
			        titleFormatter: function(cell) {
			            return `<div class='custom-header-wrapper'>
			                        <div class='header-title'>총수량<br><span class='custom-sort-button' data-sort-field='PKG'>▼</span></div>
			                        
			                    </div>`;
			        },
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
			        field:"CBM", 
			        width:50, 
			        hozAlign:"center", 
			        headerSort: false,
			        headerFilter:false,
			        titleFormatter: function(cell) {
			            return `<div class='custom-header-wrapper'>
			                        <div class='header-title'>총부피<br><span class='custom-sort-button' data-sort-field='CBM'>▼</span></div>
			                    </div>`;
			        },
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
			        field:"WGT", 
			        width:50, 
			        hozAlign:"center", 
			        headerSort: false,
			        headerFilter:false,
			        titleFormatter: function(cell) {
			            return `<div class='custom-header-wrapper'>
			                        <div class='header-title'>총무게<br><span class='custom-sort-button' data-sort-field='WGT'>▼</span></div>
			                    </div>`;
			        },
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
			        title:"사이즈",
			        field:"GOD_M_SIZE", 
			        width:120,
			        tooltip:true,
			        hozAlign:"left", 
			        formatter: God_M_Size_Formatter,
			        headerSort: false,
			        headerFilter:false,
			        titleFormatter: function(cell) {
			            return `<div class='custom-header-wrapper'>
			                        <div class='header-title'>사이즈<br><span class='custom-sort-button' data-sort-field='GOD_M_SIZE'>▼</span></div>			                        
			                    </div>`;
			        },
			        visible: true
			    },
			    {
			        title:"목적국",
			        field:"FDS_NM", 
			        width:70,
			        tooltip:true,
			        hozAlign:"left", 
			        headerSort: false,
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
			        visible: true
			    },
			    {
			        title:"하차지",
			        field:"UNLOAD_NM", 
			        width:100,
			        tooltip:true,
			        hozAlign:"left", 
			        headerSort: false,
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
			        visible: true
			    },
			    {
			        title:"하차지전화",
			        field:"UNLOAD_TEL", 
			        width:90,
			        tooltip:true,
			        hozAlign:"left", 
			        headerSort: false,
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
			        visible: true
			    },
			    {
			        title:"하차담당",
			        field:"UNLOAD_PIC_NM", 
			        width:70,
			        tooltip:true,
			        hozAlign:"left", 
			        headerSort: false,
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
			        visible: true
			    },
			    {
			        title:"하차요청일",
			        field:"UNLOAD_REQ_DT", 
			        width:70,
			        tooltip:true,
			        hozAlign:"center", 
			        sorter:"number",
			        headerSort: false,
			        headerFilter:false,
			        titleFormatter: function(cell) {
			            return `<div class='custom-header-wrapper'>
			                        <div class='header-title'>하차요청일<br><span class='custom-sort-button' data-sort-field='UNLOAD_REQ_DT'>▼</span></div>			                        
			                    </div>`;
			        },
			        visible: true
			    },
			    {
			        title:"BL_NO",
			        field:"HBL_NO", 
			        width:90,
			        tooltip:true,
			        hozAlign:"left", 
			        headerSort: false,
			        headerFilter: "input",
			        headerFilterPlaceholder: "검색",
					headerFilterParams: {
						elementAttributes: {
					   	style: "text-align: center;" // 입력 텍스트와 Placeholder를 중앙 정렬
						}
					},						
			        formatter: Hbl_No_Formatter,
			        titleFormatter: function(cell) {
			            return `<div class='custom-header-wrapper'>
			                        <div class='header-title'>BL_NO</div>
			                        <span class='custom-sort-button' data-sort-field='HBL_NO'>▼</span>
			                    </div>`;
			        },
			        visible: true
			    },
			    {
			        title:"도착보고",
			        field:"ARRIVAL",
			        hozAlign:"center",
			        width:35,
			        headerSort: false,
			        headerFilter:false,
			        titleFormatter: function(cell) {
			            return `<div class='custom-header-wrapper'>
			                        <div class='header-title'>도착<br>보고<br><span class='custom-sort-button' data-sort-field='ARRIVAL'>▼</span></div>
			                    </div>`;
			        },
			        visible: true,
					  minWidth: 35,
			        maxWidth: 35					  
			    },
			    {title:"서류", field:"DOC", width:35,hozAlign:"center",headerSort: false,minWidth: 35,maxWidth: 35,visible: true},
			    {
			        title:"비고",
			        field:"ORD_ETC",
			        hozAlign:"left",
			        width:120,
			        tooltip:true,
			        headerSort: false,
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
			        visible: true
			    },
			    {
			        title:"요율",
			        field:"TARIFF_GUBUN",
			        hozAlign:"center",
			        width:40,
			        headerSort: false,
			        visible: true,
			        minWidth: 40,
			        maxWidth: 40
			    },
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
			    {title: "",field: "SO_NO",visible: false},
			    {title: "",field: "LOAD_REQ_DT",visible: false},
			    {title: "",field: "EM_UNLOAD_REQ",visible: false},
			    {title: "",field: "CLAIM",visible: false},
			];
			
			// Formatter 함수들 (그대로 유지)
			function OrderViewFormatter(cell) {
			    const SO_NO = cell.getRow().getData().SO_NO;
			    const SO_MODE_H = cell.getRow().getData().SO_MODE_H;
			    return `<div class='OrderView' style='position: relative;top: -2px;'><a href="javascript:ord_view('${SO_NO}','${SO_MODE_H}','car');"><img src='https://www.wonderlogis.com/ozoms_v14/web_order/images/img_open.gif' onclick=''  border='0' width='25' height='25'></a></div>`;
			}
			
			function CarOperateViewFormatter(cell) {
			    const SO_NO = cell.getRow().getData().SO_NO;
			    const EM_UNLOAD_REQ = cell.getRow().getData().EM_UNLOAD_REQ;
			    let ICON_CAR;
			    if (EM_UNLOAD_REQ =='Y'){
			        ICON_CAR='car_icon4.gif';
			    }else{
			        ICON_CAR='car_icon3.gif';
			    }
			    return `<div class='OrderView' style='position: relative;top: -2px;'><a href="javascript:car_hor_reg('${SO_NO}');"><img src='https://www.wonderlogis.com/ozoms_v14/admin/images/${ICON_CAR}'  border='0' width='22' height='22'></a></div>`;
			}
			
			function God_M_Size_Formatter(cell) {
			    const GOD_M_SIZE = cell.getRow().getData().GOD_M_SIZE;
			    const ALOC_TYPE = cell.getRow().getData().ALOC_TYPE;
			    let GOD_M_SIZE_BG
			    if(ALOC_TYPE =='일산집하'){
			        GOD_M_SIZE_BG='#F5A9A9';
			    }else if(ALOC_TYPE =='안성집하'){
			        GOD_M_SIZE_BG='#81F781';
			    }else{
			        GOD_M_SIZE_BG='';
			    }
			    return `<div style='position: relative;top: -3px;left: -3px;width:100%;height:23px;z-index:99;background-color:${GOD_M_SIZE_BG}'>${GOD_M_SIZE}</div>`;
			}
			
			function Hbl_No_Formatter(cell) {
			    const SO_NO = cell.getRow().getData().SO_NO;
			    const HBL_NO = cell.getRow().getData().HBL_NO;
			    const LOAD_REQ_DT = cell.getRow().getData().LOAD_REQ_DT;
			    return `<div class='OrderView_Start' style='position: relative;top: -2px;'><a href="javascript:UNIPASS('${SO_NO}','H','${HBL_NO}','${LOAD_REQ_DT}');">${HBL_NO}</a></div>`;
			}
			
			// Tabulator 테이블 생성
			var table = new Tabulator("#Order_status_list", {
			    height: "750px",
			    rowHeight: 26,
			    layout: "fitColumns",
			    columns: savedOrder.length > 0 ? savedOrder.map(name => columnData.find(col => col.field === name)).filter(Boolean) : columnData,
			    data: tabledata,
			    selectable: true,
			    headerFilter: true,
			    movableColumns: true,
			    resizableColumns: true,
			    rowSelectionChanged: function (data) {
			        console.log("Selected Rows: ", data);
			    },
			    rowFormatter: function(row) {
			        var datas = row.getData();
			        
			        if (datas.isEmpty) {
			            var rowElement = row.getElement();
			            var cells = rowElement.querySelectorAll('.tabulator-cell');
			            
			            if (cells.length > 0) {
			                cells[0].style.width = '100%';
			                cells[0].style.borderRight = 'none';
			                cells[0].style.textAlign = 'center';
			                cells[0].style.color = '#ccc';
			                cells[0].innerHTML = '';
			                
			                for (let j = 1; j < cells.length; j++) {
			                    cells[j].style.display = 'none';
			                }
			            }
			            return;
			        }
			
			        if (datas['SO_OFF'] === "Y") {
			            row.getElement().style.color = "#7401DF";
			        }else{
			            if (datas['SO_STAT'] === "00") {
			                if (datas['SO_PT'] === "W" || datas['SO_PT'] === "E") {
			                    row.getElement().style.color = "#FE2E2E";
			                }else{
			                    row.getElement().style.color = "#2E2E2E";
			                }
			            }
			        }
			    },
			});
			
			table.on("columnMoved", function(column) {
			    saveAllocation_Column_Header_Ch1();
			});
			
			table.on("rowClick", function(e, row){
			    if (row.getElement().style.background == 'rgb(206, 216, 246)'){
			        row.getElement().style.background = "#ffffff";
			    }else{
			        row.getElement().style.background = "#CED8F6";
			    }
			});
			
			table.on("rowDblClick", function(e, row){
			    let row_datas = row.getData();
			    ord_view(row_datas.SO_NO,row_datas.SO_MODE_H,'order');
			});
			
			table.on("columnResized", function () {
			    let widths = table.getColumns().map(col => col.getWidth());
			    localStorage.setItem("Jelogis_OrderList_ColumnWidths_Ch1", JSON.stringify(widths));
			});
			
			function loadColumnWidths() {
			    let savedWidths = localStorage.getItem("Jelogis_OrderList_ColumnWidths_Ch1");
			    if (savedWidths) {
			        let widths = JSON.parse(savedWidths);
			        table.getColumns().forEach((col, index) => {
			            if (widths[index] !== undefined) {
			                col.setWidth(widths[index]);
			            }
			        });
			    }
			}
			
			function saveAllocation_Column_Header_Ch1() {
			    const columns = table.getColumns();
			    const order = columns.map(col => col.getField());
			    localStorage.setItem('Jelogis_OrderList_Column_Header_Ch1', JSON.stringify(order));
			}
			
			// ========== 테이블 빌드 완료 후 처리 ==========
			table.on("tableBuilt", function() {
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
			     addDragSelectionEvents(); // 드래그 선택 이벤트
			    // 정렬 버튼 이벤트 리스너 추가
			    setTimeout(() => {
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
			        
			        initializeSortState();
			    }, 100);
			});
			
			// 검색 기능 (생략 - 이전과 동일)
			// ... (검색 관련 함수들 그대로 유지)
			
			// 컬럼 가시성 관리 (생략 - 이전과 동일)
			function toggleColumn(field, checkbox) {
			    const column = table.getColumn(field);
			    if (column.isVisible()) {
			        column.hide();
			        checkbox.checked = false;
			    } else {
			        column.show();
			        checkbox.checked = true;
			    }
			    saveColumnVisibility();
			}
			
			function saveColumnVisibility() {
			    const visibility = {};
			    columnData.forEach(column => {
			        const checkbox = document.getElementById(column.field);
			        if (checkbox) {
			            visibility[column.field] = checkbox.checked;
			        }
			    });
			    localStorage.setItem('Jelogis_Order_Status_List_Column_Ch1', JSON.stringify(visibility));
			}
			
			function restoreColumnVisibility() {
			    const visibility = JSON.parse(localStorage.getItem('Jelogis_Order_Status_List_Column_Ch1'));
			    if (visibility) {
			        for (const [field, isVisible] of Object.entries(visibility)) {
			            const checkbox = document.getElementById(`${field}`);
			            if (checkbox) {
			                checkbox.checked = isVisible;
			                if (!isVisible) {
			                    table.getColumn(field).hide();
			                }
			            }
			        }
			    }
			}
			
			window.onload = function() {
			    restoreColumnVisibility();
			    loadColumnWidths();
				clearAllSorts();
			    setTimeout(() => {
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
			
			        table.addRow(emptyRowData, 'bottom');
			    }, 300);
			};
</script>
<?php $this->load->view('common/product_select_layer'); ?>
<?php $this->load->view('common/grid_title_select'); ?>
<?php $this->load->view('common/bottom'); ?>
