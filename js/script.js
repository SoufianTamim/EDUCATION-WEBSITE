let toggleBtn = document.getElementById("toggle-btn");
let body = document.body;
let darkMode = localStorage.getItem("dark-mode");

const enableDarkMode = () => {
 toggleBtn.classList.replace("fa-sun", "fa-moon");
 body.classList.add("dark");
 localStorage.setItem("dark-mode", "enabled");
};

const disableDarkMode = () => {
 toggleBtn.classList.replace("fa-moon", "fa-sun");
 body.classList.remove("dark");
 localStorage.setItem("dark-mode", "disabled");
};

if (darkMode === "enabled") {
 enableDarkMode();
}

toggleBtn.onclick = (e) => {
 darkMode = localStorage.getItem("dark-mode");
 if (darkMode === "disabled") {
  enableDarkMode();
 } else {
  disableDarkMode();
 }
};

let profile = document.querySelector(".header .flex .profile");

document.querySelector("#user-btn").onclick = () => {
 profile.classList.toggle("active");
 search.classList.remove("active");
};

//search toggler
let search = document.querySelector(".header .flex .search-form");
document.querySelector("#search-btn").onclick = () => {
 search.classList.toggle("active");
 profile.classList.remove("active");
};

//sidebar toggler
let sideBar = document.querySelector(".sidebar");
document.querySelector("#menu-btn").onclick = () => {
 sideBar.classList.toggle("active");
 body.classList.toggle("active");
};
document.querySelector("#close-btn").onclick = () => {
 sideBar.classList.remove("active");
 body.classList.remove("active");
};
//active links sidebar
let windowPage =
 window.location.pathname.split("/")[
  window.location.pathname.split("/").length - 1
 ];
let links = document.querySelectorAll(".sidebar .navbar a");
links.forEach((link) => {
 let hashValue = link.href.split("/")[link.href.split("/").length - 1];
 if (hashValue == windowPage) {
  link.classList.add("active");
 } else {
  link.classList.remove("active");
 }
});

//scroll window
window.onscroll = () => {
 profile.classList.remove("active");
 search.classList.remove("active");

 if (window.innerWidth < 1200) {
  sideBar.classList.remove("active");
  body.classList.remove("active");
 }
};

//password toggle
let passwordContainer = document.querySelectorAll(".password-container");
passwordContainer.forEach((x) => {
 let passwordToggle = x.querySelector(".password-toggle");
 passwordToggle.addEventListener("click", () => {
  let passwordInput = x.querySelector("input");
  let passInputType = passwordInput.getAttribute("type");
  if (passInputType == "password") {
   passwordInput.setAttribute("type", "text");
   passwordToggle.classList.add("fa-eye");
   passwordToggle.classList.remove("fa-eye-slash");
  }
  if (passInputType == "text") {
   passwordInput.type = "password";
   passwordToggle.classList.add("fa-eye-slash");
   passwordToggle.classList.remove("fa-eye");
  }
 });
});
