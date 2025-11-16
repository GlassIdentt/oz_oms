function ORD_CHANGE(SO_NO){
    window.open('ord_change_info.php?ORDER_NO='+SO_NO,'ORD_change'+ORD_ID,'scrollbars=yes,width=900,height=300,left=0,top=0');
}
function car_open(){
    CT=document.OPR.CAR_NO.value;
    openWindow('car_reg2.php?gb=1&pgubun=1&CT='+CT,'ALLOCATIONCAR','scrollbars=yes,width=850,height=450,left=0,top=0,reszie=yes');
}

function car_open2(A){
    S_CAR_NO=document.OPR.CAR_NO.value;
    openWindow('car_reg2.php?gb=1&pgubun=1&S_CAR_NO='+S_CAR_NO,'ALLOCATIONCAR'+A,'scrollbars=yes,width=850,height=450,left=0,top=0,reszie=yes');
}	

function car_hor_reg(SO_NO){
    openWindow('car_hor_reg_2.php?SO_NO='+SO_NO,'ALLOCATIONCAR','scrollbars=no,width=885,height=470,left=0,top=0,reszie=yes');
}
//-->
function OP(a,b){
      document.OPR.SO_NO_C.value=a;
      document.OPR.CAR_HOR_ADD.value=b;
    }

function operat_run(event){
    // 이벤트가 전달된 경우 기본 동작 방지
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    
    if (document.querySelector('#CAR_NO').value == '' || document.querySelector('#DRV_CD').value == ''){
        showToast('차량검색이 안되었거나, 차량번호만 입력 되었습니다!!  차량을 검색해서 선택해 주십시요!!');
        document.querySelector('#CAR_NO').focus();		
        return false;
    }else{	
        if (document.querySelector('#ALLOCATE_DV').value == ''){
            showToast('배차 차량 구분을 선택해 주세요! <br> 자차 / 협력업체 / 콜  중에 선택해주세요');
            return false;
        }else{  
            // Query 변수 업데이트
            if (typeof buildQuery === 'function') {
                buildQuery();
            }
            var Query=document.querySelector('#Query') ? document.querySelector('#Query').value : '';
            let $Allocation_page=document.querySelector('#Allocation_page').value;

            let resultData_length=grid_form_data(); // 그리드 데이터 가져오기
            document.querySelector('#CNT_NO').value=resultData_length;
            
            if (resultData_length == 0){
                showToast('오더가 선택되지 않았습니다!, 1개 이상의 오더를 선택하세요');
                document.querySelector('#CAR_NO').focus();	
                return false;
            }else{
				document.Allocation_Car.action=(typeof SITE_URL !== 'undefined' ? SITE_URL + '/' : '') + 'Allocation_car/custom_allocation_car_process?Allocation_page='+$Allocation_page;
                document.Allocation_Car.submit();			
            }
        }	
    }
    return false;
}

function grid_form_data(resultData_length){
    // 선택된 행의 데이터 가져오기
    let selectedData = table.getSelectedData();	
    // 체크박스 상태 추가
    let resultData = selectedData.map(function(row) {
        return {
            SO_NO: row.SO_NO,
            SO_PT: row.SO_PT,
            CAR_TEL: row.CAR_TEL,
            PD_DRV_CD: row.DRV_CD,
            TRAN_VEN_YN:row.TRAN_VEN_YN,
            T_INFO_YN:row.T_INFO_YN,
            T_INFO_EMAIL:row.T_INFO_EMAIL,				
            ACT_SHIP_A_NM:row.ACT_SHIP_A_NM,
            ACT_SHIP_PIC_NM:row.ACT_SHIP_PIC_NM,
            SHIP_NM_TEMP:row.SHIP_NM,
            LOAD_NM:row.LOAD_NM,
            UNLOAD_NM:row.UNLOAD_NM,				
            IO_TYPE:row.IO_TYPE,
            SO_MODE_H:row.SO_MODE_H,
            G_HBL_NO:row.HBL_NO,
            LOAD_REQ_DT:row.LOAD_REQ_DT,
            FDS_NM:row.FDS_NM,
            LOAD_AREA:row.LOAD_AREA,								
            selected: true // 체크된 경우
        };
    });
    //console.log("선택된 데이터:", resultData,'선택 수=',resultData.length); 	
    const jsonString = JSON.stringify(resultData)
    document.querySelector('#GridData').value=jsonString;
    return resultData.length;
    
}

function operat_cancle(i, event){	   
    // 이벤트가 전달된 경우 기본 동작 방지
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }
    
    // Query 변수 업데이트
    if (typeof buildQuery === 'function') {
        buildQuery();
    }
    
    document.querySelector('#A_CAR_KEY').value=i;
    let Query=document.querySelector('#Query') ? document.querySelector('#Query').value : '';					
    let $Allocation_page=document.querySelector('#Allocation_page').value;	
    let resultData_length=grid_form_data(); // 그리드 데이터 가져오기
    document.querySelector('#CNT_NO').value=resultData_length;
    
    if (resultData_length == 0){
        showToast('오더가 선택되지 않았습니다! 1개 이상의 오더를 선택하세요!!');
        return false;
    }else{
		document.Allocation_Car.action=(typeof SITE_URL !== 'undefined' ? SITE_URL + '/' : '') + 'Allocation_car/custom_allocation_car_process?Allocation_page='+$Allocation_page;
        document.Allocation_Car.submit();			
    }
    return false;
}	

function Aloc_Type_Change(event){	   
    // 이벤트가 전달된 경우 기본 동작 방지
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }	
    if (document.querySelector('#ALOC_STAT').value == '') {
        showToast('배차유형 항목을 선택해 주십시요');			
        document.querySelector('#ALOC_STAT').focus();
        return;	 
    }else{
		let Query=document.querySelector('#Query') ? document.querySelector('#Query').value : '';
        let $Allocation_page=document.querySelector('#Allocation_page').value;
        let $ALOC_STAT=document.querySelector('#ALOC_STAT').value;	
        let resultData_length=grid_form_data(); // 그리드 데이터 가져오기
        document.querySelector('#CNT_NO').value=resultData_length;
        if (resultData_length == 0){
            showToast('오더가 선택되지 않았습니다! 1개 이상의 오더를 선택하세요!!');
			return false;
        }else{
            document.Allocation_Car.action=(typeof SITE_URL !== 'undefined' ? SITE_URL + '/' : '') + 'Allocation_car/aloc_type_change_process?'+Query+'&Allocation_page='+$Allocation_page+'&ALOC_STAT='+$ALOC_STAT;
            document.Allocation_Car.submit();
        }	
		return false;  
    }
}	

function excelDown(){
    table.download("xlsx", "allocation_car_list.xlsx", {sheetName:"allocation_car_list"});
}	


function ORD_DELIVER(){	   
    if (document.querySelector('#R_CUST_CD').value=='') {
        showToast('오더를 전달 할 업체를 선택해 주십시요!');
        document.querySelector('#R_CUST_CD').focus();
        return;	 
    }else{
        let $Query=document.querySelector('#Query').value;						
        let $R_CUST_CD=document.querySelector('#R_CUST_CD').value;
        let $Allocation_page=document.querySelector('#Allocation_page').value;
        let resultData_length=grid_form_data(); // 그리드 데이터 가져오기
        document.querySelector('#CNT_NO').value=resultData_length;
        if (resultData_length == 0){
            showToast('오더가 선택되지 않았습니다!, 1개 이상의 오더를 선택하세요!!');				
            return;
        }else{

			document.Allocation_Car.action=(typeof SITE_URL !== 'undefined' ? SITE_URL + '/' : '') + 'Allocation_car/order_exchange_insert_process?Allocation_page='+$Allocation_page+'&R_CUST_CD='+$R_CUST_CD;
            document.Allocation_Car.submit();			
        }		
    }
}

function ORD_DELIVER_CANCLE(){	   
    if (document.querySelector('#R_CUST_CD').value=='') {
        showToast('오더를 취소할 업체를 선택해 주십시요!');
        document.querySelector('#R_CUST_CD').focus();
        return;	 
    }else{
        let $Query=document.querySelector('#Query').value;						
        let $R_CUST_CD=document.querySelector('#R_CUST_CD').value;
        let $Allocation_page=document.querySelector('#Allocation_page').value;
        let resultData_length=grid_form_data(); // 그리드 데이터 가져오기
        document.querySelector('#CNT_NO').value=resultData_length;
        if (resultData_length == 0){
            showToast('오더가 선택되지 않았습니다!, 1개 이상의 오더를 선택하세요!!');				
            return;
        }else{
            document.Allocation_Car.action='order_exchange_cancle_process.php?'+$Query+'&Allocation_page='+$Allocation_page+'&R_CUST_CD='+$R_CUST_CD;
            document.Allocation_Car.submit();			
        }		
    }
}

function app_order_sand(event){

    // 이벤트가 전달된 경우 기본 동작 방지
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }	
    let Query=document.Allocation_Car.Query.value;
    let $Allocation_page=document.querySelector('#Allocation_page').value;
    let resultData_length=grid_form_data(); // 그리드 데이터 가져오기
    document.querySelector('#CNT_NO').value=resultData_length;
    if (resultData_length == 0){
        showToast('오더가 선택되지 않았습니다!, 1개 이상의 오더를 선택하세요!!');
        return false;
    }else{
        document.Allocation_Car.action='app_order_sand_process.php?'+Query+'&Allocation_page='+$Allocation_page;
        document.Allocation_Car.submit();
    }
    return false;
}

function sms_order(event){	  
    if (event) {
        event.preventDefault();
        event.stopPropagation();
    }	 	
    let Query=document.Allocation_Car.Query.value;	
    let $Allocation_page=document.querySelector('#Allocation_page').value;
    let resultData_length=grid_form_data(); // 그리드 데이터 가져오기
    document.querySelector('#CNT_NO').value=resultData_length;
    if (resultData_length == 0){
        showToast('오더가 선택되지 않았습니다!, 1개 이상의 오더를 선택하세요!!');
        return false;
    }else{
        document.Allocation_Car.action='ord_sms_sand_process`.php?`'+Query+'&Allocation_page='+$Allocation_page;
        document.Allocation_Car.submit();			
    }
	return false;
}

function kakao_sand(){	   
    if (document.Allocation_Car.CHK_COUNT.value == 0){
        showToast('오더를 선택해 주십시요!!');
    }else{
        var Query=document.Allocation_Car.Query.value;			
        document.Allocation_Car.action='ord_kakao_sand_process_2.php?'+Query;						
        document.Allocation_Car.submit();
    }
}

function sms_sand_2(){   
    openWindow('sms_from_2.asp','ALLOCATIONCAR','scrollbars=no,width=400,height=400,left=0,top=0,reszie=no');
}


function sms_sand_3(){	   
    if (document.Allocation_Car.CHK_COUNT.value == 0){
        showToast('오더를 선택해 주십시요!!');
    }else{

        document.Allocation_Car.target='emptyFr';						
        document.Allocation_Car.action='pickup_sms_sand_process.asp';						
        document.Allocation_Car.submit();
    }
}	
            
function SandCheck(){
    with(document.sms_from){
        if(MMS_TXT.value == ''){
            showToast('전송할 내용을 입력해 주십시요!');
            MMS_TXT.focus();
            return;
        }else{
            let MMS_TXT=document.querySelector('#MMS_TXT').value;	   
            let CC=document.sms_from.CC.value; 
            let Query=document.querySelector('#Query').value;
            let $Allocation_page=document.querySelector('#Allocation_page').value;
            let resultData_length=grid_form_data(); // 그리드 데이터 가져오기
            document.querySelector('#CNT_NO').value=resultData_length;
            if (resultData_length == 0){
                showToast('오더가 선택되지 않았습니다!, 1개 이상의 오더를 선택하세요!!');
                return;
            }else{
                document.Allocation_Car.action='custom_mms_sand_process.php?CC='+CC+'&MMS_TXT='+MMS_TXT+'&'+Query+'&Allocation_page='+$Allocation_page;				
                document.Allocation_Car.submit();				
            }		
        }
    }
}


function textCounter_1(maxlimit){
    var frm=document.sms_from;	
    if (frm.MMS_TXT.value.length > maxlimit){
        showToast('제한된 글자 수를 초과 하였습니다!!');
        frm.MMS_TXT.value=frm.MMS_TXT.value.substring(0, maxlimit);
    }else{
        document.all.textlimit_1.innerText=frm.MMS_TXT.value.length;
    }
}	


function contents(a){
    if (a == 'ci'){
        aa="사업자번호: <%=A_CRN%>\n\n업체명: <%=A_CUST_NM%>\n\n주소:<%=A_BL_ADDR%>\n\n업태: <%=A_BIZTYPE%>\n\n종목: <%=A_BIZCOND%> \n\n대표자: <%=A_CEO%> \n\n메일주소 : taxbill@wonderlogis.co.kr"
        document.sms_from.MMS_TXT.value=aa;
    }else if(a == 'cin'){
        document.sms_from.MMS_TXT.value='';  
    }else{
        document.sms_from.MMS_TXT.value='';
    }
}	

function IIC_AT(){	  
     var ff='OPR';
     IIC_AT_F(ff);	 
}

function GpsLocationViewer(a){
    openWindow('trace_location.php?MOBILE_ID='+a,'gps_open','scrollbars=yes,width=710,height=710');
}
function ord_lms_view(a){
    openWindow('../order/order_lms_view.php?SO_NO='+a,'Order_lms_view','scrollbars=yes,width=500,height=700,left=0,top=0');
}	
    
function order_print_chk(){	
    let Query=document.Allocation_Car.Query.value;	
    let $Allocation_page=document.querySelector('#Allocation_page').value;
    let resultData_length=grid_form_data(); // 그리드 데이터 가져오기
    document.querySelector('#CNT_NO').value=resultData_length;
    if (resultData_length == 0){
        showToast('오더가 선택되지 않았습니다!, 1개 이상의 오더를 선택하세요');	
        return;
    }else{
        window.open('','order_print_chk','scrollbars=yes,width=800,height=900,left=0,top=0'); 
        document.Allocation_Car.action='../order/order_print_chk.asp';
        document.Allocation_Car.target='order_print_chk';        
        document.Allocation_Car.submit();  	
    }	
} 	

function order_receipt_print_chk(){
    let RCK=document.querySelector('#RCK').value
    let Ex_Ex='';
    if (RCK === '3'){
        Ex_Ex='Y';
    }else{
        Ex_Ex='N';	
    }

    let Query=document.Allocation_Car.Query.value;	
    let $Allocation_page=document.querySelector('#Allocation_page').value;
    let resultData_length=grid_form_data(); // 그리드 데이터 가져오기
    document.querySelector('#CNT_NO').value=resultData_length;
    if (resultData_length == 0){
        showToast('오더가 선택되지 않았습니다!, 1개 이상의 오더를 선택하세요');	
        return;
    }else{
        switch(RCK){
        case '1': 		
            window.open('','order_receipt_print_chk','scrollbars=yes,width=800,height=900,left=0,top=0'); 
            document.Allocation_Car.action='../order/stock_permit/order_receipt_print_chk.asp';		
            document.Allocation_Car.target='order_receipt_print_chk';        
            document.Allocation_Car.submit();     			
        break;		
        case '2':
        case '3':		
            window.open('','kwe_receipt_print_chk','scrollbars=yes,width=880,height=900,left=0,top=0'); 		
            document.Allocation_Car.action='../order/stock_permit/order_receipt_print_insert.php?Ex_Ex='+Ex_Ex;				
            document.Allocation_Car.target='kwe_receipt_print_chk';        
            document.Allocation_Car.submit();     			
        break;
        }		 	
    }	 
}

// ========================================
// allocationTableUtils.js
// 배차 테이블 공통 유틸리티 함수 모음
// ========================================

// ========== 다중 정렬 관련 함수 ==========

/**
* 정렬 버튼 클릭 처리
* @param {string} field - 정렬할 필드명
*/
function handleSortClick(field) {
console.log(`========== 정렬 클릭: ${field} ==========`);
console.log('클릭 전 sortState:', JSON.parse(JSON.stringify(sortState)));

let sortIndex = sortState.findIndex(s => s.field === field);

if (sortIndex === -1) {
    sortState.push({
        field: field,
        dir: 'asc',
        clickCount: 1
    });
    console.log(`? ${field} 오름차순 정렬 추가`);
} else {
    let currentSort = sortState[sortIndex];
    currentSort.clickCount++;
    
    if (currentSort.clickCount === 2) {
        currentSort.dir = 'desc';
        console.log(`? ${field} 내림차순으로 변경`);
    } else if (currentSort.clickCount >= MAX_CLICK_COUNT) {
        sortState.splice(sortIndex, 1);
        console.log(`? ${field} 정렬 제거`);
    }
}

console.log('클릭 후 sortState:', JSON.parse(JSON.stringify(sortState)));

applySorting();
updateSortUI();
saveSortState();
}

/**
* 정렬 UI 업데이트
*/
function updateSortUI() {
console.log('========== updateSortUI 시작 ==========');

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
            
            console.log(`? ${field}: ${arrow}${sortIndex + 1} 표시`);
        }
    }
});
}

// ========== Formatter 함수들 ==========

function OrderViewFormatter(cell) {
const SO_NO = cell.getRow().getData().SO_NO;
const SO_MODE_H = cell.getRow().getData().SO_MODE_H;
return `<div class='OrderView' style='position: relative;top: -2px;'><a href="javascript:ord_view('${SO_NO}','${SO_MODE_H}','car');"><img src='https://www.wonderlogis.com/ozoms_v14/web_order/images/img_open.gif' onclick=''  border='0' width='25' height='25'></a></div>`;
}

function Custom_Carno_NumFormatter(cell) {
const A_GPS_ID = cell.getRow().getData().A_GPS_ID;
if (A_GPS_ID ==''){
    return "<div style='position: relative;top: 1px;left: -3px;'>" + cell.getValue() + "</div>";
}else{
    return `<div class='custom_carno_num-cell'>${cell.getValue()}</div>`;
}
}

function Section_Carno_NumFormatter(cell) {
const B_GPS_ID = cell.getRow().getData().B_GPS_ID;
if (B_GPS_ID ==''){
    return "<div style='position: relative;top: 1px;left: -3px;'>" + cell.getValue() + "</div>";
}else{
    return `<div class='section_carno_num-cell'>${cell.getValue()}</div>`;
}
}

function LmsViewFormatter(cell) {
const SO_NO = cell.getRow().getData().SO_NO;
return `<div class='OrderView' style='position: relative;top: -3px;'><a href="javascript:ord_lms_view('${SO_NO}');"><img src='https://www.wonderlogis.com/ozoms_v14/admin/images/btn_cccm_sms_order.gif'  border='0' width='25' height='25'></a></div>`;
}

function CarOperateViewFormatter(cell) {
const SO_NO = cell.getRow().getData().SO_NO;
const EM_UNLOAD_REQ = cell.getRow().getData().EM_UNLOAD_REQ;
const LOAD_REQ_HM = cell.getRow().getData().LOAD_REQ_HM;
const LOAD_REQ_DT = cell.getRow().getData().LOAD_REQ_DT;
const LOAD_HM = cell.getRow().getData().LOAD_HM;
let ICON_CAR;
let C_TIME;
let T_DATE= getCurrentDate();

if(EM_UNLOAD_REQ == 'Y'){
    ICON_CAR="car_icon4.gif";
}else{
    ICON_CAR="car_icon3.gif";
}
const now = new Date();
const hours = String(now.getHours()).padStart(2, '0');
const minutes = String(now.getMinutes()).padStart(2, '0');
const currentTime = hours + minutes;

if (LOAD_REQ_HM !=''){
    C_TIME = parseInt(LOAD_REQ_HM) - parseInt(currentTime);
}

if(LOAD_REQ_HM =='0000'){
    if(EM_UNLOAD_REQ == 'Y'){
        ICON_CAR="car_icon4.gif";
    }else{
        ICON_CAR="car_icon3.gif"
    }
}else{
    if ((LOAD_REQ_DT == T_DATE) || (C_TIME >= 0)){
        if(LOAD_HM ==''){
            if(EM_UNLOAD_REQ == 'Y'){
                ICON_CAR="car_icon6.gif";
            }else{
                ICON_CAR="car_icon5.gif"
            }
        }else{
            if(EM_UNLOAD_REQ == 'Y'){
                ICON_CAR="car_icon4.gif";
            }else{
                ICON_CAR="car_icon3.gif"
            }
        }
    }
}

return `<div class='OrderView' style='position: relative;top: -2px;'><a href="javascript:car_hor_reg('${SO_NO}');"><img src='https://www.wonderlogis.com/ozoms_v14/admin/images/${ICON_CAR}'  border='0' width='22' height='22'></a></div>`;
}

function LoadHmFormatter(cell) {
const LOAD_HM = cell.getRow().getData().LOAD_HM;
const LOAD_IMG = cell.getRow().getData().LOAD_IMG;
const LOAD_F_IMG = cell.getRow().getData().LOAD_F_IMG;
let LOAD_BG;
if(LOAD_IMG != '0'){
    LOAD_BG='#CCFFCC';
}else{
    if(LOAD_F_IMG == 'Y'){
        LOAD_BG="#FF0040"
    }else{
        LOAD_BG=""
    }
}
return `<div class='OrderView' style='position: relative;top: -3px;left: -3px;width:32px;height:23px;background-color:${LOAD_BG}'>${LOAD_HM}</div>`;
}

function UnLoadHmFormatter(cell) {
const UNLOAD_HM = cell.getRow().getData().UNLOAD_HM;
const UNLOAD_IMG = cell.getRow().getData().UNLOAD_IMG;
const UNLOAD_F_IMG = cell.getRow().getData().UNLOAD_F_IMG;
let UNLOAD_BG;
if(UNLOAD_IMG !='0'){
    UNLOAD_BG='#FFCC66';
}else{
    if(UNLOAD_F_IMG =='Y'){
        UNLOAD_BG="#FF0040"
    }else{
        UNLOAD_BG=""
    }
}
return `<div class='OrderView' style='position: relative;top: -3px;left: -3px;width:32px;height:23px;background-color:${UNLOAD_BG}'>${UNLOAD_HM}</div>`;
}

function SoPtFormatter(cell) {
const TOSS_ORDER = cell.getRow().getData().TOSS_ORDER;
let SO_PT;
let SO_PT_BG;
if(TOSS_ORDER =='606-86-45250'){
    SO_PT_BG='#F5D0A9';
}else if(TOSS_ORDER =='366-88-02852'){
    SO_PT_BG='#A9F5A9';
}else if(TOSS_ORDER =='609-86-18830'){
    SO_PT_BG='#08088A';
}else if(TOSS_ORDER =='206-88-03127'){
    SO_PT_BG='#E3CEF6';
}else{
    SO_PT_BG='';
}

if(SO_PT_BG != ''){
    SO_PT='T';
}else{
    SO_PT = cell.getRow().getData().SO_PT;
}
return `<div class='OrderView' style='position: relative;top: -3px;left: -3px;width:22px;height:23px;background-color:${SO_PT_BG}'>${SO_PT}</div>`;
}

function ShipNmFormatter(cell) {
const SHIP_NM = cell.getRow().getData().SHIP_NM;
const CLAIM = cell.getRow().getData().CLAIM;
let CLAIM_BG;
if(CLAIM !=''){
    CLAIM_BG='#99ccff';
}else{
    CLAIM_BG=""
}
return `<div class='OrderView_Start' style='position: relative;top: -3px;left: -3px;width:100%;height:23px;background-color:${CLAIM_BG}'>${SHIP_NM}</div>`;
}

function AlocTypeFormatter(cell) {
const ALOC_TYPE = cell.getRow().getData().ALOC_TYPE;
let ALOC_TYPE_BG;
if(ALOC_TYPE =='일산집하'){
    ALOC_TYPE_BG='#F8E0E6';
}else if(ALOC_TYPE =='안성집하'){
    ALOC_TYPE_BG='#A9F5BC';
}else{
    ALOC_TYPE_BG=""
}
return `<div class='OrderView' style='position: relative;top: -1px;left: -3px;width:100%;height:23px;background-color:${ALOC_TYPE_BG}'>${ALOC_TYPE}</div>`;
}

function Hbl_No_Formatter(cell) {
const SO_NO = cell.getRow().getData().SO_NO;
const HBL_NO = cell.getRow().getData().HBL_NO;
const LOAD_REQ_DT = cell.getRow().getData().LOAD_REQ_DT;
return `<div class='OrderView_Start' style='position: relative;top: -2px;'><a href="javascript:UNIPASS('${SO_NO}','H','${HBL_NO}','${LOAD_REQ_DT}');">${HBL_NO}</a></div>`;
}

// ========== 체크박스 관련 함수 ==========

function updateCheckedCount() {
const checkboxes = document.querySelectorAll(".row-checkbox");
let checkedCount = 0;
checkboxes.forEach(checkbox => {
    if (checkbox.checked) {
        checkedCount++;
    }
});
document.getElementById('cnt').value = checkedCount;
}

function updateHeaderCheckboxState() {
var headerCheckbox = document.getElementById("header-select-all");
if (headerCheckbox) {
    var selectedRows = table.getSelectedRows();
    
    if (isFiltered) {
        var visibleRows = table.getRows("visible").filter(function(row) {
            return !row.getData().isEmpty;
        });
        
        if (visibleRows.length > 0) {
            var visibleSelectedCount = selectedRows.filter(function(selectedRow) {
                return visibleRows.includes(selectedRow);
            }).length;
            
            headerCheckbox.checked = (visibleSelectedCount === visibleRows.length);
            
            if (visibleSelectedCount > 0 && visibleSelectedCount < visibleRows.length) {
                headerCheckbox.indeterminate = true;
            } else {
                headerCheckbox.indeterminate = false;
            }
        } else {
            headerCheckbox.checked = false;
            headerCheckbox.indeterminate = false;
        }
    } else {
        var allRows = table.getRows().filter(function(row) {
            return !row.getData().isEmpty;
        });
        
        if (allRows.length > 0) {
            var validSelectedCount = selectedRows.filter(function(selectedRow) {
                return !selectedRow.getData().isEmpty;
            }).length;
            
            headerCheckbox.checked = (validSelectedCount === allRows.length);
            
            if (validSelectedCount > 0 && validSelectedCount < allRows.length) {
                headerCheckbox.indeterminate = true;
            } else {
                headerCheckbox.indeterminate = false;
            }
        } else {
            headerCheckbox.checked = false;
            headerCheckbox.indeterminate = false;
        }
    }
}
}

// ========== 검색 관련 함수 ==========

function addHighlightStyles() {
if (!document.getElementById('search-highlight-style')) {
    var style = document.createElement('style');
    style.id = 'search-highlight-style';
    style.textContent = `
        .search-highlight {
            background-color: #ffff00 !important;
            color: #000 !important;
            font-weight: bold !important;
            padding: 1px 2px !important;
            border-radius: 2px !important;
        }
        .search-current {
            background-color: #ff6600 !important;
            color: #fff !important;
        }
    `;
    document.head.appendChild(style);
}
}

function highlightText(text, searchTerm, isCurrent = false) {
if (!text || !searchTerm) return text;

var regex = new RegExp(`(${searchTerm.replace(/[.*+?^${}()|[\]\\]/g, '\\$&')})`, 'gi');
var highlightClass = isCurrent ? 'search-highlight search-current' : 'search-highlight';
return text.replace(regex, `<span class="${highlightClass}">$1</span>`);
}

function clearHighlights() {
var highlightedElements = document.querySelectorAll('.search-highlight');
highlightedElements.forEach(function(element) {
    var parent = element.parentNode;
    parent.replaceChild(document.createTextNode(element.textContent), element);
    parent.normalize();
});
searchResults = [];
searchIndex = 0;
currentSearchTerm = '';
}

function applyCellHighlight(cell, searchTerm, isCurrent = false) {
var cellElement = cell.getElement();
var cellValue = cell.getValue();

if (cellValue && cellValue.toString().toLowerCase().includes(searchTerm.toLowerCase())) {
    var highlightedContent = highlightText(cellValue.toString(), searchTerm, isCurrent);
    cellElement.innerHTML = highlightedContent;
    return true;
}
return false;
}

function performSearch(searchTerm) {
if (!searchTerm || searchTerm.trim() === '') {
    clearHighlights();
    return;
}

clearHighlights();
addHighlightStyles();

currentSearchTerm = searchTerm;
searchResults = [];

var allRows = table.getRows();
var searchFields = [
    "OP_NM", "SO_MODE_H", "IO_TYPE", "CUSTOM_CARNO_NUM", "TRAN_NM_H", 
    "CAR_TEL_H", "G_CAR_NO", "ALOC_TYPE", "ACT_SHIP_A_NM", "ACT_SHIP_TEL",
    "ACT_SHIP_PIC_NM", "SHIP_NM", "LOAD_NM", "LOAD_TEL", "LOAD_PIC_NM",
    "LOAD_AREA", "FDS_NM", "UNLOAD_NM", "UNLOAD_TEL", "UNLOAD_PIC_NM",
    "BILL_NM", "HBL_NO", "LOAD_CY", "ITEM_NM", "GOOD_NM", "CNTR_NO", 
    "SEAL_NO", "ORD_ETC","U_S_AMT","U_B_AMT","K_S_AMT","K_B_AMT","T_S_AMT",
    "T_B_AMT"
];

allRows.forEach(function(row, rowIndex) {
    var rowData = row.getData();
    var foundInRow = false;
    
    searchFields.forEach(function(field) {
        var cell = row.getCell(field);
        var cellValue = rowData[field];
        
        if (cellValue && cellValue.toString().toLowerCase().includes(searchTerm.toLowerCase())) {
            searchResults.push({
                row: row,
                cell: cell,
                field: field,
                value: cellValue.toString()
            });
            foundInRow = true;
        }
    });
});

if (searchResults.length > 0) {
    searchIndex = 0;
    goToSearchResult(0);
    console.log(`검색 완료: ${searchResults.length}개 결과 발견`);
} else {
    alert("검색 결과를 찾을 수 없습니다.");
}
}

function goToSearchResult(index) {
if (searchResults.length === 0) return;

document.querySelectorAll('.search-current').forEach(function(element) {
    element.classList.remove('search-current');
});

searchResults.forEach(function(result, i) {
    applyCellHighlight(result.cell, currentSearchTerm, i === index);
});

var currentResult = searchResults[index];
table.scrollToRow(currentResult.row, "center", true);

setTimeout(function() {
    var cellElement = currentResult.cell.getElement();
    if (cellElement) {
        var tableContainer = cellElement.closest('.tabulator');
        var tableHolder = tableContainer.querySelector('.tabulator-tableholder');
        
        if (tableHolder) {
            var cellRect = cellElement.getBoundingClientRect();
            var containerRect = tableHolder.getBoundingClientRect();
            var currentScrollLeft = tableHolder.scrollLeft;
            var cellLeft = cellRect.left - containerRect.left + currentScrollLeft;
            var cellRight = cellLeft + cellRect.width;
            var containerWidth = containerRect.width;
            
            if (cellLeft < currentScrollLeft) {
                tableHolder.scrollLeft = cellLeft - 50;
            } else if (cellRight > currentScrollLeft + containerWidth) {
                tableHolder.scrollLeft = cellRight - containerWidth + 50;
            }
        }
    }
}, 100);

console.log(`검색 결과 ${index + 1}/${searchResults.length}: ${currentResult.field} = ${currentResult.value}`);
}

function nextSearchResult() {
if (searchResults.length === 0) return;
searchIndex = (searchIndex + 1) % searchResults.length;
goToSearchResult(searchIndex);
}

function prevSearchResult() {
if (searchResults.length === 0) return;
searchIndex = (searchIndex - 1 + searchResults.length) % searchResults.length;
goToSearchResult(searchIndex);
}

// ========== 컬럼 관련 함수 ==========

function toggleColumn(field, checkbox) {
try {
    const column = table.getColumn(field);
    if (column) {
        if (column.isVisible()) {
            column.hide();
            checkbox.checked = false;
        } else {
            column.show();
            checkbox.checked = true;
        }
        saveColumnVisibility();
    }
} catch (e) {
    console.log(`컬럼 ${field}를 토글할 수 없습니다:`, e);
}
}

// ========== CSS 스타일 초기화 ==========
(function initStyles() {
if (!document.getElementById('sort-number-style')) {
    let style = document.createElement('style');
    style.id = 'sort-number-style';
    style.textContent = `
        .custom-sort-button {
            cursor: pointer;
            display: inline-block;
            padding: 0px 3px;
            margin-left: 2px;
            user-select: none;
            font-size: 10px;
            color: #666;
        }
        .custom-sort-button:hover {
            background-color: rgba(0, 0, 0, 0.1);
            border-radius: 2px;
            color: #000;
        }
    `;
    document.head.appendChild(style);
}
})();
