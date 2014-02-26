<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="attendance.css">
<title>attendance system tests</title>

<body>
<?php
// connect to sql
$db_server = mysql_connect("localhost", "pscs", "Courage!");

if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());

mysql_select_db("attendance", $db_server)

	or die("Unable to select database: " . mysql_error());
	
$userdata = mysql_query("SELECT DISTINCT name FROM studentInfo");
$users = mysql_fetch_array($userdata);

print_r($users);

foreach ($users as $user) {
	mysql_query("SELECT * FROM studentInfo WHERE name =" . $user . " ORDER BY timestamp DESC LIMIT 1");
//	echo $user . "<br />";
	}
?>
    
<table style="width:80%">
    <tr>
        <th>Student</th>
        <th>Status</th>
        <th>Comment</th>
        <th>Time</th>
    </tr>
    <tr>
        <td>Anne</td>
        <td>Present</td>
        <td></td>
        <td>5:00</td>
    </tr>
    <tr>
        <td>Bonnie</td>
        <td>Present</td>
        <td></td>
        <td>9:30</td>    
    </tr>
    <tr>
        <td>Clara</td>
        <td>Present</td>
        <td></td>
        <td>1:11</td>
    </tr>
</table>
</body>

</html>