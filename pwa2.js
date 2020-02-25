if ("serviceWorker" in navigator) {
  if (navigator.serviceWorker.controller) {
    console.log("poop");
  } else {
    navigator.serviceWorker
      .register("pwa.js", {
        scope: "./"
      })
      .then(function (reg) {
      });
  }
}
