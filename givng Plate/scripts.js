document.addEventListener("DOMContentLoaded", function () {
  const loginSection = document.getElementById("loginSection");
  const registerSection = document.getElementById("registerSection");
  const showRegister = document.getElementById("showRegister");
  const showLogin = document.getElementById("showLogin");

  showRegister.addEventListener("click", function (e) {
    e.preventDefault();
    loginSection.classList.add("hidden");
    registerSection.classList.remove("hidden");
  });

  showLogin.addEventListener("click", function (e) {
    e.preventDefault();
    registerSection.classList.add("hidden");
    loginSection.classList.remove("hidden");
  });
});
