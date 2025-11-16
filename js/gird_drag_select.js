// ===== 드래그 선택 영역 시작  =====			
function addDragSelectionStyles() {
    if (!document.getElementById('drag-selection-style')) {
        var style = document.createElement('style');
        style.id = 'drag-selection-style';
        style.textContent = `
            .cell-selected {
                background-color: #e3f2fd !important;
                border: 1px solid #1976d2 !important;
            }
            .cell-drag-highlight {
                background-color: #bbdefb !important;
                border: 1px solid #0d47a1 !important;
            }
            .selection-overlay {
                position: absolute;
                border: 1px solid #1976d2;
                background-color: rgba(25, 118, 210, 0.1);
                pointer-events: none;
                z-index: 1000;
                box-sizing: border-box;
            }
            .tabulator-cell {
                position: relative;
            }
            
            .toast {
                position: fixed;
                top: 50%;
                left: 50%;
                height: 50px;
                transform: translate(-50%, -50%) scale(0.8);
                background-color: #333;
                color: white;
                padding: 12px 24px;
                border-radius: 8px;
                box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15);
                opacity: 0;
                transition: all 0.3s ease;
                z-index: 9999;
                font-size: 16px;
                font-weight: bolder;
                display: flex;
                align-items: center;
                justify-content: center;
                white-space: pre-line;
            }
            
            .toast.show {
                opacity: 1;
                transform: translate(-50%, -50%) scale(1);
            }
            
            .toast.toast-success {
                background-color: #FF0055;
            }
            
            .toast.toast-error {
                background-color: #ef4444;
            }
        `;
        document.head.appendChild(style);
    }
}

// ===== 토스트 메시지 함수 =====
function showToast(message, type = 'success') {
    const toast = document.createElement('div');
    toast.className = `toast toast-${type}`;
    toast.textContent = message;
    document.body.appendChild(toast);
    
    setTimeout(() => toast.classList.add('show'), 10);
    
    setTimeout(() => {
        toast.classList.remove('show');
        setTimeout(() => toast.remove(), 300);
    }, 1200);
}

// ===== 선택된 셀들 하이라이트 제거 =====
function clearCellSelection() {
    selectedCells.forEach(cell => {
        if (cell && cell.getElement()) {
            cell.getElement().classList.remove('cell-selected', 'cell-drag-highlight');
        }
    });
    selectedCells = [];
    
    if (selectionOverlay) {
        selectionOverlay.remove();
        selectionOverlay = null;
    }
}

// ===== 셀 범위 선택 =====
function selectCellRange(startCell, endCell) {
    clearCellSelection();
    
    if (!startCell || !endCell) return;
    
    const startRow = startCell.getRow();
    const endRow = endCell.getRow();
    const startColumn = startCell.getColumn();
    const endColumn = endCell.getColumn();
    
    const allRows = table.getRows();
    const allColumns = table.getColumns().filter(col => col.isVisible());
    
    const startRowIndex = allRows.indexOf(startRow);
    const endRowIndex = allRows.indexOf(endRow);
    const startColIndex = allColumns.indexOf(startColumn);
    const endColIndex = allColumns.indexOf(endColumn);
    
    const minRowIndex = Math.min(startRowIndex, endRowIndex);
    const maxRowIndex = Math.max(startRowIndex, endRowIndex);
    const minColIndex = Math.min(startColIndex, endColIndex);
    const maxColIndex = Math.max(startColIndex, endColIndex);
    
    for (let i = minRowIndex; i <= maxRowIndex; i++) {
        for (let j = minColIndex; j <= maxColIndex; j++) {
            if (allRows[i] && allColumns[j]) {
                const cell = allRows[i].getCell(allColumns[j].getField());
                if (cell && cell.getElement()) {
                    cell.getElement().classList.add('cell-selected');
                    selectedCells.push(cell);
                }
            }
        }
    }
    
    createSelectionOverlay(startCell, endCell);
}

// ===== 선택 오버레이 생성 =====
function createSelectionOverlay(startCell, endCell) {
    if (selectionOverlay) {
        selectionOverlay.remove();
    }
    
    const startElement = startCell.getElement();
    const endElement = endCell.getElement();
    
    if (!startElement || !endElement) return;
    
    const tableElement = table.element;
    const tableRect = tableElement.getBoundingClientRect();
    const startRect = startElement.getBoundingClientRect();
    const endRect = endElement.getBoundingClientRect();
    
    const left = Math.min(startRect.left, endRect.left) - tableRect.left;
    const top = Math.min(startRect.top, endRect.top) - tableRect.top;
    const right = Math.max(startRect.right, endRect.right) - tableRect.left;
    const bottom = Math.max(startRect.bottom, endRect.bottom) - tableRect.top;
    const width = right - left;
    const height = bottom - top;
    
    selectionOverlay = document.createElement('div');
    selectionOverlay.className = 'selection-overlay';
    selectionOverlay.style.left = left + 'px';
    selectionOverlay.style.top = top + 'px';
    selectionOverlay.style.width = width + 'px';
    selectionOverlay.style.height = height + 'px';
    
    tableElement.style.position = 'relative';
    tableElement.appendChild(selectionOverlay);
}

// ===== 선택된 데이터를 클립보드로 복사 =====
function copySelectedCells() {
    if (selectedCells.length === 0) {
        showToast('복사할 셀을 선택해주세요.', 'error');
        return;
    }
    
    const cellsWithPosition = selectedCells.map(cell => {
        const row = cell.getRow();
        const column = cell.getColumn();
        const allRows = table.getRows();
        const allColumns = table.getColumns().filter(col => col.isVisible());
        
        return {
            cell: cell,
            rowIndex: allRows.indexOf(row),
            colIndex: allColumns.indexOf(column),
            value: cell.getValue() || ''
        };
    });
    
    cellsWithPosition.sort((a, b) => {
        if (a.rowIndex !== b.rowIndex) {
            return a.rowIndex - b.rowIndex;
        }
        return a.colIndex - b.colIndex;
    });
    
    const minRow = Math.min(...cellsWithPosition.map(c => c.rowIndex));
    const maxRow = Math.max(...cellsWithPosition.map(c => c.rowIndex));
    const minCol = Math.min(...cellsWithPosition.map(c => c.colIndex));
    const maxCol = Math.max(...cellsWithPosition.map(c => c.colIndex));
    
    const dataArray = [];
    for (let i = minRow; i <= maxRow; i++) {
        const row = [];
        for (let j = minCol; j <= maxCol; j++) {
            const cellData = cellsWithPosition.find(c => c.rowIndex === i && c.colIndex === j);
            row.push(cellData ? cellData.value : '');
        }
        dataArray.push(row);
    }
    
    const tsvData = dataArray.map(row => row.join('\t')).join('\n');
    
    // 클립보드 복사
    const textArea = document.createElement("textarea");
    textArea.value = tsvData;
    textArea.style.position = "fixed";
    textArea.style.top = "0";
    textArea.style.left = "0";
    textArea.style.width = "1px";
    textArea.style.height = "1px";
    textArea.style.opacity = "0";
    document.body.appendChild(textArea);
    textArea.focus();
    textArea.select();
    
    try {
        const successful = document.execCommand('copy');
        if (successful) {
            console.log(`${selectedCells.length}개 셀이 복사되었습니다.`);
            showToast(`데이터가 클립보드에 복사되었습니다.`);
        } else {
            showToast('복사에 실패했습니다.', 'error');
        }
    } catch (err) {
        console.error('복사 실패:', err);
        showToast('복사에 실패했습니다.', 'error');
    }
    
    document.body.removeChild(textArea);
}

// ===== 드래그 선택 이벤트 추가 =====
function addDragSelectionEvents() {
    addDragSelectionStyles();
    
    table.on("cellMouseDown", function(e, cell) {
        if (e.button !== 0) return;
        
        if (!e.ctrlKey && !e.shiftKey) {
            isDragging = true;
            startCell = cell;
            endCell = cell;
            
            clearCellSelection();
            
            cell.getElement().classList.add('cell-selected');
            selectedCells.push(cell);
            
            e.preventDefault();
        }
    });
    
    table.on("cellMouseOver", function(e, cell) {
        if (isDragging && startCell) {
            endCell = cell;
            selectCellRange(startCell, endCell);
        }
    });
    
    document.addEventListener('mouseup', function(e) {
        if (isDragging) {
            isDragging = false;
            console.log(`셀 선택 완료: ${selectedCells.length}개 셀 선택됨`);
            
            // 2개 이상의 셀이 선택되었을 때만 자동 복사
            if (selectedCells.length >= 2) {
                setTimeout(() => {
                    copySelectedCells();
                }, 100);
            }
        }
    });
    
    document.addEventListener('click', function(e) {
        if (!table.element.contains(e.target)) {
            clearCellSelection();
        }
    });
/*    
    // Ctrl+C 키 이벤트 (여러 방법 시도)
    const handleCopy = function(e) {
        // Ctrl+C 또는 Cmd+C (Mac)
        if ((e.ctrlKey || e.metaKey) && e.key === 'c') {
            console.log('Ctrl+C 감지됨', selectedCells.length);
            if (selectedCells.length > 0) {
                e.preventDefault();
                e.stopPropagation();
                e.stopImmediatePropagation();
                copySelectedCells();
                return false;
            }
        }
    };
*/    
    // 1. document에 keydown 이벤트
//    document.addEventListener('keydown', handleCopy, true);
    
    // 2. document에 keyup 이벤트 (백업)
 //   document.addEventListener('keyup', handleCopy, true);
    
    // 3. window에 keydown 이벤트
 //   window.addEventListener('keydown', handleCopy, true);
    
    // 4. copy 이벤트 직접 감지
    document.addEventListener('copy', function(e) {
        console.log('copy 이벤트 감지됨');
        if (selectedCells.length > 0) {
            e.preventDefault();
            
            const cellsWithPosition = selectedCells.map(cell => {
                const row = cell.getRow();
                const column = cell.getColumn();
                const allRows = table.getRows();
                const allColumns = table.getColumns().filter(col => col.isVisible());
                
                return {
                    cell: cell,
                    rowIndex: allRows.indexOf(row),
                    colIndex: allColumns.indexOf(column),
                    value: cell.getValue() || ''
                };
            });
            
            cellsWithPosition.sort((a, b) => {
                if (a.rowIndex !== b.rowIndex) {
                    return a.rowIndex - b.rowIndex;
                }
                return a.colIndex - b.colIndex;
            });
            
            const minRow = Math.min(...cellsWithPosition.map(c => c.rowIndex));
            const maxRow = Math.max(...cellsWithPosition.map(c => c.rowIndex));
            const minCol = Math.min(...cellsWithPosition.map(c => c.colIndex));
            const maxCol = Math.max(...cellsWithPosition.map(c => c.colIndex));
            
            const dataArray = [];
            for (let i = minRow; i <= maxRow; i++) {
                const row = [];
                for (let j = minCol; j <= maxCol; j++) {
                    const cellData = cellsWithPosition.find(c => c.rowIndex === i && c.colIndex === j);
                    row.push(cellData ? cellData.value : '');
                }
                dataArray.push(row);
            }
            
            const tsvData = dataArray.map(row => row.join('\t')).join('\n');
            
            e.clipboardData.setData('text/plain', tsvData);
            showToast(`데이터가 클립보드에 복사되었습니다.`);
            console.log(`${selectedCells.length}개 셀이 복사되었습니다.`);
        }
    });
}

// ===== 전역 함수들 =====
window.copySelectedTableCells = function() {
    copySelectedCells();
};

window.clearTableSelection = function() {
    clearCellSelection();
};

// ===== 드래그 선택 영역 끝 =====