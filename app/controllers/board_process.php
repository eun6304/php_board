<?php
if(isset($_SERVER['CONTENT_LENGTH']) && $_SERVER['CONTENT_LENGTH'] > (int) ini_get('post_max_size') * 1024 * 1024) {
  $arr = ['result' => 'post_size_exceed'];
  die(json_encode($arr));
}

include '../../lib/classes/DB/dbconfig.php';
include '../../lib/include/inc_common.php';
include '../../lib/classes/board.php';
include '../../lib/classes/member.php';

$subject =(isset($_POST['subject']) && $_POST['subject'] != '') ? $_POST['subject'] : '';
$content =(isset($_POST['content']) && $_POST['content'] != '') ? $_POST['content'] : '';
$bcode =(isset($_POST['bcode']) && $_POST['bcode'] != '') ? $_POST['bcode'] : '';
$mode =(isset($_POST['mode']) && $_POST['mode'] != '') ? $_POST['mode'] : '';
$idx =(isset($_POST['idx']) && $_POST['idx'] != '' && is_numeric($_POST['idx'])) ? $_POST['idx'] : '';
$th =(isset($_POST['th']) && $_POST['th'] != '' && is_numeric($_POST['th'])) ? $_POST['th'] : '';

if($mode == '') {
  $arr = ["result" => "empty_mode"];
  $json_str = json_encode($arr);
  die($json_str);
}

if($bcode == '') {
  $arr = ["result" => "empty_bcode"];
  $json_str = json_encode($arr);
  die($json_str);
}

$board = new Board($db);
$member = new Member($db);

if($mode == 'input') {

  preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i", $content, $matches);
  $img_array = [];

  foreach($matches[1] AS $key => $row) { // key는 숫자고 row는 data
    // 앞 5글자가 data: 이면 데이터 있는 것, 없으면 넘어가기
    if(substr($row,0,5) != 'data:') {
      continue;
    }
    list($type, $data) = explode(';', $row); // list 선언, type, data 값에 세미콜론으로 끊어낸 값을 각각 넣겠다.
    list(,$data) = explode(',', $data); // data 값을 다시 ,로 끊어서 넣겠다.
    $data = base64_decode($data); // base64 디코딩
    list(,$ext) = explode('/', $type); // $ext 값에 /로 끊어낸 값을 넣겠다. image/png -> png
    $ext = ($ext == 'jpeg') ? 'jpg' : $ext; // jpeg 이면 jpg로 통일

    $filename = date('YmdHis') .'_'. $key .'.'. $ext;

    file_put_contents(BOARD_DIR."/".$filename, $data);

    // content 라는 긴 내용을 그냥 filename 으로 바꿔요.
    $content = str_replace($row, "../assets/".BOARD_WEB_DIR."/". $filename, $content);
    $image_array[] = BOARD_WEB_DIR."/". $filename;
  }

  if($subject == '') {
    $arr = ["result" => "empty_subject"];
    $json_str = json_encode($arr);
    die($json_str);
  }

  if($content == '' || $content == '<p><br></p>') {
    $arr = ["result" => "empty_content"];
    $json_str = json_encode($arr);
    die($json_str);
  }

  // 파일 첨부
  // $_FILES[]
  $file_list_str = '';
  $file_cnt = 3;
  if(isset($_FILES['file'])) {
    $file_list_str = $board->fileAttach($_FILES['file'], $file_cnt);
  }
  
  $memArr = $member->getInfo($ses_id);
  $name = $memArr['name'];

  $arr = [
    'bcode' => $bcode,
    'id' => $ses_id,
    'name' => $name,
    'subject' => $subject,
    'content' => $content,
    'files' => $file_list_str,
    'ip' => $_SERVER['REMOTE_ADDR']
  ];

  $board->input($arr);
  die(json_encode([ "result" => "success" ]));

} else if ($mode == 'each_file_del') {
  if($idx == '') {
    $arr = ["result" => "empty_idx"];
    die(json_encode($arr));
  }
  if($th == '') {
    $arr = ["result" => "empty_th"];
    die(json_encode($arr));
  }
  $file = $board->getAttachFile($idx, $th);
  $each_files = explode('|', $file);
  if(file_exists(BOARD_DIR."/".$each_files[0])) {
    unlink(BOARD_DIR."/".$each_files[0]);
  }

  $row = $board->view($idx);
  $files = explode('?', $row['files']);
  $tmp_arr = [];
  foreach($files AS $key => $val) {
    if ($key == $th) {
      continue;
    }
    $tmp_arr[] = $val;
  } 
  $files = implode('?', $tmp_arr); // 새로 조합된 파일리스트 문자열

  $downs = explode('?', $row['downhit']);
  $tmp_arr = [];
  foreach($downs AS $key => $val) {
    if ($key == $th) {
      continue;
    }
    $tmp_arr[] = $val;
  } 
  $downs = implode('?', $tmp_arr); // 새로 조합된 파일리스트 문자열

  $board->updateFileList($idx, $files, $downs);

  $arr = [ "result" => "success"];
  die(json_encode($arr));

} else if ($mode == "file_attach") {
  // 수정에서 개별파일 첨부하기
  $file_cnt = 1;
  if(isset($_FILES['file'])) {
    $file_list_str = $board->fileAttach($_FILES['file'], $file_cnt);
  } else {
    $arr = ["result" => "empty_files"];
    die(json_encode($arr));
  }

  $row = $board->view($idx);
  if($row['id'] != $ses_id) {
    die(json_encode([ "result" => "permission_denied" ]));
  }
  if($row['files'] != '') {
    $files = $row['files'] . '?' . $file_list_str;
  } else {
    $files = $file_list_str;
  }

  if($row['downhit'] != '') {
    $downs = $row['downhit'] . '?0';
  } else {
    $downs = '';
  }

  $board->updateFileList($idx, $files, $downs);

  $arr = [ "result" => "success"];
  die(json_encode($arr));

} else if ($mode == "edit") {
  preg_match_all("/<img[^>]*src=[\"']?([^>\"']+)[\"']?[^>]*>/i", $content, $matches);

  $row = $board->view($idx);
  $old_image_arr = $board->extract_image($row['content']);
  $current_image_arr = [];

  foreach($matches[1] AS $key => $row) { // key는 숫자고 row는 data
    // 앞 5글자가 data: 이면 데이터 있는 것, 없으면 넘어가기
    if(substr($row,0,5) != 'data:') {
      continue;
    }
    list($type, $data) = explode(';', $row); // list 선언, type, data 값에 세미콜론으로 끊어낸 값을 각각 넣겠다.
    list(,$data) = explode(',', $data); // data 값을 다시 ,로 끊어서 넣겠다.
    $data = base64_decode($data); // base64 디코딩
    list(,$ext) = explode('/', $type); // $ext 값에 /로 끊어낸 값을 넣겠다. image/png -> png
    $ext = ($ext == 'jpeg') ? 'jpg' : $ext; // jpeg 이면 jpg로 통일

    $filename = date('YmdHis') .'_'. $key .'.'. $ext;

    file_put_contents(BOARD_DIR."/".$filename, $data);

    // content 라는 긴 내용을 그냥 filename 으로 바꿔요.
    $content = str_replace($row, "../assets/".BOARD_WEB_DIR."/". $filename, $content);
  }

  $diff_image_arr = array_diff($old_image_arr, $current_image_arr);
  foreach($diff_image_arr AS $value) {
    unlink("../assets/".$value);
  }

  if($subject == '') {
    $arr = ["result" => "empty_subject"];
    $json_str = json_encode($arr);
    die($json_str);
  }

  if($content == '' || $content == '<p><br></p>') {
    $arr = ["result" => "empty_content"];
    $json_str = json_encode($arr);
    die($json_str);
  }

  $arr = [
    'idx' => $idx,
    'subject' => $subject,
    'content' => $content,
  ];

  $board->edit($arr);
  die(json_encode([ "result" => "success" ]));

} else if ($mode = "delete") {
  // db 에서 해당 row 삭제
  // 첨부 파일을 삭제
  // 본문에 이미지가 있는 경우 본문 이미지도 삭제
  $row = $board->view($idx);
  $img_arr = $board->extract_image($row['content']);
  foreach($img_arr AS $value) {
    if(file_exists("../app/"."$value")) {
      unlink("../app/"."$value");
    }
  }
  
  // 첨부파일 삭제
  if($row['files'] != '') {
    $filelist = explode('?', $row['files']);
    foreach($filelist AS $value) {
      list($file_src, ) = explode('|', $value);
      unlink(BOARD_DIR . '/' . $file_src);
    }
  }

  $board->delete($idx);
  $arr = [ "result" => "success"];
  die(json_encode($arr));
}