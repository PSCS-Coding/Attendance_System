<?php
session_start();

require_once("connection.php");
require_once("function.php");
ini_set('memory_limit', -1);
$_SESSION['prevURL'] = $_SERVER['REQUEST_URI'];

//make this $_SESSION['adminSet'] if it's an admin-only page
if(!$_SESSION['set']) {
	header("location: main_login.php");
}

$globalsQuery = $db_server->query("SELECT *
								  FROM globals
								  ");
$holidaysQuery = $db_server->query("SELECT *
									FROM holidays
									");
while($globalsArray = $globalsQuery->fetch_assoc()) {
	$startDate = $globalsArray['startdate'];
	$endDate = $globalsArray['enddate'];
	$startTime = $globalsArray['starttime'];
	$endTime = $globalsArray['endtime'];
	}
	$holidaysList = array();
	while($holidaysArray = $holidaysQuery->fetch_assoc()) {
	array_push($holidaysList, new DateTime($holidaysArray['date']));
	}
$currentDate = new DateTime($startDate);
$endDate = new DateTime($endDate);
$dateList = array();
foreach($holidaysList as &$currentHoliday) {
	$loopHoliday = $currentHoliday->format("Y-m-d");
	$loopDate = $currentDate->format("Y-m-d");
	if ($loopHoliday != $loopDate) {
	date_add($currentDate, date_interval_create_from_date_string('1 day'));
			}
		}
while ($currentDate <= $endDate) {
	$weekday = $currentDate->format("w");
		if ($weekday != 0 && $weekday != 6) {
	$currentDateString = $currentDate->format("Y-m-d");
	//echo $currentDateString;
	 array_push($dateList, $currentDateString);
	}
	}
	
	//echo "weekend<br />";
	
	
	//print_r($dateList);
	foreach($dateList as $child) {
		echo $child . "<br />";
		}
	echo "<br />After this is the holidays array<br />";
	foreach($holidaysList as $child) {
		echo $child . "<br />";
		}	
		

///////////////////////////////////////////

	
	