<?php
	$row_id = 5;
    define('UPLOAD_DIR', 'uploads/');
	$img = $_GET['base64_file'];
	$img = str_replace('data:image/png;base64,', '', $img);
	$img = str_replace(' ', '+', $img);
	$data = base64_decode($img);
	$file = UPLOAD_DIR . $row_id . '.png';
	$success = file_put_contents($file, $data);
	print $success ? $file : 'Unable to save the file.';
?>