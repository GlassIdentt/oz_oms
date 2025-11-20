/**
 * 공용 JavaScript 파일
 * 모든 페이지에서 공통으로 사용하는 함수들을 정의합니다.
 */

// DOM 로드 완료 후 실행
document.addEventListener('DOMContentLoaded', function() {
    console.log('Common JS loaded');
    
    // 여기에 공용 함수들을 추가하세요
});

/**
 * .custom-select 요소의 width를 모든 option 중 가장 긴 텍스트 기준으로 자동 조정
 * @param {HTMLElement} element - select 요소
 */
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

/**
 * 모든 .custom-select 요소에 width 자동 조정 적용
 */
function initCustomSelects() {
    var customSelects = document.querySelectorAll('.custom-select');
    customSelects.forEach(function(element) {
        autoResizeCustomSelect(element);
    });
}

// DOM 로드 완료 후 자동 실행
document.addEventListener('DOMContentLoaded', function() {
    initCustomSelects();
});

function getCurrentDate() {
    var now = new Date();
    var year = now.getFullYear();
    var month = String(now.getMonth() + 1).padStart(2, '0'); // 월은 0부터 시작하므로 +1
    var day = String(now.getDate()).padStart(2, '0');

    return `${year}${month}${day}`;
}

function addComma(x) {
	var formNumber="" + x
    return formNumber.toString().replace(/\B(?=(\d{3})+(?!\d))/g, ",");
}


// 토스트 메시지 함수

function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.innerHTML = message; // textContent 대신 innerHTML 사용
    document.body.appendChild(toast);
    
    setTimeout(() => toast.classList.add('show'), 10);
    
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 1100);
}

// ========== 필터 상태 저장/복원 함수 ==========
function saveFilterState($Form_Id) {
    const filters = {};
    const headerFilters = table.getHeaderFilters();
    
    headerFilters.forEach(filter => {
        if (filter.value && filter.value.trim() !== '') {
            filters[filter.field] = filter.value;
        }
    });
    
    if (Object.keys(filters).length > 0) {
        localStorage.setItem('tabulatorFilters_'+$Form_Id, JSON.stringify(filters));
    } else {
        localStorage.removeItem('tabulatorFilters_'+$Form_Id);
    }
}

function loadFilterState($Form_Id) {
    const savedFilters = localStorage.getItem('tabulatorFilters_'+$Form_Id);
    
    if (savedFilters) {
        const filters = JSON.parse(savedFilters);
        Object.keys(filters).forEach(field => {
            table.setHeaderFilterValue(field, filters[field]);
        });
        // 필터 복원 직후 강조 표시 (추가)
        setTimeout(function() {
            updateFilterHighlight($Form_Id);
        }, 300);
    }
}
// ========== 필터 상태 저장/복원 함수 ==========


// ========== 필터 활성화 상태 표시 함수 ==========
function updateFilterHighlight() {
    // 모든 헤더 필터 input에서 filter-active 클래스 제거
    document.querySelectorAll('.tabulator-header-filter input').forEach(input => {
        input.classList.remove('filter-active');
    });
    const headerFilters = table.getHeaderFilters();
    
    // 값이 있는 필터만 강조
    headerFilters.forEach(filter => {
        if (filter.value && filter.value.trim() !== '') {
            const column = table.getColumn(filter.field);
            if (column) {
                const filterInput = column.getElement().querySelector('.tabulator-header-filter input');
                if (filterInput) {
                    filterInput.classList.add('filter-active');
                }
            }
        }
    });
}
