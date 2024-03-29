<?php

$servername = "localhost";
$dbuser = "root";
$dbpassword = "";
$dbname = "test";

try{
  $db = new PDO("mysql:host={$servername};dbname={$dbname}", $dbuser, $dbpassword);
  $db->setAttribute(PDO::ATTR_EMULATE_PREPARES, false); // PREAPARED STATEMENT를 지원하지 않는 경우 데이터베이스의 기능 사용
  $db->setAttribute(PDO::MYSQL_ATTR_USE_BUFFERED_QUERY, true); // 쿼리 버퍼링을 활성화
  $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION); // PDO 객체가 에러를 처리하는 방식 정함
} catch(PDOException $e) {
  echo "Error: " . $e->getMessage();
}

define('DOCUMENT_ROOT', $_SERVER['DOCUMENT_ROOT'].'');
define('ADMIN_DIR', DOCUMENT_ROOT .'/admin');
define('DATA_DIR', ADMIN_DIR . '/data');
define('PROFILE_DIR', DATA_DIR . '/profile/');
define('APP_DIR',DOCUMENT_ROOT .'/app');
define('APP_DATA_DIR', APP_DIR . '/assets/data');
define('BOARD_DIR', APP_DATA_DIR . '/board');
define('BOARD_WEB_DIR', 'data/board');

?>