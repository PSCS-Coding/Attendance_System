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
?>
<div>
<form method='post' action='attendancetopgui.php' id='main'>

<div>
    <input type="submit" value="Present" name="present">
</div>

<div>
    <input type="submit" value="Offsite" name="offsite">
    
    <input type="text" name="location">
    <label for="location">Location</label>
	
    <input type="text" name="offtime">
    <label for="offtime">Return time</label>
</div>

<div>
    <input type="submit" value="Field Trip" name="fieldtrip">
   
<?php
	$fac_query = "SELECT * FROM facilitators ORDER BY facilitatorname ASC";
	$fac_data = mysql_query($fac_query);
	
	if (!$fac_data) die ("Database access failed: " . mysql_error());
	$fac_rows = mysql_num_rows($fac_data);
	
	$facilitators = array();

	for ($i = 0 ; $i < $fac_rows ; ++$i)
		{
		$fac_name = mysql_result($fac_data, $i);
		array_push ($facilitators, $fac_name);
		}
	
		print_r ($facilitators);
		echo "<select name='facilitator'><option value=''>Select Facilitator</option>";
		foreach ($facilitators as $facilitator_option) {
			//$fac_raw = mysql_query("SELECT * FROM 'facilitators' WHERE facilitatorname = '".$facilitator_option."' ORDER BY facilitatorname
			//ASC LIMIT 1");
			//$fac_data = mysql_fetch_array($fac_raw);
			
			echo "<option value= '" . $facilitator_option . "' >" . $facilitator_option . "</option>";
		}
?>
        </select>
        <input type="text" name="fttime">
        <label for="fttime">Return time</label>
</div>

<div>
    <input type="submit" value="Sign Out" name="signout">
</div>
</div>
</form>
<?php
function changestatus($f_name, $f_status, $f_comment) {
	$query = "INSERT INTO studentInfo (name, status, comments)
			VALUES ('$f_name', '$f_status', '$f_comment')";
	$result = mysql_query($query)
		or die('Error querying database.');
}

if (isset($_POST['present'])) {
	$name = $_POST['person'];
	foreach ($name as $student)
	{
		changestatus($student, 'Present', '');
	}
}

if (isset($_POST['offsite'])) {
	$name = $_POST['person'];
	$status = "at " . $_POST['location'] . " returning at " . $_POST['offtime'];
	foreach ($name as $student)
	{
		changestatus($student, 'Offsite', $status);
	}
}

if (isset($_POST['fieldtrip'])) {
	$name = $_POST['person'];
	$status = "with " . $_POST['facilitator'] . " returning at " . $_POST['fttime'];
	foreach ($name as $student)
	{
		changestatus($student, 'Field Trip', $status);
	}
}
if (isset($_POST['signout'])) {
	$name = $_POST['person'];
	foreach ($name as $student)
	{
		changestatus($student, 'Checked out', '');
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
    
<table style="width:80%" class='data_table'>
    <tr>
        <th class='data_table'></th>
        <th class='data_table'>Student</th>
        <th class='data_table'>Status</th>
        <th class='data_table'>Comment</th>
    </tr>
    <?php
	
	$checkboxes = array();
    foreach ($users as $user) {
		$raw = mysql_query("SELECT * FROM studentInfo WHERE name ='".$user."' ORDER BY time DESC LIMIT 1");
		$rowdata = mysql_fetch_array($raw);
	
	if ($rowdata[1] == 'Offsite' || $rowdata[1] == 'Checked Out' || $rowdata[1] == 'Field Trip') {
        echo "<tr>";
        echo "<td class='data_table'><input type='checkbox' name='person[]' value='" . $rowdata[0] . "' form='main' class='c_box'><form action='presentbutton.php' method='post'>
		<input type='hidden' name='present' value='". $user . "'>
		<input type='submit' value='P' class='p_button'></form></td>";
        echo "<td class='data_table'>" . $rowdata[0] . "</td>";
        echo "<td class='data_table'>" . $rowdata[1] . "</td>";
        echo "<td class='data_table'>" . $rowdata[2] . "</td>";
        echo "</tr>";
	}
	
	else {
		echo "<tr>";
        echo "<td class='data_table'><input type='checkbox' name='person[]' value='" . $rowdata[0] . "' form='main'/></td>";
        echo "<td class='data_table'>" . $rowdata[0] . "</td>";
        echo "<td class='data_table'>" . $rowdata[1] . "</td>";
        echo "<td class='data_table'>" . $rowdata[2] . "</td>";
        echo "</tr>";
	}	
}	
    unset($_POST['submit']);  
   ?>
</table>
</body>
</html>