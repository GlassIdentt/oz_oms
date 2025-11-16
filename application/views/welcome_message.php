<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// 캐시 방지 헤더 추가
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

// CodeIgniter 3: url_helper 로드 (base_url 함수 사용을 위해)
if (!function_exists('base_url')) {
    $CI =& get_instance();
    $CI->load->helper('url');
}
?><!DOCTYPE html>
<html lang="ko">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>OMS_화면레이아웃</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            margin: 0;
            padding: 0;
            background-color: #FFFFFF;
            display: flex;
            align-items: flex-start;
            justify-content: flex-start;
        }

        .layout-container {
            width: 1900px;
            height: 910px;
            display: flex;
            flex-direction: column;
            align-items: flex-start;
            justify-content: flex-start;
        }

        .top_area{
            width: 1900px;
            height: 150px;
            background-image: url('<?php echo base_url("images/oms_layout_01.png"); ?>');
            background-size: 1900px 155px;
            background-position: left top;
            background-repeat: no-repeat;
            border: 0px solid red;
            display: flex;
            flex-direction: row;
            align-items: start;
            justify-content: start;			
        }

        .empty {
            width: 27px;
            height: 150px;
			border: 0px solid rgb(112, 29, 221);
        }

        .logo {
            width: 318px;
            height: 150px;
            border: 0px solid rgb(112, 29, 221);
            display: flex;
            flex-direction: column;
        }

        .logo_top {
            height: 8px;
			border: 0px solid rgb(112, 29, 221);
        }

        .logo_middle {
            flex-grow: 1;
			border: 0px solid rgb(112, 29, 221);			
        }

        .logo_bottom {
            height: 15px;
			border: 0px solid rgb(112, 29, 221);			
        }

        .TopMenu {
            width: 1175px;
            height: 300px;
            border: 0px solid rgb(112, 29, 221);
            display: flex;
            flex-direction: column;
        }

        .TopMenu_Empty {
            width: 100%;
            height: 35px;
            border: 0px solid;
			
        }

        .TopMenu_menu {
            width: 100%;
            height: 65px;
            border: 0px solid;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
        }

        .TopMenu_bottom_Section_Menu {
            width: 100%;
            height: 47px;
            border: 0px solid;
            display: flex;
            flex-direction: row;
            align-items: center;
            justify-content: flex-stretch;
        }

        #buttonContainer {
            display: flex;
            flex-wrap: wrap;
            justify-content: flex-start;
            align-items: center;
            gap: 4px 12px;
            width: 100%;
            height: 100%;
            padding-bottom: 0;
            z-index: 20;
        }

        .link {
            width: 377px;
            height: 150px;
			border: 0px solid rgb(112, 29, 221);
        }

        .contents_area  {
            width: 1900px;
            height: 766px;
            background-image: url('<?php echo base_url("images/oms_layout_02.png"); ?>');
            background-size: 1900px 816px;
            background-position: left top;
            background-repeat: no-repeat;
            border: 0px solid rgb(112, 29, 221);
            display: flex;
            flex-direction: column;
            align-items: center;
        }			

        .bottom_area {
            width: 1900px;
            height: 59px;
            background-image: url('<?php echo base_url("images/oms_layout_03.png"); ?>');
            background-size: 1900px 59px;
            background-position: left top;
            background-repeat: no-repeat;
            border: 0px solid red;
            display: flex;
            flex-direction: column;
            align-items: center;			
        }



/* 메뉴 컨테이너 스타일 */
#menuContainer {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
            align-items: center;
            gap: 20px;
            width: 100%;
            height: 100%;
            margin: 0;
        }

/* 메뉴 버튼 래퍼 스타일 */
.menu-item-wrapper {
            position: relative; 
            width: 90px; 
            height: 35px; 
            flex-shrink: 0;
        }

        /* 메뉴 버튼 배경 스타일 */
        .menu-item-bg {
            position: absolute;
            top: 50%;
            left: 50%;
            width: calc(100% + 10px); 
            height: calc(100% + 10px);
            transform: translate(-50%, -50%); 
            border-radius: 6px; 
            background: linear-gradient(to bottom, #dddddd, #bbbbbb); 
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.2);
            z-index: 5; 
        }
        
        /* 메뉴 버튼 스타일 (기본/비선택 상태) */
        .menu-item-btn {
            display: flex;
            justify-content: center;
            align-items: center;
            width: 100%; 
            height: 100%; 
            border: 1px solid;
            border-radius: 6px; 
            font-size: 16px; 
            font-weight: bold;
            cursor: pointer;
            transition: all 0.05s ease-in-out;
            white-space: nowrap;
            color: #333;
            
            /* Raised (튀어나온) 스타일 */
            background: linear-gradient(to bottom, #f0f0f0, #dcdcdc);
            border-color: #fcfcfc #b0b0b0 #b0b0b0 #fcfcfc;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.15), inset 0 1px 0 rgba(255, 255, 255, 0.7);
            position: relative; 
            z-index: 10; 
        }

        /* 선택된 메뉴 버튼 스타일 (Sunken / 눌린 상태 유지) */
        .menu-item-selected {
            border-color: #b0b0b0 #fcfcfc #fcfcfc #b0b0b0; 
            background: linear-gradient(to bottom, #dcdcdc, #f0f0f0); 
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.2), 0 0 0 rgba(0, 0, 0, 0) !important; 
        }

        /* Active (눌림) 상태: 선택 상태와 동일하게 설정 */
        .menu-item-btn:active {
            border-color: #b0b0b0 #fcfcfc #fcfcfc #b0b0b0; 
            background: linear-gradient(to bottom, #dcdcdc, #f0f0f0); 
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.2), 0 0 0 rgba(0, 0, 0, 0); 
        }
        
        
        /* ======================================================================= */
        /* 이벤트 버튼 래퍼 및 공통 스타일 */
        /* ======================================================================= */

        .event-btn-wrapper {
            position: relative;
            /* 버튼들을 중앙에 배치하고 사이에 간격(gap) 추가 */
            display: flex;
            justify-content: center; 
            gap: 10px; /* 버튼 간 간격 */
            margin-top: 10px; 
            margin-bottom: 20px; 
            width: 100%; 
            max-width: 800px; /* 최대 폭 확장 */
        }

        .event-btn {
            display: inline-flex; 
            align-items: center; 
            justify-content: center; 
            
            /* 공통 크기 */
            min-width: 80px; 
            height: 25px; 
            border-radius: 5px; 

            border: 1px solid;
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.2), 
                        inset 0 1px 0 rgba(255, 255, 255, 0.4); 

            color: #fff; 
            font-size: 13px; 
            font-weight: bold;
            cursor: pointer;
            transition: all 0.1s ease-in-out;
            padding: 0 10px; 
            white-space: nowrap;
            
            /* [수정] 네이비색 글자 외곽선 효과 */
            text-shadow: 
                1px 1px #1c2a4f,    /* 우하단 네이비 */
                -1px -1px #1c2a4f,  /* 좌상단 네이비 */
                1px -1px #1c2a4f,   /* 우상단 네이비 */
                -1px 1px #1c2a4f,   /* 좌하단 네이비 */
                0 0 3px rgba(0,0,0,0.8); /* 중앙 검은 그림자 */
        }

        /* 이벤트 버튼 눌림 효과 (공통) */
        .event-btn:active, .event-btn.event-btn-selected {
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.4), 0 0 0 rgba(0, 0, 0, 0); 
            text-shadow: 
                1px 1px #1c2a4f,    
                -1px -1px #1c2a4f,  
                1px -1px #1c2a4f,   
                -1px 1px #1c2a4f,   
                0 0 3px rgba(0,0,0,0.8);
        }

        /* 이벤트 버튼 아이콘 스타일 (공통) */
        .event-btn-icon {
            display: inline-block;
            width: 16px; 
            height: 16px; 
            margin-right: 6px; 
            position: relative;
            background-color: #fff; 
            border-radius: 3px; 
            box-shadow: 0 0 0 1px rgba(0, 0, 0, 0.2), inset 0 1px 1px rgba(0,0,0,0.1); 
            flex-shrink: 0; 
            display: flex;
            justify-content: center;
            align-items: center;
        }

        /* ---------------------------------------------------------------------- */
        /* 파란색 버튼 (배차차량선택) 스타일 */
        /* ---------------------------------------------------------------------- */
        
        .select-btn {
            background: linear-gradient(to bottom, #60b0e9, #358cce 50%, #2873a8); 
            border-color: #aaddff #217bb7 #217bb7 #aaddff; 
        }
        
        .select-btn:active, .select-btn.event-btn-selected {
            background: linear-gradient(to bottom, #2873a8, #358cce 50%, #60b0e9); 
            border-color: #217bb7 #aaddff #aaddff #217bb7; 
        }

        /* 파란색 버튼 - 체크마크 아이콘 */
        .select-btn .event-btn-icon::after {
            content: '';
            position: absolute;
            display: block;
            width: 5px; 
            height: 8px; 
            border: solid #000; 
            border-width: 0 2px 2px 0;
            transform: translate(-50%, -50%) rotate(45deg); 
            top: 50%;
            left: 50%;
        }

        /* ---------------------------------------------------------------------- */
        /* 빨간색 버튼 (배차취소) 스타일 */
        /* ---------------------------------------------------------------------- */
        
        .cancel-btn {
            background: linear-gradient(to bottom, #ff7b7b, #c43c3c 50%, #a02020); 
            border-color: #ffbaba #9c3030 #9c3030 #ffbaba; 
        }

        .cancel-btn:active, .cancel-btn.event-btn-selected {
            background: linear-gradient(to bottom, #a02020, #c43c3c 50%, #ff7b7b); 
            border-color: #9c3030 #ffbaba #ffbaba #9c3030; 
        }

        /* 빨간색 버튼 - X 아이콘 */
        .cancel-btn .event-btn-icon {
            background-color: #ffefc2; 
        }
        
        /* X 아이콘 구현: 첫 번째 선 (\) */
        .cancel-btn .event-btn-icon::before {
            content: '';
            position: absolute;
            display: block;
            width: 2px; 
            height: 12px; 
            background-color: #a02020; 
            transform: translate(-50%, -50%) rotate(45deg); 
            top: 50%;
            left: 50%;
            border-radius: 1px;
        }

        /* X 아이콘 구현: 두 번째 선 (/) */
        .cancel-btn .event-btn-icon::after {
            content: '';
            position: absolute;
            display: block;
            width: 2px; 
            height: 12px; 
            background-color: #a02020; 
            transform: translate(-50%, -50%) rotate(-45deg); 
            top: 50%;
            left: 50%;
            border-radius: 1px;
        }

        /* ---------------------------------------------------------------------- */
        /* 파란색 버튼 (인수증출력) 스타일 */
        /* ---------------------------------------------------------------------- */
        
        .print-btn {
            /* 배차차량선택 버튼과 동일한 파란색 그라데이션 */
            background: linear-gradient(to bottom, #60b0e9, #358cce 50%, #2873a8); 
            border-color: #aaddff #217bb7 #217bb7 #aaddff; 
        }

        .print-btn:active, .print-btn.event-btn-selected {
            background: linear-gradient(to bottom, #2873a8, #358cce 50%, #60b0e9); 
            border-color: #217bb7 #aaddff #aaddff #217bb7; 
        }

        /* 프린터 아이콘 구현 */
        .print-btn .event-btn-icon {
            background-color: #fff;
        }

        /* 프린터 몸체 (body) */
        .print-btn .event-btn-icon::before {
            content: '';
            position: absolute;
            display: block;
            width: 10px;
            height: 8px;
            background-color: #1c2a4f; 
            border: 1px solid #1c2a4f;
            border-bottom: 2px solid #1c2a4f; 
            border-radius: 1px;
            bottom: 2px; 
        }

        /* 프린터 용지/배출구 (paper/tray) */
        .print-btn .event-btn-icon::after {
            content: '';
            position: absolute;
            display: block;
            width: 8px;
            height: 3px;
            background-color: #f0f0f0; /* 용지 */
            border: 1px solid #1c2a4f; 
            border-bottom: none;
            border-radius: 1px 1px 0 0;
            top: 2px;
        }
        
        /* ---------------------------------------------------------------------- */
        /* 녹색 버튼 (엑셀출력) 스타일 */
        /* ---------------------------------------------------------------------- */
        
        .excel-btn {
            /* 엑셀 표준 녹색 계열 */
            background: linear-gradient(to bottom, #7fc87f, #4a9f4a 50%, #3a7c3a); 
            border-color: #c1e1c1 #336b33 #336b33 #c1e1c1; 
        }

        .excel-btn:active, .excel-btn.event-btn-selected {
            background: linear-gradient(to bottom, #3a7c3a, #4a9f4a 50%, #7fc87f); 
            border-color: #336b33 #c1e1c1 #c1e1c1 #336b33; 
        }

        /* 엑셀 아이콘 구현 */
        .excel-btn .event-btn-icon {
            background-color: #fff;
            border: 1px solid #3a7c3a; 
        }
        
        /* 엑셀 시트 몸체 (File shape) */
        .excel-btn .event-btn-icon::before {
            content: '';
            position: absolute;
            display: block;
            width: 10px;
            height: 12px;
            background-color: #fff; 
            border: 1px solid #3a7c3a;
            border-radius: 1px;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            box-shadow: 1px 1px 0 rgba(0, 0, 0, 0.1);
        }

        /* 엑셀 시그니처 'X' */
        .excel-btn .event-btn-icon::after {
            content: 'X';
            color: #3a7c3a;
            font-family: Arial, sans-serif;
            font-size: 11px;
            font-weight: 900;
            line-height: 15px; /* 중앙 정렬을 위해 조정 */
            text-align: center;
            width: 100%;
            height: 100%;
            position: absolute;
            top: 0;
            left: 0;
            text-shadow: none; /* 버튼의 텍스트 외곽선 제거 */
            z-index: 10;
        }

        /* ---------------------------------------------------------------------- */
        /* SELECT BOX 공통 스타일 (3D/Sunken 스타일) */
        /* ---------------------------------------------------------------------- */
        .select-wrapper {
            position: relative;
            display: inline-block; 
        }
        
        .custom-select {
            height: 25px;
            min-width: 50px; 
            
            font-size: 11px; 
            color: #333333; 
            
            /* 3D 스타일을 위한 패딩 (네이티브 화살표 공간 확보 포함) */
            padding: 1px 8px 1px 5px; 
            
            cursor: pointer;
            white-space: nowrap;
            
            -webkit-appearance: menulist; 
            -moz-appearance: menulist;
            appearance: menulist;
            
            background-color: #fff; 
            
            /* Sunken (눌린) 필드 스타일 적용 */
            border: 1px solid;
            border-color: #a0a0a0 #ffffff #ffffff #a0a0a0; 
            box-shadow: inset 1px 1px 2px rgba(0, 0, 0, 0.2);
            border-radius: 0; 
            
            font-weight: normal; 
            line-height: normal; 
            position: relative; 
        }

        /* [NEW] 넓은 SELECT BOX (150px) - 스타일은 custom-select를 따름 (revert 완료) */
        .wide-select {
            min-width: 150px;
        }

        /* ---------------------------------------------------------------------- */
        /* INPUT TYPE=TEXT 스타일 (요청된 plain border 스타일) */
        /* ---------------------------------------------------------------------- */
        .text-input-style {
            /* BORDER: #7f9db9 1px solid; */
            border: 1px solid #7f9db9;
            
            /* PADDING */
            padding: 2px;
            
            /* FONT-SIZE: 11px; */
            font-size: 11px;
            
            /* COLOR: #666666; */
            color: #666666;
            
            /* 최소 너비 150px 및 높이 설정 */
            min-width: 150px;
            height: 25px; /* SELECT BOX와 높이 일치 */
            
            /* 기타 설정 */
            background-color: #fff;
            line-height: normal;
            box-sizing: border-box; /* 패딩 포함하여 높이 25px 유지 */
        }
        
        /* 커스텀 화살표 삭제 (네이티브 화살표 사용) */
        .select-wrapper::after {
            content: none;
        }

        /* ---------------------------------------------------------------------- */
        /* [NEW] 필드 타이틀 DIV/SPAN 스타일 */
        /* ---------------------------------------------------------------------- */
        .field-title-style {
            color: #231f20;
            font-family: 돋움, Dotum, sans-serif; /* Fallback 추가 */
            letter-spacing: 0px;
            height: 24px;
            background-color: #5FB0E0;	
            text-align: center;
            font-size: 12px;
            font-weight: bold; /* 가독성을 위해 bold 추가 */
            line-height: 24px; /* 높이와 일치시켜 세로 중앙 정렬 */
            width: 100%; /* 컨테이너 전체 너비 사용 */
            /* 상하단 경계선을 얇게 조정 */
            border-top: 1px solid #559ec8; 
            border-bottom: 1px solid #559ec8;
        }

        /* ---------------------------------------------------------------------- */
        /* 하단 탭 버튼 스타일 (기존 유지) */
        /* ---------------------------------------------------------------------- */
        
        .btn {
            display: flex; 
            justify-content: flex-start; 
            align-items: center;
            
            min-width: 100px; 
            height: 26px; 
            
            padding: 0 8px; 
            border: 1px solid; 
            border-radius: 5px 5px 0 0; 
            
            font-size: 12px; 
            font-weight: bold;
            cursor: pointer;
            transition: all 0.05s ease-in-out;
            white-space: nowrap; 
            color: #333;
            background: linear-gradient(to bottom, #f0f0f0, #dcdcdc); 
            position: relative;
            z-index: 10;
        }

        /* 버튼 3D 효과: 볼록하게 튀어나온(Raised) 기본 상태 */
        .state-active,
        .state-inactive {
            border-color: #fcfcfc #b0b0b0 #b0b0b0 #fcfcfc; 
            box-shadow: 0 1px 2px rgba(0, 0, 0, 0.15), inset 0 1px 0 rgba(255, 255, 255, 0.7); 
        }

        /* 버튼 눌림(Pressed) 효과 */
        .btn:active {
            border-color: #b0b0b0 #fcfcfc #fcfcfc #b0b0b0; 
            background: linear-gradient(to bottom, #dcdcdc, #f0f0f0); 
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.2), 0 0 0 rgba(0, 0, 0, 0); 
        }
        
        /* 활성화된 버튼은 배경보다 약간 더 튀어나오도록 조정 */
        .state-active {
            margin-bottom: -1px; 
            border-bottom: 1px solid #fcfcfc; 
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.1);
        }

        /* 아이콘 래퍼 공통 스타일 */
        .btn-icon {
            display: inline-block;
            width: 16px; 
            height: 16px; 
            margin-left: 0; 
            margin-right: 4px; 
            position: relative;
            border-radius: 3px; 
            display: flex;
            justify-content: center;
            align-items: center;
            transform-style: preserve-3d; 
            flex-shrink: 0; 
        }
        
        /* 아이콘 내용을 CSS ::before와 ::after로 구현 */
        .btn-icon::before,
        .btn-icon::after {
            content: '';
            position: absolute;
            display: block;
        }

        /* 🟢 활성 상태 (Active State) - 녹색 체크 */
        .state-active .btn-icon {
            background-color: #3cb81a; 
            box-shadow: 0 0 0 1.5px #fff, inset 0 0 0 1.5px #3cb81a; 
        }
        
        .state-active .btn-icon::before {
            content: none; 
        }

        .state-active .btn-icon::after {
            content: ''; 
            position: absolute;
            display: block;
            width: 5px; 
            height: 10px; 
            border: solid #fff;
            border-width: 0 2.5px 2.5px 0; 
            transform: translate(-50%, -50%) rotate(45deg) translate(0, 1px); 
            top: 50%;
            left: 50%;
        }

        /* ❗ 비활성 상태 (Inactive State) - 회색 느낌표 */
        .state-inactive .btn-icon {
            background-color: #fff; 
            border: 1px solid #c0c0c0; 
        }
        
        .state-inactive .btn-icon::before {
            width: 2.5px; 
            height: 7px; 
            background-color: #444; 
            top: 3px; 
            left: 50%;
            transform: translateX(-50%);
            border-radius: 1px;
        }

        .state-inactive .btn-icon::after {
            width: 2.5px; 
            height: 2.5px; 
            background-color: #444; 
            bottom: 3px; 
            left: 50%;
            transform: translateX(-50%);
            border-radius: 50%; 
        }

        /* 호버 효과 (비활성 버튼에만 적용하여 상호작용 표시) */
        .state-inactive:hover {
            background: linear-gradient(to bottom, #e0e0e0, #c8c8c8); 
        }


    </style>
</head>
<body>
    <div class="layout-container">
        <div class="top_area">
            <div class="empty"></div>
            <div class="logo">
                <div class="logo_top"></div>
                <div class="logo_middle"></div>
                <div class="logo_bottom"></div>
            </div>
            <div class="TopMenu">
                <div class="TopMenu_menu">

					<!-- 1. 상단 메뉴 버튼 그룹 -->
					<div id="menuContainer">
						<!-- 10개의 메뉴 버튼 (오더등록이 초기 선택 상태) -->
						<div class="menu-item-wrapper">
							<div class="menu-item-bg"></div>
							<button class="menu-item-btn menu-item-selected" data-name="오더등록">오더등록</button>
						</div>
						<div class="menu-item-wrapper">
							<div class="menu-item-bg"></div>
							<button class="menu-item-btn" data-name="배차관리">배차관리</button>
						</div>
						<div class="menu-item-wrapper">
							<div class="menu-item-bg"></div>
							<button class="menu-item-btn" data-name="도착보고">도착보고</button>
						</div>
						<div class="menu-item-wrapper">
							<div class="menu-item-bg"></div>
							<button class="menu-item-btn" data-name="마감청구">마감청구</button>
						</div>
						<div class="menu-item-wrapper">
							<div class="menu-item-bg"></div>
							<button class="menu-item-btn" data-name="조회출력">조회출력</button>
						</div>
						<div class="menu-item-wrapper">
							<div class="menu-item-bg"></div>
							<button class="menu-item-btn" data-name="업체정보">업체정보</button>
						</div>
						<div class="menu-item-wrapper">
							<div class="menu-item-bg"></div>
							<button class="menu-item-btn" data-name="차량정보">차량정보</button>
						</div>
						<div class="menu-item-wrapper">
							<div class="menu-item-bg"></div>
							<button class="menu-item-btn" data-name="영업관리">영업관리</button>
						</div>
						<div class="menu-item-wrapper">
							<div class="menu-item-bg"></div>
							<button class="menu-item-btn" data-name="일반관리">일반관리</button>
						</div>
						<div class="menu-item-wrapper">
							<div class="menu-item-bg"></div>
							<button class="menu-item-btn" data-name="결산">결산</button>
						</div>
					</div>

				</div>
                <div class="TopMenu_bottom_Section_Menu">
					<div id="buttonContainer">
						<!-- 첫 번째 버튼을 기본 활성 상태로 설정 -->
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
							24시콜 배차현황
						</button>
						<button class="btn state-inactive">
							<span class="btn-icon"></span>
							LCL 구간배차
						</button>
						<button class="btn state-inactive">
							<span class="btn-icon"></span>
							AIR 구간배차
						</button>
						<button class="btn state-inactive">
							<span class="btn-icon"></span>
							FCL 구간배차
						</button>
					</div>


				</div>
            </div>
            <div class="link"></div>
        </div>
        <div class="contents_area" id="contentsArea"></div>
        <div class="bottom_area"></div>
    </div>
</body>
</html>
<script>
	const container = document.getElementById('buttonContainer');
	const buttons = container.querySelectorAll('.btn');
	const messageEl = document.getElementById('statusMessage');

	// 메뉴 버튼 관련 요소
	const menuContainer = document.getElementById('menuContainer');
	const menuButtons = menuContainer.querySelectorAll('.menu-item-btn');
	const menuMessageEl = document.getElementById('menuStatusMessage');

	// 이벤트 버튼 요소 (세 개의 버튼 모두)
	const eventButtons = document.querySelectorAll('.event-btn');

	// [추가] 날짜 선택 select 요소
	const dateSelect = document.getElementById('dateSelect');
	// [추가] 구분 선택 select 요소
	const classificationSelect = document.getElementById('classificationSelect');
	// [추가] 텍스트 입력 요소
	const textInput = document.getElementById('textInput');

	// 카테고리와 파일명 매핑
	const categoryFileMap = {
		'오더등록': 'ord_status_list',
		'배차관리': 'allocation_car_list',
		'도착보고': 'arrival_report_list',
		'마감청구': 'closing_billing_list',
		'조회출력': 'inquiry_output_list',
		'업체정보': 'company_info_list',
		'차량정보': 'car_info_list',
		'영업관리': 'sales_management_list',
		'일반관리': 'general_management_list',
		'결산': 'settlement_list'
	};

	// 콘텐츠 영역 요소
	const contentsArea = document.getElementById('contentsArea');

	/**
	 * 카테고리 뷰 파일을 AJAX로 로드합니다.
	 * @param {string} category - 카테고리 이름
	 */
	function loadCategoryView(category) {
		// 로딩 표시
		contentsArea.innerHTML = '<div style="padding: 20px; text-align: center;">로딩 중...</div>';

		// AJAX 요청
		const xhr = new XMLHttpRequest();

		// .htaccess를 통한 라우팅 방식 (index.php 제거)
		const baseUrl = window.location.origin + '/oz_oms/welcome/category';

		console.log('=== AJAX 요청 디버깅 (리라이트 방식 v4) ===');
		console.log('현재 페이지:', window.location.href);
		console.log('base URL:', baseUrl);
		console.log('카테고리:', category);

		// GET 파라미터로 카테고리 전달
		const urlWithParams = baseUrl + '?category=' + encodeURIComponent(category);
		console.log('최종 GET URL:', urlWithParams);

		xhr.open('GET', urlWithParams, true);

		xhr.onreadystatechange = function() {
			if (xhr.readyState === 4) {
				console.log('응답 상태:', xhr.status);
				console.log('응답 헤더:', xhr.getAllResponseHeaders());

				if (xhr.status === 200) {
					console.log('성공! 응답 받음');
					console.log('응답 내용 (첫 500자):', xhr.responseText.substring(0, 500));
					contentsArea.innerHTML = xhr.responseText;
				} else {
					console.error('AJAX 오류:', xhr.status, xhr.statusText);
					console.error('응답:', xhr.responseText);
					console.error('요청 URL:', urlWithParams);
					contentsArea.innerHTML = '<div style="padding: 20px; color: red;">파일을 불러오는 중 오류가 발생했습니다.<br>상태 코드: ' + xhr.status + '<br>요청 URL: ' + urlWithParams + '</div>';
				}
			}
		};

		xhr.onerror = function() {
			console.error('네트워크 오류 발생');
			contentsArea.innerHTML = '<div style="padding: 20px; color: red;">네트워크 오류가 발생했습니다.</div>';
		};

		xhr.send();
	}

	/**
	 * 상단 메뉴 버튼의 선택 상태를 업데이트하고 카테고리 뷰를 로드합니다.
	 * @param {HTMLElement} clickedButton - 클릭된 메뉴 버튼 요소
	 */
	function updateMenuState(clickedButton) {
		menuButtons.forEach(button => {
			button.classList.remove('menu-item-selected');
		});

		clickedButton.classList.add('menu-item-selected');
		
		const buttonText = clickedButton.textContent.trim();
		if (menuMessageEl) {
			menuMessageEl.textContent = `🔵 현재 선택된 메뉴: ${buttonText}`;
		}

		// 카테고리 뷰 로드
		if (categoryFileMap[buttonText]) {
			loadCategoryView(buttonText);
		}
	}
	
	/**
	 * 하단 탭 버튼 상태를 업데이트합니다.
	 * @param {HTMLElement} clickedButton - 클릭된 탭 버튼 요소
	 */
	function updateTabState(clickedButton) {
		let isChanged = false;

		// 1. 모든 버튼을 순회하며 상태를 조정합니다.
		buttons.forEach(button => {
			if (button === clickedButton) {
				if (button.classList.contains('state-active')) {
					return; 
				}
				
				button.classList.remove('state-inactive');
				button.classList.add('state-active');
				isChanged = true;
			} else {
				if (button.classList.contains('state-active')) {
					isChanged = true;
				}
				button.classList.remove('state-active');
				button.classList.add('state-inactive');
			}
		});

		// 2. 상태 메시지 업데이트
		if (isChanged || clickedButton.classList.contains('state-active')) {
			const buttonText = clickedButton.textContent.trim();
			messageEl.textContent = `✅ 현재 활성화된 페이지: ${buttonText}`;
			messageEl.classList.remove('text-red-600', 'text-gray-700');
			messageEl.classList.add('text-green-600');
		}
	}
	
	// 상단 메뉴 버튼에 클릭 이벤트 리스너를 추가합니다.
	menuButtons.forEach(button => {
		button.addEventListener('click', (event) => {
			event.preventDefault(); // 기본 동작 방지
			event.stopPropagation(); // 이벤트 버블링 방지
			updateMenuState(button);
		});
	});

	// 하단 탭 버튼에 클릭 이벤트 리스너를 추가합니다.
	buttons.forEach(button => {
		button.addEventListener('click', () => {
			updateTabState(button);
		});
	});

	// 이벤트 버튼 클릭 이벤트 리스너 (모든 이벤트 버튼 공통 처리)
	eventButtons.forEach(button => {
		button.addEventListener('click', () => {
			const buttonName = button.getAttribute('data-name');
			
			// 1. 클릭된 버튼을 제외한 모든 버튼의 선택 상태를 해제합니다.
			eventButtons.forEach(btn => {
				if (btn !== button) {
					btn.classList.remove('event-btn-selected');
				}
			});

			// 2. 클릭된 버튼의 상태를 토글(선택/해제)합니다.
			const isSelected = button.classList.toggle('event-btn-selected');
			
			// 3. 간단한 피드백 메시지
			console.log(`이벤트 버튼(${buttonName})이 ${isSelected ? '선택' : '해제'}되었습니다.`);
		});
	});

	// [추가] 날짜 선택 SELECT BOX 변경 이벤트 리스너
	if (dateSelect) {
		dateSelect.addEventListener('change', (event) => {
			const selectedText = event.target.options[event.target.selectedIndex].text;
			console.log(`조회 기준 날짜가 "${selectedText}"(으)로 변경되었습니다.`);
			// 필요에 따라 UI에 변경 사항을 반영할 수 있습니다.
		});
	}
	
	// [추가] 구분 SELECT BOX 변경 이벤트 리스너
	if (classificationSelect) {
		classificationSelect.addEventListener('change', (event) => {
			const selectedText = event.target.options[event.target.selectedIndex].text;
			console.log(`구분(Classification)이 "${selectedText}"(으)로 변경되었습니다.`);
		});
	}

	// [추가] 텍스트 입력 필드 변경 이벤트 리스너
	if (textInput) {
		textInput.addEventListener('input', (event) => {
			console.log(`텍스트 입력 필드 값이 변경되었습니다: ${event.target.value}`);
		});
	}


	// 초기 상태 메시지 설정 및 초기 카테고리 뷰 로드
	window.addEventListener('load', () => {
		console.log('=== 페이지 로드 완료 (v4) ===');

		// 하단 탭 초기 상태 설정
		const initialActiveButton = document.querySelector('.btn.state-active');
		if (initialActiveButton && messageEl) {
			messageEl.textContent = `✅ 현재 활성화된 페이지: ${initialActiveButton.textContent.trim()}`;
		}

		// 상단 메뉴 초기 상태 설정 (초기 로드는 비활성화)
		const initialSelectedMenu = document.querySelector('.menu-item-btn.menu-item-selected');
		if (initialSelectedMenu) {
			if (menuMessageEl) {
				menuMessageEl.textContent = `🔵 현재 선택된 메뉴: ${initialSelectedMenu.textContent.trim()}`;
			}

			// 초기 카테고리 뷰 로드는 주석 처리 (테스트용)
			console.log('초기 로드 비활성화됨 - 메뉴를 클릭해서 테스트하세요');
			// const categoryName = initialSelectedMenu.textContent.trim();
			// if (categoryFileMap[categoryName] && contentsArea) {
			// 	loadCategoryView(categoryName);
			// }
		}
	});
</script>

