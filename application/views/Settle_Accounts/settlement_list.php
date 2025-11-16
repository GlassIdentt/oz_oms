<?php $this->load->view('common/header', array('category' => $category)); ?>

					<!-- 하단 탭 버튼들 (동적 로딩) -->
					<?php
					$submenu_data = array(
						'folder_name' => isset($folder_name) ? $folder_name : 'Settle_Accounts',
						'current_file' => isset($current_file) ? $current_file : 'settlement_list'
					);
					$this->load->view('common/submenu', $submenu_data);
					?>

<?php $this->load->view('common/footer'); ?>

			<!-- 결산 목록 컨텐츠 -->
			<div class="contents_area" id="contentsArea" style="padding: 20px; width: 100%; height: 100%;">
				<h2>결산 목록</h2>
				<p>결산 목록 화면입니다.</p>
			</div>

<?php $this->load->view('common/bottom'); ?>
