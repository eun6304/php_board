<?php
$js_array = ['js/login.js'];

$g_title = '로그인';

$menu_code = 'login';

include './inc_header.php';


?>

<main class="mx-auto border rounded-5 p-5 d-flex gap-5" style="height : calc(100vh - 313px)">
  <form class="w-25 mt-5 m-auto" action="">
    <img src="../../app/assets/images/Modhaus_5.jpg" width="72" alt="">
    <h1 class="h3 mb-3 fw-normal">로그인</h1>
    <div class="form-floating mt-2">
      <input type="text" class="form-control" id="f_id" name="f_id" placeholder="아이디 입력">
      <label for="f_id">아이디</label>
    </div>
    <div class="form-floating mt-2">
      <input type="password" class="form-control" id="f_pw" name="f_pw" placeholder="패스워드 입력">
      <label for="f_pw">패스워드</label>
    </div>
    <button class="w-100 mt-2 btn btn-lg btn-primary" id="btn_login" type="button">로그인</button>
  </form>
</main>

<?php
include './inc_footer.php';
?>