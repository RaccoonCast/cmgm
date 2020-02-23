         // This is the service worker with the Cache-first network
         
         // Add this below content to your HTML page, or add the js file to your page at the very top to register service worker
         
         // Check compatibility for the browser we're running this in
         if ("serviceWorker" in navigator) {
           if (navigator.serviceWorker.controller) {
             console.log("[PWA Builder] active service worker found, no need to register");
           } else {
             // Register the service worker
             navigator.serviceWorker
               .register("pwa.js", {
                 scope: "./"
               })
               .then(function (reg) {
                 console.log("[PWA Builder] Service worker has been registered for scope: " + reg.scope);
               });
           }
         }
		 

// LAT LONG CODE

function myFunction() {
 if (navigator.geolocation) {
         	navigator.geolocation.getCurrentPosition(showPosition);
         }
         
         function showPosition(position) {
         	if (document.getElementById('txtresult').value == '') {
                      var result = position.coords.latitude + "," + position.coords.longitude
           
                      document.getElementById("txtresult").value = result;
         }
         
                  }
              function copy() {
         var copyText = document.querySelector("#txtresult");
         copyText.select();
         document.execCommand("copy");
         }
} 

