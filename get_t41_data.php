<?php
/**
 * T41 카테고리 데이터 조회 스크립트
 * 이 파일을 브라우저에서 실행하여 T41 그룹의 카테고리 정보를 확인합니다.
 */

// CodeIgniter 부트스트랩
define('BASEPATH', TRUE);
$system_path = 'system';
$application_folder = 'application';

// 경로 설정
define('APPPATH', __DIR__ . '/' . $application_folder . '/');
define('ENVIRONMENT', 'development');

// 데이터베이스 설정 로드
require_once(APPPATH . 'config/database.php');

// DB 연결
$db_config = $db['default'];
$conn = sqlsrv_connect($db_config['hostname'], array(
    'Database' => $db_config['database'],
    'UID' => $db_config['username'],
    'PWD' => $db_config['password'],
    'CharacterSet' => 'UTF-8'
));

if (!$conn) {
    die("데이터베이스 연결 실패: " . print_r(sqlsrv_errors(), true));
}

// T41 그룹 코드로 조회
$sql = "EXEC [dbo].[Proc_Com_Code_List_Category] @GRP_CD = N'T41'";
$stmt = sqlsrv_query($conn, $sql);

if (!$stmt) {
    die("쿼리 실행 실패: " . print_r(sqlsrv_errors(), true));
}

echo "<h2>T41 카테고리 데이터</h2>";
echo "<table border='1' cellpadding='5'>";
echo "<tr><th>CD_NM</th><th>OPT_ITEM1 (폴더명)</th><th>OPT_ITEM2 (파일명)</th></tr>";

$results = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $results[] = $row;
    echo "<tr>";
    echo "<td>" . htmlspecialchars($row['CD_NM']) . "</td>";
    echo "<td>" . htmlspecialchars($row['OPT_ITEM1']) . "</td>";
    echo "<td>" . htmlspecialchars($row['OPT_ITEM2']) . "</td>";
    echo "</tr>";
}

echo "</table>";

echo "<h3>JSON 데이터:</h3>";
echo "<pre>" . json_encode($results, JSON_UNESCAPED_UNICODE | JSON_PRETTY_PRINT) . "</pre>";

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);
