<?php 

$js_array = ['../../app/assets/scripts/member.js'];

$menu_code = 'member';

$g_title = '약관';

include '../../lib/include/inc_header.php';

?>
    <main class="p-5 border rounded-5">
      <h1 class="text-center mt-5">회원 약관 및 개인정보 취급방침 동의</h1>
      <h4>회원 약관</h4>
      <textarea name="" id="" cols="30" rows="10" class="form-control">
        Lorem ipsum, dolor sit amet consectetur adipisicing elit. Perspiciatis earum nobis necessitatibus nemo, voluptate placeat harum eaque, quidem officiis quis tenetur, veritatis cupiditate aperiam quisquam illum laborum? Odio, consectetur recusandae. Hic commodi, alias, sed ipsum tempore labore at maiores nihil modi, totam maxime explicabo! Quam, deserunt. Magnam quidem tempore neque harum nisi minima cum, ducimus commodi sunt non possimus quibusdam veritatis minus enim illum vel repudiandae quasi soluta laudantium doloribus. Culpa id tempora harum voluptatibus deserunt dolores quae expedita corporis quod animi commodi natus laudantium, ab, aliquid debitis reprehenderit. Eos officia iusto nostrum? Dolores vitae impedit quaerat? Ab ut minus autem aliquid perferendis! Laborum natus quis culpa, totam repellendus quos similique tenetur explicabo quaerat dolore est unde quibusdam necessitatibus enim consectetur reprehenderit possimus nisi neque dolor amet non, expedita sapiente ab? Vitae similique, quidem pariatur veniam id adipisci. Saepe placeat ut vero quidem fuga delectus unde. Illum corrupti esse delectus odio dolorum iste laboriosam tenetur. Repellat, sint eius! Officia tenetur odio et placeat iusto ea a sapiente explicabo, sunt commodi velit sequi ratione vel reprehenderit ipsum laboriosam temporibus? Ipsa in sed, doloribus voluptatibus minima saepe earum maiores accusamus excepturi maxime id animi dolores optio tempora repudiandae veniam? Porro quam quaerat odit vitae enim saepe corrupti, qui beatae temporibus quo, alias repudiandae magni quis tempora recusandae sint facilis tempore. Eius consectetur quos reiciendis hic autem ipsam dolores quia aspernatur similique quidem porro accusamus vitae assumenda reprehenderit, cupiditate eos! Ratione sit, deserunt quam alias at placeat doloremque, doloribus non animi dolores ea? Eligendi eveniet blanditiis sit doloribus earum mollitia deserunt laborum asperiores odio minus, eum atque assumenda dolores libero nemo corrupti. Odio consequuntur provident autem laudantium? Magnam hic, labore fugiat expedita suscipit vero assumenda obcaecati molestias distinctio ad animi quo corrupti. Numquam veniam ducimus doloremque sed dolorem beatae deserunt qui facilis. Minima!
      </textarea>
      <div class="form-check mt-2">
        <input class="form-check-input" type="checkbox" value="1" id="chk_member1">
        <label class="form-check-label" for="chk_member1">
          위 약관에 동의하시겠습니까?
        </label>
      </div>
      <h4 class="mt-3">개인정보 취급방침</h4>
      <textarea name="" id="" cols="30" rows="10" class="form-control">
        Lorem ipsum dolor sit amet consectetur, adipisicing elit. Corporis est quos officiis modi aliquam culpa sint totam iure saepe deserunt. Doloribus qui nisi expedita mollitia! Amet a molestias voluptatem perspiciatis adipisci neque odio! Quisquam eveniet ex similique ad quae veritatis aut, optio atque fugit mollitia non? Ea ab quod tenetur officia, velit, minima, quibusdam corrupti qui perferendis harum fugit. Nobis sed ab ea vitae rem deleniti nostrum corporis aliquam perferendis similique, facere nemo eos debitis dolores commodi officiis, maxime quo libero asperiores sit officia cupiditate sapiente non consequatur! Laboriosam, tempore corrupti quo itaque magnam corporis possimus sunt sapiente ut nam distinctio quasi culpa saepe adipisci eius dolores iusto asperiores quae commodi rerum, minima quibusdam sequi incidunt! Facere tempore similique id corporis? Aliquam dolorem natus deleniti? Ipsum dolorum ad consectetur eaque placeat commodi maiores quae incidunt accusantium voluptates maxime veritatis, tempora perspiciatis magni repellat asperiores vero cupiditate officia repellendus laborum laudantium tenetur, ullam veniam. Sint quibusdam libero debitis dicta nihil est ipsam nam, ratione repellat nulla sapiente, necessitatibus fugiat dolore reiciendis ut, repudiandae omnis tempore. Temporibus modi alias aspernatur nesciunt omnis vero necessitatibus corrupti magni suscipit harum dolorum ducimus, minima autem commodi sunt quae eum facilis debitis. Illum optio provident sapiente architecto adipisci ipsa corporis autem asperiores molestias, alias repellendus eos nisi tenetur doloribus, deserunt vel libero laborum nostrum neque quis? Optio nesciunt sint est architecto sapiente deserunt rerum corrupti, quo provident nisi distinctio tenetur? Temporibus, iure. Ducimus nulla, numquam nemo eum, natus modi, quam quidem ipsam deleniti eos vel voluptates! Numquam soluta assumenda commodi distinctio quis molestias voluptatum pariatur necessitatibus perspiciatis, possimus sequi id beatae aliquid nam error eos earum omnis eaque odit velit eveniet! Minus ex eveniet modi ratione sed qui quis explicabo sint! Cupiditate molestias inventore amet sequi dicta necessitatibus, omnis maiores earum, voluptate nulla iusto, corporis temporibus!
      </textarea>
      <div class="form-check mt-2">
        <input class="form-check-input" type="checkbox" value="2" id="chk_member2">
        <label class="form-check-label" for="chk_member2">
          위 개인정보 취급 방침에 동의하시겠습니까?
        </label>
      </div>

      <div class="mt-4 d-flex justify-content-center gap-2">
        <div class="btn btn-primary w-50" id="btn_member">회원가입</div>
        <div class="btn btn-secondary w-50">가입취소</div>
      </div>

      <form method="post" name="stipulation_form" action="member_input.php" style="display : none">
        <input type="hidden" name="chk" value="0">
      </form>
    </main>
<?php include '../../lib/include/inc_footer.php'; ?>