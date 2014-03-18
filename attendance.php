<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="attendance.css">
<title>attendance system tests</title>

<body>

<div>
<form method='post' action='attendance.php' id='main'>

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

// connect to sql
$db_server = mysql_connect("localhost", "pscs", "Courage!");
if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());
mysql_select_db("attendance", $db_server)
	or die("Unable to select database: " . mysql_error());

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
		echo "<select name='facilitator'><option value=''>Select Facilitator</option>";
		foreach ($facilitators as $facilitator_option) {
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
$time = time();
function changestatus($f_name, $f_status, $f_comment, $timeout, $timein) {
	$query = "INSERT INTO studentInfo (name, status, comments, timeout, timein)
			VALUES ('$f_name', '$f_status', '$f_comment', '$timeout', '$timein')";
	$result = mysql_query($query)
		or die('Error querying database.');
}

function validTime($inTime) {
$pattern   =   "/^(([0-9])|([0-1][0-9])|([2][0-3])):?([0-5][0-9])$/";
 if(preg_match($pattern,$inTime)){
   return true;
 }
}

function isPost(){
if (in_array("Present", $_POST)) {
    return true;
} elseif (in_array("Offsite", $_POST)){
    return true;
} elseif (in_array("Field Trip", $_POST)){
    return true;
} elseif (in_array("Sign Out", $_POST)){
    return true;
} else {
return false;
}
}

if (!empty($_POST['person']) && isPost()){

if (!empty($_POST['present'])) {
	$name = $_POST['person'];
	foreach ($name as $student)
	{
		changestatus($student, 'Present', '', '', $time);
	}
}

if (!empty($_POST['offsite'])) {
	$name = $_POST['person'];
    $status = "at " . $_POST['location'] . " returning at " . $_POST['offtime'];
    if (!empty($_POST['location'])){
       if (validTime($_POST['offtime'])){
	        foreach ($name as $student){
		    changestatus($student, 'Offsite', $status, $time, '');
            }
        } else {
        echo "that's not a valid time";
        }
    } else {
    echo "you need to fill out the location box before signing out to offsite";
    }
}

if (!empty($_POST['fieldtrip'])) {
	$name = $_POST['person'];
    $status = "with " . $_POST['facilitator'] . " returning at " . $_POST['fttime'];
    if (!empty($_POST['facilitator'])){
       if (validTime($_POST['fttime'])){
	        foreach ($name as $student){
		    changestatus($student, 'Field Trip', $status, $time, '');
            }
        } else {
        echo "that's not a valid time";
        }
    } else {
    echo "you need to chose a facilitator before signing out to field trip";
    }
}

if (!empty($_POST['signout'])) {
	$name = $_POST['person'];
	foreach ($name as $student)
	{
		changestatus($student, 'Checked out', '', $time, '');
	}
}

} else if(isPost() && empty($_POST['person'])) {
echo "please choose a student";

}

if (!empty($_POST['present_bstudent'])) {
	$name = $_POST['present_bstudent'];
	echo 'the conditional is working';
	changestatus($name, 'Present', '');
}

if (!empty($_POST['Late'])) {
	$name = $_POST['late_student'];
	$status = "arriving at " . $_POST['late_time'];
	changestatus($name, 'Late', $status);
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
		$day_data = new DateTime($rowdata[3]);
		$yesterday = new DateTime('yesterday 23:59:59');
		$status = $rowdata[1];
		
	if ($day_data < $yesterday) {
		$status = 'Not Checked In';
	}

	if ($status == 'Offsite' || $status == 'Checked Out' || $status == 'Field Trip' || $status == 'Not Checked In' || $status == 'Late') {
        ?>
		
		<tr>
			<td class='data_table'>
				
				<input type='checkbox' name='person[]' value='<?php echo $rowdata[0]; ?>' form='main' class='c_box'>
				<form action='attendance.php' method='post'>
					<input type='submit' value='P' class='p_button' name='present_button'>
					<input type='hidden' name='present_bstudent' value='<?php echo $rowdata[0]; ?>'>
				</form>
				<?php
				if ($day_data < $yesterday) { 
				?>
				<form action='attendance.php' method='post'>
					<input type='submit' value='Late' name='Late' class='l_button'>
					<input type='text' name='late_time'>
					<input type='hidden' name='late_student' value='<?php echo $rowdata[0]; ?>'>
				</form>
				<?php } ?>
			</td>
			<td class='data_table'><?php print $rowdata[0]; ?></td>
			<td class='data_table'><?php print $status; ?></td>
			<td class='data_table'><?php print $rowdata[2]; ?></td>
        </tr>
		<?php
	}
	
	else {
		echo "<tr>";
        echo "<td class='data_table'><input type='checkbox' name='person[]' value='" . $rowdata[0] . "' form='main'/></td>";
        echo "<td class='data_table'>" . $rowdata[0] . "</td>";
        echo "<td class='data_table'>" . $status . "</td>";
        echo "<td class='data_table'>" . $rowdata[2] . "</td>";
        echo "</tr>";
	}	
}	
   ?>
</table>
</body>
</html>