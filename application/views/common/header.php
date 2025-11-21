<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// CodeIgniter 3: url_helper 로드
$CI =& get_instance();
$CI->load->helper('url');
$CI->load->model('Common_model');

// 현재 카테고리 확인 (파라미터로 전달됨)
$current_category = isset($category) ? $category : '오더등록';

// 서브 카테고리 이름 가져오기
$submenu_category_name = $current_category; // 기본값은 상위 카테고리

// folder_name과 current_file이 전달되지 않았으면 URL에서 추출 시도
if (!isset($folder_name) || empty($folder_name)) {
    $segments = $CI->uri->segment_array();
    if (!empty($segments)) {
        // 컨트롤러 이름을 folder_name으로 사용
        $controller_name = $CI->router->class;
        // 컨트롤러 이름을 폴더명으로 변환 (예: Allocation_car -> Allocation_Car)
        $folder_name = str_replace('_', '_', ucwords(str_replace('_', ' ', $controller_name)));
        
        // 메서드명을 current_file로 사용
        $current_file = $CI->router->method;
        if ($current_file == 'index' || $current_file == '_remap') {
            // URL 세그먼트에서 파일명 추출
            if (!empty($segments[2])) {
                $current_file = $segments[2];
            }
        }
    }
}

if (isset($folder_name) && !empty($folder_name)) {
    // 서브 메뉴 목록 조회
    $submenu_list = array();
    if ($CI->db && $CI->db->conn_id) {
        try {
            $result = $CI->Common_model->get_bottom_menu_list('T41', $folder_name);
            if (!empty($result)) {
                $submenu_list = $result;
            }
        } catch (Exception $e) {
            log_message('error', 'Failed to load submenu for title: ' . $e->getMessage());
        }
    }
    
    // 현재 파일명 확인
    $current_file = isset($current_file) ? $current_file : '';
    
    // URL 파라미터 확인
    $allocation_page = $CI->input->get('Allocation_page');
    $section_allocation_page = $CI->input->get('Section_Allocation_page');
    
    // 활성화된 서브 메뉴 찾기
    if (!empty($submenu_list)) {
        foreach ($submenu_list as $menu) {
            $menu_allocation_page = '';
            $menu_section_allocation_page = '';
            
            // 종합배차현황 시리즈
            if ($menu->CD_NM == '종합배차현황') {
                $menu_allocation_page = 'ch_1';
            } elseif ($menu->CD_NM == '종합배차현황-2') {
                $menu_allocation_page = 'ch_2';
            } elseif ($menu->CD_NM == '종합배차현황-3') {
                $menu_allocation_page = 'ch_3';
            }
            // 구간배차 시리즈
            elseif ($menu->CD_NM == 'LCL구간배차') {
                $menu_section_allocation_page = 'Ch_1';
            } elseif ($menu->CD_NM == 'AIR구간배차') {
                $menu_section_allocation_page = 'Ch_2';
            } elseif ($menu->CD_NM == 'FCL구간배차') {
                $menu_section_allocation_page = 'Ch_3';
            }
            
            // 활성화 상태 판단
            $is_active = false;
            if (!empty($menu_allocation_page)) {
                $is_active = ($allocation_page == $menu_allocation_page);
            } elseif (!empty($menu_section_allocation_page)) {
                $is_active = ($section_allocation_page == $menu_section_allocation_page);
            } else {
                $is_active = ($current_file == $menu->OPT_ITEM2);
            }
            
            if ($is_active) {
                $submenu_category_name = $menu->CD_NM;
                break;
            }
        }
    }
}

// 상단 메뉴 목록 조회
$top_menu_list = array();
if ($CI->db && $CI->db->conn_id) {
    try {
        $result = $CI->Common_model->get_top_menu_list();
        if (!empty($result)) {
            $top_menu_list = $result;
        }
    } catch (Exception $e) {
        // 데이터베이스 쿼리 실패 시 로그 기록
        log_message('error', 'Failed to load menu: ' . $e->getMessage());
    }
}

// 메뉴가 비어있으면 기본 메뉴 사용
if (empty($top_menu_list)) {
    $top_menu_list = array(
        (object)array('CD_NM' => '오더등록', 'OPT_ITEM1' => 'Orders', 'OPT_ITEM2' => 'order_status_list'),
        (object)array('CD_NM' => '배차관리', 'OPT_ITEM1' => 'Allocation_Car', 'OPT_ITEM2' => 'allocation_car_list'),
        (object)array('CD_NM' => '도착보고', 'OPT_ITEM1' => 'Arrival', 'OPT_ITEM2' => 'arrival_status_list'),
        (object)array('CD_NM' => '마감청구', 'OPT_ITEM1' => 'Closing', 'OPT_ITEM2' => 'closing_billing_list'),
        (object)array('CD_NM' => '조회출력', 'OPT_ITEM1' => 'Inquiry', 'OPT_ITEM2' => 'period_inquiry_list'),
        (object)array('CD_NM' => '업체정보', 'OPT_ITEM1' => 'Company', 'OPT_ITEM2' => 'company_info_list'),
        (object)array('CD_NM' => '차량정보', 'OPT_ITEM1' => 'Car_Info', 'OPT_ITEM2' => 'car_info_list'),
        (object)array('CD_NM' => '영업관리', 'OPT_ITEM1' => 'Sales_Management', 'OPT_ITEM2' => 'sales_department_order_list'),
        (object)array('CD_NM' => '일반관리', 'OPT_ITEM1' => 'General_Management', 'OPT_ITEM2' => 'common_code_mag_list'),
        (object)array('CD_NM' => '결산', 'OPT_ITEM1' => 'Settle_Accounts', 'OPT_ITEM2' => 'daily_settlement')
    );
}
?><!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>OMS - <?php echo $submenu_category_name; ?></title>
    <link rel="stylesheet" as="style" crossorigin href="https://cdn.jsdelivr.net/gh/orioncactus/pretendard@v1.3.9/dist/web/static/pretendard.min.css" />
    <link rel="stylesheet" href="<?php echo base_url('css/layout.css'); ?>?version=<?php echo substr(md5(mt_rand()), 0, 10); ?>">
    <link rel="stylesheet" href="<?php echo base_url('css/tabulator.css'); ?>?version=<?php echo substr(md5(mt_rand()), 0, 10); ?>">
    <link href="https://unpkg.com/tabulator-tables@6.3.1/dist/css/tabulator.min.css" rel="stylesheet">
    <script src="https://unpkg.com/tabulator-tables@6.3.1/dist/js/tabulator.min.js"></script>	    
    <!-- jQuery UI Datepicker -->
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/ui-lightness/jquery-ui.css">
    <link rel="stylesheet" href="<?php echo base_url('css/datepicker.css'); ?>?version=<?php echo substr(md5(mt_rand()), 0, 10); ?>">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
    <script src="https://code.jquery.com/ui/1.13.2/i18n/datepicker-ko.min.js"></script>
    <script src="<?php echo base_url('js/common.js'); ?>?version=<?php echo substr(md5(mt_rand()), 0, 10); ?>"></script>
    <?php if (isset($folder_name) && $folder_name == 'Allocation_Car'): ?>
    <script type="text/javascript" src="https://oss.sheetjs.com/sheetjs/xlsx.full.min.js"></script>
    <script src="<?php echo base_url('js/Allocation_Tabulator.js'); ?>?version=<?php echo substr(md5(mt_rand()), 0, 10); ?>"></script>
    <script src="<?php echo base_url('js/gird_drag_select.js'); ?>?version=<?php echo substr(md5(mt_rand()), 0, 10); ?>"></script>
    <?php endif; ?>
</head>
<body>
    <div class="layout-container">
        <div class="top_area">
            <div class="empty"></div>
            <div class="logo">
                <div class="logo_top"></div>
                <div class="logo_middle"></div>
                <div class="logo_bottom"></div>
            </div>
            <div class="TopMenu">
                <div class="TopMenu_Empty"></div>
                <div class="TopMenu_menu">
					<!-- 상단 메뉴 버튼 그룹 -->
					<div id="menuContainer">
						<?php foreach ($top_menu_list as $menu): ?>
						<?php
							// 배차관리 메뉴에 기본 파라미터 추가
							$menu_url = site_url($menu->OPT_ITEM2);
							if ($menu->CD_NM == '배차관리') {
								$menu_url .= '?Allocation_page=ch_1';
							}
						?>
						<div class="menu-item-wrapper">
							<div class="menu-item-bg"></div>
							<a href="<?php echo $menu_url; ?>"
							   class="menu-item-btn <?php echo ($current_category == $menu->CD_NM) ? 'menu-item-selected' : ''; ?>"
							   data-name="<?php echo $menu->CD_NM; ?>"><?php echo $menu->CD_NM; ?></a>
						</div>
						<?php endforeach; ?>
					</div>
					<!-- 우측 영역 -->
					<div class="TopMenu_menu_right">
						<div class="TopMenu_menu_right_inner"></div>
					</div>
				</div>
                <div class="TopMenu_bottom_Section_Menu">
					<div id="buttonContainer">
						<!-- 하단 탭 버튼들은 각 카테고리별 뷰에서 정의됨 -->
