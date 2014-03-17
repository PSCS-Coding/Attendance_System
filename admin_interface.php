<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="attendance.css">
<title>Admin</title>

<body>
<?php
// connect to sql
$db_server = mysql_connect("localhost", "pscs", "Courage!");
if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());
mysql_select_db("attendance", $db_server)
	or die("Unable to select database: " . mysql_error());
?>
<!-- Table interface for looking at the students 
Interface for deleting as well -->
<table>
	<tr>
		<th>Student</th>
	</tr>
<?php
//Select table from database, queries firstname and lastname of current students
$userdata = mysql_query("SELECT firstname,lastname FROM studentdata WHERE current='1' ORDER BY firstname ASC");

//Specifies number of rows for the for loop
$rows = mysql_num_rows($userdata);

//defines blank variable to store each name as part of an array
$users = array();

//appends each name value into the blank array
	for ($i = 0 ; $i < $rows ; ++$i) {
		$student_name = mysql_result($userdata, $i);
		array_push ($users, $student_name);
	}
        
	foreach ($users as $user) {
		$rowdata = mysql_fetch_array($userdata);
		echo "<tr>";
			echo "<td class='data_table'>" . $rowdata[1] . $rowdata[2] . "</td>";
		echo "</tr>";
	}	
?>
</table>

</body>
</html>