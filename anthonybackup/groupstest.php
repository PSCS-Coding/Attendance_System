<?php
session_start();

require_once("connection.php");
require_once("function.php");

$_SESSION['prevURL'] = $_SERVER['REQUEST_URI'];

if(!$_SESSION['set']) {
	header("location: main_login.php");
}

	$getGroupNamesQuery = $db_server->query("
										SELECT `COLUMN_NAME` 
										FROM `INFORMATION_SCHEMA`.`COLUMNS` 
										WHERE `TABLE_SCHEMA`='attendance' 
										AND `TABLE_NAME`='groups';
										");
$tableNamesArray = array();
	while ($getGroupNamesArray = $getGroupNamesQuery->fetch_assoc()) {
		array_push($tableNamesArray, $getGroupNamesArray['COLUMN_NAME']);
		}
	//print_r($tableNamesArray);
	
		echo "<form method='post'>";
	foreach ($tableNamesArray as $child) {
		echo "<input type='submit' name='" . $child . "' value='" . $child . "'>";
		}
		echo "</form>";
		
	foreach ($tableNamesArray as $child) {
		if (!empty($_POST['' . $child . ''])) {
			echo $_POST['' . $child . ''];
			
			$selectedGroup = $child;
			
		$getGroupInfoQuery = $db_server->query("
											   SELECT `$selectedGroup`
											   FROM `groups`
											   ");
$groupInfoArray = array();
	while ($getGroupInfoArray = $getGroupInfoQuery->fetch_assoc()) {
		array_push($groupInfoArray, $getGroupInfoArray['' . $selectedGroup . '']);
			}
	//print_r($groupInfoArray);
	foreach ($groupInfoArray as $sub) {
		echo "<br />" . $sub;
		
		$currentTime = new DateTime('now');
		$currentTime->format('Y-m-d H:i:s');
		date_add($currentTime, date_interval_create_from_date_string('45 minutes'));
		echo $currentTime->format('Y-m-d H:i:s');
		
	//	$insertEventsQuery = $db_server->query("
		//									   INSERT INTO groups
		//									   (studentid, statusid, info, returntime, timestamp, eventid)
		//									   VALUES
		//									   ($sub, 3, Crysta, 
		}
	}
}
?>