<?php $this->load->view('common/header', array('category' => $category)); ?>

			<!-- 하단 탭 버튼들 -->
			<button class="btn state-active">
				<span class="btn-icon"></span>
				업체리스트
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				화주리스트
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				업체등록
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				오더교환업체
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				업체오더관리자
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				웹오더 포인트 전환신청 리스트
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				웹오더 포인트 기부 리스트
			</button>

<?php $this->load->view('common/footer'); ?>

		<!-- 업체정보 컨텐츠 -->
		<div style="padding: 20px; width: 100%; height: 100%;">
			<h2>업체정보</h2>
			<p>업체 정보 화면입니다.</p>
		</div>

<?php $this->load->view('common/bottom'); ?>
