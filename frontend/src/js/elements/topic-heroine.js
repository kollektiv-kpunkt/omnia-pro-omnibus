if (document.querySelector("a.opo-topic-heroine-read-more")) {
  document.querySelector("a.opo-topic-heroine-read-more").addEventListener("click", function (e) {
    e.preventDefault();
    window.scrollTo({
      top: document.querySelector(".opo-topic-content-outer").offsetTop - 20,
      behavior: "smooth"
    });
  });
}