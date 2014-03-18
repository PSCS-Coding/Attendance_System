<!DOCTYPE html>
<html>
	<link rel="stylesheet" type="text/css" href="attendance.css">
<head>
		<title>attendance system tests</title>
</head>
<body>
	<div>
<!-- Form that manages status -->
		<form method='post' action='<?php echo basename($_SERVER['PHP_SELF']); ?>' id='main'>
<!-- Present form -->
	<div>
		<input type="submit" value="Present" name="present">
	</div>
<!-- Offsite form -->
	<div>
		<input type="submit" value="Offsite" name="offsite">
		<input type="text" name="location" placeholder='Location'>
		<input type="time" name="offtime" placeholder='Return time'>
	</div>
<!-- Field trip form -->
	<div>
		<input type="submit" value="Field Trip" name="fieldtrip">
<?php

// connect to sql
	$db_server = mysql_connect("localhost", "pscs", "Courage!");
	if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());
	mysql_select_db("attendance", $db_server) or die("Unable to select database: " . mysql_error());
// ***********************make this an external file once we convert to mysqli************************

// grabs facilitator data for the field trip dropdown
	$fac_query = "SELECT * FROM facilitators ORDER BY facilitatorname ASC";
	$fac_data = mysql_query($fac_query);
	
	if (!$fac_data) die ("Database access failed: " . mysql_error());
	$fac_rows = mysql_num_rows($fac_data);

// puts each facilitator into an array
	$facilitators = array();

	for ($i = 0 ; $i < $fac_rows ; ++$i)
		{
		$fac_name = mysql_result($fac_data, $i);
		array_push ($facilitators, $fac_name);
		}
?>
<!-- Creates the dropdown entries -->
		<select name='facilitator'><option value=''>Select Facilitator</option>
<?php
			foreach ($facilitators as $facilitator_option) {
?> 
				<option value= '<?php echo $facilitator_option; ?> '> <?php echo $facilitator_option; ?></option>
<?php
			}
?>
        </select>
<!-- Field trip return time -->
        <input type="time" name="fttime" placeholder='Return time'>
	</div>

<!-- Sign out form -->
	<div>
		<input type="submit" value="Sign Out" name="signout">
	</div>
	
	</form>
	</div>
		
<?php

//function document
require_once("function.php");

//requires checkboxes to be checked
if (!empty($_POST['person']) && isPost()){

//top present form querying
	if (!empty($_POST['present'])) {
		$name = $_POST['person'];
		foreach ($name as $student)
		{
			changestatus($student, 'Present', '');
		}
	}

//offsite querying and validation
	if (!empty($_POST['offsite'])) {
		$name = $_POST['person'];
		$status = "at " . $_POST['location'] . " returning at " . $_POST['offtime'];
		if (!empty($_POST['location'])){
			if (validTime($_POST['offtime'])){
				foreach ($name as $student){
				changestatus($student, 'Offsite', $status);
				}
			} else {
			echo "that's not a valid time";
			}
		} else {
			echo "you need to fill out the location box before signing out to offsite";
		}
	}

//Fieldtrip querying and validation
	if (!empty($_POST['fieldtrip'])) {
		$name = $_POST['person'];
		$status = "with " . $_POST['facilitator'] . " returning at " . $_POST['fttime'];
		if (!empty($_POST['facilitator'])){
			if (validTime($_POST['fttime'])){
				foreach ($name as $student){
				changestatus($student, 'Field Trip', $status);
				}
			} else {
				echo "that's not a valid time";
			}
		} else {
			echo "you need to chose a facilitator before signing out to field trip";
		}
	}

//Sign out querying
	if (!empty($_POST['signout'])) {
		$name = $_POST['person'];
		foreach ($name as $student)
		{
			changestatus($student, 'Checked out', '');
		}
	}

//error message when no boxes are checked
} else if(isPost() && empty($_POST['person'])) {
	echo "please choose a student";
}

//individual present button querying
if (!empty($_POST['present_bstudent'])) {
	$name = $_POST['present_bstudent'];
	changestatus($name, 'Present', '');
}

//late status querying
if (!empty($_POST['Late'])) {
	$name = $_POST['late_student'];
	$status = "arriving at " . $_POST['late_time'];
	changestatus($name, 'Late', $status);
}

//Gets student names/id
$userdata = mysql_query("SELECT DISTINCT name FROM studentInfo ORDER BY name ASC");

//Number of student names
$rows = mysql_num_rows($userdata);

//blank array to store each name from query
$users = array();

//iterates through query, adding each name to $users array
for ($j = 0 ; $j < $rows ; ++$j)
		{
		$namedata = mysql_fetch_array($userdata);
		array_push($users, $namedata[0]);
		}
        
?>
<!-- table display for current student status -->
<table style="width:80%" class='data_table'>
    <tr>
        <th class='data_table'></th>
        <th class='data_table'>Student</th>
        <th class='data_table'>Status</th>
        <th class='data_table'>Comment</th>
    </tr>
    <?php
//creates a row for each individual student
    foreach ($users as $user) {

//variable definitions:
//queries for most recent entry for the current id
		$raw = mysql_query("SELECT * FROM studentInfo WHERE name ='".$user."' ORDER BY time DESC LIMIT 1");
//gets that as an array
		$rowdata = mysql_fetch_array($raw);
//gets the day that entry was entered
		$day_data = new DateTime($rowdata[3]);
//the day it was yesterday
		$yesterday = new DateTime('yesterday 23:59:59');
//defines what to display as current students status
		$status = $rowdata[1];

//if the last entry is not from today, make the students status display as 'not checked in'
	if ($day_data < $yesterday) {
		$status = 'Not Checked In';
	}

//checks to see if the student is not present, if they aren't it will display the present button next to their name
	if ($status == 'Offsite' || $status == 'Checked Out' || $status == 'Field Trip' || $status == 'Not Checked In' || $status == 'Late') {
        ?>
		
		<tr>
			<td class='data_table'>
<!-- checkbox that gives student data to the form at the top -->
				<input type='checkbox' name='person[]' value='<?php echo $rowdata[0]; ?>' form='main' class='c_box'>
<!-- present button, passes hidden value equal to the current student -->
				<form action='<?php echo basename($_SERVER['PHP_SELF']); ?>' method='post'>
					<input type='submit' value='P' class='p_button' name='present_button'>
					<input type='hidden' name='present_bstudent' value='<?php echo $rowdata[0]; ?>'>
				</form>
				<?php
//checks whether or not the late button should appear (only if the student is not checked in)
				if ($day_data < $yesterday) { 
				?>
<!-- Late button with time input next to it -->
				<form action='<?php echo basename($_SERVER['PHP_SELF']); ?>' method='post'>
					<input type='submit' value='Late' name='Late' class='l_button'>
					<input type='text' name='late_time' placeholder='Return time'>
					<input type='hidden' name='late_student' value='<?php echo $rowdata[0]; ?>'>
				</form>
				<?php } ?>
			</td>
<!-- displays current rows student name, that students status and any comment associated with that status -->
			<td class='data_table'><?php print $rowdata[0]; ?></td>
			<td class='data_table'><?php print $status; ?></td>
			<td class='data_table'><?php print $rowdata[2]; ?></td>
        </tr>
	<?php
	//displays user data without any buttons when they are present
	} else {
	?>
		<tr>
			<td class='data_table'>
<!-- checkbox that gives student data to the form at the top -->
				<input type='checkbox' name='person[]' value='<?php echo $rowdata[0]; ?>' form='main'/>
			</td>
<!-- displays current rows student name, that students status and any comment associated with that status -->
			<td class='data_table'><?php echo $rowdata[0]; ?></td>
			<td class='data_table'><?php echo $status; ?></td>
			<td class='data_table'><?php echo $rowdata[2] ?></td>
        </tr>
	<?php
	}	
}	
   ?>
</table>
</body>
</html>