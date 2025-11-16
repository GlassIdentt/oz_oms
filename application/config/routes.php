<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'Ord_status';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

// 데이터베이스에서 동적으로 라우팅 생성
// 카테고리-컨트롤러 매핑
$category_controller_map = array(
    '오더등록' => 'Ord_status',
    '배차관리' => 'Allocation_car',
    '도착보고' => 'Arrival_report',
    '마감청구' => 'Closing_billing',
    '조회출력' => 'Inquiry_output',
    '업체정보' => 'Company_info',
    '차량정보' => 'Car_info',
    '영업관리' => 'Sales_management',
    '일반관리' => 'General_management',
    '결산' => 'Settlement'
);

// database.php 파일에서 DB 설정 로드
require_once(APPPATH . 'config/database.php');

// DB 설정값 추출
$db_config = isset($db['default']) ? $db['default'] : array();
$db_hostname = isset($db_config['hostname']) ? $db_config['hostname'] : '';
$db_username = isset($db_config['username']) ? $db_config['username'] : '';
$db_password = isset($db_config['password']) ? $db_config['password'] : '';
$db_database = isset($db_config['database']) ? $db_config['database'] : '';

// PHP sqlsrv 확장이 로드되어 있는 경우에만 실행
if (function_exists('sqlsrv_connect') && !empty($db_hostname)) {
    try {
        // 데이터베이스 연결
        $db_conn = @sqlsrv_connect($db_hostname, array(
            'Database' => $db_database,
            'UID' => $db_username,
            'PWD' => $db_password,
            'CharacterSet' => 'UTF-8'
        ));

        if ($db_conn) {
            // T40 그룹 코드로 메뉴 조회
            $sql = "EXEC [dbo].[Proc_Com_Code_List_Category] @GRP_CD = N'T40', @OPT_ITEM1 = N''";
            $stmt = @sqlsrv_query($db_conn, $sql);

            if ($stmt) {
                while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
                    $cd_nm = isset($row['CD_NM']) ? $row['CD_NM'] : '';
                    $opt_item1 = isset($row['OPT_ITEM1']) ? $row['OPT_ITEM1'] : ''; // 폴더명
                    $opt_item2 = isset($row['OPT_ITEM2']) ? $row['OPT_ITEM2'] : ''; // 파일명

                    // 카테고리명으로 컨트롤러 찾기
                    if (isset($category_controller_map[$cd_nm]) && !empty($opt_item2)) {
                        $controller = $category_controller_map[$cd_nm];

                        // 동적 라우팅 등록: OPT_ITEM2(파일명) → Controller/OPT_ITEM1(폴더명)/OPT_ITEM2(파일명)
                        $route[$opt_item2] = $controller . '/' . $opt_item1 . '/' . $opt_item2;
                    }
                }
                @sqlsrv_free_stmt($stmt);
            }

            // T41 그룹 코드로 서브 메뉴 조회 및 라우팅 등록
            $sql_t41 = "EXEC [dbo].[Proc_Com_Code_List_Category] @GRP_CD = N'T41', @OPT_ITEM1 = N''";
            $stmt_t41 = @sqlsrv_query($db_conn, $sql_t41);

            if ($stmt_t41) {
                while ($row = sqlsrv_fetch_array($stmt_t41, SQLSRV_FETCH_ASSOC)) {
                    $opt_item1 = isset($row['OPT_ITEM1']) ? $row['OPT_ITEM1'] : ''; // 폴더명
                    $opt_item2 = isset($row['OPT_ITEM2']) ? $row['OPT_ITEM2'] : ''; // 파일명

                    if (!empty($opt_item1) && !empty($opt_item2)) {
                        // 폴더명으로 컨트롤러 매핑
                        $folder_controller_map = array(
                            'Orders' => 'Ord_status',
                            'Allocation_Car' => 'Allocation_car',
                            'Arrival' => 'Arrival_report',
                            'Closing' => 'Closing_billing',
                            'Inquiry' => 'Inquiry_output',
                            'Company' => 'Company_info',
                            'Car_Info' => 'Car_info',
                            'Sales_Management' => 'Sales_management',
                            'General_Management' => 'General_management',
                            'Settle_Accounts' => 'Settlement'
                        );

                        if (isset($folder_controller_map[$opt_item1])) {
                            $controller = $folder_controller_map[$opt_item1];
                            // 동적 라우팅 등록: OPT_ITEM2(파일명) → Controller/method
                            $route[$opt_item2] = $controller . '/' . $opt_item2;
                        }
                    }
                }
                @sqlsrv_free_stmt($stmt_t41);
            }

            @sqlsrv_close($db_conn);
        }
    } catch (Exception $e) {
        // DB 연결 실패 시 무시 (기본 라우팅 사용)
    }
}

// 각 카테고리별 기본 라우팅 (URL은 소문자, 컨트롤러는 대문자)
$route['ord_status/(:any)'] = 'Ord_status/$1';
$route['ord_status'] = 'Ord_status/index';

$route['allocation_car/(:any)'] = 'Allocation_car/$1';
$route['allocation_car'] = 'Allocation_car/index';

$route['arrival_report/(:any)'] = 'Arrival_report/$1';
$route['arrival_report'] = 'Arrival_report/index';

$route['closing_billing/(:any)'] = 'Closing_billing/$1';
$route['closing_billing'] = 'Closing_billing/index';

$route['inquiry_output/(:any)'] = 'Inquiry_output/$1';
$route['inquiry_output'] = 'Inquiry_output/index';

$route['company_info/(:any)'] = 'Company_info/$1';
$route['company_info'] = 'Company_info/index';

$route['car_info/(:any)'] = 'Car_info/$1';
$route['car_info'] = 'Car_info/index';

$route['sales_management/(:any)'] = 'Sales_management/$1';
$route['sales_management'] = 'Sales_management/index';

$route['general_management/(:any)'] = 'General_management/$1';
$route['general_management'] = 'General_management/index';

$route['settlement/(:any)'] = 'Settlement/$1';
$route['settlement'] = 'Settlement/index';
