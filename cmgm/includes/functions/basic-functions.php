<?php
// basic basic functions
function redir($page,$time) {
echo '<meta http-equiv="Refresh" content="' . $time . '; url=' . $page . '">';
die();
}

// The mobile detection function
function isMobile() {
  // Check if the 'mobile' GET parameter is explicitly set
  if (isset($_GET['mobile'])) {
      return true;
  }

  // Ensure 'HTTP_USER_AGENT' exists to avoid errors
  $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';

  // Check for mobile device keywords in the user agent
  return preg_match(
      "/(android|avantgo|blackberry|bolt|boost|cricket|docomo|fone|hiptop|mini|mobi|palm|phone|pie|tablet|up\.browser|up\.link|webos|wos)/i",
      $userAgent
  );
}
if(isMobile()) {
  $isMobile = "true";
} else {
  $isMobile = "false";
}
function set_safe_cookie(string $name, string $value, array $options = []): bool
{
    // Default cookie options
    $defaults = [
        'expires'  => time() + 365 * 24 * 60 * 60, // 1 year from now
        'domain'   => '.cmgm.us',
        'path'     => '/',
        'secure'   => !empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off',
        'httponly' => true,
        'samesite' => 'none',
    ];

    // Merge caller options over defaults
    $opts = array_merge($defaults, $options);

    return setcookie($name, $value, $opts);
}

if (isset($_GET['signOut'])) {
  unset($_COOKIE['userID']);
  foreach ( $_COOKIE as $key => $value ){
    set_safe_cookie($key, "");
    }
} 

$curr_userIP = $_SERVER["REMOTE_ADDR"];
?>
