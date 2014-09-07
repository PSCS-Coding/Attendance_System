<DOCTYPE html>
<html>
<head>
<title>Query DB Easton</title>
</head>
<body>
<p class="bodytext">Attendance</p>
<div class="input">
<li>
<form action="query.php" method="post">
<p>Name:</p><input type="text" name="name">
<p>Status:</p><input type="text" name="status">
<p>Comments:</p><input type="text" name="comments">
<input name="submit" type="submit">
</form>
</li>
</div>
<?php
	// Defines variables from input
	if(isset($_POST['name']) &&
	   isset($_POST['status']) &&
	   isset($_POST['comments']))
	{
		$name = $_POST['name'];
		$status = $_POST['status'];
		$comments = $_POST['comments'];
	}
	// Connect to DB
require_once 'login.php';
$db_server = mysqli_connect("localhost", "pscs", "Courage!", "attendance");
	// Query + values
	if (isset($_POST['submit'])) {
$query = "INSERT INTO studentInfo (name, status, comments)
VALUES ('$name', '$status', '$comments')";
	// Issue INSERT query
$result = mysqli_query($db_server, $query)
or die('Error querying database.');
}
?>
</body>
<style>
.bodytext {
text-align:center;
}
li {
float:left;
}
</style>
</html>