<!doctype html>
<html lang="en">
<head>
  <?php include '../functions.php';?>
  <link rel="stylesheet" href="styles/Edit/desktop.css">
</head>
<body>
<?php
if (isset($_GET['id'])) $id = $_GET['id'];
if (isset($_POST['id'])) $id = $_POST['id'];

// THIS CODE ONLY GETS RUN AFTER CONFIRMATION

if (isset($_POST['delete_confirmed'])) {
  $sql = "DELETE FROM `cmgm`.`database_db` WHERE  `id`=" . $id . ";";
  mysqli_query($conn, $sql); // First parameter is just return of "mysqli_connect()" function
  echo '<meta http-equiv="refresh" content="0;URL=../" /> ';
}
?>
<p>Are you sure you want to delete?</p>
<form action="Delete.php" id="form1" method="post">
  <input type="hidden" name="id" value="<?php echo $id?>">
  <input type="hidden" name="delete_confirmed" value="true">
  <button type="submit" form="form1" value="Submit">Delete</button>
<br>
</form>
</body>
</html>
