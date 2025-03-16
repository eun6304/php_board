<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title><?= (isset($g_title) && $g_title != '') ?  $g_title : 'Modhaus'; ?></title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
  <?php
    if(isset($js_array)) {
      foreach($js_array AS $var){
        echo '<script src="'.$var.'?v='.date('YmdHis').'"></script>'.PHP_EOL;
      };
    };
  ?>
</head>
<body>
  <div class="container">
    <header class="d-flex flex-wrap justify-content-center py-3 mb-4 border-bottom">
      <a href="/" class="d-flex align-items-center mb-3 mb-md-0 me-md-auto link-body-emphasis text-decoration-none">
        <img src="../images/logo.svg" style="width: 5rem;" class="me-2">
        <svg class="bi me-2" width="40" height="32"><use xlink:href="#bootstrap"/></svg>
        <span class="fs-4">ADMIN</span>
      </a>

      <ul class="nav nav-pills">
        <li class="nav-item"><a href="index.php" class="nav-link <?= ($menu_code == 'home') ? 'active' : '';"" ?>">Home</a></li>
        <li class="nav-item"><a href="member.php" class="nav-link <?= ($menu_code == 'member') ? 'active' : '';"" ?>"">회원관리</a></li>
        <li class="nav-item"><a href="board.php" class="nav-link <?= ($menu_code == 'board') ? 'active' : '';"" ?>"">게시판관리</a></li>
        <li class="nav-item"><a href="../app/controllers/logout.php" class="nav-link <?= ($menu_code == 'login') ? 'active' : '';"" ?>"">로그아웃</a></li>
      </ul>
    </header>