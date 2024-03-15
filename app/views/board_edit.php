<?php
include '../../lib/classes/DB/dbconfig.php';
include '../../lib/classes/board.php';
include '../../lib/include/inc_common.php';

$bcode = (isset($_GET['bcode']) && $_GET['bcode'] != '') ? $_GET['bcode'] : '';

if($bcode == '') {
  die("<script>alert('게시판 코드가 누락되었습니다.');history.go(-1);</script>");
};

$idx = (isset($_GET['idx']) && $_GET['idx'] != '') ? $_GET['idx'] : '';

if($idx == '') {
  die("<script>alert('게시물 번호가 누락되었습니다.');history.go(-1);</script>");
};

// 게시판 목록
include '../../lib/classes/boardManage.php';
$boardm = new BoardManage($db);
$boardArr = $boardm->list();
$board_name = $boardm->getBoardName($bcode);

// 게시판
$board = new Board($db);
$boardRow = $board->view($idx);

if($boardRow['id'] != $ses_id) {
  die("<script>alert('접근 불가합니다.');self.location.href=\"./board.php?bcode=".$bcode."\";</script>");
}
				
$js_array = ['../../app/assets/scripts/board_edit.js'];

$g_title = '게시판';

include '../../lib/include/inc_header.php';

?>
<script src="https://code.jquery.com/jquery-3.4.1.slim.min.js" integrity="sha384-J6qa4849blE2+poT4WnyKhv5vZF5SrPo0iEjwBvKU7imGFAV0wwj1yYfoRSJoZ+n" crossorigin="anonymous"></script>
<link href="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/summernote@0.8.18/dist/summernote-lite.min.js"></script>

<main class="w-75 mx-auto border rounded-2 p-5">
  <h1 class="text-center">게시판 글 수정</h1>
  <div class="mb-3">
    <input type="text" name="subject" id="id_subject" class="form-control" value="<?=$boardRow['subject']?>" placeholder="제목을 입력하세요." autocomplete="off"/>
  </div>
  <div id="summernote"></div>
  <div class="mt-3">
    <?php 
      $th = 0;
      if($boardRow['files'] != '') {
        $filelist = explode('?', $boardRow['files']);
        foreach($filelist AS $file) {
          list($file_source, $file_name) = explode('|', $file);
          echo "<a href=\"../controllers/board_download.php?idx=$idx&th=$th\">$file_name</a> <button class='btn btn-sm btn-danger mb-2 py-0' id='btn_file_del' data-th='".$th."'>삭제</button><br>";
          $th++;
        }
      }
    ?>
  </div>
  <?php if($th < 3) { ?>
    <div class="mt-3">
      <input type="file" name="attach" id="id_attach" class="form-control" >
    </div>
  <?php } ?>
  <div class="mt-3 d-flex gap-2 justify-content-end">
    <button class="btn btn-primary" id="btn_edit_submit">확인</button>
    <button class="btn btn-secondary" id="btn_board_list">목록</button>
  </div>
</main>
<script>
  $('#summernote').summernote({
    placeholder: '내용을 입력해 주세요.',
    tabsize: 2,
    height: 400,
    toolbar: [
      ['style', ['style']],
      ['font', ['bold', 'underline', 'clear']],
      ['color', ['color']],
      ['para', ['ul', 'ol', 'paragraph']],
      ['table', ['table']],
      ['insert', ['link', 'picture', 'video']],
      ['view', ['fullscreen', 'codeview', 'help']]
    ]
  });

  var boardContent = `<?= str_replace('`', '\`',$boardRow['content']) ?>`
  $('#summernote').summernote('code', boardContent)
</script>
<?php
include '../../lib/include/inc_footer.php';
?>
