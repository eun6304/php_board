<?php
$js_array = ['js/member_success.js'];

$g_title = '회원가입을 축하드립니다.';

$menu_code = 'member';

include './inc_header.php';

?>

<main class="w-75 mx-auto border rounded-5 p-5 d-flex gap-5" style="height : calc(100vh - 313px)">
  <img src="images/Modhaus_5.jpg" class="w-30 h-50" alt="">
  <div>
    <h3>회원 가입을 축하드립니다.</h3>
    <p>
     Lorem ipsum dolor sit amet consectetur adipisicing elit. Voluptate libero tempore natus eaque praesentium suscipit, dolore expedita a non sit odit totam, earum corporis. Asperiores quaerat odit aut ea quibusdam.
    </p>
    <button class="btn btn-primary" id="btn_login">로그인 하기</button>
  </div>
</main>


<?php
include './inc_footer.php';
?>