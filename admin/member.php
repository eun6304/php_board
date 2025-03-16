<?php
$js_array = ['js/member.js'];

$g_title = 'Modhaus';

$menu_code = 'member';

include './inc_common.php';
include './inc_header.php';
include '../inc/dbconfig.php';
include '../inc/member.php'; // 회원관리 Class
include '../inc/lib.php'; // 페이지네이션

$sn = (isset($_GET['sn']) && $_GET['sn'] !== '' && is_numeric($_GET['sn'])) ? $_GET['sn'] : '';
$sf = (isset($_GET['sf']) && $_GET['sf'] !== '') ? $_GET['sf'] : '';

$paramArr = [ 'sn' => $sn, 'sf' => $sf ];

$mem = new Member($db);

// 검색했을 시 total에 따라 pagenation 바뀌어야 해서 param추가
$total  = $mem -> total($paramArr);
$limit = 5;
$page_limit = 5;
$page = (isset($_GET['page']) && $_GET['page'] != '' && is_numeric($_GET['page'])) ? $_GET['page'] : 1;
$param = '';

$memArr = $mem -> list($page, $limit, $paramArr);

?>

<main class="border rounded-2 p-5" style="height : calc(100vh - 313px)">
  <div class="container">
    <h3>회원관리</h3>
  </div>

  <table class="table table-border">
    <tr>
      <th>번호</th>
      <th>아이디</th>
      <th>이름</th>
      <th>이메일</th>
      <th>등록일시</th>
      <th>관리</th>
    </tr>
    <?php
      foreach($memArr AS $row) {
        // $row['create_at'] = substr($row['create_at'],0,16)
    ?>
    <tr>
      <td><?= $row['idx'] ?></td>
      <td><?= $row['id'] ?></td>
      <td><?= $row['name'] ?></td>
      <td><?= $row['email'] ?></td>
      <td><?= $row['create_at'] ?></td>
      <td>
        <button class="btn btn-primary btn-sm btn_mem_edit"  data-idx="<?= $row['idx'] ?>">수정</button>
        <button class="btn btn-danger btn-sm btn_mem_delete" data-idx="<?= $row['idx'] ?>">삭제</button>
      </td>
    </tr>
    <?php
    }
    ?>
  </table>

  <div class="container mt-3 d-flex gap-2 w-50">
    <select class="form-select w-25" name="sn" id="sn">
      <option value="1">이름</option>
      <option value="2">아이디</option>
      <option value="3">이메일</option>
    </select>
    <input type="text" class="form-control w-25" id="sf" name="sf">
    <button class="btn btn-primary w-25"id="btn_search">검색</button>
    <button class="btn btn-success w-25"id="btn_all">전체목록</button>

  </div>

  <div class="d-flex mt-3 justify-content-between align-items-start">
    <?php
      // fist, last page 넘길 때 링크에 붙여주기 위함.
      $param = '&sn=' .$sn. '&sf=' . $sf;
      echo my_pagination($total, $limit, $page_limit, $page, $param);
    ?>
    <button class="btn btn-primary" id="btn_excel">엑셀로 저장</button>
  </div>
  
</main>


<?php
include './inc_footer.php';
?>