document.addEventListener("DOMContentLoaded", () => {
  // 이메일 중복 확인
  const btn_email_check = document.querySelector("#btn_email_check")
  btn_email_check.addEventListener("click", () => {
    const f = document.input_form
    if(f.old_email.value == f.email.value) {
      alert("기존 이메일과 동일합니다.")
      return false
    }
    if(f.email.value == '') {
      alert("이메일을 입력해 주세요.")
      f.email.focus()
      return false
    }

    const f2 = new FormData()

    f2.append('email', f.email.value)
    f2.append('mode', 'email_chk')

    const xhr = new XMLHttpRequest()
    xhr.open("POST", "../../app/controllers/member_process.php", true)
    xhr.send(f2)

    xhr.onload = () => {
      if(xhr.status == 200) {
        const data = JSON.parse(xhr.responseText)
        if(data.result == "email_format_wrong") {
          alert('이메일 형식이 맞지 않습니다.')
          f_email.value = ''
          f_email.focus()
        } else if(data.result == "success") {
          alert('사용이 가능한 이메일입니다.')
          document.input_form.email_chk.value = "1"
        } else if(data.result == "fail") {
          document.input_form.email_chk.value = "0"
          alert('이미 사용중인 이메일입니다.')
          f_email.value = ''
          f_email.focus()
        } else if(data.result == "empty_email") {
          alert('이메일을 입력하세요.')
          f_email.focus()
        }
      }
    }
  })

  const f_photo = document.querySelector("#f_photo")
f_photo.addEventListener("change", (e) => {
  console.log("photo")
  console.log(e)
  const reader = new FileReader()
  reader.readAsDataURL(e.target.files[0])

  reader.onload = function(event) {
    // console.log(event)
    // const img = document.createElement("img")
    // img.setAttribute("src", event.target.result)
    // document.querySelector("#f_preview").appendChild(img)
    const f_preview = document.querySelector("#f_preview")
    f_preview.setAttribute("src", event.target.result)
  }
})

  // 우편번호 찾기
  const btn_zipcode = document.querySelector('#btn_zipcode')
  btn_zipcode.addEventListener('click', () => {
    new daum.Postcode({
      oncomplete: function(data) {
        let addr = ''
        let zipcode = ''
        let extra_addr = ''
        zipcode = data.zonecode

        if(data.userSelectedType == 'J') {
          addr = data.jibunAddress
        } else if(data.userSelectedType == 'R') {
          addr = data.roadAddress
        }

        if(data.buildingName != '') {
          if(extra_addr == '') {
            extra_addr = data.buildingName
          } else {
            extra_addr += ', ' +  data.buildingName
          }
        }

        if(extra_addr != '') {
          extra_addr = '(' + extra_addr + ')'
        }
        
        const f_addr1 = document.querySelector("#f_addr1")
        f_addr1.value = addr + extra_addr


        const f_zipcode = document.querySelector("#f_zipcode")
        f_zipcode.value = zipcode
      }
    }).open()
  })

  // 수정확인 버튼 클릭시
  const btn_submit = document.querySelector("#btn_submit")
  btn_submit.addEventListener("click", () => {
    const f = document.input_form
    if(f.name.value == '') {
      alert('이름을 입력해 주세요.')
      f_name.focus()
      return false
    }

    // 비밀번호 일치여부 확인
    if(f.password.value != f.password2.value) {
      alert('비밀번호가 서로 일치하지 않습니다.')
      f.password.value = ''
      f.password2.value = ''
      f.password.focus()
      return false
    }

    if(f.email.value == '') {
      alert('이메일을 입력해 주세요.')
      f_email.focus()
      return false
    }
    // 이메일 변경했을 때만 중복확인 여부 체크
    if((f.email.value != f.old_email.value) && f.email_chk.value == 0) {
      alert('이메일 중복확인을 해주시기 바랍니다.')
      return false
    }

    if(f.f_zipcode.value == '') {
      alert('우편번호를 입력해 주세요.')
      return false
    }

    if(f.f_addr1.value == '') {
      alert('주소를 입력해 주세요.')
      f_addr1.focus()
      return false
    }

    if(f.f_addr2.value == '') {
      alert('상세주소를 입력해 주세요.')
      f_addr2.focus()
      return false
    }

    f.submit()
  })
})