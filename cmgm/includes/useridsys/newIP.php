<title>Unrecongized Device</title>
<META name="viewport" content="width=320; initial-scale=1.0; maximum-scale=1.0; user-scalable=0;" />
<script src="/js/setCookie.js"></script>
<?php
$siteroot = $_SERVER['DOCUMENT_ROOT'];
if (strtoupper(substr(PHP_OS, 0, 3))) {
    $secret_pass = file_get_contents($siteroot . '/secret_ip_whitelist_pass.hiddenpass', true);
    $ipinfo_token = file_get_contents($siteroot . '/secret_ipinfo_token.hiddenpass', true);
} else {
    $secret_pass = file_get_contents($siteroot . '\secret_ip_whitelist_pass.hiddenpass', true);
    $ipinfo_token = file_get_contents($siteroot . '\secret_ipinfo_token.hiddenpass', true);
}

// Check Real Quick if IP is in there to verify it's not.
$ip = $_SERVER['REMOTE_ADDR'];
/*
$sql = "SELECT * FROM userID WHERE userIP = '$ip'";
$result = mysqli_query($conn,$sql);
while($row = $result->fetch_assoc()) { foreach ($row as $key => $value) {$$key = $value;} }
if (isset($userIP)) { redir($_SERVER['REQUEST_URI'],"0"); }

$sql = "SELECT * FROM userID WHERE userIP = '$ip'";
$result = mysqli_query($conn,$sql);

while($row = $result->fetch_assoc()) {
    foreach ($row as $key => $value) {
        $$key = $value;
        }
      }

*/
if (!empty($_POST['userid'])) {
    // Check user ID validity
    if (!preg_match('/^[A-Za-z0-9]+$/', $_POST['userid'])) {
        die('Invalid userid');
    }

    // Set cookie (using PHP, sends Set-Cookie header)
    setcookie('userID', $_POST['userid']);

    // Use real redirect instead of meta (so cookie can pass through)
    header('Location: ' . $_SERVER['REQUEST_URI']);

    // No need to redirect to settings, since user pre-exists
    // if ($_SERVER['REQUEST_URI'] == "/") {
    //   redir("/settings/","0");
    // }
} elseif (isset($_POST['password']) && $secret_pass == $_POST['password']) {
    // Check for valid username first
    if (!preg_match('/^[A-Za-z0-9-]{3,32}$/', $_POST['username'])) {
        die('Invalid username');
    }

    $url = 'ipinfo.io/' . $curr_userIP . '?token=' . $ipinfo_token;
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_PROXYPORT, 3128);
    curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 2);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);
    $response = json_decode(curl_exec($ch));
    $loc = $response->loc;
    $result_ipinfo = explode(',', $loc);

    // Generate user ID
    $userID = substr(str_shuffle(md5(time())), 0, 32);
    setcookie('userID', $userID);

    $username = $_POST['username'];

    $userIP = $_SERVER['REMOTE_ADDR'];
    $gmaps_api_key_access = 'true';
    $default_carrier = 'ATT';
    $default_latitude = trim($result_ipinfo[0]);
    $default_longitude = trim($result_ipinfo[1]);
    $theme = 'black';
    $gmaps_util = '0';
    $debug_flag = '0';
    $prefLocType = 'gps';
    $cm_mapType = 'osm_street';
    $cm_groupTowers = 'false';
    $cm_showLabels = 'true';
    $cm_showLowAcc = 'true';
    $cmgm_edit_pinspace_warn = 'false'; // (not in INSERT — included for consistency)

    // -------------------
    // PREPARED STATEMENT
    // -------------------

    $stmt = $conn->prepare("
    INSERT INTO userID (
        userID,
        username,
        userIP,
        gmaps_api_key_access,
        default_carrier,
        default_latitude,
        default_longitude,
        theme,
        gmaps_util,
        debug_flag,
        cm_mapType,
        cm_groupTowers,
        cm_showLabels,
        cm_showLowAcc,
        prefLocType
    )
    VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
");

    // Types:
    // s = string
    // d = double (float)
    // i = integer

    $stmt->bind_param(
        'sssssddssssssss',
        $userID,
        $username,
        $userIP,
        $gmaps_api_key_access,
        $default_carrier,
        $default_latitude,
        $default_longitude,
        $theme,
        $gmaps_util,
        $debug_flag,
        $cm_mapType,
        $cm_groupTowers,
        $cm_showLabels,
        $cm_showLowAcc,
        $prefLocType
    );

    $stmt->execute();
    $stmt->close();

    // Redirect logic
    $redirectTarget = str_replace('?switchUser=true', '', $_SERVER['REQUEST_URI']);
    if ($redirectTarget === '/') {
        // New user, go to settings
        header('Location: /settings/');
    } else {
        header("Location: $redirectTarget");
    }

}
?>
   </head>
   <body>
     <form action="<?= str_replace('?signOut', '', $_SERVER['REQUEST_URI']) ?>" method="post" autocomplete="off">
       <p>IP address <?php echo $ip; ?> isn't recognized.</p>
       <p>Please enter magical password.</p>
         <input type="username" name="username" class="textbox" placeholder="Username"><br>
         <input type="password" name="password" class="textbox" placeholder="Password">
         <br><br>
         <input type="submit" class="sb cmgm-btn" value="Submit">
         <!-- <p>This will create a randomized userID that gets stored in the database and in a cookie on your browser to identify whos who. It's primary function right now is to authenticate users.</p> -->
     </form>
     <br>
     <form action="<?= str_replace('?signOut', '', $_SERVER['REQUEST_URI']) ?>" method="post" autocomplete="off">
     Or enter user id:
     <input type="password" name="userid" class="textbox" placeholder="User ID">
     <input type="submit" class="sb cmgm-btn" value="Submit">
     </form>
   </body>
</html>
<?php die(); ?>
