<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="attendance.css">
<title>test form</title>
<body>
<?php
function changestatus($name, $status, $comment){
$query = "INSERT INTO studentInfo (name, status, comments)
			VALUES ('$name', '$status', '$comment')";
			$result = mysql_query($query)
            or die('Error querying database.');
}
?>
    <table style="width : 80%">
        <tr>
            <td><input type="submit" value="Present" name="present"></td>
        </tr>
        <tr>
            <td><input type="submit" value="Offsite" name="offsite"></td>
            <td><input type="text" name="location">
                <label for="location">Location</label></td>
            <td><input type="text" name="offtime">
                <label for="offtime">Return time</label></td>
        </tr>
        <tr>
            <td><input type="submit" value="Field Trip" name="fieldtrip"></td>
            <td><select>
                <option value="scobie">Scobie</option>
                <option value="nic">Nic</option>
                <option value="crysta">Crysta</option>
                </select></td>
            <td><input type="text" name="ftlocation">
                <label for="ftlocation">Location</label>
            </td>
        <tr>
            <td><input type="submit" value="Sign Out" name="signout"></td>
        </tr>
    </table>    
<?php
    $db_server = mysql_connect("localhost", "pscs", "Courage!");

if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());

mysql_select_db("attendance", $db_server)

or die("Unable to select database: " . mysql_error());

?>

<!-- ===== render table ===== -->
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
        echo "<td><input type='checkbox' name='person[]' value='" . $rowdata[0] . "' form='main' class='c_box'><form action='presentbutton.php' method='post'>
		<input type='hidden' name='present' value='". $user . "'>
		<input type='submit' value='P' class='p_button'></form></td>";
        echo "<td>" . $rowdata[0] . "</td>";
        echo "<td>" . $rowdata[1] . "</td>";
        echo "<td>" . $rowdata[2] . "</td>";
        echo "</tr>";
	}
	else {
		echo "<tr>";
        echo "<td><input type='checkbox' name='person[]' value='" . $rowdata[0] . "' form='main'/></td>";
        echo "<td>" . $rowdata[0] . "</td>";
        echo "<td>" . $rowdata[1] . "</td>";
        echo "<td>" . $rowdata[2] . "</td>";
        echo "</tr>";
	}	
}	
?>
</body>
</html>