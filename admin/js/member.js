document.addEventListener("DOMContentLoaded", () => {
  const btn_search = document.querySelector("#btn_search")
  btn_search.addEventListener("click", () => {
    const sf = document.querySelector("#sf")
    if(sf.value == '') {
      alert("검색어를 입력해 주세요.")
      sf.focus()
      return false
    } else {
      const sn = document.querySelector("#sn")
      self.location.href = './member.php?sn=' + sn.value + '&sf=' + sf.value
    }
  })
})