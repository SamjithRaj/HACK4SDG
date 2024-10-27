document.addEventListener("DOMContentLoaded", function () {
  const loginSection = document.getElementById("loginSection");
  const registerSection = document.getElementById("registerSection");
  const showRegister = document.getElementById("showRegister");
  const showLogin = document.getElementById("showLogin");

  // Check if any elements are null
  if (!loginSection || !registerSection || !showRegister || !showLogin) {
    console.error("One or more elements not found:", {
      loginSection,
      registerSection,
      showRegister,
      showLogin,
    });
    return;
  }

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
