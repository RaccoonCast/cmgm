if ("serviceWorker" in navigator) {
  if (navigator.serviceWorker.controller) {
  } else {
    navigator.serviceWorker
      .register("pwa.js", {
        scope: "./"
      })
      .then(function (reg) {
      });
  }
}
