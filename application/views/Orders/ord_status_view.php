<?php $this->load->view('common/header', array('category' => $category)); ?>

			<!-- 하단 탭 버튼들 -->
			<button class="btn state-active">
				<span class="btn-icon"></span>
				오더등록현황
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				오더등록현황-2
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				AIR등록
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				LCL등록
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				일반운송
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				FCL등록
			</button>

<?php $this->load->view('common/footer'); ?>

		<!-- 오더등록 컨텐츠 -->
		<div style="padding: 20px; width: 100%; height: 100%;">
			<h2>오더등록</h2>
			<p>오더 상태 목록 화면입니다.</p>
		</div>

<?php $this->load->view('common/bottom'); ?>
