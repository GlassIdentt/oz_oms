<!-- 상품 선택 레이어 -->
<div id="productSelectLayer" class="product-layer-overlay" style="display: none;">
	<div class="product-layer-content">
		<div class="product-layer-row">
			<!-- 1번째 줄 -->
			<label class="product-checkbox-label">
				<input type="checkbox" name="product_type" value="AIR" class="product-checkbox">
				<span>AIR</span>
			</label>
			<label class="product-checkbox-label">
				<input type="checkbox" name="product_type" value="LCL" class="product-checkbox">
				<span>LCL</span>
			</label>
			<label class="product-checkbox-label">
				<input type="checkbox" name="product_type" value="FCL" class="product-checkbox">
				<span>FCL</span>
			</label>
		</div>
		<div class="product-layer-row">
			<!-- 2번째 줄 -->
			<label class="product-checkbox-label">
				<input type="checkbox" name="product_type" value="GNL" class="product-checkbox">
				<span>GNL</span>
			</label>
			<label class="product-checkbox-label">
				<input type="checkbox" name="product_type" value="GNA" class="product-checkbox">
				<span>GNA</span>
			</label>
			<label class="product-checkbox-label">
				<input type="checkbox" name="product_type" value="QUK" class="product-checkbox">
				<span>QUK</span>
			</label>
		</div>
		<div class="product-layer-row product-layer-select-buttons">
			<!-- 3번째 줄 - 전체선택/선택해제 버튼 -->
			<!--
			<button type="button" class="event-btn select-btn" id="productLayerSelectAllBtn">
				<span class="event-btn-icon icon-check"></span>
				전체선택
			</button>
			<button type="button" class="event-btn cancel-btn" id="productLayerDeselectAllBtn">
				<span class="event-btn-icon icon-cancel"></span>
				선택해제
			</button>
			-->
		</div>
		<div class="product-layer-row product-layer-buttons">
			<!-- 4번째 줄 - 확인/닫기 버튼 -->
			<button type="button" class="event-btn select-btn" id="productLayerConfirmBtn">
				<span class="event-btn-icon icon-check"></span>
				확인
			</button>
			<button type="button" class="event-btn cancel-btn" id="productLayerCloseBtn">
				<span class="event-btn-icon icon-cancel"></span>
				닫기
			</button>
		</div>
	</div>
</div>

<style>
.product-layer-overlay {
	position: absolute;
	top: 100%;
	left: 0;
	z-index: 9999;
	opacity: 0;
	transition: opacity 0.3s ease-in-out;
	margin-top: 5px;
}

.product-layer-overlay.show {
	opacity: 1;
}

.product-layer-content {
	position: relative;
	width: 250px;
	height: 180px;
	background-color: #fff;
	border: 2px solid #87CEEB; /* 라이트블루 */
	border-radius: 10px;
	padding: 15px;
	display: flex;
	flex-direction: column;
	justify-content: space-between;
	box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
}

.product-layer-row {
	flex: 1;
	display: flex;
	align-items: center;
	justify-content: flex-start;
	padding: 5px 0;
	gap: 10px;
}

.product-layer-select-buttons {
	justify-content: flex-start;
	gap: 10px;
}

.product-layer-buttons {
	justify-content: flex-start;
	gap: 10px;
	margin-top: 10px;
	position: relative;
}

.product-layer-buttons #productLayerConfirmBtn {
	margin-left: 0;
}

.product-layer-buttons #productLayerCloseBtn {
	position: absolute;
	right: 15px; /* QUK의 k 글자 끝 위치에 맞춤 (레이어 padding 15px + 마지막 label 60px + gap 10px * 2 = 200px 위치) */
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
	var productLayer = document.getElementById('productSelectLayer');
	var confirmBtn = document.getElementById('productLayerConfirmBtn');
	var closeBtn = document.getElementById('productLayerCloseBtn');
	var selectAllBtn = document.getElementById('productLayerSelectAllBtn');
	var deselectAllBtn = document.getElementById('productLayerDeselectAllBtn');
	
	// 레이어 열기 함수
	window.openProductLayer = function() {
		if (productLayer) {
			var soModeInput = document.getElementById('SO_MODE');
			if (soModeInput) {
				var wrapper = soModeInput.parentElement;
				if (wrapper && wrapper.classList.contains('product-input-wrapper')) {
					// 레이어가 이미 wrapper 안에 있는지 확인
					if (!wrapper.contains(productLayer)) {
						wrapper.appendChild(productLayer);
					}
					productLayer.style.display = 'block';
					// 페이드인 효과를 위해 약간의 지연 후 show 클래스 추가
					setTimeout(function() {
						productLayer.classList.add('show');
					}, 10);
				}
			}
		}
	};
	
	// 레이어 닫기 함수
	function closeProductLayer() {
		if (productLayer) {
			productLayer.classList.remove('show');
			setTimeout(function() {
				productLayer.style.display = 'none';
			}, 300); // 페이드아웃 시간과 동일
		}
	}
	
	// 확인 버튼 클릭 이벤트
	if (confirmBtn) {
		confirmBtn.addEventListener('click', function() {
			// 체크된 체크박스 값들을 읽어서 쉼표로 구분된 문자열 생성
			var checkedValues = [];
			var checkboxes = productLayer.querySelectorAll('.product-checkbox:checked');
			
			checkboxes.forEach(function(checkbox) {
				checkedValues.push(checkbox.value);
			});
			
			// 쉼표로 구분하고 마지막에 쉼표 추가 (예: "LCL," 또는 "AIR,LCL,")
			var selectedValue = checkedValues.length > 0 ? checkedValues.join(',') + ',' : '';
			
			// SO_MODE input에 값 설정
			var soModeInput = document.getElementById('SO_MODE');
			if (soModeInput) {
				soModeInput.value = selectedValue;
			}
			
			// 페이드 아웃 효과와 함께 레이어 닫기
			closeProductLayer();
		});
	}
	
	// 닫기 버튼 클릭 이벤트
	if (closeBtn) {
		closeBtn.addEventListener('click', function() {
			closeProductLayer();
		});
	}
	
	// 전체선택 버튼 클릭 이벤트 (주석처리됨)
	/*
	if (selectAllBtn) {
		selectAllBtn.addEventListener('click', function() {
			var checkboxes = productLayer.querySelectorAll('.product-checkbox');
			checkboxes.forEach(function(checkbox) {
				checkbox.checked = true;
			});
		});
	}
	
	// 선택해제 버튼 클릭 이벤트 (주석처리됨)
	if (deselectAllBtn) {
		deselectAllBtn.addEventListener('click', function() {
			var checkboxes = productLayer.querySelectorAll('.product-checkbox');
			checkboxes.forEach(function(checkbox) {
				checkbox.checked = false;
			});
		});
	}
	*/
	
	// 레이어 외부 클릭 시 닫기
	if (productLayer) {
		productLayer.addEventListener('click', function(e) {
			if (e.target === productLayer) {
				closeProductLayer();
			}
		});
	}
	
	// 기존 IIC 함수가 있다면 레이어 열기로 연결
	if (typeof IIC === 'undefined') {
		window.IIC = function() {
			openProductLayer();
		};
	} else {
		var originalIIC = window.IIC;
		window.IIC = function() {
			openProductLayer();
		};
	}
});
</script>

