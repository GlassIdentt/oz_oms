<!-- 배경 오버레이 -->
<div id="title_item_overlay" class="title-select-overlay" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background-color:rgba(0,0,0,0.5);z-index:9998;transition:opacity 0.3s ease-in-out;"></div>

<div id="title_item" class="title-select-layer" style="display:none;width:1200px;max-height:80vh;overflow-y:auto;position:fixed;top:50%;left:50%;transform:translate(-50%, -50%);z-index:9999;border-radius:5px;border: 5px solid #58ACFA; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);background-color:#ffffff;transition:opacity 0.3s ease-in-out;">
    <style>
        .title-select-layer {
            padding: 20px;
            opacity: 0;
        }
        .title-select-layer.show {
            opacity: 1 !important;
        }
        .title-select-overlay {
            cursor: pointer;
            opacity: 0;
        }
        .title-select-overlay.show {
            opacity: 1 !important;
        }
        .title-select-layer .tables {
            width: 100%;
            border-collapse: collapse;
            margin: 10px 0;
        }
        .title-select-layer .ths, .title-select-layer td {
            padding: 5px 10px;
            text-align: left;
            border: 1px solid #ccc;
            width: auto;
            height: 35px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
            vertical-align: middle;
            box-sizing: border-box;
        }
        .title-select-layer .title-checkbox-label {
            white-space: nowrap;
            font-size: 12px;
            display: flex;
            align-items: center;
            margin: 0;
            height: 100%;
            width: auto;
            padding-left: 0;
            position: relative;
            cursor: pointer;
        }
        .title-select-layer .title-checkbox-label input[type="checkbox"] {
            margin-right: 5px;
            margin-top: 0;
            margin-bottom: 0;
            vertical-align: middle;
            position: relative;
            opacity: 1;
            width: 18px;
            height: 18px;
            left: auto;
            top: auto;
            appearance: none;
            -webkit-appearance: none;
            -moz-appearance: none;
            background-color: #ffffff;
            border: 1px solid #ccc;
            border-radius: 3px;
            cursor: pointer;
        }
        .title-select-layer .title-checkbox-label input[type="checkbox"]:checked {
            background-color:rgb(22, 138, 247);
            border-color:rgb(71, 70, 69);
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='15' height='15' viewBox='0 0 12 12'%3E%3Cpath fill='white' d='M10 3L4.5 8.5L2 6l1.5-1.5L4.5 6L8.5 2z'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: center;
            background-size: 20px 20px;
        }
        .title-select-layer .title-checkbox-label input[type="checkbox"]:hover {
            border-color: #FF9800;
        }
        .title-select-layer .title-checkbox-label input[type="checkbox"]:checked:hover {
            background-color: #FFB84D;
            border-color: #FF9800;
        }
        .title-select-layer .title-checkbox-label span {
            font-size: 12px;
            line-height: 1.2;
            vertical-align: middle;
            color: #1d1d1d;
        }
        .title-select-layer-buttons {
            text-align: center;
            padding: 10px 0;
            margin-top: 10px;
        }
    </style>
    <table class="tables" border="1">
        <tbody id="checkbox-container" class="ths tds"></tbody>
    </table>
    <div class="title-select-layer-buttons">
        <button type="button" class="event-btn cancel-btn" id="titleSelectLayerCloseBtn">
            <span class="event-btn-icon icon-cancel"></span>
            <span>닫기</span>
        </button>
    </div>
    <script>
        // HTML 태그 필터링 함수
        function stripHtmlTags(input) {
            return input.replace(/<[^>]*>/g, '').trim();
        }
        
        // 레이어 닫기 함수 (페이드아웃) - 전역 함수로 등록
        window.closeTitleSelectLayer = function() {
            var layer = document.getElementById('title_item');
            var overlay = document.getElementById('title_item_overlay');
            if (layer) {
                layer.classList.remove('show');
                setTimeout(function() {
                    layer.style.display = 'none';
                }, 300); // 페이드아웃 시간과 동일
            }
            if (overlay) {
                overlay.classList.remove('show');
                setTimeout(function() {
                    overlay.style.display = 'none';
                }, 300);
            }
        }
        
        $(document).ready(function() {
            const $checkboxContainer = $('#checkbox-container');
        
            if ($checkboxContainer.length === 0) {
                console.error('checkbox-container 요소를 찾을 수 없습니다.');
            } else {
                let $row = $('<tr></tr>');
                let cellCount = 0;
        
                columnData.forEach((column, index) => {
                    if (column.field) {
                        // 공백을 제외하고 title이 정확히 "-"일 때만 field를 사용
                        const titleValue = column.title ? stripHtmlTags(column.title).trim() : "";
                        
                        // title이 공백만 있거나 빈 값이면 출력하지 않음
                        if (titleValue === "") {
                            return;
                        }
                        
                        const displayTitle = (titleValue === "-") 
                            ? column.field 
                            : titleValue;
                        
                        // title-checkbox-label과 title-checkbox 클래스 사용
                        const checkboxHtml = `<label class="title-checkbox-label">
                            <input type="checkbox" id="${column.field}" class="title-checkbox" ${column.visible ? 'checked' : ''} onchange="toggleColumn('${column.field}', this)">
                            <span>${displayTitle}</span>
                        </label>`;
                        const $cell = $('<td></td>').html(checkboxHtml);
                        $row.append($cell);
                        cellCount++;
        
                        // 10개가 되었거나 마지막 항목일 경우 행을 추가
                        if (cellCount === 10) {
                            $checkboxContainer.append($row);
                            $row = $('<tr></tr>');
                            cellCount = 0;
                        }
                    }
                });
        
                // 마지막에 남아있는 셀들이 있을 경우 행을 추가
                if (cellCount > 0) {
                    $checkboxContainer.append($row);
                }
            }
            
            // 닫기 버튼 이벤트 연결
            var closeBtn = document.getElementById('titleSelectLayerCloseBtn');
            if (closeBtn) {
                closeBtn.addEventListener('click', function() {
                    closeTitleSelectLayer();
                });
            }
            
            // 오버레이 클릭 시 레이어 닫기
            var overlay = document.getElementById('title_item_overlay');
            if (overlay) {
                overlay.addEventListener('click', function() {
                    closeTitleSelectLayer();
                });
            }
        });
    </script>
</div>
