<?php
include '../../lib/classes/DB/dbconfig.php';
include '../../lib/classes/board.php';
include '../../lib/classes/comment.php';
include '../../lib/include/inc_common.php';

$bcode = (isset($_GET['bcode']) && $_GET['bcode'] != '') ? $_GET['bcode'] : '';
$idx = (isset($_GET['idx']) && $_GET['idx'] != '' && is_numeric($_GET['idx'])) ? $_GET['idx'] : 1;


if($bcode == '') {
  die("<script>alert('게시판 코드가 누락되었습니다.');history.go(-1);</script>");
};

if($idx == '') {
  die("<script>alert('게시물 번호가 누락되었습니다.');history.go(-1);</script>");
};

// 게시판 목록
include '../../lib/classes/boardManage.php';
$boardm = new BoardManage($db);
$boardArr = $boardm->list();
$board_name = $boardm->getBoardName($bcode);

$menu_code = 'board';

$board = new Board($db);

$js_array = ['../../app/assets/scripts/board_view.js'];

$g_title = '게시판';

$boardRow = $board->view($idx);

if($boardRow == null) {
  die("<script>alert('게시물 번호가 누락되었습니다.');history.go(-1);</script>");
}

$comment = new Comment($db);
$commentRs = $comment -> list($idx);

// $_SERVER['REMOTE_ADDR'] : 지금 접속한 사람의 IP정보를 담고 있음.
if($boardRow['last_reader'] != $_SERVER['REMOTE_ADDR']) {
  $board->hitInc($idx);
  $board->updateLastReader($idx, $_SERVER['REMOTE_ADDR']);
}
$downhit_arr = explode('?', $boardRow['downhit']);

include '../../lib/include/inc_header.php';
include '../../lib/include/lib.php'; // 페이지네이션

?>
<style>
  .tr { cursor : pointer; }
</style>

<main class="w-100 mx-auto border rounded-2 p-5">
  <h1 class="text-center"><?= $board_name ?></h1>
  <div class="vstack w-75 mx-auto">
    <div class="p-3">
      <span class="h3 fw-bolder"><?= $boardRow['subject'] ?></span>
    </div>
    <div class="d-flex border border-top-0 border-start-0 border-end-0 border-bottom-1">
      <span><?= $boardRow['name'] ?></span>
      <span class="ms-5 me-auto"><?= $boardRow['hit'] ?>회</span>
      <span><?= $boardRow['create_at'] ?></span>
    </div>
    <div class="p-3">
      <?= $boardRow['content'] ?>
      <?php 
        if($boardRow['files'] != '') {
          $filelist = explode('?', $boardRow['files']);
          $th = 0;
          foreach($filelist AS $file) {
            list($file_source, $file_name) = explode('|', $file);
            echo "<a href=\"../controllers/board_download.php?idx=$idx&th=$th\">$file_name</a>(down :  ".$downhit_arr[$th].")<br>";
            $th++;
          }
        }
      ?>
    </div>
    <div class="d-flex gap-2 p-3">
      <button class="btn btn-secondary me-auto" id="btn_list">목록</button>
    <?php if($boardRow['id'] == $ses_id) { ?>
      <button class="btn btn-primary" id="btn_edit">수정</button>
      <button class="btn btn-danger" id="btn_delete">삭제</button>
    <?php } ?>
    </div>
    
    <div class="d-flex gap-2 mt-3">
      <textarea name="" rows="3" class="form-control" id="comment_content"></textarea>
      <button class="btn btn-secondary" id="btn_comment">등록</button>
    </div>
    <div class="mt-3">
      <table class="table">
      <colgroup>
        <col width="50%"/>
        <col width="10%"/>
        <col width="10%"/>
      </colgroup>
      <?php
          foreach($commentRs AS $comRow) {
        ?>
        <tr>
          <td>
          <span><?php echo $comRow['content'] ?></span>
          <?php 
            if($comRow['id'] == $ses_id) {
              echo '<button class="btn btn-info btn-sm py-0 ms-2 btn_comment_edit" data-comment-idx="'.$comRow['idx'].'">수정</button>';
              echo '<button class="btn btn-danger btn-sm py-0 ms-2 btn_comment_delete" data-comment-idx="'.$comRow['idx'].'">삭제</button>';
            }
          ?>
          </td>
          <td><?php echo $comRow['id']; ?></td>
          <td><?php echo $comRow['create_at']; ?></td>
        </tr>
        <?php
          }
        ?>
      </table>
    </div>
  </div>
</main>
<?php
include '../../lib/include/inc_footer.php';
?>
