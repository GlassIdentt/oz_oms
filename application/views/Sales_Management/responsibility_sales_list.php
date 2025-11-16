<?php $this->load->view('common/header', array('category' => $category)); ?>

					<!-- 하단 탭 버튼들 (동적 로딩) -->
					<?php
					$submenu_data = array(
						'folder_name' => isset($folder_name) ? $folder_name : 'Sales_Management',
						'current_file' => isset($current_file) ? $current_file : 'responsibility_sales_list'
					);
					$this->load->view('common/submenu', $submenu_data);
					?>

<?php $this->load->view('common/footer'); ?>

			<!-- 배차관리 목록 컨텐츠 -->
			<div class="contents_area" id="contentsArea" style="padding: 20px; width: 100%; height: 100%;">
				<h2>배차관리 목록</h2>
				<p>배차 관리 목록 화면입니다.</p>
			</div>

<?php $this->load->view('common/bottom'); ?>
