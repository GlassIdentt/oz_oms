<?php $this->load->view('common/header', array('category' => $category)); ?>

					<!-- 하단 탭 버튼들 (동적 로딩) -->
					<?php
					$submenu_data = array(
						'folder_name' => isset($folder_name) ? $folder_name : 'General_Management',
						'current_file' => isset($current_file) ? $current_file : 'common_code_mag_list'
					);
					$this->load->view('common/submenu', $submenu_data);
					?>

<?php $this->load->view('common/footer'); ?>

			<!-- 일반관리 목록 컨텐츠 -->
			<div class="contents_area" id="contentsArea" style="padding: 20px; width: 100%; height: 100%;">
				<h2>일반관리 목록</h2>
				<p>일반관리 목록 화면입니다.</p>
			</div>

<?php $this->load->view('common/bottom'); ?>
