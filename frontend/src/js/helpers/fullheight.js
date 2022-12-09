function fitScreenHeight() {
  let height = window.innerHeight + "px";
  document.querySelectorAll(".h-screen").forEach(function (el) {
    el.style.height = height;
  });
  document.querySelectorAll(".min-h-screen").forEach(function (el) {
    el.style.minHeight = height;
  });
}

if (document.querySelector(".h-screen") || document.querySelector(".min-h-screen")) {
  window.addEventListener("resize", fitScreenHeight);
  window.addEventListener("DOMContentLoaded", fitScreenHeight);
}