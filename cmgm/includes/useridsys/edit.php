<form action="create.php" method="post" autocomplete="off">
   <p>Username: </p>
   <input type="text" name="username" id="username" required>
   <p>PIN: </p>
   <input type="password" maxlength="4" name="pin" id="pin" required>
   <p>Google Maps API Key: </p>
   <input type="text" name="gmaps_api_key" id="gmaps_api_key">
   <p>Latitude: </p>
   <input type="text" name="default_latitude" id="default_latitude" required>
   <p>Longitude: </p>
   <input type="text" name="default_longitude" id="default_longitude" required>
   <p>Carrier: </p>
   <select class="custominput dropdown" autocomplete="on" name="default_carrier">
     <option value="T-Mobile">T-Mobile</option>
     <option value="ATT">AT&T</option>
     <option value="Verizon">Verizon</option>
     <option value="Sprint">Sprint</option>
   </select>
   <select class="custominput dropdown" autocomplete="on" name="theme">
     <option value="black">AMOLED Black</option>
     <option value="dark">Dark</option>
     <option value="light">Light</option>
     <option value="light">Original</option>
   </select>
   <input type="button" class="submitbutton" onclick="myFunction();" style="color: #00000;"  value="Locate">
   <input type="submit" class="submitbutton" style="color: #00000;"  value="Submit">
</form>
