<script>
function getPos(position) {location.href = "/?q=" + position.coords.latitude + "," + position.coords.longitude;}
function positionGetErr() {location.href = "/?q=" + <?php echo $default_latitude; ?> + "," + <?php echo $default_longitude; ?>;}

navigator.geolocation.getCurrentPosition(getPos, positionGetErr);
</script>
