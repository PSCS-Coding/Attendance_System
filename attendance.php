	<!DOCTYPE html>
	<html>
	<head>
		<title>PSCS Attendance</title>
		<link rel="stylesheet" type="text/css" href="attendance.css">
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
		//this function sorts multidimensional arrays by one of their subkeys
	    //$a = the array to be sorted -- $subkey = the subkey to be sorted by
		function subval_sort($a, $subkey, $result) {
			
			//dynamic variable naming to ensure the result array is named 'subkey'_array
			$temp_varname = $result . "_array";
			$$temp_varname = array();
			
			//goes through the array, $k = the key, $v = value in the array
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
		
	    
	// changestatus functions to create new entries in the database
	//each only triggers if each field is correctly filled out, and the corresponding submit button has been pressed
	
	//checks value of either checkboxes - $name = all checked students
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
	
	//absent buttons
	if (!empty($_POST['Absent'])) {
		$name = $_POST['late_student'];
		changestatus($name, '7', '', '');
	}
	
	//basic checks to set a variable equal to the correct string to be passed into the get variable
	//sortBy == the value that the table should be sorted by $r == whether to reverse sort
	
	//default values, when get is empty
	if (empty($_GET['sortBy'])) {
		$getvar_sort_status = 'sortBy=status&r=0';
		$getvar_sort_student = 'sortBy=student&r=0';
	}
	//triggers when get[sortby] is populated with values
	else {
		//correctly makes the table reverse sortable when a header is clicked twice
		//also assigns the default value for the opposite sortby value when the get variable is populated
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
			//button for the alternate status view option
			//displays a button to return the user to the main page when at the alternate view
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
			<!-- top interface present button -->
	        <input type="submit" value="Present" name="present">
	    </div>
	    
	    <div>
			<!-- top interface offsite -->
	        <input type="submit" name="offsite" value="Offsite">
	        <input type="text" name="offloc" placeholder='Location' autocomplete='on'>
			<input type="text" name="offtime" placeholder='Return time' id="offtime">
	    </div>
	    
	    <div>
			<!-- top interface fieldtrip -->
	       <input type="submit" name="fieldtrip" value="Field Trip"> 
	    
			<!-- Creates the dropdown of facilitators -->
			<select name='facilitator'><option value=''>Select Facilitator</option>
	        <?php
				//checks the database of facilitators to ensure the dropdown menu is correctly populated by all current staff/facilitators
				foreach ($facilitators as $facilitator_option) {
	        ?> 
					<option value= '<?php echo $facilitator_option; ?> '> <?php echo $facilitator_option; ?></option>
	        <?php
				}
	        ?>
	        </select>
	        <input type="text" name="fttime" placeholder="Return time" id="fttime">
	    </div>
	
		<div>
			<!-- top interface sign out button -->
			<input type="submit" value="Sign Out" name="signout">
		</div>
		
		</form>
		</div>
	<!-- student information table rendering -->
	
	<table width="80%" class='data_table' id='big_table'>
	    <tr>
	        <th class='data_table' style="width:10%"></th>
			<!-- clickable headers for the table, allows them to be sorted -->
	        <th class='data_table' style="width:10%"><a href="attendance.php?<?php echo $getvar_sort_student; ?>">Student</a></th>
	        <th class='data_table' id='status_header' style="width:20%"><a href="attendance.php?<?php echo $getvar_sort_status; ?>">Status</a></th>
	    </tr>
	    <?php
		
		$student_data_array = array();
			//loops through current students
				while ($current_student_id = $current_users_result->fetch_assoc()) {
					//fetches most recent data from the events table
					//joins with the tables that key student names/status names to the ids in the events table
					$result = $db_server->query("SELECT firstname,lastname,statusname,studentdata.studentid,info,timestamp,returntime
										 FROM events 
										 JOIN statusdata ON events.statusid = statusdata.statusid
										 RIGHT JOIN studentdata ON events.studentid = studentdata.studentid
										 WHERE studentdata.studentid = $current_student_id[studentid] 
										 ORDER BY timestamp DESC
										 LIMIT 1") 
										 or die(mysqli_error($db_server));
					$result_array = $result->fetch_assoc();
					//pushed each individual students data into an array				
					array_push($student_data_array, $result_array);
				}
				
		//renders alternate status view when chosen
		if (!empty($_POST['admin_view'])) {
			
				//using the above data from the query, this renders the alternate status view
				?> <table id='status_display'> <?php
				//creates a table header for each of the possible status'	
				foreach ($status_array as $status) {
					//calls the sort function to sort the array of students by subkey status
					$sorted_data_array = subval_sort($student_data_array, 'statusname' , $status);
					//only renders the table headers for status' that have students assigned to that status
					if (!empty($sorted_data_array)) {
					?>
					<th style='float:left'><?php echo $status; ?></th>
					<?php
						foreach ($sorted_data_array as $student) {
							?>
							<tr><td><?php echo $student['firstname']; ?></td></tr>
							<?php
						}
					}	
				} 
		} //closes the alternate view for status
	
	//checks how the table should be sorted. Default is alphabetically by student
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
	//loops through student data array
	//does it twice because when the table is sorted by status, the array containing the data has an extra dimension
		foreach ($student_data_array as $mini_array) {
			foreach ($mini_array as $latestdata) {
				//sets up relevant time and date data to automatically start a new day the first time the page is loaded
				$day_data = new DateTime($latestdata['timestamp']);
				$yesterday = new DateTime('yesterday 23:59:59');
				//if the last entry for a student was yesterday, this makes an entry for 'not checked in'
				if ($day_data < $yesterday) {
					changestatus($latestdata['studentid'], '8', '', '');
				}
				?>
				<tr>
					<td class='data_table'>
						<!-- checkbox that gives student data to the form at the top -->
						<input type='checkbox' name='person[]' value='<?php echo $latestdata['studentid']; ?>' form='main' class='c_box'>
	
						<?php 
						// if the student is not present or hasn't updated since midnight, show a present button 
						if (($latestdata['statusname'] != 'Present' && $latestdata['statusname'] != 'Absent') || ($day_data < $yesterday)) {
						?>
						<form action='<?php echo basename($_SERVER['PHP_SELF']); ?>' method='post'>
							<input type='submit' value='P' class='p_button' name='present_button'>
							<input type='hidden' name='present_bstudent' value='<?php echo $latestdata['studentid']; ?>'>
						</form>
						
						<?php 
						}
						// if the student hasn't updated status since midnight, display a late button
						if ($latestdata['statusname'] == 'Not Checked In') {
						?>
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
				//variable equal to a students last name initial
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
	?>
	</table>
	</table>
	</body>
	</html>