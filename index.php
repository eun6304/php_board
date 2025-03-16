<?php
include './inc_common.php';
include './inc/dbconfig.php';
include './boardManage.php';

$js_array = ['./js/home.js'];

$g_title = 'php_board';

$menu_code = 'home';

$boardm = new BoardManage($db);
$boardArr = $boardm->list();

include './inc_header.php';

?>

<main class="w-75 mx-auto border rounded-5 p-5 d-flex gap-5" style="height : calc(100vh - 313px)">
  <img src="images/meta-image.png" class="w-30 h-50" alt="">
  <div>
    <h3>home 입니다. </h3>
  </div>
</main>


<?php
include './inc_footer.php';
?>