<?php
include '../inc/dbconfig.php';
include '../inc/member.php';

$mem = new Member($db);

$id =(isset($_POST['id']) && $_POST['id'] != '') ? $_POST['id'] : '';
$password =(isset($_POST['password']) && $_POST['password'] != '') ? $_POST['password'] : '';
$email =(isset($_POST['email']) && $_POST['email'] != '') ? $_POST['email'] : '';
$name =(isset($_POST['name']) && $_POST['name'] != '') ? $_POST['name'] : '';
$zipcode =(isset($_POST['zipcode']) && $_POST['zipcode'] != '') ? $_POST['zipcode'] : '';
$addr1 =(isset($_POST['addr1']) && $_POST['addr1'] != '') ? $_POST['addr1'] : '';
$addr2 =(isset($_POST['addr2']) && $_POST['addr2'] != '') ? $_POST['addr2'] : '';

$mode =(isset($_POST['mode']) && $_POST['mode'] != '') ? $_POST['mode'] : '';


if( $mode == 'id_chk') {
  if($id == '') {
    die(json_encode(['result' => 'empty_id']));
  }

  if($mem->id_exists($id)) {
    die(json_encode(['result' => 'fail']));

  } else {
    die(json_encode(['result' => 'success']));

  }  
} else if( $mode == 'email_chk') {
  if($email == '') {
    die(json_encode(['result' => 'empty_email']));
  } 

  if($mem->email_format_check($email) == false) {
    die(json_encode(['result' => 'email_format_wrong']));
  }else if($mem->email_exists($email)) {
    die(json_encode(['result' => 'fail']));

  } else {
    die(json_encode(['result' => 'success']));

  }  
} else if( $mode == 'input') {
  $photo = '';
  /// Profile Image
  if(isset($_FILES['photo']) && $_FILES['photo']['name'] != '') {
    $photoname = explode(',', $_FILES['photo']['name']);
    $ext = end($photoname);  // ['2', 'jpg']
    $photo = $id . '.' . $ext;

    copy($_FILES['photo']['tmp_name'], "../data/profile/" . $photo);
  }

  $arr = [
    'id' => $id,
    'password' => $password,
    'email' => $email,
    'name' => $name,
    'zipcode' => $zipcode,
    'addr1' => $addr1,
    'addr2' => $addr2,
    'photo' => $photo
  ];

  $mem->input($arr);
  echo "
  <script>
    alert('정상적으로 가입되었습니다.')
    self.location.href='../index.php'
  </script>
  ";

} else if( $mode == 'edit') {
  // Profile Image
  $old_photo =(isset($_POST['old_photo']) && $_POST['old_photo'] != '') ? $_POST['old_photo'] : '';

  if(isset($_FILES['photo']) && $_FILES['photo']['name'] != '') {
    $new_photo = $_FILES['photo'];
    $old_photo = $mem->profile_upload($id, $new_photo, $old_photo);
  }

  session_start();

  $arr = [
    'id' => $_SESSION['ses_id'],
    'password' => $password,
    'email' => $email,
    'name' => $name,
    'zipcode' => $zipcode,
    'addr1' => $addr1,
    'addr2' => $addr2,
    'photo' => $old_photo
  ];

  $mem->edit($arr);
  echo "
    <script>
      alert('수정되었습니다.')
      self.location.href='../index.php'
    </script>
    ";

}
?>