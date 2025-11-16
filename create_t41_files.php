<?php
/**
 * T41 카테고리 기반 자동 파일 생성 스크립트
 * EXEC [dbo].[Proc_Com_Code_List_Category] @GRP_CD=N'T41' 실행 결과로 파일 생성
 */

// 에러 표시 활성화
error_reporting(E_ALL);
ini_set('display_errors', 1);

// BASEPATH 정의 (CodeIgniter 보안 체크 우회)
define('BASEPATH', TRUE);

// ENVIRONMENT 정의 (database.php에서 필요)
if (!defined('ENVIRONMENT')) {
    define('ENVIRONMENT', 'development');
}

// 경로 설정
define('APPPATH', __DIR__ . '/application/');

echo "<h2>T41 카테고리 기반 파일 자동 생성</h2>";
echo "<p>APPPATH: " . APPPATH . "</p>";

// database.php 파일에서 DB 설정 로드
try {
    require_once(APPPATH . 'config/database.php');
    echo "<p style='color: green;'>✓ database.php 로드 성공</p>";
} catch (Exception $e) {
    die("<p style='color: red;'>✗ database.php 로드 실패: " . $e->getMessage() . "</p>");
}

// DB 설정값 추출
if (!isset($db) || !isset($db['default'])) {
    die("<p style='color: red;'>✗ database.php에서 \$db 변수를 찾을 수 없습니다.</p>");
}

$db_config = $db['default'];
$db_hostname = isset($db_config['hostname']) ? $db_config['hostname'] : '';
$db_username = isset($db_config['username']) ? $db_config['username'] : '';
$db_password = isset($db_config['password']) ? $db_config['password'] : '';
$db_database = isset($db_config['database']) ? $db_config['database'] : '';

echo "<p>DB Hostname: " . $db_hostname . "</p>";
echo "<p>DB Database: " . $db_database . "</p>";
echo "<hr>";

// DB 연결
if (!function_exists('sqlsrv_connect')) {
    die("sqlsrv 확장이 설치되어 있지 않습니다.");
}

$conn = sqlsrv_connect($db_hostname, array(
    'Database' => $db_database,
    'UID' => $db_username,
    'PWD' => $db_password,
    'CharacterSet' => 'UTF-8'
));

if (!$conn) {
    die("데이터베이스 연결 실패: " . print_r(sqlsrv_errors(), true));
}

echo "<p style='color: green;'>✓ 데이터베이스 연결 성공</p>";

// T41 그룹 코드로 조회
$sql = "EXEC [dbo].[Proc_Com_Code_List_Category] @GRP_CD = N'T41', @OPT_ITEM1 = N''";
$stmt = sqlsrv_query($conn, $sql);

if (!$stmt) {
    die("쿼리 실행 실패: " . print_r(sqlsrv_errors(), true));
}

echo "<p style='color: green;'>✓ 프로시저 실행 성공</p>";
echo "<hr>";

$created_count = 0;
$skipped_count = 0;
$error_count = 0;

// allocation_car_list.php 템플릿 읽기
$template_file = APPPATH . 'views/Allocation_Car/allocation_car_list.php';
if (!file_exists($template_file)) {
    die("템플릿 파일을 찾을 수 없습니다: " . $template_file);
}
$template_content = file_get_contents($template_file);

echo "<h3>파일 생성 결과:</h3>";
echo "<table border='1' cellpadding='5' style='border-collapse: collapse;'>";
echo "<tr style='background: #f0f0f0;'><th>CD_NM</th><th>폴더명 (OPT_ITEM1)</th><th>파일명 (OPT_ITEM2)</th><th>상태</th></tr>";

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $cd_nm = isset($row['CD_NM']) ? $row['CD_NM'] : '';
    $folder_name = isset($row['OPT_ITEM1']) ? $row['OPT_ITEM1'] : '';
    $file_name = isset($row['OPT_ITEM2']) ? $row['OPT_ITEM2'] : '';

    echo "<tr>";
    echo "<td>" . htmlspecialchars($cd_nm) . "</td>";
    echo "<td>" . htmlspecialchars($folder_name) . "</td>";
    echo "<td>" . htmlspecialchars($file_name) . "</td>";

    // 폴더명과 파일명이 모두 있는지 확인
    if (empty($folder_name) || empty($file_name)) {
        echo "<td style='color: orange;'>⚠ 건너뜀 (폴더명 또는 파일명 없음)</td>";
        $skipped_count++;
        echo "</tr>";
        continue;
    }

    // 폴더 경로 생성
    $folder_path = APPPATH . 'views/' . $folder_name;

    // 폴더가 없으면 생성
    if (!is_dir($folder_path)) {
        if (!mkdir($folder_path, 0755, true)) {
            echo "<td style='color: red;'>✗ 폴더 생성 실패</td>";
            $error_count++;
            echo "</tr>";
            continue;
        }
    }

    // 파일 경로
    $file_path = $folder_path . '/' . $file_name . '.php';

    // 파일이 이미 존재하는지 확인
    if (file_exists($file_path)) {
        echo "<td style='color: blue;'>○ 이미 존재함 (건너뜀)</td>";
        $skipped_count++;
        echo "</tr>";
        continue;
    }

    // 템플릿 내용 수정
    $new_content = str_replace(
        "<?php \$this->load->view('common/header', array('category' => \$category)); ?>",
        "<?php \$this->load->view('common/header', array('category' => \$category)); ?>",
        $template_content
    );

    // 파일 생성
    if (file_put_contents($file_path, $new_content)) {
        echo "<td style='color: green;'>✓ 생성 완료</td>";
        $created_count++;
    } else {
        echo "<td style='color: red;'>✗ 생성 실패</td>";
        $error_count++;
    }

    echo "</tr>";
}

echo "</table>";

echo "<hr>";
echo "<h3>요약:</h3>";
echo "<ul>";
echo "<li>✓ 생성된 파일: <strong style='color: green;'>{$created_count}개</strong></li>";
echo "<li>○ 건너뛴 파일: <strong style='color: blue;'>{$skipped_count}개</strong></li>";
echo "<li>✗ 오류 발생: <strong style='color: red;'>{$error_count}개</strong></li>";
echo "</ul>";

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);

echo "<hr>";
echo "<p style='color: green;'>작업이 완료되었습니다.</p>";
