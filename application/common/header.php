<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// CodeIgniter 3: url_helper 로드
$CI =& get_instance();
$CI->load->helper('url');
$CI->load->model('Common_model');

// 현재 카테고리 확인 (파라미터로 전달됨)
$current_category = isset($category) ? $category : '오더등록';

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
    <title>OMS - <?php echo $current_category; ?></title>
    <link rel="stylesheet" href="<?php echo base_url('css/layout.css'); ?>">
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
						<div class="menu-item-wrapper">
							<div class="menu-item-bg"></div>
							<a href="<?php echo site_url($menu->OPT_ITEM2); ?>"
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
