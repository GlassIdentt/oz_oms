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
    return;
}
?>
<!-- 하단 탭 버튼들 -->
<?php foreach ($submenu_list as $index => $menu): ?>
<button class="btn <?php echo ($current_file == $menu->OPT_ITEM2) ? 'state-active' : 'state-inactive'; ?>">
    <span class="btn-icon"></span>
    <a href="<?php echo site_url($menu->OPT_ITEM2); ?>" style="color: inherit; text-decoration: none;">
        <?php echo htmlspecialchars($menu->CD_NM); ?>
    </a>
</button>
<?php endforeach; ?>
