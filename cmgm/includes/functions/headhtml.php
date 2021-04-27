<title>EvilCM - <?php echo $without_extension?></title>
<link rel="icon" type="image/png" href="/images/logo.png">
<link rel='manifest' href='/manifest.json'>
<link rel="apple-touch-icon" href="images/icons-192.png">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
<meta charset="utf-8">
<meta name="theme-color" content="#fff"/>
<link rel="preconnect" href="https://fonts.gstatic.com">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400&display=swap" rel="stylesheet">
<?php
if ($_SERVER['SERVER_NAME'] == 'cmgm.gq') {
  $urlPrefix = "https://";
} else {
  $urlPrefix = "http://";
}
$siteUrl = $urlPrefix . ' ' . $_SERVER['SERVER_NAME'];
?>
