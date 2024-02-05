document.addEventListener("DOMContentLoaded", () => {
  const btn_login = document.querySelector('#btn_login')
  btn_login.addEventListener("click", () => {
    console.log('야')
    self.location.href = '/project/member/login.php'
  })
})
