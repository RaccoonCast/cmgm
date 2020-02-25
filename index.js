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
}
