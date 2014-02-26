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
	
$userdata = mysql_query("SELECT DISTINCT name FROM studentInfo ORDER BY name ASC");
$rows = mysql_num_rows($userdata);
$users = array();

for ($j = 0 ; $j < $rows ; ++$j)
		{
		$namedata = mysql_fetch_row($userdata);
		array_push($users, $namedata[0]);
		}

print_r($users);

foreach ($users as $user) {
	mysql_query("SELECT * FROM studentInfo WHERE name =" . $user . " ORDER BY timestamp DESC LIMIT 1");
	echo $user . "<br />";
	}
?>
    
<table style="width:80%">
    <tr>
        <th></th>
        <th>Student</th>
        <th>Status</th>
        <th>Comment</th>
        <th>Time</th>
    </tr>
    for ($i = 1; $i <= $NumStudents; $i++) {
        <td><input type="checkbox"/></td>
        <td>$studentInfo[0]</td>
        <td>$studentInfo[1]</td>
        <td>$studentInfo[2]</td>
        <td>$studentInfo[3]</td
    }
</table>
</body>

</html>