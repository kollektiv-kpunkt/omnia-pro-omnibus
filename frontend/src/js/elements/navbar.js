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
    menuWrapper.classList.add("scrollbar");
  }
  if (window.scrollY < 100) {
    menuInner.style = `--menu-color: ${defaultColor}`;
    menuInner.classList.remove("bg-accent");
    menuWrapper.classList.remove("scrollbar");
    menuWrapper.classList.remove("scrollup");
  }
  return window.scrollY;
}

let prevScroll = window.scrollY;
const menuWrapper = document.querySelector(".opo-navbar-outer");
const menuInner = document.querySelector(".opo-navbar-inner");
const defaultColor = getComputedStyle(menuInner).getPropertyValue("--menu-color");
let prevColor = defaultColor;

if (document.querySelector(".opo-navbar-outer")) {
  document.addEventListener("scroll", () => {
    prevScroll = setMenus(prevScroll, menuWrapper, menuInner, defaultColor);
  });
  document.addEventListener('swup:contentReplaced', () => {
    prevScroll = setMenus(prevScroll, menuWrapper, menuInner, defaultColor);
  });
}

if (document.querySelector(".opo-navbar-menu")) {
  let isScrollbar = false;
  document.querySelector(".opo-navbar-menu").addEventListener("click", () => {
    if (!document.documentElement.classList.contains("menu-open")) {
      if (menuWrapper.classList.contains("scrollbar")) {
        isScrollbar = true;
        menuWrapper.classList.remove("scrollbar");
        menuInner.classList.remove("bg-accent");
      } else {
        isScrollbar = false;
      }
      document.documentElement.classList.add("menu-open");
      prevColor = getComputedStyle(menuInner).getPropertyValue("--menu-color");
      menuInner.style = "--menu-color: #ffffff";
      document.querySelector(".opo-mainnav-wrapper").classList.add("visible");
      setTimeout(() => {
        document.querySelector(".opo-mainnav-wrapper").classList.add("interactive");
      }, 500);
    } else {
      if (isScrollbar) {
        menuWrapper.classList.add("scrollbar");
        menuInner.classList.add("bg-accent");
      }
      menuInner.style = `--menu-color: ${prevColor}`;
      document.querySelector(".opo-mainnav-wrapper").classList.remove("interactive");
      document.documentElement.classList.remove("menu-open");
      setTimeout(() => {
        document.querySelector(".opo-mainnav-wrapper").classList.remove("visible");
      }, 500);
    }
  });
}