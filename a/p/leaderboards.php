<html>
<head>
	<title>Edit Students</title>
	<?php require_once('header.php'); ?>
</head>
<body>
<?php 
$studentData = $db_server->query("SELECT * FROM studentdata WHERE current = 1 ORDER BY firstname");
 while ($studentRow = mysqli_fetch_assoc($studentData)) {
	print_r($studentRow);
}
?>
</body>
</html>