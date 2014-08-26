<html>
<head>
<title>Admin: Edit Events Table</title>
<link rel='stylesheet' href='style.css'/>
<link rel='stylesheet' href="css/pikaday.css" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js" ></script>
<link rel="stylesheet" type="text/css" href="css/jquery.timepicker.css">       
</head>
<body>
<style> 
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
<?php
	session_start();

if(!empty($_POST['id'])){
$_SESSION['id']=$_POST['id'];
}

// set up mysql connection
	require_once("../connection.php");
//function document
	require_once("function.php");

$studentquery=$db_server->query("SELECT * FROM studentdata ORDER BY firstname ASC");


	if(!empty($_SESSION['id'])){
	$queryname=$_SESSION['id'];
	
	$namequery=$db_server->query("SELECT firstname FROM studentdata WHERE studentid = '".$queryname."'");
	$namerow=mysqli_fetch_row($namequery);
	echo $namerow[0];
	}
	
?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<select name='id'>
<?php	
	while ($dropdown_option = $studentquery->fetch_assoc()) {
	?>
	<option value= '<?php echo $dropdown_option['studentid']; ?> '> <?php echo $dropdown_option['firstname'] . " " . $dropdown_option['lastname'][0]; ?></option>
	<?php }	?>
	</select>
	<input type="submit" name="search" value="Show events" />
<table style="width:600px">
		<th style="text-align:left">Status</th>
		<th style="text-align:left">Info</th>
		<th style="text-align:left">Start Time</th>
		<th style="text-align:left">End Time</th>
		<th style="text-align:left">Edit</th>
		<th style="text-align:left">Delete</th>

	
	
<?php


if(!empty($_SESSION['id'])){

	$getevents=$db_server->query("SELECT * FROM events WHERE studentid = '".$_SESSION['id']."' ORDER BY timestamp ASC");
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
		<td><input type="submit" name="edit<?php echo $eventrow['eventid']; ?>" value="Edit"></td>
		<td><input type="submit" name="delete<?php echo $eventrow['eventid']; ?>" value="X"></td>
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
 
 </form>
</body>
</html>