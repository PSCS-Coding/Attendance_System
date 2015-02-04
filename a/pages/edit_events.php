<?php
require_once("../../connection.php");
require_once("../../function.php");

if (!empty($_GET['eventid'])) {
	$eventid = $_GET['eventid'];
	$changerow = "inline_edit_" . $_GET['eventid'];
	$deleterow = "inline_delete_" . $_GET['eventid'];
	if (!empty($_POST['edit_submit'])) { // THESE FUNCTIONS UPDATE AN EDITED EVENT
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
   <title>Edit Events</title>
   <link rel="stylesheet" type="text/css" href="../css/jquery.datetimepicker.css" />
   <link rel="stylesheet" type="text/css" href="../../attendance.css">
   <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
   <script src="../js/jquery.datetimepicker.js"></script>
   <style type="text/css">
      .message {
         color: red;
         background: #fff;
         border: 1px solid red;
         padding: 0.5em;
         margin: 1em;
         display: inline-block;
      }
      .new-event input {
         width: 100%;
      }
      h2 {
         margin: 2em;
         text-align: center;
         font-size: 2em;
      }
      h2 span {
         font-weight: bold;
      }
   </style>
</head>

<body>

<?php if (!empty($_POST[$deleterow])) {
   echo "<div class='message'>Event #".$eventid." was deleted.</div>";
} ?>

   <h1 class="headerr">Edit Events</h1>
     
   <form method='post' id='studentform' class='studentselect' action='<?php echo basename($_SERVER['PHP_SELF']); ?>'>
      <select name='studentselect'>
         <?php foreach($current_users_result as $student) { ?>
            <option value='<?php echo $student['studentid']; ?>'><?php echo $student['firstname']?></option>
         <?php } ?>
      </select>
      <input type='submit' name='studentsubmit' class='studentselect' value="Load this student's events">
   </form>

   <?php 
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
      <h2>Events for: <span><?php echo $current_student; ?></span></h2>
      <table class='eventlog'>
         <tr>
            <th>ID</th>
            <th>Timestamp</th>
            <th>Status</th>
            <th>Info</th>
            <th>Return Time</th>
            <th>Edit</th>
         </tr>
         <tr class="new-event"> <!--TODO put new event in its own table at top-->
            <form method="post" name="new_event" action="<?php echo basename($_SERVER['PHP_SELF']); ?>">
               <td>Auto</td>
                <td>
                  <input type='text' id='new_timestamp' name='new_timestamp'>
               </td>
               <td>
                  <select name='new_status'>
                     <option value="">Select...</option>
                     <?php foreach($status_array as $status) { ?>
                        <option value='<? echo $status['statusid'] ?>'><? echo $status['statusname'] ?></option>
                     <?php } ?>
                  </select>
               </td>
               <td>
                  <input type='text' name='new_info' placeholder="Info">
               </td>
               <td>
                  <input type='text' id="new_return" name='new_return' placeholder="Return time">
               </td>
               <td>
                  <input type='submit' name='new_submit' value='Add New Event'>
               </td>
            </form>
         </tr>
         <?php
         global $timestamp_to_edit;
         foreach ($student_data_array as $event) {
            if ($event['statusname'] != 'Not Checked In') {
               $postedit = "inline_edit_" . $event['eventid'];
               if (!empty($_POST[$postedit])) {
                  $timestamp_to_edit = $event['timestamp']; // Capture this to pass to the JS timepicker
            ?>
            <form method='post' name='inline_edit' action='<?php echo basename($_SERVER['PHP_SELF']); ?>?id=<?echo $current_student_id?>&eventid=<?echo $event['eventid']?>'>
              <tr class="editing-row" style="background-color: orange;">
                  <td><?php echo $event['eventid'] ?></td>
                  <td>
                     <input type='text' id='stamp_edit' name='stamp_edit' value='<?php echo $event['timestamp']?>'> <!--TODO Format Timestamp-->
                  </td>
                  <td>
                     <select name='status_select'>
                        <?php foreach($status_array as $status) { ?>
                           <option value='<? echo $status['statusid'] ?>' <?php if ($status['statusname'] == $event['statusname']) { echo 'selected';} ?>><? echo $status['statusname'] ?></option>
                        <?php } ?>
                     </select>
                  </td>
                  <td>
                     <input type='text' name='info_edit' value='<?php echo $event['info'] ?>'>
                  </td>
                  <td>
                     <input type='text' name='time_edit' value='<?php echo $event['returntime']?>'>
                  </td>
                  <td>
                     <input type='submit' name='edit_submit' value='Save'>
                     <input type='submit' name='cancel_submit' value='Cancel'>
                  </td>
              </tr>
            </form>
            <?php } else { ?>
            <tr>
               <td><?php echo $event['eventid'] ?></td>
               <td><?php echo $event['timestamp'] ?></td>
               <td><?php echo $event['statusname'] ?></td>
               <td><?php echo $event['info'] ?></td>
               <td><?php echo $event['returntime'] ?></td>
               <td>
                  <form method='post' class='edit_interface' action='<?php echo basename($_SERVER['PHP_SELF']); ?>?id=<?echo $current_student_id?>&eventid=<?echo $event['eventid']?>'>
                   <input name='eventid' type='hidden' value='<?php echo $event['eventid'] ?>'>
                   <input type='submit' name="inline_edit_<?php echo $event['eventid']?>" value='Edit'>
                   <input type='submit' name="inline_delete_<?php echo $event['eventid']?>" value='Delete' onclick="return confirm('Are you sure you want to delete this event?');">
                  </form>
               </td>
            </tr>
            <?php
              } //end else
            } // end if not Not Checked in  
         } // end foreach event
      } // end if isset studentid
      ?>
      </table>
   <script type="text/javascript"> // This is down here so that the appropriate record's timestamp can be used as default value
      $(document).ready(function(){
         $('#stamp_edit').datetimepicker({
            onGenerate:function( ct ){
               jQuery(this).find('.xdsoft_date.xdsoft_weekend')
                  .addClass('xdsoft_disabled');
            },
            minDate:'2014/09/08',
            maxDate:'2015/6/17', // SET THESE TO GLOBALS FOR START DATE AND END DATE
            format:'Y-m-d H:i:s',
            value: '<?php echo $timestamp_to_edit; ?>',
            step: 5,
         });
         $('#new_timestamp').datetimepicker({
            onGenerate:function( ct ){
               jQuery(this).find('.xdsoft_date.xdsoft_weekend')
                  .addClass('xdsoft_disabled');
            },
            minDate:'2014/09/08',
            maxDate:'2015/6/17', // SET THESE TO GLOBALS FOR START DATE AND END DATE
            format:'Y-m-d H:i:s', 
            step: 5,
         }); 
         $('#new_return').datetimepicker({
            datepicker: false,
            format:'H:i:s',
            minTime:'09:00',
            maxTime:'15:31',
            step: 5,
         }); 
      });
   </script>
</body>
</html>