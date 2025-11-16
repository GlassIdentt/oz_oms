<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * 서브 메뉴 표시
 * @param string $folder_name 폴더명 (OPT_ITEM1)
 * @param string $current_file 현재 활성화된 파일명
 */

// CodeIgniter 인스턴스 가져오기
$CI =& get_instance();
$CI->load->model('Common_model');

// 폴더명이 전달되지 않으면 종료
if (!isset($folder_name) || empty($folder_name)) {
    return;
}

// 현재 파일명 확인
$current_file = isset($current_file) ? $current_file : '';

// URL 파라미터 확인
$allocation_page = $CI->input->get('Allocation_page');
$section_allocation_page = $CI->input->get('Section_Allocation_page');

// T41 그룹에서 해당 폴더의 서브 메뉴 조회
$submenu_list = array();
if ($CI->db && $CI->db->conn_id) {
    try {
        $result = $CI->Common_model->get_bottom_menu_list('T41', $folder_name);
        if (!empty($result)) {
            $submenu_list = $result;
        }
    } catch (Exception $e) {
        log_message('error', 'Failed to load submenu: ' . $e->getMessage());
    }
}

// 서브 메뉴가 없으면 종료
if (empty($submenu_list)) {
    echo "<!-- 디버그: submenu_list가 비어있습니다. folder_name = {$folder_name} -->";
    return;
}
?>
<!-- 하단 탭 버튼들 -->
<?php foreach ($submenu_list as $index => $menu): ?>
<?php
    // OPT_ITEM2가 있으면 사용, 없으면 CD_NM을 URL로 변환
    $link_url = !empty($menu->OPT_ITEM2) ? $menu->OPT_ITEM2 : strtolower(str_replace(' ', '_', $menu->CD_NM));

    // 배차관리 파라미터 처리
    $query_params = '';
    $menu_allocation_page = '';
    $menu_section_allocation_page = '';

    // 종합배차현황 시리즈 (Allocation_page 사용)
    if ($menu->CD_NM == '종합배차현황') {
        $query_params = '?Allocation_page=ch_1';
        $menu_allocation_page = 'ch_1';
    } elseif ($menu->CD_NM == '종합배차현황-2') {
        $query_params = '?Allocation_page=ch_2';
        $menu_allocation_page = 'ch_2';
    } elseif ($menu->CD_NM == '종합배차현황-3') {
        $query_params = '?Allocation_page=ch_3';
        $menu_allocation_page = 'ch_3';
    }
    // 구간배차 시리즈 (Section_Allocation_page 사용)
    elseif ($menu->CD_NM == 'LCL구간배차') {
        $query_params = '?Section_Allocation_page=ch_1';
        $menu_section_allocation_page = 'ch_1';
    } elseif ($menu->CD_NM == 'AIR구간배차') {
        $query_params = '?Section_Allocation_page=ch_2';
        $menu_section_allocation_page = 'ch_2';
    } elseif ($menu->CD_NM == 'FCL구간배차') {
        $query_params = '?Section_Allocation_page=ch_3';
        $menu_section_allocation_page = 'ch_3';
    }

    // 활성화 상태 판단
    if (!empty($menu_allocation_page)) {
        // 종합배차현황 시리즈: Allocation_page 파라미터로 판단
        $is_active = ($allocation_page == $menu_allocation_page);
    } elseif (!empty($menu_section_allocation_page)) {
        // 구간배차 시리즈: Section_Allocation_page 파라미터로 판단
        $is_active = ($section_allocation_page == $menu_section_allocation_page);
    } else {
        // 일반 메뉴: 파일명으로 판단
        $is_active = ($current_file == $menu->OPT_ITEM2);
    }

    $full_url = base_url($link_url) . $query_params;

    // 디버그 정보
    $debug_info = "CD_NM: {$menu->CD_NM}, OPT_ITEM2: {$menu->OPT_ITEM2}, URL: {$full_url}";
?>
<!-- 디버그: <?php echo $debug_info; ?> -->
<a href="<?php echo $full_url; ?>" class="btn <?php echo $is_active ? 'state-active' : 'state-inactive'; ?>" onclick="console.log('클릭됨: <?php echo $full_url; ?>'); return true;"><span class="btn-icon"></span><span><?php echo htmlspecialchars($menu->CD_NM); ?></span></a>
<?php endforeach; ?>
