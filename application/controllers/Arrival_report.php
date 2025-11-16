<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Arrival_report extends CI_Controller {

	public function index()
	{
		$data['category'] = '도착보고';
		$this->load->view('Arrival/arrival_report_view', $data);
	}

	/**
	 * 동적 view 로딩
	 * URL 패턴: /controller/folder/file
	 */
	public function _remap($method, $params = array())
	{
		$data['category'] = '도착보고';
		$data['folder_name'] = 'Arrival';
		$data['current_file'] = $method;

		if ($method === 'index') {
			$this->load->view('Arrival/arrival_report_view', $data);
		} else {
			// $method가 폴더명, $params[0]이 파일명
			if (!empty($params[0])) {
				$folder = $method; // OPT_ITEM1 (폴더명)
				$file = $params[0]; // OPT_ITEM2 (파일명)
				$data['folder_name'] = $folder;
				$data['current_file'] = $file;
				$view_file = $folder . '/' . $file . '.php';
			} else {
				// 기존 방식 호환
				$view_file = 'Arrival/' . $method . '.php';
			}

			if (file_exists(APPPATH . 'views/' . $view_file)) {
				$this->load->view(str_replace('.php', '', $view_file), $data);
			} else {
				show_404();
			}
		}
	}
}
