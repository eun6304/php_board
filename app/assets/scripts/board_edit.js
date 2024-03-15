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
  const params = getUrlParams()
  const btn_file_dels = document.querySelectorAll("#btn_file_del")
  btn_file_dels.forEach((box) => {
    box.addEventListener("click", () => {
      if (!confirm("해당 첨부파일을 삭제하시겠습니까?")) {
        return false
      }

      const f = new FormData()
      f.append("idx", params['idx']) // 게시물 번호
      f.append("th", box.dataset.th) // 게시물 제목
      f.append("bcode", params['bcode']) // 게시판 코드
      f.append("mode", "each_file_del") // 모드 : 개별파일삭제

      const xhr = new XMLHttpRequest()
      xhr.open("POST", "../controllers/board_process.php", true)
      xhr.send(f)

      xhr.onload = () => {
        if (xhr.status == 200) {
          const data = JSON.parse(xhr.responseText);
          if (data.result == 'empty_idx') {
            alert("게시물 번호가 빠졌습니다.")
          } else if (data.result == 'empty_th') {
            alert("알 수 없는 첨부파일 입니다.")
          } else if (data.result == 'success') {
            self.location.reload()
          }
        } else if (xhr.status == 404) {
          alert("파일이 없습니다.")
        }
      }
    })
  })
  const id_attach = document.querySelector("#id_attach")
  if(id_attach) {
    id_attach.addEventListener("change", () => {
      const f = new FormData()
      f.append("idx", params['idx']) // 게시물 번호
      f.append("bcode", params['bcode']) // 게시판 코드
      f.append("mode", "file_attach") // 모드 : 파일 첨부

      if(id_attach.files[0].size > 40 * 1024 * 1024) {
        alert("파일 용량이 큽니다.")
        id_attach.value = ''
        return false
      }

      ext = getExtensionOffilename(id_attach.files[0].name)
      if(ext == 'txt' || ext == 'exe' || ext == 'xls' || ext == 'php' || ext == 'js') {
        alert("지원되지 않는 확장자입니다.")
        id_attach.value = ''
        return false
      }
      f.append("file[]", id_attach.files[0]) // 파일 첨부

      const xhr = new XMLHttpRequest()
      xhr.open("POST", "../controllers/board_process.php", true)
      xhr.send(f)

      xhr.onload = () => {
        if (xhr.status == 200) {
          const data = JSON.parse(xhr.responseText);
          if (data.result == 'success') {
            self.location.reload()
          } else if (data.result == 'empty_files') {
            alert("파일이 첨부되지 않았습니다.")
          }
        } else if (xhr.status == 404) {
          alert("파일이 없습니다.")
        }
      }
    })
  }
  const btn_board_list = document.querySelector("#btn_board_list")
  btn_board_list.addEventListener("click", () => {
    self.location.href = "../views/board.php?bcode=" + params['bcode']
  })

  const btn_edit_submit = document.querySelector("#btn_edit_submit")
  btn_edit_submit.addEventListener("click", () => {
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

    const f = new FormData()
    f.append("subject", id_subject.value) // 게시물 제목
    f.append("content", markupStr)       // 게시물 내용
    f.append("idx", params['idx'])    // 게시물 번호
    f.append("bcode", params['bcode'])  // 게시판 코드
    f.append("mode", "edit")          // 모드 : 글수정

    const xhr = new XMLHttpRequest()
    xhr.open("POST", "../controllers/board_process.php", true)
    xhr.send(f)
    xhr.onload = () => {
      if(xhr.status == 200) {
        const data = JSON.parse(xhr.responseText)
        if(data.result == 'success') {
          alert("수정하였습니다.")
          self.location.href = './board.php?bcode=' + params['bcode']
        } else if (data.result == 'permission_denied') {
          alert("수정 권한이 없는 게시물입니다..")
          self.location.href = './board.php?bcode=' + params['bcode']
          return false
        } else {
          alert("통신실패")
        }
      }
    }
  })
})