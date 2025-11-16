<?php
/**
 * 모든 카테고리 폴더의 view 파일에 동적 서브 메뉴 적용
 */

// BASEPATH 정의
define('BASEPATH', TRUE);
define('ENVIRONMENT', 'development');
define('APPPATH', __DIR__ . '/application/');

// 폴더별 설정
$folders = array(
    'Orders' => 'Orders',
    'Allocation_Car' => 'Allocation_Car',
    'Arrival' => 'Arrival',
    'Closing' => 'Closing',
    'Inquiry' => 'Inquiry',
    'Company' => 'Company',
    'Car_Info' => 'Car_Info',
    'Sales_Management' => 'Sales_Management',
    'General_Management' => 'General_Management',
    'Settle_Accounts' => 'Settle_Accounts'
);

echo "<h2>View 파일 일괄 업데이트</h2>";
echo "<hr>";

$total_updated = 0;
$total_skipped = 0;
$total_errors = 0;

foreach ($folders as $folder_name => $folder_path) {
    echo "<h3>폴더: {$folder_name}</h3>";

    $view_dir = APPPATH . 'views/' . $folder_path;

    if (!is_dir($view_dir)) {
        echo "<p style='color: red;'>✗ 폴더가 존재하지 않습니다: {$view_dir}</p>";
        continue;
    }

    // 폴더 내 모든 PHP 파일 가져오기
    $files = glob($view_dir . '/*.php');

    echo "<table border='1' cellpadding='5' style='border-collapse: collapse; margin-bottom: 20px;'>";
    echo "<tr style='background: #f0f0f0;'><th>파일명</th><th>상태</th></tr>";

    foreach ($files as $file_path) {
        $file_name = basename($file_path);

        // _view.php 파일은 건너뛰기
        if (strpos($file_name, '_view.php') !== false) {
            echo "<tr><td>{$file_name}</td><td style='color: orange;'>○ 건너뜀 (view 파일)</td></tr>";
            $total_skipped++;
            continue;
        }

        // 파일 읽기
        $content = file_get_contents($file_path);

        if ($content === false) {
            echo "<tr><td>{$file_name}</td><td style='color: red;'>✗ 읽기 실패</td></tr>";
            $total_errors++;
            continue;
        }

        // 이미 동적 서브 메뉴가 적용되어 있는지 확인
        if (strpos($content, 'common/submenu') !== false) {
            echo "<tr><td>{$file_name}</td><td style='color: blue;'>○ 이미 적용됨</td></tr>";
            $total_skipped++;
            continue;
        }

        // header.php와 footer.php 사이에 서브 메뉴 코드가 있는지 확인
        $has_header = (strpos($content, 'common/header') !== false);
        $has_footer = (strpos($content, 'common/footer') !== false);

        if (!$has_header || !$has_footer) {
            echo "<tr><td>{$file_name}</td><td style='color: orange;'>⚠ header/footer 없음</td></tr>";
            $total_skipped++;
            continue;
        }

        // 기존 서브 메뉴 버튼 패턴 찾기
        $pattern = '/<!-- 하단 탭 버튼들 -->.*?(?=<\?php \$this->load->view\(\'common\/footer\'\);)/s';

        // 새로운 서브 메뉴 코드
        $file_name_without_ext = str_replace('.php', '', $file_name);
        $new_submenu = "<!-- 하단 탭 버튼들 (동적 로딩) -->\n\t\t\t\t\t<?php\n\t\t\t\t\t\$submenu_data = array(\n\t\t\t\t\t\t'folder_name' => isset(\$folder_name) ? \$folder_name : '{$folder_name}',\n\t\t\t\t\t\t'current_file' => isset(\$current_file) ? \$current_file : '{$file_name_without_ext}'\n\t\t\t\t\t);\n\t\t\t\t\t\$this->load->view('common/submenu', \$submenu_data);\n\t\t\t\t\t?>\n\n";

        // 패턴이 매치되면 교체
        if (preg_match($pattern, $content)) {
            $new_content = preg_replace($pattern, $new_submenu, $content);

            // 파일 저장
            if (file_put_contents($file_path, $new_content)) {
                echo "<tr><td>{$file_name}</td><td style='color: green;'>✓ 업데이트 완료</td></tr>";
                $total_updated++;
            } else {
                echo "<tr><td>{$file_name}</td><td style='color: red;'>✗ 저장 실패</td></tr>";
                $total_errors++;
            }
        } else {
            echo "<tr><td>{$file_name}</td><td style='color: orange;'>⚠ 패턴 미발견</td></tr>";
            $total_skipped++;
        }
    }

    echo "</table>";
}

echo "<hr>";
echo "<h3>요약:</h3>";
echo "<ul>";
echo "<li>✓ 업데이트된 파일: <strong style='color: green;'>{$total_updated}개</strong></li>";
echo "<li>○ 건너뛴 파일: <strong style='color: blue;'>{$total_skipped}개</strong></li>";
echo "<li>✗ 오류 발생: <strong style='color: red;'>{$total_errors}개</strong></li>";
echo "</ul>";

echo "<p style='color: green;'>작업이 완료되었습니다.</p>";
