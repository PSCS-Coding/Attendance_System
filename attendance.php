	<!DOCTYPE html>
	<html>
	<head>
		<title>PSCS Attendance</title>
		<link rel="stylesheet" type="text/css" href="InUse.css">
		<link rel="stylesheet" type="text/css" href="../css/jquery.timepicker.css">    
	    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js" ></script>
	    <script src="../js/jquery.timepicker.min.js" type="text/javascript"></script>
	    <script type="text/javascript">
			$(document).ready(function(){
				$('#offtime').timepicker({ 'scrollDefaultNow': true, 'minTime': '9:00am', 'maxTime': '3:30pm', 'timeFormat': 'g:i', 'step': 5 });
				$('#fttime').timepicker({ 'scrollDefaultNow': true, 'minTime': '9:00am', 'maxTime': '3:30pm', 'timeFormat': 'g:i', 'step': 15 });
				$('.late_time').timepicker({ 'scrollDefaultNow': true, 'minTime': '9:00am', 'maxTime': '3:30pm', 'timeFormat': 'g:i', 'step': 5 });
			});
		</script>
	</head>
	<!-- setup -->
	<?php
	    require_once("connection.php");
	    require_once("function.php");
		
		$status_result = $db_server->query("SELECT DISTINCT statusname FROM statusdata");
			$result_array = array();
			while ($blah = $status_result->fetch_assoc()) {
				$status_array[] = $blah['statusname'];
			}
	    //$a = the array to be sorted -- $subkey = the subkey to be sorted by
		function subval_sort($a, $subkey, $result) {
		//goes through the array, $k = the key, $v = value in the array?
			$temp_varname = $result . "_array";
			$$temp_varname = array();
			
			foreach($a as $k=>$v) {
				$b[$k] = $v[$subkey];
			}
			if (!empty($b)) {
			asort($b);
			foreach($b as $key=>$val) {
				if ($val == $result) {
					array_push($$temp_varname, $a[$key]);
				}	
			}
				asort($$temp_varname);
				return $$temp_varname;
	   		}
		}
	    //facilitator array, $facilitators is array of all from sql    
	    $facget = $db_server->query("SELECT * FROM facilitators ORDER BY facilitatorname ASC");
	    
	    $facilitators = array();
	    while ($fac_row = $facget->fetch_row()) {
			array_push ($facilitators, $fac_row[0]);
	    }
	    
	    //current students array
		$studentquery = "SELECT studentid FROM studentdata WHERE current=1 ORDER BY firstname";
		if (!empty($_GET['sortBy'])) {
			if ($_GET['sortBy'] == 'student' && $_GET['r'] == 1) {
				$studentquery = "SELECT studentid FROM studentdata WHERE current=1 ORDER BY firstname DESC";
			}
		}
	    $current_users_result = $db_server->query($studentquery);
		
	    
	//===========================================
	//==========on submit button click===========
	//===========================================
	
	if (!empty($_POST['person']) && isPost()){
			$name = $_POST['person'];
	
	    //present    
		if (!empty($_POST['present'])) {
			foreach ($name as $student)
			{
				changestatus($student, '1', '', '', '');
			}
		}
	
	    //offsite
		if (!empty($_POST['offsite'])) {
			if (!empty($_POST['offloc'])){
	        		$info = $_POST['offloc'];
				if (validTime($_POST['offtime'])){
					foreach ($name as $student){
					changestatus($student, '2', $info, $_POST['offtime']);
					}
				} else {
				echo "that's not a valid time";
				}
			} else {
				echo "you need to fill out the location box before signing out to offsite";
			}
		}
	
	    //fieldtrip
		if (!empty($_POST['fieldtrip'])) {
	
			if (!empty($_POST['facilitator'])){
	        		$info = $_POST['facilitator'];
				if (validTime($_POST['fttime'])){
					foreach ($name as $student){
					changestatus($student, '3', $info, $_POST['fttime']);
					}
				} else {
					echo "that's not a valid time";
				}
			} else {
				echo "you need to chose a facilitator before signing out to field trip";
			}
		}
	
	//Sign out querying -- "4" refers to "Checked Out" in statusdata table
		if (!empty($_POST['signout'])) {
			$name = $_POST['person'];
			foreach ($name as $student)
			{
				changestatus($student, '4', '', '');
			}
		}
	
	//error message when no boxes are checked
	} else if(isPost() && empty($_POST['person'])) {
		echo "please choose a student";
	}
	
	//individual present button querying -- "1" refers to "Present" in statusdata table
	if (!empty($_POST['present_bstudent'])) {
		$name = $_POST['present_bstudent'];
		changestatus($name, '1', '', '');
	}
	
	//late status querying -- "5" refers to "Late" in statusdata table
	if (!empty($_POST['Late'])) {
		$name = $_POST['late_student'];
		$status = $_POST['late_time'];
		changestatus($name, '5', '', $status);
	}
	
	if (!empty($_POST['Absent'])) {
		$name = $_POST['late_student'];
		changestatus($name, '7', '', '');
	}
	
	//Variable that is equal to the current url/php file
	$get_doc = basename($_SERVER['PHP_SELF']);
	
	if (!empty($_GET['sortBy'])) {
		if ($_GET['sortBy'] == 'student') {
			$sortBy = 'student';
		}
		if ($_GET['sortBy'] == 'status') {
			$sortBy = 'status';
		}
	}
	else {
		$sortBy = 'student';
	}
	if (empty($_GET['sortBy'])) {
		$getvar_sort_status = 'sortBy=status&r=0';
		$getvar_sort_student = 'sortBy=student&r=0';
	}
	else {
		if ($_GET['sortBy'] == 'status' && $_GET['r'] == 0) {
			$getvar_sort_status = 'sortBy=status&r=1';
			$getvar_sort_student = 'sortBy=student&r=0';
		}
		if ($_GET['sortBy'] == 'student' && $_GET['r'] == 0) {
			$getvar_sort_student = 'sortBy=student&r=1';
			$getvar_sort_status = 'sortBy=status&r=0';
		}
		if ($_GET['sortBy'] == 'status' && $_GET['r'] == 1) {
			$getvar_sort_status = 'sortBy=status&r=0';
			$getvar_sort_student = 'sortBy=student&r=0';
		}
		if ($_GET['sortBy'] == 'student' && $_GET['r'] == 1) {
			$getvar_sort_student = 'sortBy=student&r=0';
			$getvar_sort_status = 'sortBy=status&r=0';
		}
	}
	?>
	
	
	
	<!-- top form for change status -->
	<div id="tiptop">
	</div>
	<div id="top_header">
	<IMG SRC ="http://pscs.org/wp-content/themes/Starkers/images/PSCSlogo.gif" id='pscs_logo'>
	<form method='post' action='<?php echo basename($_SERVER['PHP_SELF']); ?>' id='main' style=''>
	    <div style='float:right'> 
			<?php
			if (!empty($_POST['admin_view'])) {
				?>
				<input type='submit' value='Main View' name='main_view'>
				<?php
			}
			else {			
				?>
				<input type='submit' value='Status View' name='admin_view'>
				<?php
			}
			?>
		</div>
		<div>
	        <input type="submit" value="Present" name="present">
	    </div>
	    
	    <div>
	        <input type="submit" name="offsite" value="Offsite">
	        <input type="text" name="offloc" placeholder='Location' autocomplete='on'>
			<input type="text" name="offtime" placeholder='Return time' id="offtime">
	    </div>
	    
	    <div>
	       <input type="submit" name="fieldtrip" value="Field Trip"> 
	    
	<!-- Creates the dropdown of facilitators -->
			<select name='facilitator'><option value=''>Select Facilitator</option>
	        <?php
				foreach ($facilitators as $facilitator_option) {
	        ?> 
					<option value= '<?php echo $facilitator_option; ?> '> <?php echo $facilitator_option; ?></option>
	        <?php
				}
	        ?>
	        </select>
	        <input type="text" name="fttime" placeholder="Return time" id="fttime">
	    </div>
	<!-- Sign out form -->
		<div>
			<input type="submit" value="Sign Out" name="signout">
		</div>
		
		</form>
		</div>
	<!-- student information table rendering -->
	
	<table width="80%" class='data_table' id='big_table'>
	    <tr>
	        <th class='data_table' style="width:10%"></th>
	        <th class='data_table' style="width:10%"><a href="attendance.php?<?php echo $getvar_sort_student; ?>">Student</a></th>
	        <th class='data_table' id='status_header' style="width:20%"><a href="attendance.php?<?php echo $getvar_sort_status; ?>">Status</a></th>
	    </tr>
	    <?php
		if (!empty($_POST['admin_view'])) {
			$student_data_array = array();
				while ($current_student_id = $current_users_result->fetch_assoc()) { // LOOPS THROUGH ALL OF THE CURRENT STUDENTS	
					$result = $db_server->query("SELECT firstname,lastname,statusname,studentdata.studentid,info,timestamp,returntime
										 FROM events 
										 JOIN statusdata ON events.statusid = statusdata.statusid
										 RIGHT JOIN studentdata ON events.studentid = studentdata.studentid
										 WHERE studentdata.studentid = $current_student_id[studentid] 
										 ORDER BY timestamp DESC
										 LIMIT 1") 
										 or die(mysqli_error($db_server));
					$result_array = $result->fetch_assoc();				
					array_push($student_data_array, $result_array);
				}
	
				?> <table id='status_display'> <?php		
				foreach ($status_array as $status) {
					
					$sorted_data_array = subval_sort($student_data_array, 'statusname' , $status);
					if (!empty($sorted_data_array)) {
					?>
					<th><?php echo $status; ?></th>
					<?php
						foreach ($sorted_data_array as $student) {
							?>
							<tr><td><?php echo $student['firstname']; ?></td></tr>
							<?php
						}
					}	
				} 
		}

			
			$student_data_array = array();
			while ($current_student_id = $current_users_result->fetch_assoc()) { // LOOPS THROUGH ALL OF THE CURRENT STUDENTS	
				$result = $db_server->query("SELECT firstname,lastname,statusname,studentdata.studentid,info,timestamp,returntime
										 FROM events 
										 JOIN statusdata ON events.statusid = statusdata.statusid
										 RIGHT JOIN studentdata ON events.studentid = studentdata.studentid
										 WHERE studentdata.studentid = $current_student_id[studentid] 
										 ORDER BY timestamp DESC
										 LIMIT 1")
										 or die(mysqli_error($db_server));
				$result_array = $result->fetch_assoc();
				array_push($student_data_array, $result_array);
			}
	if (isset($_GET['sortBy'])) {
	if ($_GET['sortBy'] == 'status')	{
		$result_total_array = array();
		foreach ($status_array as $status)	{
			$result_array = subval_sort($student_data_array, 'statusname', $status);
			array_push($result_total_array, $result_array);
		}
		$student_data_array = $result_total_array;
	}
	}
	// we might need this...
	if (isset($_GET['sortBy'])) {
	if ($_GET['sortBy'] != 'status') {
		$result_total_array = array();
		array_push($result_total_array, $student_data_array);
		$student_data_array = $result_total_array;
	}
	}
	elseif (empty($_GET['sortBy'])) {
		$result_total_array = array();
		array_push($result_total_array, $student_data_array);
		$student_data_array = $result_total_array;
	}
	if (!empty($_GET['sortBy'])) {
				if ($_GET['sortBy'] == 'status' && $_GET['r'] == 0) {
					$student_data_array = array_reverse($student_data_array);
				}
			}
		foreach ($student_data_array as $mini_array) {
			foreach ($mini_array as $latestdata) {
					
				$day_data = new DateTime($latestdata['timestamp']);
				//the day it was yesterday
				$yesterday = new DateTime('yesterday 23:59:59');
				if ($day_data < $yesterday) {
						changestatus($latestdata['studentid'], '8', '', '');
				}
				?>
				<tr>
					<td class='data_table'>
						<!-- checkbox that gives student data to the form at the top -->
						<input type='checkbox' name='person[]' value='<?php echo $latestdata['studentid']; ?>' form='main' class='c_box'>
	
						<?php if (($latestdata['statusname'] != 'Present' && $latestdata['statusname'] != 'Absent') || ($day_data < $yesterday)) { // if the student is not present or hasn't updated since midnight, show a present button ?>
						<!-- present button, passes hidden value equal to the current student -->
						<form action='<?php echo basename($_SERVER['PHP_SELF']); ?>' method='post'>
							<input type='submit' value='P' class='p_button' name='present_button'>
							<input type='hidden' name='present_bstudent' value='<?php echo $latestdata['studentid']; ?>'>
						</form>
						<?php } // end "not present" if clause
						if ($latestdata['statusname'] == 'Not Checked In') { // if the student hasn't updated status since midnight, display a late button ?>
						<!-- Late button with time input next to it -->
						<form action='<?php echo basename($_SERVER['PHP_SELF']); ?>' method='post'>
							<input type='submit' value='A' name='Absent' class='absent_button' style='float:left'>
							<input type='hidden' name='absent_student' value='<?php echo $latestdata['studentid']; ?>'>
						</form>
						<form action='<?php echo basename($_SERVER['PHP_SELF']); ?>' method='post'>
							<input type='submit' value='Late' name='Late' class='l_button'>
							<input type='input' name='late_time' placeholder='Expected' class='late_time'>
							<input type='hidden' name='late_student' value='<?php echo $latestdata['studentid']; ?>'>
						</form>
						
						<?php } ?>
					</td>
				<?php 
				$lastinitial = substr($latestdata['lastname'], 0, 1); ?>
	            <!-- displays current rows student name, that students status and any comment associated with that status -->
					<td class='student_data'><a style="text-decoration:none" href="user.php?id=<?php echo $latestdata['studentid']; ?>&name=<?php echo $latestdata['firstname'];?>"><?php print $latestdata['firstname'] . " " . $lastinitial; ?></a></td>
					<td class='status_data'><?php 
						$returntimeobject = new DateTime($latestdata['returntime']);
						echo $latestdata['statusname'] . " "; 
						
						if ($latestdata['statusname'] == "Offsite") {
							echo "at " . $latestdata['info'] . " returning at " . $returntimeobject->format('h:i');
						}
						if ($latestdata['statusname'] == "Field Trip") {
							echo "with " . $latestdata['info'] . " returning at " . $returntimeobject->format('h:i');
						}
	
						if ($latestdata['statusname'] == "Late") {
							echo $latestdata['info'] . " arriving at " . $returntimeobject->format('h:i');
						}
	
						?>
						</td>
				</tr>
	<?php		
			} 
			}


		//================================================================================//
		//================================================================================//
		// FINISHES THE WHILE LOOP THAT GOES THROUGH THE LATEST ROWS FROM THE EVENTS TABLE//
		//================================================================================//
		//================================================================================//
	?>
	</table>
	</table>
	</body>
	</html>