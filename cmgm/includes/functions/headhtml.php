<?php if (!isset($titleOverride)) { ?>
<title>CMGM - <?php echo basename($_SERVER['PHP_SELF'],'.php');?></title>
<link rel="icon" type="image/png" href="/images/logo.png">
<?php } ?>
<meta name="color-scheme" content="<?php if ($theme == "black") { echo 'dark'; } else {echo 'light';} ?>">
<!--
<script type="module">
import 'https://cdn.jsdelivr.net/npm/@pwabuilder/pwaupdate';

const el = document.createElement('pwa-update');
document.body.appendChild(el);
</script>
<meta name="theme-color" content="#fff"/>
<link rel="apple-touch-icon" href="images/icons-192.png">
<link rel='manifest' href='/manifest.json'>
-->
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=yes">
<link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400&display=swap" rel="stylesheet">
<link rel="preconnect" href="https://fonts.gstatic.com">
