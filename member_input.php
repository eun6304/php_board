<?php
if(!isset($_POST['chk']) || $_POST['chk'] != 1) {
  die("<script>alert('약관 등을 동의하시고 접근하시기 바랍니다.'); self.location.href = './stipulation.php';</script>");
};

$js_array = ['./js/member_input.js'];

$g_title = '회원가입';

$menu_code = 'member';

include './inc_header.php';

?>
<script src="//t1.daumcdn.net/mapjsapi/bundle/postcode/prod/postcode.v2.js"></script>
<main class="w-50 mx-auto border rounded-5 p-5">
  <h1 class="text-center">회원가입</h1>
  <form name="input_form" method="post" enctype="multipart/form-data" action="/php_board/pg/member_process.php">
    <input type="hidden" name="mode" value="input">
    <input type="hidden" name="id_chk" value="0">
    <input type="hidden" name="email_chk" value="0">
    <div class="d-flex gap-2 align-items-end">
      <div>
        <label for="f_id" class="form-label">아이디</label>
        <input type="text" class="form-control" name="id" id="f_id" placeholder="아이디를 입력해 주세요.">
      </div> 
      <button type="button" class="btn btn-secondary" id="btn_id_check">아이디 중복확인</button>
    </div>

    <div class="d-flex mt-3 gap-2 align-items-end">
      <div>
        <label for="f_name" class="form-label">이름</label>
        <input type="text" class="form-control" name="name" id="f_name" placeholder="이름을 입력해 주세요.">
      </div> 
    </div>

    <div class="d-flex mt-3 gap-2 justify-content-between">
      <div class="flex-grow-1">
        <label for="f_password" class="form-label">비밀번호</label>
        <input type="password" name="password" class="form-control" id="f_password" placeholder="비밀번호를 입력해 주세요.">
      </div> 
      <div class="flex-grow-1">
        <label for="f_password2" class="form-label">비밀번호 확인</label>
        <input type="password" name="password2" class="form-control" id="f_password2" placeholder="비밀번호를 입력해 주세요.">
      </div> 
    </div>

    <div class="d-flex mt-3 gap-2 align-items-end">
      <div class="flex-grow-1">
        <label for="f_email" class="form-label">이메일</label>
        <input type="text" class="form-control" name="email" id="f_email" placeholder="이메일을 입력해 주세요.">
      </div> 
      <button type="button" class="btn btn-secondary" id="btn_email_check">이메일 중복확인</button>
    </div>

    <div class="d-flex mt-3 gap-2 align-items-end">
      <div>
        <label for="f_zipcode">우편번호</label>
        <input type="text" name="zipcode" id="f_zipcode" class="form-control" maxlength="5" minlegth="5" readonly>
      </div>
      <button type="button" class="btn btn-secondary" id="btn_zipcode">우편번호 찾기</button>
    </div>

    <div class="d-flex mt-3 gap-2 justify-content-between">
      <div class="flex-grow-1">
        <label for="f_addr1" class="form-label">주소</label>
        <input type="text" class="form-control" name="addr1" id="f_addr1" placeholder="">
      </div> 
      <div class="flex-grow-1">
        <label for="f_addr2" class="form-label">상세주소</label>
        <input type="text" class="form-control" name="addr2" id="f_addr2" placeholder="상세주소를 입력해 주세요.">
      </div> 
    </div>
    
    <div class="mt-3 d-flex gap-5">
      <div>
        <label for="f_photo" class="form-label">프로필 이미지</label>
        <input type="file" name="photo" id="f_photo" class="form-control">
      </div>
      <img src="images/person.png" id="f_preview" class="w-25" alt="profile image">
    </div>

    <div class="mt-3 d-flex gap-2">
      <button type="button" class="btn btn-primary w-50" id="btn_submit">가입확인</button>
      <button type="button" class="btn btn-secondary w-50">가입취소</button>
    </div>
  </form>
</main>
<?php
include './inc_footer.php';
?>

