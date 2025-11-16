<?php $this->load->view('common/header', array('category' => $category)); ?>

			<!-- 하단 탭 버튼들 -->
			<button class="btn state-active">
				<span class="btn-icon"></span>
				청구처마감
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				매출마감현황
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				청구현황조회
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				대납금리스트
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				창고료리스트
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				업체별미수현황
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				입금내역리스트
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				일반비용리스트
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				차량업체별
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				매입마감현황
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				지급현황조회
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				미지급현황
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				차량업체 마감현황
			</button>

<?php $this->load->view('common/footer'); ?>

		<!-- 마감청구 컨텐츠 -->
		<div style="padding: 20px; width: 100%; height: 100%;">
			<h2>마감청구</h2>
			<p>마감 청구 화면입니다.</p>
		</div>

<?php $this->load->view('common/bottom'); ?>
