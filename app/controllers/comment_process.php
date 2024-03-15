<?php

include '../../lib/classes/DB/dbconfig.php';
include '../../lib/include/inc_common.php';
include '../../lib/classes/member.php';
include '../../lib/classes/comment.php';

$mode =(isset($_POST['mode']) && $_POST['mode'] != '') ? $_POST['mode'] : '';
$idx =(isset($_POST['idx']) && $_POST['idx'] != '' && is_numeric($_POST['idx'])) ? $_POST['idx'] : '';
$pidx =(isset($_POST['pidx']) && $_POST['pidx'] != '' && is_numeric($_POST['pidx'])) ? $_POST['pidx'] : '';
$content =(isset($_POST['content']) && $_POST['content'] != '') ? $_POST['content'] : '';

if($ses_id == '') {
  $arr = ["result" => "not login"];
  die(json_encode($arr));
}

if($mode == '') {
  $arr = ["result" => "empty mode"];
  die(json_encode($arr));
}

$comment = new Comment($db);

if($mode == 'input') {
  if($pidx == '') {
    $arr = ["result" => "empty pidx"];
    die(json_encode($arr));
  }
  if($content == '') {
    $arr = ["result" => "empty content"];
    die(json_encode($arr));
  }

  $arr = [ "pidx" => $pidx, "content" => $content, "id" => $ses_id];
  $comment->input($arr);

  $arr = [ "result" => "success"];
  die(json_encode($arr));

} else if ($mode == 'delete') {
  if($pidx == '') {
    $arr = ["result" => "empty pidx"];
    die(json_encode($arr));
  }
  if($idx == '') {
    $arr = ["result" => "empty idx"];
    die(json_encode($arr));
  }
  $arr = [ "pidx" => $pidx, "idx" => $idx];
  $comment->delete($pidx, $idx);
  $arr = [ "result" => "success"];
  die(json_encode($arr));
}
