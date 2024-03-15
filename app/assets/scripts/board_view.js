function getUrlParams() {
  const params = {};

  window.location.search.replace(/[?&]+([^=&]+)=([^&]*)/gi,
    function(str, key, value){
      params[key] = value;
    }
  )
  return params
}

document.addEventListener("DOMContentLoaded", () => {
  const params = getUrlParams()
  // 글목록 버튼 클릭
  const btn_list = document.querySelector("#btn_list")
  btn_list.addEventListener("click", () => {
    self.location.href = '../../app/views/board.php?bcode=' + params['bcode']
  })

  // 글수정 버튼 클릭
  const btn_edit = document.querySelector("#btn_edit")
  if(btn_edit) {
    btn_edit.addEventListener("click", () => {
      self.location.href = '../../app/views/board_edit.php?bcode=' + params['bcode'] + '&idx=' + params['idx']
    })
  }

  // 글목록 버튼 클릭
  const btn_delete = document.querySelector("#btn_delete")
  if(btn_delete) {
    btn_delete.addEventListener("click", () => {
      if(confirm("삭제하시겠습니까?")) {
        const f = new FormData()
        f.append("idx", params['idx'])    // 게시물 번호
        f.append("mode", "delete")          // 모드 : 글수정
        f.append("bcode", params['bcode'])          // 모드 : 글수정

        const xhr = new XMLHttpRequest()
        xhr.open("POST", "../controllers/board_process.php", true)
        xhr.send(f)
        xhr.onload = () => {
          if(xhr.status == 200) {
            const data = JSON.parse(xhr.responseText)
            if(data.result == 'success') {
              alert("삭제하였습니다.")
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
      }
    })
  }

  // 댓글 등록 버튼 클릭
  const btn_comment = document.querySelector("#btn_comment")
  btn_comment.addEventListener("click", () => {
    const comment_content = document.querySelector("#comment_content")
    if(comment_content.value == '') {
      alert("댓글 내용을 입력바랍니다.")
      comment_content.focus()
      return false
    }
    const f = new FormData()
    f.append("pidx", params['idx'])    // 게시물 번호
    f.append("content", comment_content.value)
    f.append("mode", "input")          // 모드 : 글수정

    const xhr = new XMLHttpRequest()
    xhr.open("POST", "../controllers/comment_process.php", true)
    xhr.send(f)
    xhr.onload = () => {
      if(xhr.status == 200) {
        const data = JSON.parse(xhr.responseText)
        if(data.result == 'success') {
          self.location.reload()
        } else if (data.result == 'empty pidx') {
          alert("게시물 번호가 누락되었습니다.")
          return false
        } else if (data.result == 'empty content') {
          alert("게시물 내용이 없습니다.")
          comment_content.focus()
          return false
        } else {
          alert("통신실패")
        }
      }
    }
  })

  // 댓글 등록 버튼 클릭
  const btn_comment_deletes = document.querySelectorAll(".btn_comment_delete")
  btn_comment_deletes.forEach((box) => {
    box.addEventListener("click", () => {
      // data-comment-idx -> dataset.commentIdx
      if(!confirm('이 댓글을 삭제하시겠습니까?')) {
        return false
      }
      const f = new FormData()
      f.append("pidx", params['idx'])    // 게시물 번호
      f.append("idx", box.dataset.commentIdx)
      f.append("mode", "delete")          // 모드 : 글수정

      const xhr = new XMLHttpRequest()
      xhr.open("POST", "../controllers/comment_process.php", true)
      xhr.send(f)
      xhr.onload = () => {
        if(xhr.status == 200) {
          const data = JSON.parse(xhr.responseText)
          if(data.result == 'success') {
            self.location.reload()
          } else if (data.result == 'empty pidx') {
            alert("게시물 번호가 누락되었습니다.")
            return false
          } else if (data.result == 'empty idx') {
            alert("알 수 없는 댓글입니다.")
            return false
          } else {
            alert("통신실패")
          }
        }
      }
    })
  })

  // 댓글 수정 버튼 클릭
  const btn_comment_edits = document.querySelectorAll(".btn_comment_edit")
  btn_comment_edits.forEach((box) => {
    box.addEventListener("click", () => {
      // data-comment-idx -> dataset.commentIdx
      const comment_content = document.querySelector("#comment_content")
      comment_content.value = box.parentNode.childNodes[1].outerText
      comment_content.style.backgroundColor = "Khaki"
      comment_content.focus()
      // const f = new FormData()
      // f.append("pidx", params['idx'])    // 게시물 번호
      // f.append("idx", box.dataset.commentIdx)
      // f.append("mode", "edit")          // 모드 : 글수정

      // const xhr = new XMLHttpRequest()
      // xhr.open("POST", "../controllers/comment_process.php", true)
      // xhr.send(f)
      // xhr.onload = () => {
      //   if(xhr.status == 200) {
      //     const data = JSON.parse(xhr.responseText)
      //     if(data.result == 'success') {
      //       self.location.reload()
      //     } else if (data.result == 'empty pidx') {
      //       alert("게시물 번호가 누락되었습니다.")
      //       return false
      //     } else if (data.result == 'empty idx') {
      //       alert("알 수 없는 댓글입니다.")
      //       return false
      //     } else {
      //       alert("통신실패")
      //     }
      //   }
      // }
    })
  })
})