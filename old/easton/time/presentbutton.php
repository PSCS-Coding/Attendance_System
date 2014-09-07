<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="attendance.css">
<title>attendance system tests</title>
<body>
<?php
$db_server = mysql_connect("localhost", "pscs", "Courage!");
print_r ($_POST);
$user = $_POST['present'];
if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());

mysql_select_db("attendance", $db_server)

	or die("Unable to select database: " . mysql_error());
	
$presentquery = "INSERT INTO studentInfo (name, status)
        VALUES ('$user', 'Present')";

$presentsubmit = mysql_query($presentquery);

header('Location: http://code.pscs.org/attendance/attendance.php');
exit;
?>
</body>
</html>