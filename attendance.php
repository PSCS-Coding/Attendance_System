<!DOCTYPE html>
<html>
<head>
	<title>PSCS Attendance</title>
	<link rel="stylesheet" type="text/css" href="attendance.css">
	<link rel="stylesheet" type="text/css" href="css/jquery.timepicker.css">    
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js" ></script>
    <script src="js/jquery.timepicker.min.js" type="text/javascript"></script>
    <script type="text/javascript">
		$(document).ready(function(){
			$('#offtime').timepicker({ 'scrollDefaultNow': true, 'minTime': '9:00am', 'maxTime': '3:30pm', 'timeFormat': 'g:i', 'step': 5 });
			$('#fttime').timepicker({ 'scrollDefaultNow': true, 'minTime': '9:00am', 'maxTime': '3:30pm', 'timeFormat': 'g:i', 'step': 15 });
			$('#late_time').timepicker({ 'scrollDefaultNow': true, 'minTime': '9:00am', 'maxTime': '3:30pm', 'timeFormat': 'g:i', 'step': 5 });
		});
	</script>
</head>
<!-- setup -->
<link rel="stylesheet" type="text/css" href="attendance.css">
<?php
    require_once("connection.php");
    require_once("function.php");
    
    
    //facilitator array, $facilitators is array of all from sql    
    $facget = $db_server->query("SELECT * FROM facilitators ORDER BY facilitatorname ASC");
    
    $facilitators = array();
    while ($fac_row = $facget->fetch_row()) {
		array_push ($facilitators, $fac_row[0]);
    }
    
    //current students array
    $current_users_result = $db_server->query("SELECT studentid FROM studentdata WHERE current=1 ORDER BY firstname");
    
//===========================================
//==========on submit button click===========
//===========================================

if (!empty($_POST['person']) && isPost()){
		$name = $_POST['person'];

    //present    
	if (!empty($_POST['present'])) {
		foreach ($name as $student)
		{
			changestatus($student, '1', '', '');
		}
	}

    //offsite
	if (!empty($_POST['offsite'])) {
		if (!empty($_POST['offloc'])){
        		$info = $_POST['offloc'];
			if (validTime($_POST['offtime'])){
				foreach ($name as $student){
				changestatus($student, '2', $info, $_POST['offtime']);
				}
			} else {
			echo "that's not a valid time";
			}
		} else {
			echo "you need to fill out the location box before signing out to offsite";
		}
	}

    //fieldtrip
	if (!empty($_POST['fieldtrip'])) {

		if (!empty($_POST['facilitator'])){
        		$info = $_POST['facilitator'];
			if (validTime($_POST['fttime'])){
				foreach ($name as $student){
				changestatus($student, '3', $info, $_POST['fttime']);
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
			changestatus($student, '4', '', '');
		}
	}

//error message when no boxes are checked
} else if(isPost() && empty($_POST['person'])) {
	echo "please choose a student";
}

//individual present button querying -- "1" refers to "Present" in statusdata table
if (!empty($_POST['present_bstudent'])) {
	$name = $_POST['present_bstudent'];
	changestatus($name, '1', '', '');
}

//late status querying -- "5" refers to "Late" in statusdata table
if (!empty($_POST['Late'])) {
	$name = $_POST['late_student'];
	$status = "arriving at " . $_POST['late_time'];
	changestatus($name, '5', $status);
}

?>



<!-- top form for change status -->

<form method='post' action='<?php echo basename($_SERVER['PHP_SELF']); ?>' id='main'>
    <div>
        <input type="submit" value="Present" name="present">
    </div>
    
    <div>
        <input type="submit" name="offsite" value="Offsite">
        <input type="text" name="offloc" placeholder='Location' autocomplete='on'>
		<input type="text" name="offtime" placeholder='Return time' id="offtime">
    </div>
    
    <div>
       <input type="submit" name="fieldtrip" value="Field Trip"> 
    
<!-- Creates the dropdown of facilitators -->
		<select name='facilitator'><option value=''>Select Facilitator</option>
        <?php
			foreach ($facilitators as $facilitator_option) {
        ?> 
				<option value= '<?php echo $facilitator_option; ?> '> <?php echo $facilitator_option; ?></option>
        <?php
			}
        ?>
        </select>
        <input type="text" name="fttime" placeholder="Return time" id="fttime">
    </div>

<!-- Sign out form -->
	<div>
		<input type="submit" value="Sign Out" name="signout">
	</div>
	
	</form>
	</div>
<!-- student information table rendering -->

<table style="width:80%" class='data_table'>
    <tr>
        <th class='data_table'></th>
        <th class='data_table'>Student</th>
        <th class='data_table'>Status</th>
        <th class='data_table'>Info</th>
    </tr>
    <?php

while ($current_student_id = $current_users_result->fetch_assoc()) { // LOOPS THROUGH ALL OF THE CURRENT STUDENTS
				
		$result = $db_server->query("SELECT firstname,lastname,statusname,studentdata.studentid,info,timestamp
									 FROM events 
									 JOIN statusdata ON events.statusid = statusdata.statusid
									 RIGHT JOIN studentdata ON events.studentid = studentdata.studentid
									 WHERE studentdata.studentid = $current_student_id[studentid] 
									 ORDER BY timestamp DESC
									 LIMIT 1") 
									 or die(mysqli_error($db_server));
                                     
		while ($latestdata = $result->fetch_assoc()) { // LOOPS THROUGH THE LATEST ROWS FROM THE EVENTS TABLE
			//gets the day that entry was entered
			$day_data = new DateTime($latestdata['timestamp']);
			//the day it was yesterday
			$yesterday = new DateTime('yesterday 23:59:59');
			?>
			<tr>
				<td class='data_table'>
					<!-- checkbox that gives student data to the form at the top -->
					<input type='checkbox' name='person[]' value='<?php echo $latestdata['studentid']; ?>' form='main' class='c_box'>

					<?php if (($latestdata['statusname'] != 'Present') || ($day_data < $yesterday)) { // if the student is not present or hasn't updated since midnight, show a present button ?>
					<!-- present button, passes hidden value equal to the current student -->
					<form action='<?php echo basename($_SERVER['PHP_SELF']); ?>' method='post'>
						<input type='submit' value='P' class='p_button' name='present_button'>
						<input type='hidden' name='present_bstudent' value='<?php echo $latestdata['studentid']; ?>'>
					</form>
					<?php } // end "not present" if clause
					if ($day_data < $yesterday) { // if the student hasn't updated status since midnight, display a late button ?>
					<!-- Late button with time input next to it -->
					<form action='<?php echo basename($_SERVER['PHP_SELF']); ?>' method='post'>
						<input type='submit' value='Late' name='Late' class='l_button'>
						<input type='input' name='late_time' placeholder='Expected' id="late_time">
						<input type='hidden' name='late_student' value='<?php echo $latestdata['studentid']; ?>'>
					</form>
					<?php } ?>
				</td>
			<?php 
			$lastinitial = substr($latestdata['lastname'], 0, 1); ?>
            <!-- displays current rows student name, that students status and any comment associated with that status -->
				<td class='data_table'><?php print $latestdata['firstname'] . " " . $lastinitial; ?></td>
				<td class='data_table'><?php if ($day_data < $yesterday) { echo "Not checked in"; } else { echo $latestdata['statusname']; } ?></td>
				<td class='data_table'><?php echo $latestdata['info'] ?></td>
			</tr>
<?php		
		} // FINISHES THE WHILE LOOP THAT GOES THROUGH THE LATEST ROWS FROM THE EVENTS TABLE

	}
?>

</table>
</body>
</html>