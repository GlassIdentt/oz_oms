<?php $this->load->view('common/header', array('category' => $category)); ?>

					<!-- 하단 탭 버튼들 -->
					<button class="btn state-active">
						<span class="btn-icon"></span>
						종합배차현황
					</button>
					<button class="btn state-inactive">
						<span class="btn-icon"></span>
						종합배차현황-2
					</button>
					<button class="btn state-inactive">
						<span class="btn-icon"></span>
						종합배차현황-3
					</button>
					<button class="btn state-inactive">
						<span class="btn-icon"></span>
						24시콜배차현황
					</button>
					<button class="btn state-inactive">
						<span class="btn-icon"></span>
						LCL구간배차
					</button>
					<button class="btn state-inactive">
						<span class="btn-icon"></span>
						AIR구간배차
					</button>
					<button class="btn state-inactive">
						<span class="btn-icon"></span>
						GNL구간배차
					</button>
					<button class="btn state-inactive">
						<span class="btn-icon"></span>
						차량위치확인
					</button>

<?php $this->load->view('common/footer'); ?>

			<!-- 배차관리 컨텐츠 -->
			<div style="padding: 20px; width: 100%; height: 100%;">
				<h2>배차관리</h2>
				<p>배차 관리 화면입니다.</p>
			</div>

<?php $this->load->view('common/bottom'); ?>
