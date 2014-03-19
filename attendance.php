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


// SETTING UP ALL THE MySQL STUFF
	require_once("connection.php");

//function document
	require_once("function.php");

// grabs facilitator data for the field trip dropdown
	$result = $db_server->query("SELECT * FROM facilitators ORDER BY facilitatorname ASC");
	$fac_rows = $result->num_rows;

// puts each facilitator into an array
	$facilitators = array();
    while ($row = $result->fetch_row()) {
		array_push ($facilitators, $row[0]);
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

//requires checkboxes to be checked
if (!empty($_POST['person']) && isPost()){

//top present form querying -- "1" refers to "Present" in statusdata table
	if (!empty($_POST['present'])) {
		$name = $_POST['person'];
		foreach ($name as $student)
		{
			changestatus($student, '1', '');
		}
	}

//offsite querying and validation -- "2" refers to "Offsite" in statusdata table
	if (!empty($_POST['offsite'])) {
		$name = $_POST['person'];
		$status = "at " . $_POST['location'] . " returning at " . $_POST['offtime'];
		if (!empty($_POST['location'])){
			if (validTime($_POST['offtime'])){
				foreach ($name as $student){
				changestatus($student, '2', $status);
				}
			} else {
			echo "that's not a valid time";
			}
		} else {
			echo "you need to fill out the location box before signing out to offsite";
		}
	}

//Fieldtrip querying and validation -- "3" refers to "Field Trip" in statusdata table
	if (!empty($_POST['fieldtrip'])) {
		$name = $_POST['person'];
		$status = "with " . $_POST['facilitator'] . " returning at " . $_POST['fttime'];
		if (!empty($_POST['facilitator'])){
			if (validTime($_POST['fttime'])){
				foreach ($name as $student){
				changestatus($student, '3', $status);
				}
			} else {
				echo "that's not a valid time";
			}
		} else {
			echo "you need to chose a facilitator before signing out to field trip";
		}
	}

//Sign out querying -- "4" refers to "Checked Out" in statusdata table
	if (!empty($_POST['signout'])) {
		$name = $_POST['person'];
		foreach ($name as $student)
		{
			changestatus($student, '4', '');
		}
	}

//error message when no boxes are checked
} else if(isPost() && empty($_POST['person'])) {
	echo "please choose a student";
}

//individual present button querying -- "1" refers to "Present" in statusdata table
if (!empty($_POST['present_bstudent'])) {
	$name = $_POST['present_bstudent'];
	changestatus($name, '1', '');
}

//late status querying -- "5" refers to "Late" in statusdata table
if (!empty($_POST['Late'])) {
	$name = $_POST['late_student'];
	$status = "arriving at " . $_POST['late_time'];
	changestatus($name, '5', $status);
}

//Gets student names/id
////////////////////////
///////// ALERT: Sorting by studentid will not show names alphabetically!!! 
////////////////////////
$result = $db_server->query("SELECT studentid,firstname,lastname FROM studentdata WHERE current=1");



//Number of student names
$rows = $result->num_rows;

//blank array to store each name from query
$current_users = array();

//iterates through query, adding each name to $current_users array
for ($j = 0 ; $j < $rows ; ++$j)
		{
		$namedata = $result->fetch_array(MYSQLI_NUM);
		array_push($current_users, $namedata[0]);
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
    foreach ($current_users as $user) {

//variable definitions:
//queries for most recent entry for the current id
		$raw = $db_server->query("SELECT * FROM events WHERE studentid ='".$user."' ORDER BY timestamp DESC LIMIT 1");
//gets that as an array
		$rowdata = $raw->fetch_array(MYSQLI_NUM);
//gets the day that entry was entered
		$day_data = new DateTime($rowdata[3]);
//the day it was yesterday
		$yesterday = new DateTime('yesterday 23:59:59');
//defines what to display as current students status
		$status = $rowdata[1];

		$query = ("SELECT studentdata.firstname, events.studentid
		FROM studentdata 
		LEFT JOIN events ON studentdata.studentid=events.studentid WHERE studentdata.studentid='".$user."'");
		
$studentid_query = $db_server->query($query);
		
$studentid_data = $studentid_query->fetch_array(MYSQLI_ASSOC);

print_r($studentid_data);

//if the last entry is not from today, make the students status display as 'not checked in'
	if ($day_data < $yesterday) {
		$status = 'Not Checked In';
	}
		
//checks to see if the student is not present, if they aren't it will display the present button next to their name
////////////////////////////
//// ALERT: Change handling for "Not Checked In"?
////////////////////////////
	if ($status == '2' || $status == '4' || $status == '3' || $status == 'Not Checked In' || $status == '5') {
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
			<td class='data_table'><?php print $studentid_data['firstname']; ?></td>
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
			<td class='data_table'><?php print $studentid_data['firstname']; ?></td>
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