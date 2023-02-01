document.addEventListener("DOMContentLoaded", () => {
  removeErrors()
  let pass = document.getElementById("form2Example2")

  let btns = document.querySelectorAll(".eyes")

  btns.forEach((btn) => {
    btn.addEventListener("click", (e) => {
      if (e.target.dataset.show === "true") {
        pass.setAttribute("type", "text")
        e.target.classList.add("d-none")
        document.querySelector(".fa-eye-slash").classList.remove("d-none")
      } else {
        e.target.classList.add("d-none")
        document.querySelector(".fa-eye").classList.remove("d-none")
        pass.setAttribute("type", "password")
      }
    })
  })
})

function removeErrors() {
  setTimeout(() => {
    let errors = document.querySelectorAll(".alert")

    if (errors) {
      errors.forEach((er) => {
        er.remove()
      })
    }
  }, 2000)
}
