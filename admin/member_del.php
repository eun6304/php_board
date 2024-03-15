<?php
// ajax는 script가 통하지 않아서 inc_common으로 보안 체크를 할 수 없음.
include '../lib/classes/DB/dbconfig.php';
include '../lib/classes/member.php';

session_start();

$ses_id = (isset($_SESSION['ses_id']) && $_SESSION['ses_id'] != '') ? $_SESSION['ses_id'] : '';
$ses_level = (isset($_SESSION['ses_level']) && $_SESSION['ses_level'] != '') ? $_SESSION['ses_level'] : '';

if($ses_id == '' && $ses_level != 10) {
  $arr = ["result" => "access_denied"];
  die(json_encode($arr));
}

$idx = (isset($_POST['idx']) && $_POST['idx'] != '' && is_numeric($_POST['idx']) ) ? $_POST['idx'] : '';

if($idx == '') {
  $arr = ["result" => "empty_idx"];
  // die는 프로그램을 종료하는 것. 
  die(json_encode($arr)); // { "result" : "empty_idx" }
} 

$mem = new Member($db);
$mem->member_del($idx);

$arr = ["result" => "success"];
die(json_encode($arr));