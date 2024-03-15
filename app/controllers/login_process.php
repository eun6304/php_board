<?php

include '../../lib/classes/DB/dbconfig.php';
include '../../lib/classes/member.php';


$mem = new Member($db);


$id =(isset($_POST['id']) && $_POST['id'] != '') ? $_POST['id'] : '';
$pw =(isset($_POST['pw']) && $_POST['pw'] != '') ? $_POST['pw'] : '';


if($id == '') {
  $arr = ['result' => 'empty_id'];
  die(json_encode($arr));
}

if($pw == '') {
  $arr = ['result' => 'empty_pw'];
  die(json_encode($arr));
}

if($mem->login($id, $pw)) {
  $arr = ['result' => 'login_success'];

  $memArr = $mem -> getInfo($id);

  session_start();
  $_SESSION['ses_id'] = $id;
  $_SESSION['ses_level'] = $memArr['level'];

} else {
  $arr = ['result' => 'login_fail'];
}

die(json_encode($arr));


?>