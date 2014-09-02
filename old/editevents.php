<html>
<head><title>Edit Events</title></head>
<body>
<?
require_once('../connection.php');
require_once('function.php');
$current_student_id = 30;
if (!empty($_POST['inline_delete'])) {
	$eventid = $_POST['eventid'];
	$delete = "DELETE * FROM events WHERE eventid='$eventid'";
	$db_server->query($delete);
}
//current students array
$studentquery = "SELECT studentid, firstname, lastname FROM studentdata WHERE current=1 ORDER BY firstname";
$current_users_query = $db_server->query($studentquery);
$current_users_result = array();
while ($student = $current_users_query->fetch_array()) {
	array_push($current_users_result, $student);
}
if (!empty($_POST['studentselect'])){
    $current_student_id = $_POST['studentselect'];
}
elseif (!isset($current_student_id)) {
	echo "Please select a student ";
}
if (isset($current_student_id)) {
$student_data_array = array();
//fetches most recent data from the events table
//joins with the tables that key student names/status names to the ids in the events table
$result = $db_server->query("SELECT info,statusname,studentdata.studentid,studentdata.firstname,studentdata.lastname,timestamp,returntime,events.eventid, yearinschool
		FROM events 
		JOIN statusdata ON events.statusid = statusdata.statusid
		RIGHT JOIN studentdata ON events.studentid = studentdata.studentid
		WHERE studentdata.studentid = $current_student_id
		ORDER BY timestamp ASC") or die(mysqli_error($db_server));
while ($student_data_result = $result->fetch_assoc()) {
	array_push($student_data_array, $student_data_result);
}
$current_student = $student_data_array[0]['firstname'] . " " . $student_data_array[0]['lastname'];
}

$status_result = $db_server->query("SELECT DISTINCT statusname FROM statusdata");
			$result_array = array();
			while ($blah = $status_result->fetch_assoc()) {
				$status_array[] = $blah['statusname'];
			}
?>
<form method='post' id='studentform' class='studentselect' action='<?php echo basename($_SERVER['PHP_SELF']); ?>'>
<select name='studentselects' class='studentselect'>
<?php 
foreach($current_users_result as $student) {
	?>
	<option name='studentselect' value='<?php echo $student['studentid']; ?>'><?php echo $student['firstname']?></option>
	<?php
}
?>
</select>
<input type='submit' name='studentsubmit' class='studentselect'>
</form>
<? if (isset($current_student_id)) {
?>
<p><?php echo $current_student; ?></p>
<table class='eventlog'>
<th>Status</th>
<th>Info</th>
<th>Return Time</th>
<th>Timestamp</th>
<th></th>
<?php
foreach ($student_data_array as $event) {
	if (!empty($_POST['inline_edit'])) {
		if ($_POST['eventid'] == $event['eventid']) {
			?>
			<form method='post' name='inline_edit' action='<?php echo basename($_SERVER['PHP_SELF']); ?>'>
			<tr>
				<td>
					<select name='status_select'>
					<? foreach($status_array as $status) {
					?>
					<option value='<? echo $status ?>'><? echo $status ?></option>
					<? } ?>
					</select>
				</td>
				<td>
					<input type='text' name='info_edit' placeholder='<? echo $event['info'] ?>'>
				</td>
				<td>
					<input type='text' name='time_edit' placeholder='<? echo $event['returntime']?>'>
				</td>
				<td>
					<input type='text' name='stamp_edit' placeholder='<?echo $event['timestamp']?>'>
				</td>
				<td>
					<input type='submit' name='edit_submit' value='Save Changes'>
				</td>
			</tr>
			</form>
			<?
			} else {
			?>
			<tr>
	<td><?php echo $event['statusname'] ?></td>
	<td><?php echo $event['info'] ?></td>
	<td><?php echo $event['returntime'] ?></td>
	<td><?php echo $event['timestamp'] ?></td>
	<td>
	<form method='post' class='edit_interface' action='<?php echo basename($_SERVER['PHP_SELF']); ?>'>
	<input name='eventid' type='hidden' value='<?php echo $event['eventid'] ?>'>
	<input type='submit' name="inline_edit" value='Edit'>
	<input type='submit' name="inline_delete" value='Delete'>
	</tr>
	<? }
	} else {
?>
	<tr>
	<td><?php echo $event['statusname'] ?></td>
	<td><?php echo $event['info'] ?></td>
	<td><?php echo $event['returntime'] ?></td>
	<td><?php echo $event['timestamp'] ?></td>
	<td>
	<form method='post' class='edit_interface' action='<?php echo basename($_SERVER['PHP_SELF']); ?>'>
	<input name='eventid' type='hidden' value='<?php echo $event['eventid'] ?>'>
	<input type='submit' name="inline_edit" value='Edit'>
	<input type='submit' name="inline_delete" value='Delete'>
	</tr>
<?php 
	}
}
} 
?>
</table>
</body>
</html>