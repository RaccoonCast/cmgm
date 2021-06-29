<?php
header('Expires: Sun, 01 Jan 2014 00:00:00 GMT');
header('Cache-Control: no-store, no-cache, must-revalidate');
header('Cache-Control: post-check=0, pre-check=0', FALSE);
header('Pragma: no-cache'); ?>
<!DOCTYPE html>
<html lang="en">
<head>
<?php
$titleOverride = "true";
include "../functions.php";
include "includes/edit/delete.php"; ?>
<?php
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

/// Database column names - todo:// edit_lock(IPs, name?)
$list_of_vars = array('id', 'cellsite_type', 'concealed', 'LTE_1', 'LTE_2', 'LTE_3', 'LTE_4', 'LTE_5', 'LTE_6', 'NR_1', 'NR_2', 'pci_match',
'id_pattern_match', 'sector_match', 'other_user_map_primary', 'carrier', 'latitude', 'longitude', 'city', 'zip', 'state', 'address', 'bio', 'tags', 'status',
'evidence_a', 'evidence_b', 'evidence_c', 'photo_a', 'photo_b', 'photo_c', 'photo_d', 'photo_e', 'photo_f','attached_a', 'attached_b', 'attached_c','permit_score',
'trails_match', 'carriers_dont_trail_match','antennas_match_carrier', 'cellmapper_triangulation', 'image_evidence', 'verified_by_visit', 'sector_split_match',
'archival_antenna_addition', 'only_reasonable_location', 'alt_carriers_here', 'edit_history', 'street_view_url_a', 'street_view_url_b', 'street_view_url_c', 'street_view_url_d');

// If $POST_NEW is set create a new DB wherever an ID is available.
if (isset($_POST['new'])) include "includes/edit/create_new.php";


// Read data from SQL DB
if (isset($id)) include "includes/edit/read_data.php";

// SQL Edit Code
include "includes/edit/the_edit_code.php";

// Not found? Ok... let's try some things.
if (!isset($status) OR isset($_GET['id_search'])) include "includes/edit/missing_id.php";

// If delete tag is specified
if (@$delete == "true") delete($id,"true",$redirPage,$conn);
if (@$delete == "false") delete($id,"false",$redirPage,$conn);
if (isset($delete)) $no_delete = "true"; // widget hide delete option

// Create links for street_view_a/b/c/d, evidence_a/b/c, photo_a/b/c/d/e/f, misc a/b/c
include "includes/edit/file_attach_link_gen.php";
?>
</head>
<body>
<?php
// THE FORM
include "includes/edit/the-form.php";
$no_edit = "true";
?> <div class="widget_holder"> <?php
if (!isset($delete)) if (!isset($_GET['new'])) include "../includes/widgets/widgets.php";
?> </div> <?php
if (!isset($delete)) if (!isset($_GET['new'])) include "includes/edit/prev_next.php";
?>
<script> if ( window.history.replaceState ) { window.history.replaceState( null, null, window.location.href );}</script>
<?php include "includes/footer.php"; ?>
</body>
</html>
