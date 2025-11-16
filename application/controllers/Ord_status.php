<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Ord_status extends CI_Controller {

	public function index()
	{
		$data['category'] = '오더등록';
		$this->load->view('Orders/ord_status_view', $data);
	}

	/**
	 * 동적 view 로딩
	 * URL 세그먼트를 기반으로 해당하는 view 파일 로드
	 * URL 패턴: /controller/folder/file
	 */
	public function _remap($method, $params = array())
	{
		$data['category'] = '오더등록';
		$data['folder_name'] = 'Orders';
		$data['current_file'] = $method;

		// 메서드명이 index인 경우 기본 view
		if ($method === 'index') {
			$this->load->view('Orders/ord_status_view', $data);
		} else {
			// $method가 폴더명, $params[0]이 파일명
			// 폴더명과 파일명이 모두 있는 경우
			if (!empty($params[0])) {
				$folder = $method; // OPT_ITEM1 (폴더명)
				$file = $params[0]; // OPT_ITEM2 (파일명)
				$data['folder_name'] = $folder;
				$data['current_file'] = $file;
				$view_file = $folder . '/' . $file . '.php';
			} else {
				// 폴더명만 있는 경우 (기존 방식 호환)
				$view_file = 'Orders/' . $method . '.php';
			}

			// 파일이 존재하는지 확인
			if (file_exists(APPPATH . 'views/' . $view_file)) {
				$this->load->view(str_replace('.php', '', $view_file), $data);
			} else {
				show_404();
			}
		}
	}
}
