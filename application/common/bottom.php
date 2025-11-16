        </div>

        <div class="bottom_area">
            <!-- 하단 영역 -->
            <div class="bottom_stats_container">
                <div class="stats_item">
                    <span class="stats_label">총매출:</span>
                    <div id="T_S_AMT" class="stats_value">100,000,000,000,000원</div>
                </div>
                <div class="stats_item">
                    <span class="stats_label">총매입:</span>
                    <div id="T_B_AMT" class="stats_value"></div>
                </div>
                <div class="stats_item">
                    <span class="stats_label">총수익:</span>
                    <div id="TTC" class="stats_value"></div>
                </div>
                <div class="stats_item">
                    <span class="stats_label">총오더수:</span>
                    <div id="T_Order_Count" class="stats_value stats_value_small"></div>
                </div>
                <div class="stats_item">
                    <span class="stats_label">AIR:</span>
                    <div id="T_AIR" class="stats_value stats_value_small"></div>
                </div>
                <div class="stats_item">
                    <span class="stats_label">LCL:</span>
                    <div id="T_LCL" class="stats_value stats_value_small"></div>
                </div>
                <div class="stats_item">
                    <span class="stats_label">FCL:</span>
                    <div id="T_FCL" class="stats_value stats_value_small"></div>
                </div>
                <div class="stats_item">
                    <span class="stats_label">GNL:</span>
                    <div id="T_GNL" class="stats_value stats_value_small"></div>
                </div>
                <div class="stats_item">
                    <span class="stats_label">CAS:</span>
                    <div id="T_CAS" class="stats_value stats_value_small"></div>
                </div>
            </div>
        </div>
    </div>

<script>
// 하단 탭 버튼 클릭 이벤트 처리
document.addEventListener('DOMContentLoaded', function() {
	const buttons = document.querySelectorAll('#buttonContainer .btn');

	buttons.forEach(button => {
		button.addEventListener('click', function(event) {
			event.preventDefault();

			// 모든 버튼을 비활성 상태로 변경
			buttons.forEach(btn => {
				btn.classList.remove('state-active');
				btn.classList.add('state-inactive');
			});

			// 클릭된 버튼을 활성 상태로 변경
			this.classList.remove('state-inactive');
			this.classList.add('state-active');
		});
	});
});
</script>
</body>
</html>
