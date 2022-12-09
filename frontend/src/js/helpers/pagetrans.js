import Swup from 'swup';
import SwupPreloadPlugin from '@swup/preload-plugin';
import SwupOverlayTheme from '@swup/overlay-theme';


const swup = new Swup({
  containers: ["#opo-page-container"],
  plugins: [
    new SwupPreloadPlugin(),
    new SwupOverlayTheme({
      color: '#CC0000',
      duration: 500,
      direction: 'to-right',
    })]
});


function reloadJS() {
  let scriptSRC = document.querySelector('.opo-bundle-script').src;
  document.querySelector('.opo-bundle-script').remove();
  let script = document.createElement('script');
  script.src = scriptSRC;
  script.className = 'opo-bundle-script';
  document.body.appendChild(script);
}

swup.on('contentReplaced', reloadJS);
swup.on('animationOutDone', () => {
  document.documentElement.style.scrollBehavior = 'auto';
  window.scrollTo({
    top: 0,
    behavior: "auto"
  })
  document.documentElement.style.scrollBehavior = 'smooth';
});