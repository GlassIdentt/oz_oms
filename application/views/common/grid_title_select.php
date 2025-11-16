<div id="title_item"  class="custom-checkbox" style="display:none;width:1000px;height:300px;position:absolute;top:0px;left:388px;z-index:999;border-radius:5px;border: 5px solid #58ACFA; box-shadow: 0 5px 10px rgba(0, 0, 0, 0.1);background-color:#ffffff;">
    <style>
        .tables {
            width: 1000px;
            border-collapse: collapse;
            margin: 20px 0;
        }
        .ths, tds {
            padding: 10px;
            text-align: left;
            border: 1px solid #ccc;
            width: 100px; /* 각 셀의 너비를 100px로 설정 */
            height: 30px; /* 각 셀의 너비를 100px로 설정 */			
        }
    </style>
    <table class="tables" border="1">
        <tbody id="checkbox-container" class="ths tds"></tbody>
    </table>
    <table class="tables">
		<tr>
			<td align="center">
				<a href="javascript:div_hide('title_item');"><img src="../../web_order/images/btn_close_50.png"  border="0"></a>
			</td>
		</tr>	
    </table>	
    <script>
		//console.log('columnData',columnData);
        // HTML 태그 필터링 함수
        function stripHtmlTags(input) {
            return input.replace(/<[^>]*>/g, '').trim(); // 정규 표현식으로 HTML 태그 제거
        }		
		$(document).ready(function() {
		    const $checkboxContainer = $('#checkbox-container');
		
		    if ($checkboxContainer.length === 0) {
		        console.error('checkbox-container 요소를 찾을 수 없습니다.');
		    } else {
		        let $row = $('<tr></tr>'); // 초기화
		        let cellCount = 0; // 셀 카운터
		
		        columnData.forEach((column, index) => {
		            if (column.title && column.title.trim() !== "") {
		                const checkboxHtml = `<input type="checkbox" id="${column.field}" ${column.visible ? 'checked' : ''} onchange="toggleColumn('${column.field}', this)">`;
		                const $cell = $('<td></td>').html(checkboxHtml + `<label style='font-size:11.5px;' for="${column.field}">${stripHtmlTags(column.title)}</label>`);
		                $row.append($cell);
		                cellCount++;
		
		                // 10개가 되었거나 마지막 항목일 경우 행을 추가
		                if (cellCount === 10) {
		                    $checkboxContainer.append($row); // 행 추가
		                    $row = $('<tr></tr>'); // 새로운 행 생성
		                    cellCount = 0; // 셀 카운터 초기화
		                }
		            }
		        });
		
		        // 마지막에 남아있는 셀들이 있을 경우 행을 추가
		        if (cellCount > 0) {
		            $checkboxContainer.append($row); // 마지막 행 추가
		        }
		    }
		});
	</script>	
</div>
