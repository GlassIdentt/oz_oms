<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Allocation_car extends CI_Controller {

	public function index()
	{
		$data['category'] = '배차관리';
		$this->load->view('Allocation_Car/allocation_car_view', $data);
	}

	/**
	 * 배차유형 변경 처리
	 */
	public function aloc_type_change_process()
	{
		$this->load->database();
		$this->load->helper('common_helper');

		// POST 데이터 받기
		$CNT_NO = $this->input->post('CNT_NO');
		$CAR_NO = $this->input->post('CAR_NO');
		$LISENCE_NO = $this->input->post('LISENCE_NO');
		$CAR_POSION = $this->input->post('CAR_POSION');
		$TRAN_CD = $this->input->post('TRAN_CD');
		$TRAN_NM = $this->input->post('TRAN_NM');

		// TRAN_NM 문자열 치환
		$TRAN_NM = str_replace(' ', '', $TRAN_NM);
		$TRAN_NM = str_replace('(주)', '', $TRAN_NM);
		$TRAN_NM = str_replace('주식회사', '', $TRAN_NM);
		$TRAN_NM = str_replace('㈜', '', $TRAN_NM);

		$DRV_NM = $this->input->post('DRV_NM');
		$CAR_TEL = $this->input->post('CAR_TEL');
		$CAR_TYPE = $this->input->post('CAR_TYPE');
		$DRV_CD = $this->input->post('DRV_CD');
		$CAR_HOR_ADD = $this->input->post('CAR_HOR_ADD');
		$I_CRUD = $this->input->post('I_CRUD');
		$CHK_COUNT = $this->input->post('CHK_COUNT');
		$A_CAR_KEY = $this->input->post('A_CAR_KEY');
		$cancle_key = $this->input->post('cancle_key');
		$ALLOCATE_DV = $this->input->post('ALLOCATE_DV');
		$Allocation_page = $this->input->post('Allocation_page');
		$PAGE_KEY = $this->input->post('PAGE_KEY');
		$ALOC_STAT = $this->input->post('ALOC_STAT');

		// Query 파라미터 가져오기
		$Query = $this->input->post('Query');

		// Query 문자열 구성
		$query_params = array(
			'CAR_NO' => $CAR_NO,
			'LISENCE_NO' => $LISENCE_NO,
			'CAR_POSION' => $CAR_POSION,
			'TRAN_NM' => $TRAN_NM,
			'DRV_NM' => $DRV_NM,
			'CAR_TEL' => $CAR_TEL,
			'CAR_TYPE' => $CAR_TYPE,
			'DRV_CD' => $DRV_CD,
			'Allocation_page' => $Allocation_page
		);

		// 기존 Query에 파라미터 추가
		$query_string = $Query . '&' . http_build_query($query_params);

		// GridData JSON 받기
		$GridData = $this->input->post('GridData');

		// JSON 데이터 파싱
		$jsonArray = json_decode($GridData, true);

		if (!empty($jsonArray) && is_array($jsonArray)) {
			$CNT = intval($CNT_NO);

			// 각 항목 처리
			foreach ($jsonArray as $item) {
				if (isset($item['SO_NO'])) {
					$SO_NO = trim($item['SO_NO']);

					// 저장 프로시저 호출 (MSSQL은 EXEC 사용)
					$sql = "EXEC Proc_So_Order_Aloc_Type_Update ?, ?";

					try {
						$this->db->query($sql, array($SO_NO, trim($ALOC_STAT)));
					} catch (Exception $e) {
						// 에러 로깅
						log_message('error', 'Failed to update ALOC_TYPE for SO_NO: ' . $SO_NO . ' - ' . $e->getMessage());
					}
				}
			}
		}

		// 이전 페이지 URL 가져오기
		$referer = $this->input->server('HTTP_REFERER');

		// Query 문자열 추가
		if ($referer) {
			// 기존 URL에 쿼리 파라미터가 있는지 확인
			$separator = (strpos($referer, '?') !== false) ? '&' : '?';
			$redirect_url = $referer . $separator . $query_string;
		} else {
			// Referer가 없는 경우 기본 페이지로
			$redirect_url = site_url('allocation_car_list') . '?' . $query_string;
		}

		// 공용 함수를 사용하여 메시지 출력 후 페이지 이동
		MsgGo('배차유형이 변경 되었습니다!', $redirect_url);
	}

	/**
	 * 동적 view 로딩
	 * URL 세그먼트를 기반으로 해당하는 view 파일 로드
	 * URL 패턴: /controller/folder/file
	 */
	public function _remap($method, $params = array())
	{
		// aloc_type_change_process는 실제 메서드로 처리
		if ($method === 'aloc_type_change_process') {
			return $this->aloc_type_change_process();
		}

		$data['category'] = '배차관리';
		$data['folder_name'] = 'Allocation_Car';
		$data['current_file'] = $method;

		// 메서드명이 index인 경우 기본 view
		if ($method === 'index') {
			$this->load->view('Allocation_Car/allocation_car_view', $data);
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
				$view_file = 'Allocation_Car/' . $method . '.php';
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
