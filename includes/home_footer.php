<div class="footer">
<a id="findlater">FindlaterDB</a>
<a id="database">DatabaseDB</a>
</div>
<script>
if (navigator.geolocation) {
    navigator.geolocation.getCurrentPosition(showPosition);

function showPosition(position) {
  var href_fl = "findlater/FindlaterDB.php?latitude=" + position.coords.latitude + "&longitude=" + position.coords.longitude;
  var href_db = "database/DatabaseDB.php?latitude=" + position.coords.latitude + "&longitude=" + position.coords.longitude;
  var yourElement_fl = document.getElementById("findlater");
  var yourElement_db = document.getElementById("database");
  yourElement_fl.setAttribute("href", href_fl);
  yourElement_db.setAttribute("href", href_db);
}
}
</script>
