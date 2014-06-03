<DOCTYPE html>
<html>
<head>
<title>View Reports</title>
</head>
<body>
<?php
	session_start();

	$_SESSION['prevURL'] = $_SERVER['REQUEST_URI'];
	
	//make this $_SESSION['adminSet'] if it's an admin-only page
	if(!$_SESSION['set'])
	{
		header("location: main_login.php");
	}
?>
<?php
	require("../connection.php");
		$studentlistquery = $db_server->query("
		SELECT firstname, lastname, studentid
		FROM studentdata
		WHERE current = 1
		ORDER BY firstname ASC
		")
		or die("Error querying database ".mysqli_error());
		
				echo '<form method="post" action="viewreports.php">
				<select name="studentlist" class="studentlist">';
					while ($a_studentlist = $studentlistquery->fetch_assoc()){
								$studentoption = "<option class='name' value='" . $a_studentlist['studentid'] . "'>" . $a_studentlist['firstname'] . " " . $a_studentlist['lastname'][0] . "</option>";
								echo $studentoption;
								}
								echo '</select>
								<input type="submit" name="submit">
								</form>';
							


function completeCode() {

// connect to sql
require("../connection.php");
//require("function.php");


	//or die("Unable to select database: " . mysqli_error());
		if (!empty($_SESSION['idd'])){
		$id = $_SESSION['idd'];
		}
		if (!empty($_SESSION['vrname'])){
		$id = $_SESSION['vrname'];
		}
		if (!empty($_POST['studentlist'])){
        $id = $_POST['studentlist'];
		
		}
			$_SESSION['vrname'] = $id;
					$id = $_SESSION['vrname'];
		
					$totalindstudy = 0;
	//echo "<br/>".$name."<br/>".$id."<br/>";
		
		$studentdataquery = $db_server->query("
		SELECT studentid, firstname, lastname
		FROM studentdata
		WHERE studentid = '$id'
		")
		or die("Error querying database ".mysqli_error());
		
		while($studentdata = $studentdataquery->fetch_assoc()) {
		$name = $studentdata['firstname'];
		}
		$_SESSION['name1'] = $id;
		////////////////////////////////////////////////////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////////////////////////////////
		/////                                                                                                  /////
		/////                             ISSAC PUT YOUR CODE HERE!                                            /////
		/////                    $name is the selected person's first name                                     /////
		/////                     $id is the selected person's student id                                      /////
		/////                           Below this comment is a bunch of                                       /////
		/////							calculation stuff, I will remove                                       /////
		/////							     it once you finish.                                               /////
		/////                                                                                                  /////
		////////////////////////////////////////////////////////////////////////////////////////////////////////////
		////////////////////////////////////////////////////////////////////////////////////////////////////////////
	
		echo '<p class="bettername">' . $name . '</p>
				<style>
				.bettername {
				font-size:20pt;
				}
				* {
				font-family:Arial;
				}
				</style>';
				
	
		
				
								
					//echoing week offsite		echo $w1;echo '<br />';echo $w2;echo '<br />';echo $w3;echo '<br />';echo $w4;echo '<br />';echo $w5;echo '<br />';	echo $w6;echo '<br />';echo $w7;echo '<br />';echo $w8;echo '<br />';echo $w9;echo '<br />';echo $w10;echo '<br />';echo $w11;echo '<br />';echo $w12;echo '<br />';echo $w13;echo '<br />';echo $w14;echo '<br />';echo $w15;echo '<br />';	echo $w16;echo '<br />';echo $w17;echo '<br />';echo $w18;echo '<br />';echo $w19;echo '<br />';echo $w20;echo '<br />';echo $w21;echo '<br />';echo $w22;echo '<br />';echo $w23;echo '<br />';echo $w24;echo '<br />';echo $w25;echo '<br />';echo $w26;echo '<br />';echo $w27;echo '<br />';echo $w28;echo '<br />';echo $w29;echo '<br />';echo $w30;echo '<br />';	echo $w31;echo '<br />';echo $w32;echo '<br />';echo $w33;echo '<br />';echo $w34;echo '<br />';echo $w35;echo '<br />';	echo $w36;echo '<br />';echo $w37;echo '<br />';echo $w38;echo '<br />';echo $w39;echo '<br />';echo $w40;echo '<br />';	echo $w41;echo '<br />';echo $w42;echo '<br />';echo $w43;echo '<br />';echo $w44;echo '<br />';echo $w45;echo '<br />';	echo $w46;echo '<br />';echo $w47;echo '<br />';echo $w48;echo '<br />';echo $w49;echo '<br />';echo $w50;echo '<br />';	echo $w51;echo '<br />';echo $w52;echo '<br />';
						
					
						
						
						//		echo $readable_offsiteleft;			echo '<br />';
						//		echo $readable_averagetimeout;		echo '<br /><br />';
						//		echo $readable_totalindstudy;		echo '<br />';
						//		echo $readable_indstudyleft;        echo '<br /><br />';
							
$student_data_array = array();
//fetches most recent data from the events table
//joins with the tables that key student names/status names to the ids in the events table
$current_student_id = 30;
$result = $db_server->query("SELECT statusname,studentdata.studentid,timestamp,returntime,events.eventid, yearinschool
		FROM events 
		JOIN statusdata ON events.statusid = statusdata.statusid
		RIGHT JOIN studentdata ON events.studentid = studentdata.studentid
		WHERE studentdata.studentid = '$id'
		ORDER BY timestamp DESC") or die(mysqli_error($db_server));
while ($student_data_result = $result->fetch_assoc()) {
	array_push($student_data_array, $student_data_result);
}
//holidays array
$holiday_data_array = array();
$holiday_dt_array = array();
$holidayquery = "SELECT date FROM holidays";
$holiday_result = $db_server->query($holidayquery);
while ($holiday_data = $holiday_result->fetch_array()) {
	array_push($holiday_dt_array, $holiday_data);
}
foreach ($holiday_dt_array as $k) {
	array_push($holiday_data_array, $k[0]);
}
//allotted hours query
$yearinschool = $student_data_array[0]['yearinschool'];
$allottedquery = "SELECT * FROM allottedhours WHERE yis = '$yearinschool'";
$allotted_result = $db_server->query($allottedquery);
$allotted_data_array = $allotted_result->fetch_array();

//globals query
$globalsquery = "SELECT * FROM globals";
$globals_result = $db_server->query($globalsquery);
$globalsdata = $globals_result->fetch_array();

$studyhours_all = $allotted_data_array['IShours'] * 60;
$offsitehours_all = $allotted_data_array['offsitehours'] * 60;
$commhours_all = $allotted_data_array['communityhours'] * 60;

$studyhours_remaining = $studyhours_all;
$offsitehours_remaining = $offsitehours_all;
$commhours_remaining = $commhours_all;

$studyhours_used = 0;
$offsitehours_used = 0;
$commhours_used = 0;

$num_lates = 0;
$num_unexpected = 0;
$num_absent = 0;

$today = New DateTime();
//counts time
//loops through each event for the given student
foreach($student_data_array as $event_key => $event_val) {
	if (count($student_data_array) != $event_key) {
		//makes the timestamp into datetime obj
		if (isset($student_data_array[$event_key+1])) {
			$event_datetime_1 = new DateTime($student_data_array[$event_key+1]['timestamp']);
		}
		else {
			break;
		}
		$event_datetime_2 = new DateTime($event_val['timestamp']);
		//variables for easy comparison
		$early = $event_datetime_1->format('m/d/y') . " 9:00:00";
		$late = $event_datetime_1->format('m/d/y') . " 15:30:00";
		$event_early = new DateTime($early);
		$event_late = new DateTime($late);
		//echo $event_val['eventid'] . " || " . $event_datetime_1->format('m/d/y') . " || " . $event_datetime_1->format('H:i:s') . " || " . $student_data_array[$event_key+1]['eventid'] . " || " . $event_datetime_2->format('m/d/y') . " || " . $event_datetime_2->format('H:i:s') . " || " . "<br />";
		//is event 1 on a weekend or holiday?
		if (($event_datetime_1->format('w') == 0 || $event_datetime_1->format('w') == 6) || (in_array($event_datetime_1->format('Y-m-d'), $holiday_data_array) == True)) {
			continue;
		}
		//is event 1 before 9:00am?
		if ($event_datetime_1 <= $event_early){
			$event_datetime_1->setTime(9, 0, 0);
		}
		//is event 2 after 3:30 of the same day of event 1?
		if ($event_datetime_2 >= $event_late){
			$event_datetime_2 = clone $event_datetime_1;
			$event_datetime_2->setTime(15, 30, 0);
		}
		//echo $event_val['eventid'] . " || " . $event_datetime_1->format('m/d/y') . " || " . $event_datetime_1->format('H:i:s') . " || " . $student_data_array[$event_key+1]['eventid'] . " || " . $event_datetime_2->format('m/d/y') . " || " . $event_datetime_2->format('H:i:s') . " || " . "<br />";			
		//diff between adjacent events
		$elapsed = $event_datetime_1->diff($event_datetime_2);
		//format as total minutes
		$elapsed_minutes = $elapsed->format('%i');
		if ($event_val['statusname'] == 'Present' || $event_val['statusname'] == 'Field Trip' || $event_val['statusname'] == 'Independent Study') {
			$commhours_remaining -= $elapsed_minutes;
			$commhours_used += $elapsed_minutes;
			if ($event_val['statusname'] == 'Independent Study') {
				$studyhours_remaining -= $elapsed_minutes;
				$studyhours_used += $elapsed_minutes;
			}
		}
		else {
			$offsitehours_remaining -= $elapsed_minutes;
			$offsitehours_used += $elapsed_minutes;
			if ($event_val['statusname'] == 'Not Checked In') {
				$num_unexpected += 1;
				$num_lates += 1;
			}
			if ($event_val['statusname'] == 'Late') {
				$num_lates += 1;
			}
			if ($event_val['statusname'] == 'Absent') {
				$num_absent += 1;
			}
		}
	}
}

//Offsite information echoing
$offsiteHrs_remaining = floor($offsitehours_remaining / 60);
$offsiteMin_remaining = $offsitehours_remaining % 60;

$readable_offsiteleft = "<p class='reporttext'> You have " . $offsiteHrs_remaining . " hours and " . $offsiteMin_remaining . " minutes of offsite left. </p>";
if ($offsitehours_remaining < 0) {
	$readable_offsiteleft = "<p class='reporttext'> You are out of offsite! You are over by " . $offsiteHrs_remaining . " hours and " . $offsiteMin_remaining . " minutes. </p>";
}
echo $readable_offsiteleft;

$offsiteHrs_used = floor($offsitehours_used / 60);
$offsiteMin_used = $offsitehours_remaining % 60;

$daystillend = 0;
$today = New DateTime();
$today = $today->SetTime(0, 0, 0);
$enddate = new DateTime($globalsdata['enddate']);
$interval = new DateInterval('P1D');
$period = new DatePeriod($today, $interval, $enddate);
foreach ($period as $date) {
	if ($date->format('w') != 0 || $date->format('w') != 6) {
		$daystillend += 1;
	}
}
$minutesperday = floor($offsitehours_remaining / $daystillend);

echo "<p class='reporttext'> You have " . $minutesperday . " minutes of offsite per day.</p>";
echo "<p class='reporttext'> You have used " . $offsiteHrs_used . " hours and " . $offsiteMin_used . " minutes of your offsite time.</p>";

//Late information echoing
echo "<p class='reporttext'> You have been late " . $num_lates . " times.</p>";
echo "<p class='reporttext'> You have been unexpectedly late " . $num_unexpected . " times. </p>";
echo "<p class='reporttext'> You have been absent " . $num_absent . " times. </p>";

//IS information echoing
$studyHrs_remaining = floor($studyhours_remaining / 60);
$studyMin_remaining = $studyhours_remaining % 60;

$readable_studyleft = "<p class='reporttext'> You have " . $studyHrs_remaining . " hours and " . $studyMin_remaining . " minutes of independent study left. </p>";
if ($studyhours_remaining < 0) {
	$readable_studyleft = "<p class='reporttext'> You are out of independent study! You are over by " . $studyHrs_remaining . " hours and " . $studyMin_remaining . " minutes. </p>";
}
echo $readable_studyleft;

$studyHrs_used = floor($studyhours_used / 60);
$studyMin_used = $studyhours_remaining % 60;
echo "<p class='reporttext'> You have used " . $studyHrs_used . " hours and " . $studyMin_used . " minutes of your independent study time.</p>";

/*echo "Study hours all: "; echo $studyhours_all; echo "<br />";
echo "Community hours all: "; echo $commhours_all; echo "<br />";
echo "Offsite hours all: "; echo $offsitehours_all; echo "<br />";
echo "Study hours used: "; echo $studyhours_used; echo "<br />";
echo "Community hours used: "; echo $commhours_used; echo "<br />";
echo "Offsite hours used: "; echo $offsitehours_used; echo "<br />";
echo "Study hours remaining: "; echo $studyhours_remaining; echo "<br />";
echo "Community hours remaining: "; echo $commhours_remaining; echo "<br />";
echo "Offsite hours remaining: "; echo $offsitehours_remaining; echo "<br />";

echo "Unexpected Lates: " . $num_unexpected; echo "<br />";
echo "Lates: " . $num_lates; echo "<br />";
echo "Absents: " . $num_absent; echo "<br />";
*/
								
							
							
						//			  echo '<br /><table cellspacing="0" class="graphtable" style="400px">';
									
						// DO FOR LOOP FOR ECHOING TR		for {
						//		for  ($i=0;$i<$highestoffsite;$i++) {
						//		echo '<tr><td>lal</td><td>lol</td><td>lil</td></tr>';
						//		}
						//	echo '</table>';
						//			echo '<br /><table style="200px"><tr><td>ll</td><td>ll</td><td>ll</td></tr><tr><td>ll</td><td>ll</td><td>ll</td></tr><tr><td>ll</td><td>ll</td><td>ll</td></tr></table>';
						
								
								//
							
								//
							
									 $style1 = '<style>
								input[value="submit3"] {
			background:url(http://code.pscs.org/attendance/down.png);
			width:20px;
			height:20px;
			border-radius:15px;
			font-size:0.1;
			}
			input[value="Go"] {
			background:url(http://code.pscs.org/attendance/down.png);
			width:20px;
			height:20px;
			border-radius:15px;
			font-size:0.1;
			}
			input[value="submit4"] {
			background:url(http://code.pscs.org/attendance/up.png);
			width:20px;
			height:20px;
			border-radius:15px;
			font-size:0.1;
			}
			.eventlog {
			border-style:solid;
			border-width:2px;
			}
			.eventlog th {
			border-style:solid;
			border-width:2px;
			}
			.eventlog tr {
			border-style:solid;
			border-width:2px;
			}
			.eventlog td {
			border-style:solid;
			border-width:2px;
		
			}
			.graphtable table{
			border-width:1px;
			}
			.graphtable th {
			border-style:solid;
			border-width:thin;
			margin-left:0px;
			margin-right:0px;
			border-spacing:0px;
			border-collapse:collapse;
			}
			.graphtable td {
			border-style:solid;
			border-width:thin;
				margin-left:0px;
			margin-right:0px;
			border-spacing:0px;
			border-collapse:collapse;
			}
			.graphtable tr {
			border-style:solid;
			border-width:thin;
				margin-left:0px;
				border-spacing:0px;
			margin-right:0px;
			border-collapse:collapse;
			}
			
		</style>
	';
		echo $style1;
			
			$_SESSION['style1'] = $style1;
		?>
						<table class="eventlog" cellspacing="0">
		<th>Status</th>
		<th>Info</th>
		<th>Start Time</th>
		<th>End Time</th>

	
	
<?php

	$getevents=$db_server->query("SELECT * FROM events WHERE studentid = '".$id."' ORDER BY timestamp ASC");
	$eventrow=mysqli_fetch_row($getevents);
	$eventnum=$getevents->num_rows;
	while($eventnum > 0){
	$eventrow=mysqli_fetch_row($getevents);
	$instatid=$eventrow[1];
	
	$statquery=$db_server->query("SELECT statusname FROM statusdata WHERE statusid = '".$instatid."'");
	$status= $statquery->fetch_assoc();
	$info=$eventrow[2];
	$starttime=$eventrow[4];
	if(isset($lasttimestamp)){
	$endtime=$lasttimestamp;
	} else {
	$endtime="";
	}
	if(!empty($status)){
	?>	
	<tr>
		<td><?php echo $status['statusname'] ?></td>
		<td><?php echo $info ?></td>
		<td><?php echo $starttime?></td>
		<td><?php echo $endtime?></td>
	</tr>
	<?php
	}
	$status='';
	$lasttimestamp=$eventrow[4];
	$eventnum=$eventnum-1;
	}
	}
	?>
	
	</table> 
 <?php
								
														if (!empty($_POST['submit3'])){
														$name = $_SESSION['vrname'];
														$id = $_SESSION['name1'];
										completeCode();
								}
														
														if (!empty($_POST['submit4'])){
																$id = $_SESSION['name1'];
																echo $_SESSION['style'];
																echo $_SESSION['style1'];
												
																echo $_SESSION['readable_offsiteleft'];
																echo '<br />';
																echo $_SESSION['readable_averagetimeout'];
																echo '<br />';
																echo '<form method="post">
									<input type="text" name="selector3">
									<input type="submit" name="submit3" value="submit3">
									  </form>';
										echo '<br />';
																}
						
														if (!empty($_POST['submit'])) {
																completeCode();
																}
														if (!empty($_SESSION['idd']) && empty($_POST['submit'])) {
																completeCode();
																}
													
		?>
			<style>
			.eventlog {
			border-style:solid;
			border-width:1px;
			}
			.eventlog th {
			border-style:solid;
			border-width:1px;
			}
			.eventlog tr {
			border-style:solid;
			border-width:1px;
			}
			.eventlog td {
			border-style:solid;
			border-width:1px;
		
			}
			.graphtable table{
			border-width:1px;
			}
			.graphtable th {
			border-style:solid;
			border-width:thin;
			margin-left:0px;
			margin-right:0px;
			border-spacing:0px;
			border-collapse:collapse;
			}
			.graphtable td {
			border-style:solid;
			border-width:thin;
				margin-left:0px;
			margin-right:0px;
			border-spacing:0px;
			border-collapse:collapse;
			}
			.graphtable tr {
			border-style:solid;
			border-width:thin;
				margin-left:0px;
				border-spacing:0px;
			margin-right:0px;
			border-collapse:collapse;
			}
			</style>
		</body>
		</html>
		