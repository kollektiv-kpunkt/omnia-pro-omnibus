import axios from "axios";
import reloadJS from "../helpers/pagetrans";
import feather from "feather-icons";

if (document.querySelector(".opo-kandi-gallery-item-inner img")) {
  setupKandiGallery();
}

function setupKandiGallery() {
  document.querySelectorAll(".opo-kandi-gallery-item-inner img").forEach((item) => {
    item.addEventListener("click", () => {
      item = item.closest(".opo-kandi-gallery-item-outer");
      const kandiID = item.id;
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
if (kandiSlug) {
  const kandiID = document.querySelector(`[data-slug="${kandiSlug}"]`).id;
  document.getElementById(kandiID).querySelector(".opo-kandi-gallery-item-inner img").click();
}

if (document.querySelector("button.opo-constituency-selector")) {
  const button = document.querySelector(".opo-constituency-selector-button")
  const selector = button.querySelector("button.opo-constituency-selector")
  const menu = document.querySelector(".opo-constituencies-wrapper")
  selector.addEventListener("click", (e) => {
    console.log(e.target)
    selector.classList.toggle("open");
    if (selector.classList.contains("open")) {
      menu.style.maxHeight = `40vh`;
      selector.querySelector("svg").style.transform = "rotate(180deg)";
    } else {
      menu.style.maxHeight = "";
      selector.querySelector("svg").style.transform = "rotate(0)";
    }
  })

  document.querySelectorAll(".opo-constituency").forEach((item) => {
    item.addEventListener("click", () => {
      item.querySelector("svg").classList.toggle("hidden");
      let constituencies;
      if (!item.querySelector("svg").classList.contains("hidden")) {
        constituencies = [
          ...JSON.parse(button.getAttribute("data-constituencies")),
          item.getAttribute("data-constituency-id")
        ];
      } else {
        constituencies = JSON.parse(button.getAttribute("data-constituencies"));
        constituencies = constituencies.filter((constituency) => {
          return constituency != item.getAttribute("data-constituency-id");
        });
      }
      button.setAttribute("data-constituencies", JSON.stringify(constituencies));
    })

  })
  document.querySelector(".opo-constituencies-filter-button").addEventListener("click", async (e) => {
    let constituencies = JSON.parse(button.getAttribute("data-constituencies"));
    let url = "/part/kandis";
    url += (constituencies.length > 0) ? "?wahlkreise=" + constituencies.join(",") : ""
    let grid = await (await axios.get(url)).data;
    document.querySelector(".opo-kandi-gallery-outer").innerHTML = grid;
    console.log("this");
    selector.click();
    setupKandiGallery();
    feather.replace();
  })
}