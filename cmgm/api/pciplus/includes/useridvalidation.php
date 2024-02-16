<?php
// User ID Validation
if (isset($_POST['userID'])) $userID = preg_replace("[^_a-zA-Z0-9,-]", "", $_POST['userID']); // pci+ userID / PCI+ TAC updating
if (isset($_POST['username'])) $username = preg_replace("[^_a-zA-Z0-9,-]", "", $_POST['username']) . " via PCI+"; // cm username \ PCI+ TAC updating
if (isset($_POST['cmgm_session_id'])) $cmgm_session_id = preg_replace("[^_a-zA-Z0-9,-]", "", $_POST['cmgm_session_id']);

if (isset($_POST['cmgm_session_id'])) {
    $username = mysqli_fetch_array(mysqli_query($conn, "SELECT username FROM userID WHERE userID='$cmgm_session_id'"))['username'];
    if (empty($username)) erorr("Invalid user ID.", $cmgm_session_id);
} else {
    if (!isset($userID)) error("User ID not set.","");
    $pciplus_usr = file_get_contents($siteroot . "/secret_pciplus_login_key.hiddenpass", true);
    $tmp_username = mysqli_fetch_array(mysqli_query($conn, "SELECT username FROM userID WHERE userID='$userID'"))['username'];
    if ($tmp_username != $pciplus_usr) error("Invalid user ID.",$userID);
}

?>
