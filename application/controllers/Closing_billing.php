<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Closing_billing extends CI_Controller {

	public function index()
	{
		$data['category'] = '마감청구';
		$this->load->view('Closing/closing_billing_view', $data);
	}

	/**
	 * 동적 view 로딩
	 * URL 패턴: /controller/folder/file
	 */
	public function _remap($method, $params = array())
	{
		$data['category'] = '마감청구';
		$data['folder_name'] = 'Closing';
		$data['current_file'] = $method;

		if ($method === 'index') {
			$this->load->view('Closing/closing_billing_view', $data);
		} else {
			if (!empty($params[0])) {
				$folder = $method;
				$file = $params[0];
				$data['folder_name'] = $folder;
				$data['current_file'] = $file;
				$view_file = $folder . '/' . $file . '.php';
			} else {
				$view_file = 'Closing/' . $method . '.php';
			}

			if (file_exists(APPPATH . 'views/' . $view_file)) {
				$this->load->view(str_replace('.php', '', $view_file), $data);
			} else {
				show_404();
			}
		}
	}
}
