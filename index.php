<?php
include './lib/include/inc_common.php';
include './lib/classes/DB/dbconfig.php';
include './lib/classes/boardManage.php';

$js_array = ['./app/assets/scripts/home.js'];

$g_title = 'Modhaus';

$menu_code = 'home';

$boardm = new BoardManage($db);
$boardArr = $boardm->list();

include './lib/include/inc_header.php';

?>

<main class="w-75 mx-auto border rounded-5 p-5 d-flex gap-5" style="height : calc(100vh - 313px)">
  <img src="images/Modhaus_5.jpg" class="w-30 h-50" alt="">
  <div>
    <h3>home 입니다. </h3>
  </div>
</main>


<?php
include './lib/include/inc_footer.php';
?>