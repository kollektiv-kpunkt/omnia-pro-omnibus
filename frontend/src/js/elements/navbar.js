function setMenus(scrollPrevious, menuWrapper, menuInner, defaultColor) {
  if (window.scrollY > scrollPrevious) {
    menuWrapper.classList.add("scrollup");
  }
  if (window.scrollY < scrollPrevious) {
    menuWrapper.classList.remove("scrollup");
  }
  if (window.scrollY > 100) {
    menuInner.style = "--menu-color: #ffffff";
    menuInner.classList.add("bg-accent");
    menuWrapper.classList.add("mix-blend-difference", "scrollbar");
  }
  if (window.scrollY < 100) {
    menuInner.style = `--menu-color: ${defaultColor}`;
    menuInner.classList.remove("bg-accent");
    menuWrapper.classList.remove("mix-blend-difference", "scrollbar");
    menuWrapper.classList.remove("scrollup");
  }
  return window.scrollY;
}

let prevScroll = window.scrollY;
const menuWrapper = document.querySelector(".opo-navbar-outer");
const menuInner = document.querySelector(".opo-navbar-inner");
const defaultColor = getComputedStyle(menuInner).getPropertyValue("--menu-color");

if (document.querySelector(".opo-navbar-outer")) {
  document.addEventListener("scroll", () => {
    prevScroll = setMenus(prevScroll, menuWrapper, menuInner, defaultColor);
  });
  document.addEventListener('swup:contentReplaced', () => {
    prevScroll = setMenus(prevScroll, menuWrapper, menuInner, defaultColor);
  });
}