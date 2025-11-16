<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Common_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    /**
     * 카테고리별 코드 목록 조회
     * @param string $grp_cd 그룹 코드
     * @param string $opt_item1 옵션 아이템1 (기본값: '')
     * @return array
     */
    public function get_code_list_by_category($grp_cd, $opt_item1 = '')
    {
        $sql = "EXEC [dbo].[Proc_Com_Code_List_Category] @GRP_CD = ?, @OPT_ITEM1 = ?";
        $query = $this->db->query($sql, array($grp_cd, $opt_item1));
        return $query->result();
    }

    /**
     * 상단 메뉴 목록 조회 (T40)
     * @return array
     */
    public function get_top_menu_list()
    {
        return $this->get_code_list_by_category('T40', '');
    }

    /**
     * 하단 탭 버튼 목록 조회
     * @param string $grp_cd 그룹 코드
     * @param string $opt_item1 옵션 아이템1 (기본값: '')
     * @return array
     */
    public function get_bottom_menu_list($grp_cd, $opt_item1 = '')
    {
        return $this->get_code_list_by_category($grp_cd, $opt_item1);
    }
}
