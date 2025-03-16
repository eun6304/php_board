<?php
include '../inc/dbconfig.php';
include '../board.php';
include '../inc_common.php';

$bcode = (isset($_GET['bcode']) && $_GET['bcode'] != '') ? $_GET['bcode'] : '';
$page = (isset($_GET['page']) && $_GET['page'] != '' && is_numeric($_GET['page'])) ? $_GET['page'] : 1;
$sn = (isset($_GET['sn']) && $_GET['sn'] !== '' && is_numeric($_GET['sn'])) ? $_GET['sn'] : '';
$sf = (isset($_GET['sf']) && $_GET['sf'] !== '') ? $_GET['sf'] : '';

if($bcode == '') {
  die("<script>alert('게시판 코드가 누락되었습니다.');history.go(-1);</script>");
};

// 게시판 목록
include '..//boardManage.php';
$boardm = new BoardManage($db);
$boardArr = $boardm->list();
$board_name = $boardm->getBoardName($bcode);

$menu_code = 'board';

$board = new Board($db);

$limit = 10;
$page_limit = 5;
$paramArr = ['sn' => $sn, 'sf' => $sf];

$boardRs = $board->list($bcode, $page, $limit, $paramArr);

$js_array = ['../js/board.js'];

$g_title = '게시판';

$total = $board->total($bcode, $paramArr);

include '../inc_header.php';
include '../inc/lib.php'; // 페이지네이션

?>
<style>
  .tr { cursor : pointer; }
</style>

<main class="w-100 mx-auto border rounded-2 p-5">
  <h1 class="text-center"><?= $board_name ?></h1>
  <table class="table table-striped table-hover mt-5">
    <colgroup>
      <col width="10%">
      <col width="45%">
      <col width="10%">
      <col width="15%">
      <col width="10%">
    </colgroup>
    <thead>
      <tr>
        <th scope="col">번호</th>
        <th scope="col">이름</th>
        <th scope="col">제목</th>
        <th scope="col">날짜</th>
        <th scope="col">조회 수</th>
      </tr>
    </thead>
    <tbody>
    <?php 
      $cnt = 0;
      $ntotal = $total - ($page - 1) * $limit;
      foreach($boardRs AS $boardRow) {
        $number = $ntotal - $cnt;
        $cnt++;
    ?>
      <tr class="tr" data-idx="<?= $boardRow['idx']?>">
        <td scope="row"><?= $number ?></td>
        <td><?= $boardRow['subject'] ?></td>
        <td><?= $boardRow['name'] ?></td>
        <td><?= $boardRow['create_at'] ?></td>
        <td><?= $boardRow['hit'] ?></td>
      </tr>
    <?php
    }
    ?>
    </tbody>
    
  </table>
  <div class="container mt-3 w-50 d-flex gap-2">
    <select name="" id="sn" class="form-select w-25">
      <option value="1" <?php if($sn == 1) echo 'selected'; ?>>제목+내용</option>
      <option value="2" <?php if($sn == 2) echo 'selected'; ?>>제목</option>
      <option value="3" <?php if($sn == 3) echo 'selected'; ?>>내용</option>
      <option value="4" <?php if($sn == 4) echo 'selected'; ?>>글쓴이</option>
    </select>
    <input type="text" class="form-control w-50" id="sf" value="<?= $sf ?>">
    <button class="btn btn-primary w-25" id="btn_search">검색</button>
    <button class="btn btn-info w-25" id="btn_all">전체목록</button>
  </div>
  <div class="d-flex justify-content-between align-items-start">
    <?php
      // fist, last page 넘길 때 링크에 붙여주기 위함.
      $param = '&bcode=' . $bcode;
      if(isset($sn) && $sn != '' && isset($sf) && $sf == '') {
        $param .= '&sn=' .$sn. '&sf=' . $sf;
      }
      echo my_pagination($total, $limit, $page_limit, $page, $param);
    ?>
    <button class="btn btn-primary" id="btn_write">글쓰기</button>
  </div>
 

</main>
<?php
include '../inc_footer.php';
?>
