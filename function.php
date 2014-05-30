<?php
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

	$stmt = $db_server->prepare("UPDATE events SET elapsed = ? WHERE studentid = ? AND timestamp = ?");
	$stmt->bind_param('iss', $minutes, $f_id, $rowdata[0]);
	$stmt->execute(); 		
	$stmt->close();
	
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
$pattern   =   "/^((([9])|([0-2])|([0-1][0-2])):?([0-5][0-9]))|(([0-3]):?([0-2][0-9])|([0-3][0]))$/";
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

//function plan
function plan($id, $status, $eventdate, $returntime, $info) {
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

function login(){
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
    'Reply-To: code.pscs.org' . "\r\n" .
    'X-Mailer: PHP/' . phpversion();
mail($facilitator, "PSCS Attendance", $message, $headers);
}

?>