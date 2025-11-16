<?php $this->load->view('common/header', array('category' => $category)); ?>

			<!-- 하단 탭 버튼들 -->
			<button class="btn state-active">
				<span class="btn-icon"></span>
				사용자관리
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				공통코드리스트
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				미디어관리
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				지출결의서
			</button>

<?php $this->load->view('common/footer'); ?>

		<!-- 일반관리 컨텐츠 -->
		<div style="padding: 20px; width: 100%; height: 100%;">
			<h2>일반관리</h2>
			<p>일반 관리 화면입니다.</p>
		</div>

<?php $this->load->view('common/bottom'); ?>
