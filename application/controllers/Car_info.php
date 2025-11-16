<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Car_info extends CI_Controller {

	public function index()
	{
		$data['category'] = '차량정보';
		$this->load->view('Car_Info/car_info_view', $data);
	}

	/**
	 * 동적 view 로딩
	 * URL 패턴: /controller/folder/file
	 */
	public function _remap($method, $params = array())
	{
		$data['category'] = '차량정보';
		$data['folder_name'] = 'Car_Info';
		$data['current_file'] = $method;

		if ($method === 'index') {
			$this->load->view('Car_Info/car_info_view', $data);
		} else {
			if (!empty($params[0])) {
				$folder = $method;
				$file = $params[0];
				$data['folder_name'] = $folder;
				$data['current_file'] = $file;
				$view_file = $folder . '/' . $file . '.php';
			} else {
				$view_file = 'Car_Info/' . $method . '.php';
			}

			if (file_exists(APPPATH . 'views/' . $view_file)) {
				$this->load->view(str_replace('.php', '', $view_file), $data);
			} else {
				show_404();
			}
		}
	}
}
