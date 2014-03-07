<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="attendance.css">
<title>attendance system tests</title>
<form method='post' action='attendance.php'>
<body>

<table style="width : 80%">
<tr>
    <td><form method='post' action='attendance.php'><select name="status">
        <option value="Present">Present</option>
        <option value="Offsite">Offsite</option>
        <option value="Field Trip">Field Trip</option>
        <option value="Checked Out">Checked Out</option></td>
    <td><input type="submit" value="Submit" name="submit"></td>
    <td>Comment: <input type="text" name="comment"></td>
</tr>
</table>    

<?php
// connect to sql
$db_server = mysql_connect("localhost", "pscs", "Courage!");

if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());

mysql_select_db("attendance", $db_server)

	or die("Unable to select database: " . mysql_error());
if (isset($_POST['submit'])) {
        $name = $_POST['person'];
        $status = $_POST['status'];
        $comments = $_POST['comment'];
      
		foreach ($name as $student) {

            $query = "INSERT INTO studentInfo (name, status, comments)
        VALUES ('$student', '$status', '$comments')";
        $result = mysql_query($query)
        or die('Error querying database.');
}
    }
    
$userdata = mysql_query("SELECT DISTINCT name FROM studentInfo ORDER BY name ASC");
$rows = mysql_num_rows($userdata);
$users = array();

for ($j = 0 ; $j < $rows ; ++$j)
		{
		$namedata = mysql_fetch_array($userdata);
		array_push($users, $namedata[0]);
		}
        
?>
    
<table style="width:80%">
    <tr>
        <th></th>
        <th>Student</th>
        <th>Status</th>
        <th>Comment</th>
    </tr>
    <?php
	
	$checkboxes = array();
    foreach ($users as $user) {
	$raw = mysql_query("SELECT * FROM studentInfo WHERE name ='".$user."' ORDER BY time DESC LIMIT 1");
	$rowdata = mysql_fetch_array($raw);
	
	if ($rowdata[1] == 'Offsite' || $rowdata[1] == 'Checked Out' || $rowdata[1] == 'Field Trip') {
        echo "<tr>";
        echo "<td><input type='checkbox' name='person[]' value='" . $rowdata[0] . "' form='main'><form action='presentbutton.php' method='post'>
		<input type='hidden' name='present' value='". $user . "'>
		<input type='submit' value='P'></form></td>";
        echo "<td>" . $rowdata[0] . "</td>";
        echo "<td>" . $rowdata[1] . "</td>";
        echo "<td>" . $rowdata[2] . "</td>";
        echo "</tr>";
	}
	
	else {
		echo "<tr>";
        echo "<td><input type='checkbox' name='person[]' value=" . $rowdata[0] . "form='main'/></td>";
        echo "<td>" . $rowdata[0] . "</td>";
        echo "<td>" . $rowdata[1] . "</td>";
        echo "<td>" . $rowdata[2] . "</td>";
        echo "<td>" . $rowdata[3] . "</td>";
        echo "</tr>";
	}	
}	
    unset($_POST['submit']);  
   ?>
</table>
</body>
</html>