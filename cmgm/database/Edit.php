<?php
header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.1/jquery.min.js"></script>
<script src="../js/copyToClipboard.js"></script>
<script src="../js/database.js"></script>
<?php
$titleOverride = "true";
include "../functions.php";
include "../includes/useridsys/getUsername.php";
include "includes/edit/delete.php";
include "includes/edit/lockorunlock.php";
include "../js/database-edit.js.php";

if (isset($_GET['id_search'])) $id = $_GET['id_search'];
if (isset($_GET['back'])) $back_num = $_GET['back'];
if (isset($_GET['next'])) $next_num = $_GET['next'];
if (isset($_GET['id'])) $id = $_GET['id'];
if (isset($_POST['id'])) $id = $_POST['id'];
if (isset($_GET['redirPage'])) $redirPage = $_GET['redirPage'];
if (isset($_POST['redirPage'])) $redirPage = $_POST['redirPage'];
if (!isset($redirPage)) $redirPage = "Edit";
if (isset($_GET['delete'])) $delete = $_GET['delete'];
if (isset($_POST['delete'])) $delete = $_POST['delete'];
if (isset($_GET['lock_status']))  $lock_status = $_GET['lock_status'];
if (isset($_POST['lock_status'])) $lock_status = $_POST['lock_status'];

// If $POST_NEW is set create a new DB wherever an ID is available.
if (isset($_POST['new'])) include "includes/edit/create_new.php";

// Read data from SQL DB
if (isset($id)) include "includes/edit/sql_mgm/read_data.php";

// Unlock/Lock
if (@$edit_lock != $userID && !empty($edit_lock)) $padlock = "true";
if (@$edit_lock == $userID && !empty($edit_lock)) $padlock = "false";
if (empty($edit_lock)) $padlock = "false";
if ($padlock == "true") echo getUsername($edit_lock,$conn) . " blocked editing.";

// Unlock/Lock controls
if ($padlock == "false") if (isset($lock_status)) lockorunlock($id,$lock_status,$redirPage,$conn,$userID);

// Not found? Ok... let's try some things.
if (!isset($status) OR isset($_GET['id_search'])) include "includes/edit/missing_id.php";

// SQL Edit Code
if ($padlock == "false") include "includes/edit/sql_mgm/the_edit_code.php";

// If delete tag is specified
if ($padlock == "false") if (@$delete == "true") delete($id,"true",$redirPage,$conn);
if ($padlock == "false") if (@$delete == "false") delete($id,"false",$redirPage,$conn);

// Create links for sv_a/b/c/d, evidence_a/b/c, photo_a/b/c/d/e/f, misc a/b/c
include "includes/edit/file_attach_link_gen.php";
?>
</head>
<body>
<?php
// THE FORM
include "includes/edit/the_form.php";
$no_edit = "true";
if (!empty($latitude)) include "includes/edit/mapWithPin.php";
echo '<div class="edit_utilitiy_holder">';
if (!isset($delete) && !isset($_GET['new']) && !isset($_GET['lock_status']) && $padlock == "false") include "includes/edit/prev_next.php";
if (!isset($delete) && !isset($_GET['new']) && !isset($_GET['lock_status']) && $padlock == "false") include "includes/edit/id_input/footer_search.php";
echo '</div>';
?>
<script> if ( window.history.replaceState ) { window.history.replaceState( null, null, window.location.href );}</script>
<div style="padding-bottom: 70px" class="pre_footer"></div>
<?php include "includes/footer.php"; ?>
</body>
</html>
