<?php
session_start();

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
		$event_datetime_1 = new DateTime($student_data_array[$event_key+1]['timestamp']);
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

echo "Study hours all: "; echo $studyhours_all; echo "<br />";
echo "Community hours all: "; echo $commhours_all; echo "<br />";
echo "Offsite hours all: "; echo $offsitehours_all; echo "<br />";
echo "Study hours used: "; echo $studyhours_used; echo "<br />";
echo "Community hours used: "; echo $commhours_used; echo "<br />";
echo "Offsite hours used: "; echo $offsitehours_used; echo "<br />";
echo "Study hours remaining: "; echo $studyhours_remaining; echo "<br />";
echo "Community hours remaining: "; echo $commhours_remaining; echo "<br />";
echo "Offsite hours remaining: "; echo $offsitehours_remaining; echo "<br />";

echo "Unexpected Lates: " . $num_unexpected;
echo "Lates: " . $num_lates;
echo "Absents: " . $num_absent;
?>