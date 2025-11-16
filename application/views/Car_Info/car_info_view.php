<?php $this->load->view('common/header', array('category' => $category)); ?>

			<!-- 하단 탭 버튼들 -->
			<button class="btn state-active">
				<span class="btn-icon"></span>
				차량리스트
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				차랑업체통합등록
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				차량등록
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				스마트오더차량
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				차량협력업체관리자
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				GPS장착리스트
			</button>

<?php $this->load->view('common/footer'); ?>

		<!-- 차량정보 컨텐츠 -->
		<div style="padding: 20px; width: 100%; height: 100%;">
			<h2>차량정보</h2>
			<p>차량 정보 화면입니다.</p>
		</div>

<?php $this->load->view('common/bottom'); ?>
