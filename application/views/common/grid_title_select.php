<!-- 배경 오버레이 -->
<div id="title_item_overlay" class="title-select-overlay" style="display:none;position:fixed;top:0;left:0;width:100%;height:100%;background-color:rgba(0,0,0,0.5);z-index:9998;transition:opacity 0.3s ease-in-out;"></div>

<div id="title_item" class="title-select-layer" style="display:none;width:1000px;max-height:80vh;overflow-y:auto;position:fixed;top:50%;left:50%;transform:translate(-50%, -50%);z-index:9999;border-radius:5px;border: 5px solid #58ACFA; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);background-color:#ffffff;transition:opacity 0.3s ease-in-out;">
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
        .title-select-layer .ths, .title-select-layer tds {
            padding: 10px;
            text-align: left;
            border: 1px solid #ccc;
            width: 100px;
            height: 30px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        .title-select-layer .product-checkbox-label {
            white-space: nowrap;
            font-size: 12px !important;
        }
        .title-select-layer .product-checkbox-label span {
            font-size: 12px !important;
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
                        const displayTitle = (titleValue === "-") 
                            ? column.field 
                            : (titleValue !== "" ? titleValue : column.field);
                        
                        // product-checkbox-label과 product-checkbox 클래스 사용
                        const checkboxHtml = `<label class="product-checkbox-label">
                            <input type="checkbox" id="${column.field}" class="product-checkbox" ${column.visible ? 'checked' : ''} onchange="toggleColumn('${column.field}', this)">
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
