<?php $this->load->view('common/header', array('category' => $category)); ?>

			<!-- 하단 탭 버튼들 -->
			<button class="btn state-active">
				<span class="btn-icon"></span>
				영업부 오더리스트
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				영업일지작성
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				영업일지목록
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				담당자별 매출현황
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				상품별 매출현황
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				전원비교분석
			</button>
			<button class="btn state-inactive">
				<span class="btn-icon"></span>
				인센티브 매출자료
			</button>

<?php $this->load->view('common/footer'); ?>

		<!-- 영업관리 컨텐츠 -->
		<div style="padding: 20px; width: 100%; height: 100%;">
			<h2>영업관리</h2>
			<p>영업 관리 화면입니다.</p>
		</div>

<?php $this->load->view('common/bottom'); ?>
