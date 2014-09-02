<html>
<head>
<title>Admin: Edit Events Table</title>
<link rel='stylesheet' href='style.css'/>
<link rel='stylesheet' href="css/pikaday.css" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js" ></script>
<link rel="stylesheet" type="text/css" href="css/jquery.timepicker.css">       
</head>
<body>
<style> <!-- anoying inline css -->
.textbox { 
    background-color: #BDD7F1; 
    border: solid 1px #646464; 
    outline: none; 
    padding: 2px 2px;
tr:nth-child(even)
{
background:#F7F7F7;
}    
} 
tr:nth-child(odd)
{
background:#E7E7FF;
}
table
{
border-spacing:0px;
}
</style>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<?php

//start session
session_start();

// set up mysql connection
require_once("../connection.php");


//function document
require_once("function.php");

//query all student data of active students
$studentquery=$db_server->query("SELECT * FROM studentdata WHERE current = 1 ORDER BY firstname ASC");
//query all status names
$statlist=$db_server->query("SELECT * FROM statusdata");
// =============== end setup ====================



if(!empty($_POST['update'])){ // edit form completed, updating contents
	
	$q_statusid = $_POST['status'];

	
	if(!empty($_POST['info'])){
	$q_info = $_POST['info'];
	} else {
	$q_info = NULL;
	}
	
	if(!empty($_POST['endtime'])){
	$q_whenreturn = new DateTime($_POST['endtime']);
	$q_timestamp = $q_whenreturn->format('Y-m-d H:i:s');
	echo $q_timestamp;
	} else {
	$q_timestamp = NULL;
	}
	
	if(!empty($_SESSION['eventid'])){
	$q_eventid = $_SESSION['eventid'];
	} else {
	$q_eventid = NULL;
	}
	
	
	// old query >>>>  $q_updateEvents=$db_server->query("UPDATE events SET statusid = $q_statusid, info= $q_info, timestamp=$q_timestamp WHERE eventid=$q_eventid") or die("Error" . mysql_error());
	
	$sql = "UPDATE events SET statusid=?, info=?, timestamp=? WHERE eventid=?";
	$stmt = $db_server->prepare($sql);
	$stmt->bind_param('issi', $q_statusid, $q_info, $q_timestamp, $q_eventid);
	$stmt->execute();

	if ($stmt->errno) {
		echo "FAILURE!!! " . $stmt->error;
	}
	else echo "Updated {$stmt->affected_rows} rows";

	
	unset($_SESSION['eventid']);
}

//if the dropdown has an option selected...
if(!empty($_POST['id'])){

//if the dropdown option is "none" the set the name to be
//the $_SESSION['id'] 
	if($_POST['id'] == "none"){
	$queryname = $_SESSION['id'];
	
// if the option is a name then set the name as selected
	} else {
	
	$_SESSION['id'] = $_POST['id'];
	$queryname = $_POST['id'];// queryname is used for getting the students firstname
	}
	}
	
	//get firstname of selected person and print it
	if(!empty($queryname)){
	$namequery=$db_server->query("SELECT firstname FROM studentdata WHERE studentid = '".$queryname."'");
	$namerow=mysqli_fetch_row($namequery);
	echo $namerow[0];
	}
	
if(!empty($_SESSION['id'])){ //get all events for the selected person

	$getevents=$db_server->query("SELECT * FROM events WHERE studentid = '".$_SESSION['id']."' ORDER BY timestamp ASC");
	$eventrow=mysqli_fetch_row($getevents);
	$eventnum=$getevents->num_rows;
	while($eventnum > 0){ //loop through all rows of events table that are related to the selected student
	$eventrow=mysqli_fetch_row($getevents);
	$instatid=$eventrow[1];
	
	$statquery=$db_server->query("SELECT statusname FROM statusdata WHERE statusid = '".$instatid."'"); // get clear-text version of status
	$status= $statquery->fetch_assoc();
	$info=$eventrow[2];
	$starttime=$eventrow[4];
	if(isset($lasttimestamp)){
	$endtime=$lasttimestamp;
	
	} else {
	$endtime="";
	}
	if(!empty($status)){ //render event for each row
	
	//make strings to compare against post variable to get what buttons were pressed
	$editstring = "edit" . $eventrow[5];
	$delstring = "delete" . $eventrow[5];
	$queryid = $eventrow[5];
	
	if(!empty($_POST[$delstring])){ //delete ========
		$delevent=$db_server->query("DELETE FROM events WHERE eventid = '".$queryid."'");
	}
		
	

	
	}
	$status='';
	$lasttimestamp=$eventrow[4];
	$eventnum=$eventnum-1; // decrement the number of events left to render
	}
	}


?>

<select name='id'>
<option value= "none"> </option> <!-- the first tag that is blank, helps handle reloads and stays with the same name -->
<?php	
	while ($dropdown_option = $studentquery->fetch_assoc()) { //render all other names as options
	?>
	<option value= '<?php echo $dropdown_option['studentid']; ?> '> <?php echo $dropdown_option['firstname'] . " " . $dropdown_option['lastname'][0]; ?></option>
	<?php }	?>
	</select>
	<input type="submit" name="search" value="Show events" />
<table style="width:600px">
		<th style="text-align:left">Status</th>
		<th style="text-align:left">Info</th>
		<!--<th style="text-align:left">Start Time</th>-->
		<th style="text-align:left">End Time</th>
		<th style="text-align:left">Edit</th>
		<th style="text-align:left">Delete</th>

	
	
<?php


if(!empty($_SESSION['id'])){ //get all events for the selected person

	$getevents=$db_server->query("SELECT * FROM events WHERE studentid = '".$_SESSION['id']."' ORDER BY timestamp ASC");
	$eventrow=mysqli_fetch_row($getevents);
	$eventnum=$getevents->num_rows;
	while($eventnum > 0){ //loop through all rows of events table that are related to the selected student
	$eventrow=mysqli_fetch_row($getevents);
	$instatid=$eventrow[1];
	
	$statquery=$db_server->query("SELECT statusname FROM statusdata WHERE statusid = '".$instatid."'"); // get clear-text version of status
	$status= $statquery->fetch_assoc();
	$info=$eventrow[2];
	$starttime=$eventrow[4];
	if(isset($lasttimestamp)){
	$endtime=$lasttimestamp;
	
	} else {
	$endtime="";
	}
	if(!empty($status)){ //render event for each row
	
	//make strings to compare against post variable to get what buttons were pressed
	$editstring = "edit" . $eventrow[5];
	$delstring = "delete" . $eventrow[5];
	$queryid = $eventrow[5];
	
	
	
	if(!empty($_POST[$editstring])){ //edit form ==========
	$_SESSION['eventid'] = $eventrow[5];
	
	?>	
	<tr>
		<td>
			<select name='status'>
				<?php	
					while ($dropdown_stat = $statlist->fetch_assoc()) { //render all other names as options
						?>
						<option value= '<?php echo $dropdown_stat['statusid']; ?> '> <?php echo $dropdown_stat['statusname'];?></option>
				<?php }	?>
			</select>
		</td>
		<td><input type="text" name="info" value="<?php echo $eventrow[2]?>"></td>
		<!--<td><input type="text" name="starttime" value="<?php //echo $starttime?>"></td>-->
		<td><input type="text" name="endtime" value="<?php echo $endtime?>"></td>
		<td><input type="submit" name="update" value="Go"></td><!-- submit button for editing -->
		<td><input type="submit" name="cancel" value="Cancel"></td> <!-- cancel button for editing -->
	</tr>
	<?php
	} else {
	?>	
	<tr>
		<td><?php echo $status['statusname'] ?></td>
		<td><?php echo $info ?></td>
		<!--<td><?php echo $starttime?></td>-->
		<td><?php echo $endtime?></td>
		<td><input type="submit" name="edit<?php echo $eventrow[5]; ?>" value="&#9998;"></td><!-- edit button with name: "edit " . $eventid -->
		<td><input type="submit" name="delete<?php echo $eventrow[5]; ?>" value="&#10006;"></td> <!-- delete button with name: "delete " . $eventid -->
	</tr>
	<?php
	}
	}
	$status='';
	$lasttimestamp=$eventrow[4];
	$eventnum=$eventnum-1; // decrement the number of events left to render
	}
	}
	?>
	
	</table> 
 
 </form>
</body>
</html>