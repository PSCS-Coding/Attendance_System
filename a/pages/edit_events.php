<?php
require_once("../../connection.php");
require_once("../../function.php");

if (!empty($_GET['eventid'])) {
	$eventid = $_GET['eventid'];
	$changerow = "inline_edit_" . $_GET['eventid'];
	$deleterow = "inline_delete_" . $_GET['eventid'];
	if (!empty($_POST['edit_submit'])) {
		if (!empty($_POST['status_select'])) {
			$statusid = $_POST['status_select'];
			$update = $db_server->prepare("UPDATE events SET statusid=? WHERE eventid=?");
			$update->bind_param('ii', $statusid, $eventid);
			$update->execute();
			$update->close();
		}
		if (!empty($_POST['info_edit'])) {
			$info = $_POST['info_edit'];
			$update = $db_server->prepare("UPDATE events SET info=? WHERE eventid=?");
			$update->bind_param('si', $info, $eventid);
			$update->execute();
			$update->close();
		}
		if (!empty($_POST['time_edit'])) {
			$time = $_POST['time_edit'];
			$update = $db_server->prepare("UPDATE events SET returntime=? WHERE eventid=?");
			$update->bind_param('ss', $time, $eventid);
			$update->execute();
			$update->close();
		}
		if (!empty($_POST['stamp_edit'])) {
			$stamp = $_POST['stamp_edit'];
			$update = $db_server->prepare("UPDATE events SET timestamp=? WHERE eventid=?");
			$update->bind_param('ss', $stamp, $eventid);
			$update->execute();
			$update->close();
		}
	}
	if (!empty($_POST[$deleterow])) {
		$delete = $db_server->prepare("DELETE FROM events WHERE eventid=?");
		$delete->bind_param('i', $eventid);
		$delete->execute();
		$delete->close();
		}
	elseif (!empty($_POST[$deleterow])) {
	
	}
}

//current students array
$studentquery = "SELECT studentid, firstname, lastname FROM studentdata WHERE current=1 ORDER BY firstname";
$current_users_query = $db_server->query($studentquery);
$current_users_result = array();
while ($student = $current_users_query->fetch_array()) {
	array_push($current_users_result, $student);
}
//status query
$status_result = $db_server->query("SELECT DISTINCT statusname, statusid FROM statusdata");
$status_array = array();
while ($blah = $status_result->fetch_assoc()) {
	array_push($status_array, $blah);
}
//keeps student id in get var
if (!empty($_GET['id'])) {
	$current_student_id = $_GET['id'];
}
if (!empty($_POST['studentselect'])) {
	$current_student_id = $_POST['studentselect'];
	header("Location: " . basename($_SERVER['PHP_SELF']) . "?id=" . $current_student_id);
	exit();
}
	?>
<html>
   <head>
      <title>This page needs to have a title attribute</title>
      <link rel="stylesheet" type="text/css" href="../css/jquery.datetimepicker.css"/ >
      <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
      <script src="../js/jquery.datetimepicker.js"></script>
      <script type="text/javascript">
         $(document).ready(function(){
            $('#stamp_edit').datetimepicker();
         })
      </script>
   </head>
<body>

<h1 class="headerr">Edit Events</h1>
     
	<form method='post' id='studentform' class='studentselect' action='<?php echo basename($_SERVER['PHP_SELF']); ?>'>
		<select name='studentselect'>
		<?php 
		foreach($current_users_result as $student) {
		?>
			<option value='<?php echo $student['studentid']; ?>'><?php echo $student['firstname']?></option>
		<?php
		}
		?>
		</select>
		<input type='submit' name='studentsubmit' class='studentselect'>
	</form>

<?
if (isset($current_student_id)) {
$student_data_array = array();
//fetches most recent data from the events table
//joins with the tables that key student names/status names to the ids in the events table
$result = $db_server->query("SELECT info,statusname,studentdata.studentid,studentdata.firstname,studentdata.lastname,timestamp,returntime,events.eventid, yearinschool
		FROM events 
		JOIN statusdata ON events.statusid = statusdata.statusid
		RIGHT JOIN studentdata ON events.studentid = studentdata.studentid
		WHERE studentdata.studentid = $current_student_id
		ORDER BY timestamp DESC") or die(mysqli_error($db_server));
while ($student_data_result = $result->fetch_assoc()) {
	array_push($student_data_array, $student_data_result);
}
$current_student = $student_data_array[0]['firstname'] . " " . $student_data_array[0]['lastname'];
?>
<p><?php echo $current_student; ?></p>
<table class='eventlog'>
<th>ID</th>
<th>Status</th>
<th>Info</th>
<th>Return Time</th>
<th>Timestamp</th>
<th></th>
<?php
foreach ($student_data_array as $event) {
	$postedit = "inline_edit_" . $event['eventid'];
	if (!empty($_POST[$postedit])) {
?>
	<form method='post' name='inline_edit' action='<?php echo basename($_SERVER['PHP_SELF']); ?>?id=<?echo $current_student_id?>&eventid=<?echo $event['eventid']?>'>
	<tr>
		<td><?php echo $event['eventid'] ?></td>
                <td>
			<select name='status_select'>
			<? foreach($status_array as $status) {
			?>
			<option value='<? echo $status['statusid'] ?>' <?php if ($status['statusname'] == $event['statusname']) { echo 'selected';} ?>><? echo $status['statusname'] ?></option>
			<? } ?>
			</select>
		</td>
		<td>
			<input type='text' name='info_edit' value='<?php echo $event['info'] ?>'>
		</td>
		<td>
			<input type='text' name='time_edit' value='<?php echo $event['returntime']?>'>
		</td>
		<td>
			<input type='text' id='stamp_edit' name='stamp_edit' value='<?php echo $event['timestamp']?>'>
		</td>
		<td>
			<input type='submit' name='edit_submit' value='Save Changes'>
                           <!--ADD DISCARD CHANGES BUTTON HERE-->
		</td>
	</tr>
	</form>
	<?php
	} else {
	?>
	<tr>
	<td><?php echo $event['eventid'] ?></td>
        <td><?php echo $event['statusname'] ?></td>
	<td><?php echo $event['info'] ?></td>
	<td><?php echo $event['returntime'] ?></td>
	<td><?php echo $event['timestamp'] ?></td>
	<td>
	<form method='post' class='edit_interface' action='<?php echo basename($_SERVER['PHP_SELF']); ?>?id=<?echo $current_student_id?>&eventid=<?echo $event['eventid']?>'>
         <input name='eventid' type='hidden' value='<?php echo $event['eventid'] ?>'>
         <input type='submit' name="inline_edit_<?php echo $event['eventid']?>" value='Edit'>
         <input type='submit' name="inline_delete_<?php echo $event['eventid']?>" value='Delete'>
        </form>
	</tr>
	<?php
	}
} // end foreach
} // end if isset studentid
?>
</table>

</body>
</html>