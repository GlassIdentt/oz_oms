
// 카운트 함수
function updateOrderCounter() {
    // table 객체가 존재하는지 확인
    if (typeof table === 'undefined' || !table) {
        console.error('table 객체가 정의되지 않았습니다.');
        return;
    }
    
    const table_data = table.getData(); // 현재 Tabulator 데이터 가져오기
	
	//console.log('table_data=',table_data);
    let T_AIR = table_data.filter(row => row.SO_MODE_H == 'AIR').length; // "AIR" 카운트
    let T_LCL = table_data.filter(row => row.SO_MODE_H == 'LCL').length; // "LCL" 카운트
    let T_FCL = table_data.filter(row => row.SO_MODE_H == 'FCL').length; // "FCL" 카운트
	let T_QUK = table_data.filter(row => row.SO_MODE_H == 'QUK').length; // "QUK" 카운트
    let T_GNL = table_data.filter(row => row.SO_MODE_H == 'GNL').length; // "GNL" 카운트
    let T_GNA = table_data.filter(row => row.SO_MODE_H == 'GNA').length; // "GNA" 카운트
	let T_CAS = table_data.filter(row => row.SO_MODE_H == 'CAS').length; // "CAS" 카운트	
    let T_CNT = table_data.filter(row => row.SO_MODE_H).length; // 토탈 카운트

	// 동적으로 통계 값 계산
	let T_S_AMT = table_data.reduce((sum, row) => {
	    return sum + (row.T_S_AMT ? Number(row.T_S_AMT) : 0);
	}, 0);
	
	let T_B_AMT = table_data.reduce((sum, row) => {
	    return sum + (row.T_B_AMT ? Number(row.T_B_AMT) : 0);
	}, 0);
	let T_P_AMT = Number(T_S_AMT) - Number(T_B_AMT);	

	// 동적으로 생성된 통계 항목에 값 설정
	console.log('counterItemsMapping 확인:', typeof counterItemsMapping, counterItemsMapping);
	
	if (typeof counterItemsMapping !== 'undefined' && Array.isArray(counterItemsMapping) && counterItemsMapping.length > 0) {
	    console.log('동적 매핑 방식 사용, 항목 수:', counterItemsMapping.length);
	    counterItemsMapping.forEach(function(item) {
	        console.log('처리 중인 항목:', item);
	        if (item.id && item.field) {
	            let value = 0;
	            let suffix = '';
	            
	            // 필드명에 따라 계산 방식 결정
	            if (item.field === 'T_S_AMT') {
	                value = T_S_AMT;
	                suffix = '원';
	            } else if (item.field === 'T_B_AMT') {
	                value = T_B_AMT;
	                suffix = '원';
	            } else if (item.field === 'T_P_AMT') {
	                value = T_P_AMT;
	                suffix = '원';
	            } else if (item.field === 'T_Order_Count') {
	                value = T_CNT;
	                suffix = '개';
	            } else if (item.field === 'T_AIR') {
	                value = T_AIR;
	                suffix = '개';
	            } else if (item.field === 'T_LCL') {
	                value = T_LCL;
	                suffix = '개';
	            } else if (item.field === 'T_FCL') {
	                value = T_FCL;
	                suffix = '개';
	            } else if (item.field === 'T_QUK') {
	                value = T_QUK;
	                suffix = '개';
	            } else if (item.field === 'T_GNL') {
	                value = T_GNL;
	                suffix = '개';
	            } else if (item.field === 'T_GNA') {
	                value = T_GNA;
	                suffix = '개';
	            } else if (item.field === 'T_CAS') {
	                value = T_CAS;
	                suffix = '개';
	            } else {
	                // 알 수 없는 필드인 경우 테이블 데이터에서 직접 계산 시도
	                value = table_data.reduce((sum, row) => {
	                    return sum + (row[item.field] ? Number(row[item.field]) : 0);
	                }, 0);
	                suffix = '';
	            }
	            
	            // 요소가 존재하는지 확인 후 값 설정
	            // .bottom_stats_container 내부의 div 요소만 찾기 (체크박스와 구분)
	            let $element = $('.bottom_stats_container div#' + item.id);
	            console.log('ID:', item.id, '요소 존재:', $element.length > 0, '값:', value, '접미사:', suffix);
	            
	            // 요소를 찾지 못한 경우 추가 시도
	            if ($element.length === 0) {
	                console.warn('요소를 찾지 못했습니다. 다른 방법으로 시도:', item.id);
	                // .bottom_stats_container 내부에서 찾기
	                var container = document.querySelector('.bottom_stats_container');
	                if (container) {
	                    var directElement = container.querySelector('div#' + item.id);
	                    if (directElement && directElement.tagName === 'DIV') {
	                        $element = $(directElement);
	                        console.log('.bottom_stats_container 내부에서 div 요소 찾음:', item.id);
	                    } else {
	                        // 모든 stats_value div 요소를 확인
	                        var allStats = container.querySelectorAll('div.stats_value');
	                        console.log('전체 stats_value div 요소 수:', allStats.length);
	                        allStats.forEach(function(elem, index) {
	                            console.log('stats_value[' + index + '] id:', elem.id, 'tagName:', elem.tagName, 'text:', elem.textContent);
	                        });
	                    }
	                }
	            }
	            
	            // 찾은 요소가 div가 아닌 경우 경고
	            if ($element.length > 0 && $element[0].tagName !== 'DIV') {
	                console.warn('잘못된 요소를 찾았습니다. div가 아닙니다:', item.id, 'tagName:', $element[0].tagName);
	                $element = $(); // 빈 jQuery 객체로 리셋
	            }
	            
	            if ($element.length > 0) {
	                let displayValue = addComma(value) + suffix;
	                
	                // 값 설정 - innerHTML을 사용하여 확실하게 설정
	                $element[0].innerHTML = displayValue;
	                
	                // 총매출과 총매입에 대해서는 추가 로깅 및 특별 처리
	                if (item.id === 'T_S_AMT' || item.id === 'T_B_AMT') {
	                    console.log('=== 총매출/총매입 특별 처리 ===');
	                    console.log('요소:', $element[0]);
	                    console.log('요소 클래스:', $element[0].className);
	                    var computedStyle = window.getComputedStyle($element[0]);
	                    console.log('요소 스타일 - display:', computedStyle.display);
	                    console.log('요소 스타일 - visibility:', computedStyle.visibility);
	                    console.log('요소 스타일 - opacity:', computedStyle.opacity);
	                    console.log('요소 스타일 - color:', computedStyle.color);
	                    console.log('요소 스타일 - font-size:', computedStyle.fontSize);
	                    console.log('설정할 값:', displayValue);
	                }
	                
	                // CSS 스타일 강제 적용 (화면에 확실히 보이도록)
	                // 모든 항목에 동일한 폰트 컬러 적용
	                var cssProps = {
	                    'display': 'block',
	                    'visibility': 'visible',
	                    'opacity': '1',
	                    'background-color': '#ffffff',
	                    'height': '25px',
	                    'min-height': '25px',
	                    'width': 'auto',
	                    'min-width': '100px',
	                    'text-align': 'right',
	                    'line-height': '25px',
	                    'font-size': '12px',
	                    'font-weight': 'normal',
	                    'color': '#000000',
	                    'z-index': '9999',
	                    'position': 'relative'
	                };
	                
	                $element.css(cssProps);
	                
	                // 추가로 직접 스타일 속성 설정 (더 강력한 방법)
	                var element = $element[0];
	                if (element) {
	                    element.style.setProperty('display', 'block', 'important');
	                    element.style.setProperty('visibility', 'visible', 'important');
	                    element.style.setProperty('opacity', '1', 'important');
	                    element.style.setProperty('color', '#000000', 'important');
	                    element.style.setProperty('font-size', '12px', 'important');
	                    element.style.setProperty('line-height', '25px', 'important');
	                    element.style.setProperty('background-color', '#ffffff', 'important');
	                    element.style.setProperty('font-weight', 'normal', 'important');
	                }
	                
	                console.log('값 설정 완료:', item.id, '=', displayValue);
	                
	                // 실제 DOM에 값이 설정되었는지 확인
	                setTimeout(function() {
	                    let actualText = $element.text();
	                    let actualHTML = $element.html();
	                    let computedStyle = window.getComputedStyle($element[0]);
	                    let isVisible = computedStyle.visibility !== 'hidden' && 
	                                   computedStyle.display !== 'none' && 
	                                   computedStyle.opacity !== '0';
	                    
	                    console.log('실제 DOM 텍스트 확인:', item.id, '=', actualText);
	                    console.log('실제 DOM HTML 확인:', item.id, '=', actualHTML);
	                    console.log('요소 가시성 확인:', item.id, 'visible:', isVisible, 'display:', computedStyle.display, 'opacity:', computedStyle.opacity);
	                    
	                    // 총매출과 총매입에 대해서는 추가 확인
	                    if (item.id === 'T_S_AMT' || item.id === 'T_B_AMT') {
	                        console.log('=== 총매출/총매입 DOM 확인 ===');
	                        console.log('요소 존재:', $element.length > 0);
	                        console.log('요소 ID:', $element[0].id);
	                        console.log('요소 클래스:', $element[0].className);
	                        console.log('요소 innerHTML:', $element[0].innerHTML);
	                        console.log('요소 textContent:', $element[0].textContent);
	                        console.log('요소 offsetWidth:', $element[0].offsetWidth);
	                        console.log('요소 offsetHeight:', $element[0].offsetHeight);
	                        console.log('computed color:', computedStyle.color);
	                        console.log('computed fontSize:', computedStyle.fontSize);
	                        console.log('computed display:', computedStyle.display);
	                        console.log('computed visibility:', computedStyle.visibility);
	                        console.log('computed opacity:', computedStyle.opacity);
	                        
	                        // 화면에 실제로 보이는지 확인
	                        var rect = $element[0].getBoundingClientRect();
	                        console.log('요소 위치:', 'top:', rect.top, 'left:', rect.left, 'width:', rect.width, 'height:', rect.height);
	                        console.log('요소가 화면에 보이는가?', rect.width > 0 && rect.height > 0 && rect.top >= 0 && rect.left >= 0);
						
						// 부모 요소의 스타일도 확인
						var parent = $element[0].parentElement;
						if (parent) {
							var parentStyle = window.getComputedStyle(parent);
							console.log('부모 요소 display:', parentStyle.display);
							console.log('부모 요소 visibility:', parentStyle.visibility);
							console.log('부모 요소 opacity:', parentStyle.opacity);
						}
	                    }
	                    
	                    if (actualText !== displayValue) {
	                        console.warn('값이 덮어씌워졌을 수 있습니다. 예상:', displayValue, '실제:', actualText);
	                        // 다시 설정 시도
	                        $element[0].innerHTML = displayValue;
	                        $element.css({
	                            'display': 'block',
	                            'visibility': 'visible',
	                            'opacity': '1',
	                            'color': '#000000',
	                            'font-size': '12px'
	                        });
	                        var element = $element[0];
	                        if (element) {
	                            element.style.setProperty('display', 'block', 'important');
	                            element.style.setProperty('color', '#000000', 'important');
	                            element.style.setProperty('font-size', '12px', 'important');
	                        }
	                    }
	                    
	                    // 화면에 보이지 않는 경우 강제로 다시 렌더링
	                    if (!isVisible || actualText === '') {
	                        console.warn('요소가 화면에 보이지 않습니다. 강제로 다시 렌더링합니다:', item.id);
	                        $element.css({
	                            'display': 'none'
	                        });
	                        setTimeout(function() {
	                            $element[0].innerHTML = displayValue;
	                            $element.css({
	                                'display': 'block',
	                                'visibility': 'visible',
	                                'opacity': '1',
	                                'color': '#000000',
	                                'font-size': '12px',
	                                'line-height': '25px'
	                            });
	                            var element = $element[0];
	                            if (element) {
	                                element.style.setProperty('display', 'block', 'important');
	                                element.style.setProperty('color', '#000000', 'important');
	                                element.style.setProperty('font-size', '12px', 'important');
	                            }
	                        }, 10);
	                    }
	                }, 100);
	            } else {
	                console.warn('요소를 찾을 수 없습니다:', item.id);
	            }
	        } else {
	            console.warn('항목에 id 또는 field가 없습니다:', item);
	        }
	    });
	} else {
	    console.log('기존 방식 사용 (하드코딩된 ID)');
	    // 기존 방식 (하드코딩된 ID) - 하위 호환성을 위해 유지
	    $('#T_S_AMT').text(addComma(T_S_AMT)+'원');
	    $('#T_B_AMT').text(addComma(T_B_AMT)+'원');
	    $('#T_P_AMT').text(addComma(T_P_AMT)+'원');
	    $('#T_Order_Count').text(addComma(T_CNT)+'개');
	    $('#T_AIR').text(addComma(T_AIR)+'개');
	    $('#T_LCL').text(addComma(T_LCL)+'개');
	    $('#T_FCL').text(addComma(T_FCL)+'개');
	    $('#T_QUK').text(addComma(T_QUK)+'개');	
	    $('#T_GNL').text(addComma(T_GNL)+'개');
	    $('#T_GNA').text(addComma(T_GNA)+'개');
	    $('#T_CAS').text(addComma(T_CAS)+'개');
	}
	
    console.log("AIR 개수=", T_AIR,'T_CNT=',T_CNT,'T_S_AMT=',T_S_AMT,'T_B_AMT=',T_B_AMT,'T_P_AMT=',T_P_AMT); // 콘솔에 출력
}

// DOM이 준비된 후 실행
$(document).ready(function() {
    var maxAttempts = 30; // 재시도 횟수
    
    // 업데이트 함수 (재시도 로직 포함)
    function tryUpdateCounter(attempts) {
        attempts = attempts || 0;
        attempts++;
        
        // table 객체와 counterItemsMapping이 준비되었는지 확인
        if (typeof table === 'undefined' || !table) {
            if (attempts < maxAttempts) {
                setTimeout(function() {
                    tryUpdateCounter(attempts);
                }, 200);
            } else {
                console.warn('table 객체를 찾을 수 없습니다. 최대 재시도 횟수 초과.');
            }
            return;
        }
        
        if (typeof counterItemsMapping === 'undefined') {
            if (attempts < maxAttempts) {
                setTimeout(function() {
                    tryUpdateCounter(attempts);
                }, 200);
            } else {
                console.warn('counterItemsMapping이 정의되지 않았습니다. 최대 재시도 횟수 초과.');
            }
            return;
        }
        
        // 테이블 데이터가 있는지 확인 (데이터가 없어도 실행 가능하도록 수정)
        try {
            var tableData = table.getData();
            // tableData가 배열이 아니면 재시도
            if (!Array.isArray(tableData)) {
                if (attempts < maxAttempts) {
                    setTimeout(function() {
                        tryUpdateCounter(attempts);
                    }, 200);
                }
                return;
            }
        } catch (e) {
            console.warn('테이블 데이터 접근 오류:', e);
            if (attempts < maxAttempts) {
                setTimeout(function() {
                    tryUpdateCounter(attempts);
                }, 200);
            }
            return;
        }
        
        // 모든 조건이 충족되면 업데이트 실행
        console.log('통계 카운터 실행 시도:', attempts);
        updateOrderCounter();
    }
    
    // 여러 시점에서 실행 시도 (각각 독립적으로)
    // 1. document.ready 직후
    setTimeout(function() {
        tryUpdateCounter(0);
    }, 500);
    
    // 2. window.onload 후
    $(window).on('load', function() {
        setTimeout(function() {
            tryUpdateCounter(0);
        }, 800);
    });
    
    // 3. 추가 안전장치
    setTimeout(function() {
        tryUpdateCounter(0);
    }, 2000);
    
    // 테이블이 이미 존재하는 경우 이벤트 리스너 등록
    function registerTableEvents(attempts) {
        attempts = attempts || 0;
        attempts++;
        
        if (typeof table !== 'undefined' && table) {
            // tableBuilt 이벤트 (테이블이 완전히 생성된 후)
            table.on('tableBuilt', function() {
                setTimeout(function() {
                    tryUpdateCounter(0);
                }, 300);
            });
            
            // dataLoaded 이벤트
            table.on('dataLoaded', function() {
                setTimeout(function() {
                    tryUpdateCounter(0);
                }, 200);
            });
            
            // dataChanged 이벤트
            table.on('dataChanged', function() {
                setTimeout(function() {
                    tryUpdateCounter(0);
                }, 200);
            });
            
            // redraw 이벤트
            table.on('redraw', function() {
                setTimeout(function() {
                    tryUpdateCounter(0);
                }, 200);
            });
            
            console.log('테이블 이벤트 리스너 등록 완료');
        } else {
            // table이 아직 없으면 나중에 다시 시도
            if (attempts < 20) {
                setTimeout(function() {
                    registerTableEvents(attempts);
                }, 300);
            }
        }
    }
    
    // 이벤트 리스너 등록 시도
    setTimeout(function() {
        registerTableEvents(0);
    }, 1000);
    
    // MutationObserver를 사용하여 DOM 변경 감지
    if (typeof MutationObserver !== 'undefined') {
        var observer = new MutationObserver(function(mutations) {
            var shouldUpdate = false;
            mutations.forEach(function(mutation) {
                if (mutation.type === 'childList') {
                    mutation.addedNodes.forEach(function(node) {
                        if (node.nodeType === 1 && (node.id === 'T_S_AMT' || node.id === 'T_B_AMT' || node.classList.contains('stats_value'))) {
                            shouldUpdate = true;
                        }
                    });
                }
                if (mutation.type === 'attributes' && mutation.attributeName === 'class') {
                    if (mutation.target.classList.contains('stats_value')) {
                        shouldUpdate = true;
                    }
                }
            });
            
            if (shouldUpdate) {
                setTimeout(function() {
                    updateAttempts = 0;
                    tryUpdateCounter();
                }, 100);
            }
        });
        
        // bottom_stats_container 감시
        var statsContainer = document.querySelector('.bottom_stats_container');
        if (statsContainer) {
            observer.observe(statsContainer, {
                childList: true,
                subtree: true,
                attributes: true,
                attributeFilter: ['class']
            });
        }
    }
});
