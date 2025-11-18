
// 카운트 함수
$(document).ready(function() {
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
	if (typeof counterItemsMapping !== 'undefined' && Array.isArray(counterItemsMapping)) {
	    counterItemsMapping.forEach(function(item) {
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
	            let $element = $('#' + item.id);
	            if ($element.length > 0) {
	                $element.text(addComma(value) + suffix);
	            }
	        }
	    });
	} else {
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
});
