<?php $this->load->view('common/header', array('category' => $category)); ?>

			<!-- 하단 탭 버튼들 -->
			<button class="btn state-active">
				<span class="btn-icon"></span>
				도착보고리스트
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				업체리스트
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				도착보고관리자
			</button>

<?php $this->load->view('common/footer'); ?>

		<!-- 도착보고 컨텐츠 -->
		<div style="padding: 20px; width: 100%; height: 100%;">
			<h2>도착보고</h2>
			<p>도착 보고 화면입니다.</p>
		</div>

<?php $this->load->view('common/bottom'); ?>
