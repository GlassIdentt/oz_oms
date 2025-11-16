
// 카운트 함수
$(document).ready(function() {
    const table_data = table.getData(); // 현재 Tabulator 데이터 가져오기
	
	//console.log('table_data=',table_data);
    let T_AIR = table_data.filter(row => row.SO_MODE_H == 'AIR').length; // "AIR" 카운트
    let T_LCL = table_data.filter(row => row.SO_MODE_H == 'LCL').length; // "LCL" 카운트
    let T_FCL = table_data.filter(row => row.SO_MODE_H == 'FCL').length; // "FCL" 카운트
    let T_QUK = table_data.filter(row => row.SO_MODE_H == 'QUK').length; // "QUK" 카운트
    let T_CNT = table_data.filter(row => row.SO_MODE_H).length; // 토탈 카운트

	let T_S_AMT = table_data.reduce((sum, row) => {
	    return sum + (row.T_S_AMT ? Number(row.T_S_AMT) : 0);
	}, 0);
	
	let T_B_AMT = table_data.reduce((sum, row) => {
	    return sum + (row.T_B_AMT ? Number(row.T_B_AMT) : 0);
	}, 0);
	let TTC = Number(T_S_AMT) - Number(T_B_AMT);	

	
	
	
	$('#T_S_AMT', parent.document).text(addComma(T_S_AMT)+'원');
	$('#T_B_AMT', parent.document).text(addComma(T_B_AMT)+'원');
	$('#TTC', parent.document).text(addComma(TTC)+'원');
	$('#T_Order_Count', parent.document).text(addComma(T_CNT)+'개');
	$('#T_AIR', parent.document).text(addComma(T_AIR)+'개');
	$('#T_LCL', parent.document).text(addComma(T_LCL)+'개');
	$('#T_FCL', parent.document).text(addComma(T_FCL)+'개');
	$('#T_QUK', parent.document).text(addComma(T_QUK)+'개');	
	
    //console.log("AIR 개수=", T_AIR,'T_CNT=',T_CNT,'T_S_AMT=',T_S_AMT,'T_B_AMT=',T_B_AMT,'TTC=',TTC); // 콘솔에 출력
});
