	<!DOCTYPE html>
	<html>
	<head>
        <title>PSCS Attendance</title>
		<link rel="stylesheet" type="text/css" href="attendance.css">
		<link rel="stylesheet" type="text/css" href="css/jquery.timepicker.css">    
	    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js" ></script>
	    <script src="js/jquery.timepicker.min.js" type="text/javascript"></script>
        <link rel="shortcut icon" type="image/png" href="img/mobius.png"/>
	    <script type="text/javascript">
			$(document).ready(function(){
				$('#offtime').timepicker({ 'scrollDefaultNow': true, 'minTime': '9:00am', 'maxTime': '3:30pm', 'timeFormat': 'g:i', 'step': 5 });
				$('#fttime').timepicker({ 'scrollDefaultNow': true, 'minTime': '9:00am', 'maxTime': '3:30pm', 'timeFormat': 'g:i', 'step': 15 });
				$('.late_time').timepicker({ 'scrollDefaultNow': true, 'minTime': '9:00am', 'maxTime': '3:30pm', 'timeFormat': 'g:i', 'step': 5 });
			});
		</script>
		<script type="text/javascript">
			function setIdle(cb, seconds) {
    			var timer; 
    			var interval = seconds * 1000;
    			function refresh() {
            		clearInterval(timer);
            		timer = setTimeout(cb, interval);
    			};
    		$(document).on('keypress, click, mousemove', refresh);
    		refresh();
			}
			
			setIdle(function() {location.href = location.href;}, 300);
		</script>
	</head>
	<body class="mainpage">
	<div id="puttheimagehere"><img src="img/mobius.png" /></div>
	<!-- setup -->
	<?php
    //    require_once("login.php");
	    require_once("connection.php");
	    require_once("function.php");
		
		$null_value = null;
		
		$status_result = $db_server->query("SELECT DISTINCT statusname FROM statusdata");
			$result_array = array();
			while ($blah = $status_result->fetch_assoc()) {
				$status_array[] = $blah['statusname'];
			}
		
		//time for fetching groups!!
		$groupsQuery = $db_server->query("SELECT name, studentids FROM groups ORDER BY name DESC");
			$groupsResult = array();
			while ($group = $groupsQuery->fetch_array()) {
				array_push($groupsResult, $group);
				//$groupsCount += 1;
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
			if ($subkey == 'statusname') {
			asort($b);
			}
			foreach($b as $key=>$val) {
				if ($val == $result) {
					array_push($$temp_varname, $a[$key]);
				}	
			}
			if ($subkey == 'statusname') {
				asort($$temp_varname);
			}
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
				changestatus($student, '1', '', '');
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
					echo "<div class='error'>Please enter a valid return time.</div>";
				}
			} else {
				echo "<div class='error'>Please fill out the location box before signing out to offsite.</div>";
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
					echo "<div class='error'>Please enter a valid return time.</div>";
				}
			} else {
				echo "<div class='error'>Please chose a valid facilitator.</div>";
			}
		}
	
	//Sign out querying -- "4" refers to "Checked Out" in statusdata table
		if (!empty($_POST['checkout'])) {
			foreach ($name as $student) {
				changestatus($student, '4', '', '');
			}
		}
			
	//error message when no boxes are checked
	} else if(isPost() && empty($_POST['person'])) {
		echo "<div class='error'>Please choose a student.</div>";
	}
	
	//individual present button querying -- "1" refers to "Present" in statusdata table
	if (!empty($_POST['present_bstudent'])) {
		$name = $_POST['present_bstudent'];
		changestatus($name, '1', '', $null_value);
	}
	

//individual Checked Out button querying -- "4" refers to "Checked Out" in statusdata table
	if (!empty($_POST['co_bstudent'])) {
		$name = $_POST['co_bstudent'];
		changestatus($name, '4', '', $null_value);
	}

	//late status querying -- "5" refers to "Late" in statusdata table
	if (!empty($_POST['Late'])) {
		if (validTime($_POST['late_time'])) {
			$name = $_POST['late_student'];
			$status = $_POST['late_time'];
			changestatus($name, '5', '', $status);
			}
		else {
				echo "<div class='error'>Please enter a valid expected arrival time.</div>";
			}
	}
	
	//absent buttons
	if (!empty($_POST['Absent'])) {
		$name = $_POST['absent_student'];
		changestatus($name, '7', '', $null_value);
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



        // THIS IS FOR THE "Check Out" Buttons at the end of the day
        //Sets current time
       $phpdatetime = new dateTime();
       $current_time = $phpdatetime->format('h:i a');
        //Query for globals
        $globalsresult = $db_server->query("SELECT * FROM globals");
        while ($list = mysqli_fetch_assoc($globalsresult))
        {
            // Making time look nice
            $pretty_end_time = new DateTime($list['endtime']);
            // Code for checking if 10 min before globals.endtime
            $co_start_time = date($list['endtime']);
            $co_start = strtotime ( '-10 minute' , strtotime ( $co_start_time ) ) ;
            $co_start = date ( 'h:i a' , $co_start );
            
            // Code for checking if 10 min after globals.endtime
            $co_end_time = date($list['endtime']);
            $co_end = strtotime ( '+10 minute' , strtotime ( $co_end_time ) ) ;
            $co_end = date ( 'h:i a' , $co_end );
        }
        //Making Varibles possible
        $date1 = DateTime::createFromFormat('h:i a', $current_time);
        $date2 = DateTime::createFromFormat('h:i a', $co_start);
        $date3 = DateTime::createFromFormat('h:i a', $co_end);
        // Checking if between globals.endtime time
        if ($date1 > $date2 && $date1 < $date3) {
        if ($pretty_end_time->format('hi a') < date('hi a')) { ?>
        
            <div class='COTimer COTgood'>Current Time: <?php echo date('g:i a'); ?></div>
        
        <?php } elseif ($pretty_end_time->format('hi a') > date('hi a')) { ?>
        
            <div class='COTimer COTbad'>Current Time: <?php echo date('g:i a'); ?></div>
        
       <?php } else { ?>
        <div class='COTimer COTgood'>Current Time: <?php echo date('g:i a'); ?></div>
        <?php } }?>
	<!-- top form for change status -->
	<div id="top_header">
	<form method='post' action='<?php echo basename($_SERVER['PHP_SELF']); ?>' id='main' >
		
		<div>
			<!-- top interface present button -->
	        <input class="button" type="submit" value="Present" name="present">
	    </div>
	   
	   	<div>
			<!-- top interface sign out button -->
			<input class="button" type="submit" value="Check Out" name="checkout">
		</div>
 
	    <div>
			<!-- top interface offsite -->
	        <input list="offloc" name="offloc" placeholder="Offsite Location">
<datalist id="offloc">
  <?php
		     $placeget = $db_server->query("SELECT * FROM offsiteloc ORDER BY place ASC");
		      while ($place_option = $placeget->fetch_assoc()) {
	        ?>  <option value= "<?php echo $place_option['place']; ?> "></option> <?php } ?>
</datalist>
			<input type="text" name="offtime" placeholder='Return time' id="offtime" maxlength="20">
	        <input class="button" type="submit" name="offsite" value="Offsite">
	    </div>
	    
	    <div>
			<!-- top interface fieldtrip -->
	    
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
	       <input class="button" type="submit" name="fieldtrip" value="Field Trip"> 
	    </div>
		<div>
			<a href="statusview.php">Status View</a>
		</div>
        <!-- Link To Admin Page -->
        <?php
                       if (isset($_COOKIE['login'])) {
                           
    if ($_COOKIE['login'] == "admin") {
    echo '<div><a href="/a">Admin</a></div>';
        
        }
    } 
        ?>
		<div>
			<a href="viewreports.php">View Reports</a>
		</div>
        <div>
			<a href="secondary_login.php?logout=1">Logout</a>
		</div>
		<?php
			for ($j = 0; $j < count($groupsResult); $j++) {
			echo "<div>";
			echo "<input type='submit' name='" . $groupsResult[$j]["name"] . "' value='" . $groupsResult[$j]["name"] . "'>";
			echo "</div> ";
		}
	?>
		</form>
	</div>
	<!-- student information table rendering -->
	<div id="main_table">
	<table class='data_table' id='big_table'>
	    <tr>
	        <th class='select_col'></th>
			<!-- clickable headers for the table, allows them to be sorted -->
	        <th class='student_col'><a href="index.php?<?php echo $getvar_sort_student; ?>">Student</a></th>
			<th></th>
	        <th class='status_col' id='status_header'><a href="index.php?<?php echo $getvar_sort_status; ?>">Status</a></th>
			
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
	if (empty($_POST['admin_view'])) {
		foreach ($student_data_array as $mini_array) {
			foreach ($mini_array as $latestdata) {
				//sets up relevant time and date data to automatically start a new day the first time the page is loaded
				$day_data = new DateTime($latestdata['timestamp']);
				$yesterday = new DateTime('yesterday 23:59:59');
				$today = new DateTime();
				$todaydate = $today->format('Y-m-d');
			if ($day_data < $yesterday) {
				$student = $latestdata['studentid'];
				$future_event_query = $db_server->query("SELECT * FROM `preplannedevents` WHERE `eventdate` = '".$todaydate."' and `studentid` = '".$student."'") or die (mysqli_error($db_server));
				$future_events = mysqli_fetch_assoc($future_event_query);
				if($future_events!=False){ 
				changestatus($student, $future_events['statusid'],$future_events['info'],$future_events['returntime']);
				} else {
				//if the last entry for a student was yesterday and there are no preplanned events, this makes an entry for 'not checked in'
				changestatus($latestdata['studentid'], '8', '', '');
				}
				}
				?>
				<tr>
					<td class='select_col'>
						<!-- checkbox that gives student data to the form at the top -->
						<input type='checkbox' id='<?php echo $latestdata['studentid']; ?>' value='<?php echo $latestdata['studentid']; ?>' form='main' class='c_box'>
	
					</td>
				<?php 
						for ($k = 0; $k < count($groupsResult); $k++) {
			if (!empty($_POST[$groupsResult[$k]["name"]])) {
				$ids = explode(",", $groupsResult[$k]['studentids']);
				for ($l = 0; $l < count($ids); $l++) {
					//echo $ids[$l];
					echo "<script>document.getElementById(" . $ids[$l] . ").checked = true;</script>";
				}
			}	
		}	
				//variable equal to a students last name initial
				$lastinitial = substr($latestdata['lastname'], 0, 1); ?>
	            <!-- displays current rows student name, that students status and any comment associated with that status -->
					<td class='student_col'>
						<a href="user.php?id=<?php echo $latestdata['studentid']; ?>&name=<?php echo $latestdata['firstname'];?>"><?php print $latestdata['firstname'] . " " . $lastinitial; ?></a>
					</td>
					<td class="student_col_buttons">
						                    
                        <!-- IF CHECK OUT IS NEAR -->
				<?php
                // Checking if checkout times are within range
                if ($date1 > $date2 && $date1 < $date3) {
                  //checking if before checkout time  
                if ($pretty_end_time->format('hi a') > date('hi a')) {
                    if ($latestdata['statusname'] != 'Checked Out')
                    {
                        ?>
                        
                        <form action='<?php echo basename($_SERVER['PHP_SELF']); ?>' method='post'>
							<input type='submit' value='Check&nbsp;Out' class='p_button' name='co_button'>
							<input type='hidden' name='co_bstudent' value='<?php echo $latestdata['studentid']; ?>'>
						</form>
                        
                     <?php   } } }
						// if the student is not present or hasn't updated since midnight, show a present button 
						if (($latestdata['statusname'] != 'Present' && $latestdata['statusname'] != 'Absent' && $latestdata['statusname'] != 'Checked Out') || ($day_data < $yesterday)) {
						?>
						<form action='<?php echo basename($_SERVER['PHP_SELF']); ?>' method='post'>
							<input type='submit' value='P' class='p_button tablebutton' name='present_button'>
							<input type='hidden' name='present_bstudent' value='<?php echo $latestdata['studentid']; ?>'>
						</form>
						<?php 
						}
						// if the student is not checked in, display an absent button
						if ($latestdata['statusname'] == 'Not Checked In') {
						?>
                            <form action='<?php echo basename($_SERVER['PHP_SELF']); ?>' method='post'>
							<input class="tablebutton" type='submit' value='A' name='Absent' class='absent_button' >
							<input type='hidden' name='absent_student' value='<?php echo $latestdata['studentid']; ?>'>
                                </form>
						<?php } 

						// if the student is not checked in or is already late, display a late button
						if ($latestdata['statusname'] == 'Not Checked In' || $latestdata['statusname'] == 'Late') {
						?>
						<!-- Late button with time input next to it -->
                            <form action='<?php echo basename($_SERVER['PHP_SELF']); ?>' method='post'>
							<input class="tablebutton" type='submit' value='Late' name='Late' class='l_button'>
							<input type='input' name='late_time' placeholder='Expected' class='late_time'>
							<input type='hidden' name='late_student' value='<?php echo $latestdata['studentid']; ?>'>
                                </form>
						</form>
						<?php } ?>

					</td>
					<td class='status_col'><?php 
						$returntimeobject = new DateTime($latestdata['returntime']);
						echo $latestdata['statusname'] . " "; 
						if ($latestdata['statusname'] == "Offsite") {
							echo "at " . $latestdata['info'] . " returning at " . $returntimeobject->format('g:i');
						}
						if ($latestdata['statusname'] == "Field Trip") {
							echo "with " . $latestdata['info'] . " returning at " . $returntimeobject->format('g:i');
						}
						if ($latestdata['statusname'] == "Late") {
							echo $latestdata['info'] . " arriving at " . $returntimeobject->format('g:i');
						}
						if ($latestdata['statusname'] == "Independent Study") {
							echo $latestdata['info'] . " returning at " . $returntimeobject->format('g:i');
						}
						?>
						</td>
				</tr>
	<?php		
			} 
		}
	}
	?>
	</table>
	</table>
	</div>
	
	</body>
	</html>