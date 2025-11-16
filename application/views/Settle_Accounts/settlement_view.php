<?php $this->load->view('common/header', array('category' => $category)); ?>

			<!-- 하단 탭 버튼들 -->
			<button class="btn state-active">
				<span class="btn-icon"></span>
				년결산
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				일일결산
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				거래처별결산
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				업체불만사항
			</button>

<?php $this->load->view('common/footer'); ?>

		<!-- 결산 컨텐츠 -->
		<div style="padding: 20px; width: 100%; height: 100%;">
			<h2>결산</h2>
			<p>결산 화면입니다.</p>
		</div>

<?php $this->load->view('common/bottom'); ?>
