<?php $this->load->view('common/header', array('category' => $category)); ?>

					<!-- 하단 탭 버튼들 (동적 로딩) -->
					<?php
					$submenu_data = array(
						'folder_name' => isset($folder_name) ? $folder_name : 'Closing',
						'current_file' => isset($current_file) ? $current_file : 'Sales_closing_status'
					);
					$this->load->view('common/submenu', $submenu_data);
					?>

<?php $this->load->view('common/footer'); ?>

			<!-- 배차관리 목록 컨텐츠 -->
			<div class="contents_area" id="contentsArea" style="padding: 20px; width: 100%; height: 100%;">
				<h2>마감현황</h2>
				<p>매출마감현황 화면입니다.</p>
			</div>

<?php $this->load->view('common/bottom'); ?>
