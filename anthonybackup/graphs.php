<DOCTYPE html>
<html>
	<head>
		<title>Graphs</title>
	</head>
<?php

	/*
	I'm trying out style50 for this page... 
	If you add code to this please use CS50 format
	For example, giving curly brackets their own lines
	*/

session_start();

require_once("connection.php");
require_once("function.php");				 				

$_SESSION['prevURL'] = $_SERVER['REQUEST_URI'];
 
//make this $_SESSION['adminSet'] if it's an admin-only page
if(!$_SESSION['set']) 
	{
	header("location: main_login.php");
	}
	
	$id = 30;
	//$eventsQuery = $db_server->query("SELECT * FROM events WHERE studentid = $id");
	$datesQuery = $db_server->query("SELECT * FROM globals");
	while($dateArray = $datesQuery->fetch_assoc())
		{
		$startDate = new DateTime($dateArray['startdate']);
		$endDate = new DateTime($dateArray['enddate']);
		}
		
	//down is setting up for the foreach loop that cycles through all dates between school start and school end

	//down is loop
	$curr = clone $startDate;
	while($curr <= $endDate) 
		{
		
		$currWeekday = $curr->format("w"); //currWeekday is the day of the week of $curr - Sunday is 1 & Saturday is 6
		
		//down is checking if the date is a holiday or is a weekend - check line 114 of function.php for the function
		if (validDate($curr->format("Y-m-d")) == false)
			{
			//echo $currWeekday;
			$height = 90;
			echo "<div display:inline-block; class='notvalid'; height='" . $height . "px'> </div>";
			} else {
			echo $currWeekday;
			$height = 30;
			echo "<div class='valid'> </div>";
			}
			date_add($curr, date_interval_create_from_date_string('1 day'));
		}
	//decho $startDate . "<br />" . $endDate;
?>
<style>
.notvalid {
background: red;
width: 30px;
display: block;
}
.valid {
background: green;
width: 30px;
height: 50px;
display: block
}
</style>