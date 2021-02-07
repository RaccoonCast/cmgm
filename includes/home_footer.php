<footer>
    <a class="footerlink" id="database">Database</a>
</footer>

<script>
// this will get overwritten if GPS is working
var cookie_latitude = "<?php echo $cookie_latitude?>"
var cookie_longitude = "<?php echo $cookie_longitude?>"

var href_db = "database/DatabaseDB.php?latitude=" + cookie_latitude + "&longitude=" + cookie_longitude;
var yourElement_db = document.getElementById("database");
yourElement_db.setAttribute("href", href_db);


if (navigator.geolocation) {
  function showPosition(position) {
    var latitude = position.coords.latitude;
    var longitude = position.coords.longitude;
    var href_db = "database/DatabaseDB.php?latitude=" + latitude + "&longitude=" + longitude;
    var yourElement_db = document.getElementById("database");
    yourElement_db.setAttribute("href", href_db);
  }
  navigator.geolocation.getCurrentPosition(showPosition);
}


</script>
