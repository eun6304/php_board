function getUrlParams() {
  const params = {};

  window.location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi,
    function(str, key, value){
      params[key] = value;
    }
  )
  return params
}

function getExtensionOffilename(filename) {
  const filelen = filename.length
  const lastdot = filename.lastIndexOf('.')
  return filename.substring(lastdot + 1, filelen).toLowerCase()
}

document.addEventListener("DOMContentLoaded", () => {
  // 게시판 목록으로 이동하기
  const btn_board_list = document.querySelector("#btn_board_list")
  btn_board_list.addEventListener("click", () => {
    const params = getUrlParams()
    self.location.href = './board.php?bcode=' + params['bcode']
  })

  // 게시판 작성 후 확인 버튼 클릭시
  const id_subject = document.querySelector("#id_subject");
  const btn_write_submit = document.querySelector("#btn_write_submit")
  btn_write_submit.addEventListener("click", () => {
    if(id_subject.value == '') {
      alert("게시물 제목을 입력해 주세요.")
      id_subject.focus()
      return false
    }

    const markupStr = $('#summernote').summernote('code')
    if(markupStr == '<p><br></p>') {
      alert("내용을 입력하세요.")
      return false
    }

    // 파일 첨부
    const id_attach = document.querySelector("#id_attach")

    const params = getUrlParams()
    const bcode = params['bcode']

    const f = new FormData()
    f.append("subject", id_subject.value) // 게시물 제목
    f.append("content", markupStr)       // 게시물 내용
    f.append("bcode", bcode)            // 게시판 코드
    f.append("mode", "input")          // 모드 : 글등록

    let ext = ''

    for(const file of id_attach.files) {
      if(file.size > 40 * 1024 * 1024) {
        alert("파일 용량이 큽니다.")
        id_attach.value = ''
        return false
      }

      ext = getExtensionOffilename(file.name)
      if(ext == 'txt' || ext == 'exe' || ext == 'xls' || ext == 'php' || ext == 'js') {
        alert("지원되지 않는 확장자입니다.")
        id_attach.value = ''
        return false
      }
      f.append("file[]", file) // 파일 첨부
    }

    const xhr = new XMLHttpRequest()
    xhr.open("POST", "../controllers/board_process.php", true)
    xhr.send(f)
    xhr.onload = () => {
      if(xhr.status == 200) {
        const data = JSON.parse(xhr.responseText)
        if(data.result == 'success') {
          alert("등록하였습니다.")
          self.location.href = './board.php?bcode=' + params['bcode']
        } else if (data.result == 'file_upload_count_exceed') {
          alert("파일 업로드 개수를 초과했습니다.")
          id_attach.value = ''
          return false
        } else if (data.result == 'post_size_exceed') {
          alert("첨부 파일 용량을 초과했습니다.")
          id_attach.value = ''
          return false
        } else if (data.result == 'not_allowed_file') {
          alert("지원되지 않는 확장자입니다.")
          id_attach.value = ''
          return false
        } else {
          alert("통신실패")
        }
      }
    }
  })

  id_attach.addEventListener("change", () => {
    if(id_attach.files.length > 3) {
      alert("최대 3개까지 첨부 가능합니다.")
      id_attach.value = ''
      return false
    }
  })
})