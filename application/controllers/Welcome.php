<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/userguide3/general/urls.html
	 */
	public function index()
	{
		// 새로운 독립 페이지 구조로 리다이렉트
		$this->load->helper('url');
		redirect('Ord_status');
	}

	/**
	 * 카테고리별 뷰 파일을 로드합니다 (AJAX 요청 처리)
	 */
	public function category()
	{
		// 디버깅: 메서드가 호출되었는지 확인
		log_message('info', 'Welcome::category() 메서드가 호출되었습니다.');

		// 카테고리와 파일명 매핑
		$category_map = array(
			'오더등록' => 'ord_status_list',
			'배차관리' => 'allocation_car_list',
			'도착보고' => 'arrival_report_list',
			'마감청구' => 'closing_billing_list',
			'조회출력' => 'inquiry_output_list',
			'업체정보' => 'company_info_list',
			'차량정보' => 'car_info_list',
			'영업관리' => 'sales_management_list',
			'일반관리' => 'general_management_list',
			'결산' => 'settlement_list'
		);

		// POST 또는 GET 요청 모두 처리
		$category = $this->input->post('category');
		if (empty($category)) {
			$category = $this->input->get('category');
		}
		
		if (empty($category)) {
			echo '<div style="padding: 20px; color: red;"><p>카테고리가 전달되지 않았습니다.</p></div>';
			return;
		}
		
		if (isset($category_map[$category])) {
			$view_file = $category_map[$category];
			
			// 뷰 파일 존재 여부 확인
			if (file_exists(APPPATH . 'views/' . $view_file . '.php')) {
				$this->load->view($view_file);
			} else {
				echo '<div style="padding: 20px; color: red;"><p>뷰 파일을 찾을 수 없습니다: ' . $view_file . '.php</p></div>';
			}
		} else {
			echo '<div style="padding: 20px; color: red;"><p>카테고리를 찾을 수 없습니다: ' . htmlspecialchars($category) . '</p></div>';
		}
	}
	
	/**
	 * 테스트용 메서드 - 메서드가 호출되는지 확인
	 */
	public function test()
	{
		echo "컨트롤러 메서드가 정상적으로 호출되었습니다!";
	}

	/**
	 * 디버깅용 메서드 - URL 정보 확인
	 */
	public function debug_url()
	{
		$this->load->helper('url');
		echo "<h2>URL 디버깅 정보</h2>";
		echo "<p>base_url(): " . base_url() . "</p>";
		echo "<p>site_url(): " . site_url() . "</p>";
		echo "<p>site_url('welcome/category'): " . site_url('welcome/category') . "</p>";
		echo "<p>base_url('index.php/welcome/category'): " . base_url('index.php/welcome/category') . "</p>";
		echo "<p>Current URL: " . current_url() . "</p>";
		echo "<p>URI String: " . $this->uri->uri_string() . "</p>";
	}
}
