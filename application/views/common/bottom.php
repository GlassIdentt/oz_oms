        </div>

        <div class="bottom_area">
            <!-- 하단 영역 -->
            <?php
            // 폴더명 확인 (header.php에서 설정된 변수 사용 또는 직접 확인)
            $CI =& get_instance();
            $current_folder_name = isset($folder_name) ? $folder_name : '';
            
            // folder_name이 없으면 URL에서 추출 시도
            if (empty($current_folder_name)) {
                $controller_name = $CI->router->class;
                $current_folder_name = str_replace('_', '_', ucwords(str_replace('_', ' ', $controller_name)));
            }
            
            // Orders, Allocation_Car, Inquiry 폴더일 때만 bottom_stats_container 출력
            $allowed_folders = array('Orders', 'Allocation_Car', 'Inquiry');
            $counter_items = array();
            if (in_array($current_folder_name, $allowed_folders)):
                // 프로시저 호출하여 통계 항목 목록 가져오기
                $CI->load->model('Common_model');
                $counter_items = $CI->Common_model->get_code_list_by_category('T42', 'COUNTER');
            ?>
            <div class="bottom_stats_container">
                <?php 
                if (!empty($counter_items)) {
                    foreach ($counter_items as $index => $item) {
                        $cd_nm = isset($item->CD_NM) ? $item->CD_NM : '';
                        $opt_item2 = isset($item->OPT_ITEM2) ? $item->OPT_ITEM2 : '';
                        
                        // 첫 번째 항목은 stats_value만, 나머지는 stats_value stats_value_small
                        $value_class = ($index == 0) ? 'stats_value' : 'stats_value stats_value_small';
                ?>
                <div class="stats_item">
                    <span class="stats_label"><?php echo htmlspecialchars($cd_nm); ?>:</span>
                    <div id="<?php echo htmlspecialchars($opt_item2); ?>" class="<?php echo $value_class; ?>"></div>
                </div>
                <?php 
                    }
                }
                ?>
            </div>
            <?php endif; ?>
            
            <script>
            // 통계 항목 매핑 정보를 JavaScript 변수로 전달 (조건 밖에서 정의)
            var counterItemsMapping = <?php echo json_encode(array_map(function($item) {
                return array(
                    'id' => isset($item->OPT_ITEM2) ? $item->OPT_ITEM2 : '',
                    'field' => isset($item->OPT_ITEM2) ? $item->OPT_ITEM2 : '',
                    'label' => isset($item->CD_NM) ? $item->CD_NM : ''
                );
            }, $counter_items ?: array()), JSON_UNESCAPED_UNICODE); ?>;
            console.log('counterItemsMapping:', counterItemsMapping);
            </script>
        </div>
    </div>
    
    <?php
    // Orders, Allocation_Car, Inquiry 폴더일 때만 Order_Counter.js 인클루드
    $allowed_folders_js = array('Orders', 'Allocation_Car', 'Inquiry');
    if (isset($current_folder_name) && in_array($current_folder_name, $allowed_folders_js)):
    ?>
    <script src="<?php echo base_url('js/Order_Counter.js'); ?>"></script>
    <?php endif; ?>

<script>
// 하단 탭 버튼 클릭 이벤트 처리
document.addEventListener('DOMContentLoaded', function() {
	const buttons = document.querySelectorAll('#buttonContainer .btn');

	buttons.forEach(button => {
		button.addEventListener('click', function(event) {
			// event.preventDefault() 제거 - 링크가 정상적으로 작동하도록 함

			// 모든 버튼을 비활성 상태로 변경
			buttons.forEach(btn => {
				btn.classList.remove('state-active');
				btn.classList.add('state-inactive');
			});

			// 클릭된 버튼을 활성 상태로 변경
			this.classList.remove('state-inactive');
			this.classList.add('state-active');

			// a 태그인 경우 href로 이동
			if (this.tagName === 'A' && this.href) {
				window.location.href = this.href;
			}
		});
	});
	
	// S_DATE 필드가 있는 경우 Datepicker 초기화
	if (document.getElementById('S_DATE')) {
		// jQuery가 로드될 때까지 대기
		function initDatePicker() {
			if (typeof jQuery !== 'undefined' && typeof jQuery.ui !== 'undefined' && typeof jQuery.ui.datepicker !== 'undefined') {
				jQuery(function($) {
					var $dateInput = $('#S_DATE');
					
					if ($dateInput.length > 0) {
						// 한국어 월 이름 설정
						$.datepicker.setDefaults($.datepicker.regional['ko']);
						
						$dateInput.datepicker({
							dateFormat: 'yy-mm-dd', // YYYY-MM-DD 형식
							numberOfMonths: 3, // 3개의 달력을 동시에 표시
							showButtonPanel: true,
							changeMonth: true,
							changeYear: true,
							monthNames: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
							monthNamesShort: ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'],
							dayNames: ['일요일', '월요일', '화요일', '수요일', '목요일', '금요일', '토요일'],
							dayNamesShort: ['일', '월', '화', '수', '목', '금', '토'],
							dayNamesMin: ['일', '월', '화', '수', '목', '금', '토'],
							beforeShow: function(input, inst) {
								// 달력 표시 전 readonly 해제
								$(this).removeAttr('readonly');
							},
							onShow: function(inst) {
								// 달력 표시 후 헤더 형식 업데이트
								setTimeout(function() {
									updateDatepickerHeaders();
								}, 50);
							},
							onSelect: function(dateText, inst) {
								// 날짜 선택 시 readonly 다시 설정
								$(this).attr('readonly', 'readonly');
							},
							onClose: function(dateText, inst) {
								// 달력 닫힌 후 readonly 다시 설정
								$(this).attr('readonly', 'readonly');
							},
							onChangeMonthYear: function(year, month, inst) {
								// 월/년도 변경 시 헤더 형식 업데이트
								var self = this;
								setTimeout(function() {
									updateDatepickerHeaders();
									// select 변경 이벤트도 추가
									$('.ui-datepicker-month, .ui-datepicker-year').off('change.datepicker-header').on('change.datepicker-header', function() {
										setTimeout(function() {
											updateDatepickerHeaders();
										}, 10);
									});
								}, 50);
							}
						});
						
						// 헤더 형식을 "2025년 11월" 형식으로 변경하는 함수
						function updateDatepickerHeaders() {
							$('.ui-datepicker-header').each(function() {
								var $header = $(this);
								var $title = $header.find('.ui-datepicker-title');
								if ($title.length > 0) {
									var $month = $title.find('.ui-datepicker-month');
									var $year = $title.find('.ui-datepicker-year');
									
									// select 요소인 경우 (changeMonth/changeYear가 true일 때)
									if ($month.is('select') && $year.is('select')) {
										var monthVal = $month.val();
										var yearVal = $year.val();
										if (monthVal !== undefined && monthVal !== null && yearVal) {
											var monthNum = parseInt(monthVal) + 1; // 0-based index
											// select 요소는 유지하되, 헤더 텍스트 추가
											if ($title.find('.custom-header-text').length === 0) {
												$title.prepend('<span class="custom-header-text" style="display:inline-block; margin-right:5px; color:#000000;">' + yearVal + '년 ' + monthNum + '월</span>');
											} else {
												$title.find('.custom-header-text').text(yearVal + '년 ' + monthNum + '월').css('color', '#000000');
											}
											// select 요소는 작게 표시
											$month.css({'font-size': '10px', 'width': 'auto', 'display': 'none'});
											$year.css({'font-size': '10px', 'width': 'auto', 'display': 'none'});
										}
									} 
									// span 요소인 경우
									else if ($month.length > 0 && $year.length > 0 && !$month.is('select') && !$year.is('select')) {
										var monthText = $month.text().trim();
										var yearText = $year.text().trim();
										
										// 월 이름에서 숫자 추출
										var monthNum = monthText.replace('월', '').replace(/[^0-9]/g, '').trim();
										
										// 숫자가 없으면 월 이름 배열에서 찾기
										if (!monthNum || monthNum === '') {
											var monthNames = ['1월', '2월', '3월', '4월', '5월', '6월', '7월', '8월', '9월', '10월', '11월', '12월'];
											var monthIndex = $.inArray(monthText, monthNames);
											if (monthIndex >= 0) {
												monthNum = monthIndex + 1;
											}
										}
										
										if (monthNum && yearText) {
											$month.hide();
											$year.hide();
											$title.html('<span style="display:inline-block; color:#000000;">' + yearText + '년 ' + monthNum + '월</span>');
										}
									}
								}
							});
						}
						
						// 클릭 시 헤더 형식 업데이트
						$dateInput.on('click', function() {
							setTimeout(function() {
								updateDatepickerHeaders();
								// select 변경 이벤트도 추가
								$('.ui-datepicker-month, .ui-datepicker-year').off('change.datepicker-header').on('change.datepicker-header', function() {
									setTimeout(function() {
										updateDatepickerHeaders();
									}, 10);
								});
							}, 200);
						});
						
						// 직접 입력 방지
						$dateInput.on('keydown', function(e) {
							e.preventDefault();
							return false;
						});
						
						// 클릭 시 달력 표시
						$dateInput.on('click', function() {
							$(this).datepicker('show');
						});
					}
				});
			} else {
				// jQuery가 아직 로드되지 않았으면 100ms 후 다시 시도
				setTimeout(initDatePicker, 100);
			}
		}
		
		initDatePicker();
	}
	
	// .text-input-style 클래스를 가진 input/select 요소의 width 자동 조정
	function autoResizeInput(element) {
		// SO_MODE input은 고정 크기 유지 (자동 조정 제외)
		if (element.id === 'SO_MODE') {
			return;
		}
		
		var minWidth = 50; // 최소 너비 50px
		var textToMeasure = '';
		
		// select 요소인 경우 선택된 옵션의 텍스트 사용
		if (element.tagName === 'SELECT') {
			var selectedOption = element.options[element.selectedIndex];
			if (selectedOption) {
				textToMeasure = selectedOption.text;
			} else {
				textToMeasure = '';
			}
		} 
		// input 요소인 경우 value 사용
		else {
			textToMeasure = element.value || '';
		}
		
		// 텍스트가 없으면 최소 너비만 적용
		if (!textToMeasure || textToMeasure.trim() === '') {
			element.style.width = minWidth + 'px';
			return;
		}
		
		// 임시 span 요소를 생성하여 텍스트 너비만 측정 (padding, border 제외)
		var tempSpan = document.createElement('span');
		tempSpan.style.visibility = 'hidden';
		tempSpan.style.position = 'absolute';
		tempSpan.style.whiteSpace = 'pre';
		tempSpan.style.fontSize = window.getComputedStyle(element).fontSize;
		tempSpan.style.fontFamily = window.getComputedStyle(element).fontFamily;
		tempSpan.style.fontWeight = window.getComputedStyle(element).fontWeight;
		tempSpan.style.padding = '0';
		tempSpan.style.border = '0';
		tempSpan.style.margin = '0';
		
		document.body.appendChild(tempSpan);
		
		// 텍스트 너비 측정
		tempSpan.textContent = textToMeasure;
		var textWidth = tempSpan.offsetWidth;
		
		// padding과 border 너비 추가 (box-sizing: border-box이므로)
		var computedStyle = window.getComputedStyle(element);
		var paddingLeft = parseFloat(computedStyle.paddingLeft) || 0;
		var paddingRight = parseFloat(computedStyle.paddingRight) || 0;
		var borderLeft = parseFloat(computedStyle.borderLeftWidth) || 0;
		var borderRight = parseFloat(computedStyle.borderRightWidth) || 0;
		
		// select 요소의 경우 화살표 공간 추가
		var arrowSpace = (element.tagName === 'SELECT') ? 25 : 0;
		
		var totalWidth = textWidth + paddingLeft + paddingRight + borderLeft + borderRight + arrowSpace + 4; // 여유 공간 4px
		var calculatedWidth = Math.max(minWidth, totalWidth);
		
		element.style.width = calculatedWidth + 'px';
		
		document.body.removeChild(tempSpan);
	}
	
	// .custom-select 요소의 width를 모든 option 중 가장 긴 텍스트 기준으로 자동 조정
	function autoResizeCustomSelect(element) {
		if (element.tagName !== 'SELECT') {
			return;
		}
		
		var minWidth = 50; // 최소 너비 50px
		var maxTextWidth = 0;
		var longestText = '';
		
		// 모든 option을 순회하며 가장 긴 텍스트 찾기
		for (var i = 0; i < element.options.length; i++) {
			var optionText = element.options[i].text;
			if (optionText && optionText.trim() !== '') {
				// 임시 span 요소를 생성하여 텍스트 너비 측정
				var tempSpan = document.createElement('span');
				tempSpan.style.visibility = 'hidden';
				tempSpan.style.position = 'absolute';
				tempSpan.style.whiteSpace = 'pre';
				tempSpan.style.fontSize = window.getComputedStyle(element).fontSize;
				tempSpan.style.fontFamily = window.getComputedStyle(element).fontFamily;
				tempSpan.style.fontWeight = window.getComputedStyle(element).fontWeight;
				tempSpan.style.padding = '0';
				tempSpan.style.border = '0';
				tempSpan.style.margin = '0';
				
				document.body.appendChild(tempSpan);
				tempSpan.textContent = optionText;
				var textWidth = tempSpan.offsetWidth;
				document.body.removeChild(tempSpan);
				
				if (textWidth > maxTextWidth) {
					maxTextWidth = textWidth;
					longestText = optionText;
				}
			}
		}
		
		// 가장 긴 텍스트가 없으면 최소 너비만 적용
		if (maxTextWidth === 0) {
			element.style.width = minWidth + 'px';
			return;
		}
		
		// padding과 border 너비 추가
		var computedStyle = window.getComputedStyle(element);
		var paddingLeft = parseFloat(computedStyle.paddingLeft) || 0;
		var paddingRight = parseFloat(computedStyle.paddingRight) || 0;
		var borderLeft = parseFloat(computedStyle.borderLeftWidth) || 0;
		var borderRight = parseFloat(computedStyle.borderRightWidth) || 0;
		
		// select 요소의 화살표 공간 추가
		var arrowSpace = 25;
		
		var totalWidth = maxTextWidth + paddingLeft + paddingRight + borderLeft + borderRight + arrowSpace + 4; // 여유 공간 4px
		var calculatedWidth = Math.max(minWidth, totalWidth);
		
		element.style.width = calculatedWidth + 'px';
	}
	
	// 모든 .text-input-style 요소에 이벤트 리스너 추가
	var textInputs = document.querySelectorAll('.text-input-style');
	textInputs.forEach(function(element) {
		// 초기 width 설정
		autoResizeInput(element);
		
		// input 요소인 경우
		if (element.tagName === 'INPUT') {
			// 입력 시 width 조정
			element.addEventListener('input', function() {
				autoResizeInput(this);
			});
			
			// 포커스 시 width 조정
			element.addEventListener('focus', function() {
				autoResizeInput(this);
			});
		}
		// select 요소인 경우
		else if (element.tagName === 'SELECT') {
			// 선택 변경 시 width 조정
			element.addEventListener('change', function() {
				autoResizeInput(this);
			});
		}
	});
	
	// 모든 .custom-select 요소에 width 자동 조정 적용
	var customSelects = document.querySelectorAll('.custom-select');
	customSelects.forEach(function(element) {
		// 초기 width 설정 (모든 option 중 가장 긴 텍스트 기준)
		autoResizeCustomSelect(element);
		
		// 동적으로 option이 추가될 수 있으므로 MutationObserver 사용 (선택사항)
		// 또는 단순히 초기 로드 시에만 적용
	});
});


// ========== CSS 스타일 추가 ==========
if (!document.getElementById('custom-header-style')) {
    let style = document.createElement('style');
    style.id = 'custom-header-style';
    style.textContent = `
        /* 헤더 래퍼 */
        .custom-header-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            height: 100%;
            padding: 0 2px;
            gap: 4px;
        }
        
        /* 헤더 타이틀 */
        .header-title {
            text-align: center;
            line-height: 1.2;
            font-size: 12px;
        }
        
        /* 정렬 버튼 */
        .custom-sort-button {
            cursor: pointer;
            display: inline-block;
            padding: 2px 4px;
            margin-left: 0;
            user-select: none;
            font-size: 10px;
            color: #666;
            flex-shrink: 0;
        }
        
        .custom-sort-button:hover {
            background-color: rgba(0, 0, 0, 0.1);
            border-radius: 2px;
            color: #000;
        }
        
        /* 헤더 필터 입력란 기본 스타일 */
        .tabulator-col .tabulator-header-filter input {
            width: 100% !important;
            box-sizing: border-box !important;
            padding: 2px 4px !important;
            font-size: 11px !important;
            height: 25px !important;
            margin-top: 2px !important;
            border: 1px solid #837777 !important;
            transition: border-color 0.2s ease;
        }
        
        /* 필터가 활성화된 input 강조 - 오렌지색 */
        .tabulator-col .tabulator-header-filter input.filter-active {
            border: 2px solid #FF9800 !important;
            background-color: #FFF3E0 !important;
        }
        
        /* 헤더 높이 증가 */
        .tabulator .tabulator-header .tabulator-col {
            min-height: 40px !important;
        }
        
        .tabulator .tabulator-header .tabulator-col .tabulator-col-content {
            padding: 4px !important;
        }
    `;
    document.head.appendChild(style);
}
</script>
</body>
</html>
