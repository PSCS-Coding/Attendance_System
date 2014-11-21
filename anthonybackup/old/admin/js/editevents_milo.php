<html>
<head>
<title>Admin: Edit Events Table</title>
<link rel='stylesheet' href='style.css'/>
<link rel='stylesheet' href="css/pikaday.css" />
<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js" ></script>
<link rel="stylesheet" type="text/css" href="css/jquery.timepicker.css">       
</head>
<body>
<?php
	session_start();
// set up mysql connection
	require_once("../connection.php");
//function document
	require_once("function.php");

$studentquery=$db_server->query("SELECT * FROM studentdata ORDER BY firstname ASC");

?>
<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<select name='id'>
<?php	
	while ($dropdown_option = $studentquery->fetch_assoc()) {
	?>
	<option value= '<?php echo $dropdown_option['studentid']; ?> '> <?php echo $dropdown_option['firstname'] . " " . $dropdown_option['lastname'][0]; ?></option>
	<?php } ?>
	</select>
	<input type="submit" name="search" value="Show events" />
<table>
		<th>Status</th>
		<th>Info</th>
		<th>Start Time</th>
		<th>End Time</th>

	
	
<?php
if(!empty($_POST['search']) and !empty($_POST['id'])){
	$id=$_POST['id'];

	$getevents=$db_server->query("SELECT * FROM events WHERE studentid = '".$id."' ORDER BY timestamp ASC");
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