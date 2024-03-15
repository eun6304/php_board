<?php
// 세션을 사용하겠다.
session_start();

$ses_id = (isset($_SESSION['ses_id']) && $_SESSION['ses_id'] != '') ? $_SESSION['ses_id'] : '';
$ses_level = (isset($_SESSION['ses_level']) && $_SESSION['ses_level'] != '') ? $_SESSION['ses_level'] : '';

if($ses_id == '' && $ses_level != 10) {
  die("
    <script>
    alret('관리자만 접근 가능합니다.')
    self.location.href = '../'
    </script>
  ");
}

$js_array = ['js/home.js'];

$g_title = 'Modhaus';

$menu_code = 'home';

include './inc_header.php';

?>

<main class="w-75 mx-auto border rounded-5 p-5 d-flex gap-5" style="height : calc(100vh - 313px)">
  <img src="../app/assets/images/Modhaus_5.jpg" class="w-30 h-50" alt="">
  <div>
    <h3>home 입니다. </h3>
  </div>
</main>


<?php
include './inc_footer.php';
?>