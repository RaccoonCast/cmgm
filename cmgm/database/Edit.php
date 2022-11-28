<?php
header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<script src="https://code.jquery.com/jquery-latest.min.js"></script>
<script src="../js/copyToClipboard.js"></script>
<script src="../js/database.js"></script>
<script src="../js/redir.js"></script>
<?php
ini_set('display_errors', '1');
ini_set('display_startup_errors', '1');
error_reporting(E_ALL);
$titleOverride = "true";
$allowGuests = "true";
include "../functions.php";
if (isset($_GET['pciplus'])) include "../includes/pciplus/main.php";

if (isset($_GET['back'])) $back_num = $_GET['back'];
if (isset($_GET['next'])) $next_num = $_GET['next'];
if (isset($_GET['q'])) $id = $_GET['q'];
if (isset($_POST['q'])) $id = $_POST['q'];
if (isset($_GET['id'])) $id = $_GET['id'];
if (isset($_POST['id'])) $id = $_POST['id'];
if (isset($_GET['redirPage'])) $redirPage = $_GET['redirPage'];
if (isset($_POST['redirPage'])) $redirPage = $_POST['redirPage'];
if (!isset($redirPage)) $redirPage = "Edit";
if (isset($_GET['delete'])) $delete = $_GET['delete'];
if (isset($_POST['delete'])) $delete = $_POST['delete'];
if (isset($_GET['lock_status']))  $lock_status = $_GET['lock_status'];
if (isset($_POST['lock_status'])) $lock_status = $_POST['lock_status'];
if (isset($_GET['new']) OR isset($_GET['pciplus']) OR isset($_POST['new'])) $new = "true";

// If $POST_NEW is set create a new DB wherever an ID is available.
if (isset($_POST['new'])) {
  $id = mysqli_fetch_array(mysqli_query($conn, "SELECT t1.id + 1 FROM db t1 WHERE NOT EXISTS (SELECT id FROM db t2 WHERE t2.id = t1.id + 1) LIMIT 1"))['t1.id + 1'];
  include "includes/edit/create_new.php";
}

// Read data from SQL DB
if (isset($id)) include "includes/edit/sql_mgm/read_data.php";

// Not found? Ok... let's try some things.
if (!isset($status) && !isset($new)) {
  include "includes/edit/Edit-DB.php";
  die();
}

// Unlock/Lock
include "includes/edit/lockorunlock.php";
include "includes/edit/delete.php";
include "../includes/useridsys/getUsername.php";
if (@$edit_lock != $userID && !empty($edit_lock)) $padlock = "true";
if (@$edit_lock == $userID && !empty($edit_lock)) $padlock = "false";
if (empty($edit_lock)) $padlock = "false";
if ($padlock == "true") echo getUsername($edit_lock,$conn) . " blocked editing.";
if ($userID == "guest") $padlock = "true";

// Unlock/Lock controls
if ($padlock == "false") if (isset($lock_status)) lockorunlock($id,$lock_status,$redirPage,$conn,$userID);

// Include JS file to require cerain fields to be set.
include "../js/database-edit.js.php";

// SQL Edit Code
if ($padlock == "false") include "includes/edit/sql_mgm/the_edit_code.php";

if (isset($_GET['id'])) { if (@$_GET['id'] == "new") {
  redir("Edit.php?id=$id","0");
} }

// If delete tag is specified
if ($padlock == "false") if (@$delete == "true") delete($id,"true",$redirPage,$conn,$userID);
if ($padlock == "false") if (@$delete == "false") delete($id,"false",$redirPage,$conn,$userID);

// Create links for sv_a/b/c/d, evidence_a/b/c, photo_a/b/c/d/e/f, misc a/b/c
include "includes/edit/file_attach_link_gen.php";
?>
</head>
<body>
<?php
// THE FORM
include "includes/edit/the_form.php";
$no_edit = "true";
echo '<div class="edit_utilitiy_holder">';
if (!isset($delete) && !isset($new) && !isset($_GET['lock_status']) && $padlock == "false") include "includes/edit/prev_next.php";
if (!isset($delete) && !isset($new) && !isset($_GET['lock_status']) && $padlock == "false") include "includes/edit/id_input/footer_search.php";
echo '</div>';
?>
<script> if ( window.history.replaceState ) { window.history.replaceState( null, null, window.location.href );}</script>
<div style="padding-bottom: 70px" class="pre_footer"></div>
<?php include "includes/footer.php"; ?>
</body>
</html>
