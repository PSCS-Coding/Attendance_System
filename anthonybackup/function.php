<?php
require_once("connection.php");
//changestatus inserts name, status and any comment associated into the studentInfo database
function changestatus($f_id, $f_status, $f_info, $f_returntime) {
	global $db_server;
	$result = $db_server->query("SELECT timestamp FROM events WHERE studentid = '$f_id' ORDER BY timestamp DESC LIMIT 1");
	$rowdata = $result->fetch_array(MYSQLI_BOTH);

    $last = new DateTime($rowdata['timestamp']);
    $now = new DateTime();
	$lastdate = $last->format('Y-m-d');
	$last330 = $lastdate . '15:30:00';
	$lastendofday = new DateTime($last330);
    $nowstamp = $now->getTimestamp();
    $laststamp = $last->getTimestamp();
    $lastendstamp = $lastendofday->getTimestamp();
	if ($nowstamp > $lastendstamp) {
		$minutes = round(($lastendstamp - $laststamp)/60);
		} else {
		$minutes = round(($nowstamp - $laststamp)/60);
		}

	#$stmt = $db_server->prepare("UPDATE events SET elapsed = ? WHERE studentid = ? AND timestamp = ?");
	#$stmt->bind_param('iss', $minutes, $f_id, $rowdata[0]);
	#$stmt->execute(); 		
	#$stmt->close();
	
	$whenreturn = new DateTime($f_returntime);
	$returntimestring = $whenreturn->format('Y-m-d H:i:s');
	$stmt = $db_server->prepare("INSERT INTO events (studentid, statusid, info, returntime) VALUES (?, ?, ?, ?)");
	$stmt->bind_param('ssss', $f_id, $f_status, $f_info, $returntimestring);
	$stmt->execute(); 
	$stmt->close();
}
//defines valid time entries for time text boxes
//only allows integers and colons
function validTime($inTime) {
$pattern   =   "/^(((([9])|([0-2])|([0-1][0-2])):([0-5][0-9]))|(([3]):(([0-2][0-9])|([3][0]))))$/";;
 if(preg_match($pattern,$inTime)){
   return true;
 }
}
//checks if you've hit any of the submit buttons that are a part of the top form
function isPost(){
if (in_array("Present", $_POST)) {
    return true;
} elseif (in_array("Offsite", $_POST)){
    return true;
} elseif (in_array("Field Trip", $_POST)){
    return true;
} elseif (in_array("Check Out", $_POST)){
    return true;
} else {
return false;
}
}
//function add favorite
function favorite($id, $status, $info, $returntime) {
	global $db_server;
	if (!empty($returntime)){
		$whenreturn = new DateTime($returntime);
		$returntimestring = $whenreturn->format('H:i:s');
	} else {
		$returntimestring="";
	}
	
	$getfav = $db_server->query("SELECT * FROM cookiedata WHERE studentid = '".$id."'");
	$frowcnt =  $getfav->num_rows;
	
	if ($frowcnt <10){		
	$stmt = $db_server->prepare("INSERT INTO cookiedata (studentid, statusid, info, returntime) VALUES (?, ?, ?, ?)");
	$stmt->bind_param('ssss', $id, $status, $info, $returntimestring);
	$stmt->execute(); 
	$stmt->close();
	} else {
		echo "There is a maximum of ten favorites";
	}
}

function plan($id, $status, $eventdate, $returntime, $info) { //function for pre-planning events
	global $db_server;
	
	if (!empty($returntime)){
		$whenreturn = new DateTime($returntime);
		$returntimestring = $whenreturn->format('H:i:s');
	} else {
		$returntimestring="";
	}
		
	$stmt = $db_server->prepare("INSERT INTO preplannedevents (studentid, statusid, eventdate, returntime, info) VALUES (?, ?, FROM_UNIXTIME(?), ?, ?)");
	$stmt->bind_param('iisss', $id, $status, $eventdate, $returntimestring, $info);
	$stmt->execute(); 
	$stmt->close();
}

function login(){ //logs you in
	global $login;
		if (isset($_SESSION['student'])){
		return True;
		$login="student";
		} elseif (isset($_SESSION['admin'])){
		return True;
		$login="admin";
	}	
}

function sendmail($facilitator, $message){
$headers = 'From: PSCS Attendance' . "\r\n" .
    'Reply-To: DO-NOT-REPLY@code.pscs.org' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
mail($facilitator, "PSCS Attendance", $message, $headers);
}

function validDate($v_date){ // ===================================== valid date function ============================================================
require_once("connection.php");
global $db_server;
$globalsQuery = $db_server->query("SELECT *
								  FROM globals
								  ");

$holidayQuery = $db_server->query("SELECT *
								  FROM holidays
								  ");
								  
while($globalsArray = $globalsQuery->fetch_assoc()) {
	$startDate = $globalsArray['startdate'];
	$endDate = $globalsArray['enddate'];
	$startTime = $globalsArray['starttime'];
	$endTime = $globalsArray['endtime'];
	}

$currentDate = new DateTime($startDate);
$endDate = new DateTime($endDate);
$dateList = array();

while ($currentDate <= $endDate) {
	$weekday = $currentDate->format("w");
		if ($weekday != 0 && $weekday != 6) {
	$currentDateString = $currentDate->format("Y-m-d");
	
	 array_push($dateList, $currentDateString);
	}
	date_add($currentDate, date_interval_create_from_date_string('1 day'));
	}
	
	// remove holidays 
	$rowcnt =  $holidayQuery->num_rows;
	while ($rowcnt>0){
	$holidayRow = mysqli_fetch_row($holidayQuery);
	$currentHoliday = $holidayRow[2];
	if (in_array($currentHoliday, $dateList)){
	$key = array_search($currentHoliday, $dateList);
	unset($dateList[$key]);
	}
	$rowcnt = $rowcnt-1;
	}
	if (!in_array($v_date, $dateList)){
	return false;
	} else {
	return true;
	}
}
function daysLeft(){ // ===================================== num school days ============================================================
require_once("connection.php");
global $db_server;
$globalsQuery = $db_server->query("SELECT *
								  FROM globals
								  ");

$holidayQuery = $db_server->query("SELECT *
								  FROM holidays
								  ");
								  
while($globalsArray = $globalsQuery->fetch_assoc()) {
	$startDate = $globalsArray['startdate'];
	$endDate = $globalsArray['enddate'];
	$startTime = $globalsArray['starttime'];
	$endTime = $globalsArray['endtime'];
	}

$currentDate = new DateTime($startDate);
$endDate = new DateTime($endDate);
$dateList = array();

while ($currentDate <= $endDate) {
	$weekday = $currentDate->format("w");
		if ($weekday != 0 && $weekday != 6) {
	$currentDateString = $currentDate->format("Y-m-d");
	
	 array_push($dateList, $currentDateString);
	}
	date_add($currentDate, date_interval_create_from_date_string('1 day'));
	}
	
	// remove holidays 
	$rowcnt =  $holidayQuery->num_rows;
	while ($rowcnt>0){
	$holidayRow = mysqli_fetch_row($holidayQuery);
	$currentHoliday = $holidayRow[2];
	if (in_array($currentHoliday, $dateList)){
	$key = array_search($currentHoliday, $dateList);
	unset($dateList[$key]);
	}
	$rowcnt = $rowcnt-1;
	}
	return count($dateList);
}

function idToName($id){
global $db_server;
$query = $db_server->query("SELECT firstname FROM studentdata WHERE studentid = $id");
$tempvar = $query->fetch_assoc();
$name = $tempvar['firstname'];
return($name);
}

function statconvert($id){
global $db_server;
$query = $db_server->query("SELECT statusname FROM statusdata WHERE statusid = $id");
$tempvar = $query->fetch_assoc();
$name = $tempvar['statusname'];
return($name);
}

// Apirl Fools Stuff is bad

$currtoday = date("n.j");

$aprfools = '4.1';
//         month day


if ($currtoday == $aprfools) {
  
 // FLIP PAGES UPSIDEDOWN SCRIPT
  echo "<script>['', '-ms-', '-webkit-', '-o-', '-moz-'].map(function(prefix){
	document.body.style[prefix + 'transform'] = 'rotate(0deg)';
});</script>" ;
  
  echo "<font color=pink>";

}


?>