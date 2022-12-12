if (document.querySelector(".opo-kandi-gallery-item-inner img")) {
  document.querySelectorAll(".opo-kandi-gallery-item-inner img").forEach((item) => {
    item.addEventListener("click", () => {
      item = item.closest(".opo-kandi-gallery-item-outer");
      const kandiID = item.id;
      console.log(kandiID);
      if (item.classList.contains("kandi-active")) {
        closeText(kandiID);
        deactivateKandi(kandiID);
      } else if (document.querySelector(".kandi-active")) {
        closeText(document.querySelector(".kandi-active").id);
        deactivateKandi(document.querySelector(".kandi-active").id);
        activateKandi(kandiID);
        setTimeout(() => {
          openText(kandiID);
        }, 250);
      } else {
        activateKandi(kandiID);
        setTimeout(() => {
          openText(kandiID);
        }, 250);
      }
    });
  });
}

function activateKandi(kandiID) {
  document.querySelector(".opo-kandi-gallery-inner").classList.add("kandi-open");
  document.querySelector(`[id='${kandiID}']`).classList.add("kandi-active");
  let kandiSlug = document.querySelector(`[id='${kandiID}']`).getAttribute("data-slug");
  window.history.replaceState({}, document.title, "/kandi/" + kandiSlug);
}

function deactivateKandi(kandiID) {
  document.querySelector(".opo-kandi-gallery-inner").classList.remove("kandi-open");
  document.querySelector(`[id='${kandiID}']`).classList.remove("kandi-active");
  window.history.replaceState({}, document.title, "/kandis");
}


function openText(kandiID) {
  let textHeight = document.querySelector(`[id='${kandiID}'] .opo-kandi-gallery-item-details`).offsetHeight;
  document.querySelector(`[id='${kandiID}'] .opo-kandi-gallery-item-details-wrapper`).style.maxHeight = `${textHeight}px`;
  setTimeout(() => {
    document.querySelector(`[id='${kandiID}'] .opo-kandi-gallery-item-details`).style.maxHeight = "";
    document.querySelector(`[id='${kandiID}']`).scrollIntoView({
      behavior: "smooth"
    });
  }, 250);
}

function closeText(kandiID) {
  document.querySelector(`[id='${kandiID}'] .opo-kandi-gallery-item-details-wrapper`).style.maxHeight = "";
}



const params = new URLSearchParams(window.location.search);
const kandiSlug = params.get("kandi");
console.log(kandiSlug);
if (kandiSlug) {
  const kandiID = document.querySelector(`[data-slug="${kandiSlug}"]`).id;
  document.getElementById(kandiID).querySelector(".opo-kandi-gallery-item-inner img").click();
}
