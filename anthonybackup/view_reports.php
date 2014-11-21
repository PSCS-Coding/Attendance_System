<?php

// at the moment, this is a simple valid date function, and produces an array of 
// all of the valid dates

session_start();

require_once("connection.php");
require_once("function.php");
$_SESSION['prevURL'] = $_SERVER['REQUEST_URI'];

//make this $_SESSION['adminSet'] if it's an admin-only page
if(!$_SESSION['set']) {
	header("location: main_login.php");
}


// start function
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
	
	foreach($dateList as $child) {
		echo $child . "<br />";
		}