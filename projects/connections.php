<?php

require_once("../connection.php");
require_once("../function.php");

$query = $db_server->query("SELECT studentid,timestamp,statusid FROM events WHERE statusid = 2");

$qresult = array();

while ($result = $query->fetch_array()) {
    
	array_push($qresult, $result);
	
}

$ts = $qresult[0]["timestamp"];

for ($i = 1; $i < count($qresult); $i++) {
	if ($qresult[$i]['statusid'] == 2) {
		foreach(lookfor($qresult[$i]["timestamp"]) as $sub) {
			echo $sub . "<br />";
		}
	}
}
function lookfor($ts) {
	global $qresult;
	global $connarray;
	$toreturn = array();
	foreach($qresult as $event) {
		if ($event["timestamp"] == $ts) {
			array_push($toreturn, idToName($event["studentid"]));
		}
	}	
	array_push($toreturn, $ts);
	return $toreturn;
}
?>