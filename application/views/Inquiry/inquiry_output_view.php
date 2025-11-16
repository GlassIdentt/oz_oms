<?php $this->load->view('common/header', array('category' => $category)); ?>

			<!-- 하단 탭 버튼들 -->
			<button class="btn state-active">
				<span class="btn-icon"></span>
				기간별조회
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				일일운송내역
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				항목/차량별내역
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				오더취소현황
			</button>

<?php $this->load->view('common/footer'); ?>

		<!-- 조회출력 컨텐츠 -->
		<div style="padding: 20px; width: 100%; height: 100%;">
			<h2>조회출력</h2>
			<p>조회 출력 화면입니다.</p>
		</div>

<?php $this->load->view('common/bottom'); ?>
