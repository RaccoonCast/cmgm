function cleanUrlParam() {
  const url = new URL(window.location);
  if (url.searchParams.has("updateDb")) {
    url.searchParams.delete("updateDb");
    history.replaceState(null, "", url);
  }
}

// New page load somehow
window.addEventListener("DOMContentLoaded", () => {
  cleanUrlParam();
});

// In case page is loaded from back/fwd cache, we still want to cleanup
window.addEventListener("pageshow", (e) => {
  if (e.persisted) {
    cleanUrlParam(); // handle BFCache restore
  }
});
