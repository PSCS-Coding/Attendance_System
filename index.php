<?php require_once('login.php'); ?>
<!DOCTYPE html>
	<html>
	<head>
	    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no" />
	<meta name="HandheldFriendly" content="true" />
        <?php require_once('header.php'); ?>
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
	<!-- setup -->
	<?php
		$null_value = null;
		
		$status_result = $db_server->query("SELECT DISTINCT statusname FROM statusdata");
			$result_array = array();
			while ($blah = $status_result->fetch_assoc()) {
				$status_array[] = $blah['statusname'];
			}


        //time for fetching groups!!
		$groupsQuery = $db_server->query("SELECT * FROM groups ORDER BY name DESC");
			$groupsResult = array();
			while ($group = $groupsQuery->fetch_array()) {
				array_push($groupsResult, $group);
				//$groupsCount += 1;
			}
            $group = ltrim($group, ",");
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
		
	    // get dates
                $globals_query = "SELECT * FROM globals";
                $globals_result = $db_server->query($globals_query);
                $globals_data = $globals_result->fetch_array();
				$getendDate = new DateTime($globals_data['enddate']);
				$getstartDate = new DateTime($globals_data['startdate']);
				date_add($getstartDate, date_interval_create_from_date_string('1 day'));
				date_add($getendDate, date_interval_create_from_date_string('-1 day'));
				$startDate = $getstartDate->format('Y-m-d H:i:s');
				$endDate = $getendDate->format('Y-m-d H:i:s');
				
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
if (!empty($_POST['customtext'])) {
				$info = $_POST['customtext'];
if (validTime($_POST['offtime'])){
					foreach ($name as $student){
					changestatus($student, '2', $info, $_POST['offtime']);
					}
				} else {
					echo "<div class='error'>Please enter a valid return time.</div>";
				}
				//echo "<p style='font-size:30px;'>" . $info . "</p>";
				} else {
			if (!empty($_POST['offlocDropdown']) && $_POST['offlocDropdown'] != ''){
	        		$info = $_POST['offlocDropdown'];
				if (validTime($_POST['offtime'])){
					foreach ($name as $student){
					changestatus($student, '2', $info, convertHours('offtime'));
					}
				} else {
					echo "<div class='error'>Please enter a valid return time.</div>";
				}
			}
		}
	}
	    //fieldtrip
		if (!empty($_POST['fieldtrip'])) {
	
			if (!empty($_POST['facilitator'])){
	        		$info = $_POST['facilitator'];
				if (validTime($_POST['fttime'])){
					foreach ($name as $student){
					changestatus($student, '3', $info, convertHours('fttime'));
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
			$status = convertHours('late_time');
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
         <?php
		$studentcount = 0;
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
					//pushed each individual student's data into an array				
					array_push($student_data_array, $result_array);
					$studentcount ++;
				}
			/*echo "<pre>";
			print_r($student_data_array);
			echo "</pre>";*/
			$fieldTripArray = array();
			$uniqueFacil = array();
			for ($i = 0; $i < count($student_data_array); $i++) {
				if ($student_data_array[$i]['statusname'] == "Field Trip") {
					if (!in_array($student_data_array[$i]['info'], $uniqueFacil)) {
						array_push($uniqueFacil, $student_data_array[$i]['info']);
					}
					array_push($fieldTripArray, $student_data_array[$i]['studentid'] . "---" . $student_data_array[$i]['info']);
				}
			}
			//echo "<pre>";
			//print_r($fieldTripArray); //works
			//echo "</pre>";
			//echo "<br /><pre>";
			//print_r($uniqueFacil); //works
			//echo "</pre>";
	?>
                <form method='post' action='<?php echo basename($_SERVER['PHP_SELF']); ?>' id='lmain' >
        		<?php
            if (!empty($groupsResult)) {
            echo "<div class='groupsGUI'>";
            echo "<h1 class='groupHeader tab'>Groups</h1>";
			for ($j = 0; $j < count($groupsResult); $j++) {
			echo "<input class='groupButton' type='submit' name='" . $groupsResult[$j]["name"] . "' value='" . str_replace("_"," ", $groupsResult[$j]["name"]) . "'><br />";
		}	
			if (!empty($uniqueFacil)) {
				echo "<p class='groupHeader'>Field Trip Groups</p>";
				foreach ($uniqueFacil as $sub) {
					echo "<input class='groupButton' type='submit' name='" . $sub . "' value = '" . $sub . "'><br />";
				}
			}
echo "</div> ";
                }
	?>
            </form>
	<!-- top form for change status -->
	<div id="top_header">
	<form method='post' action='<?php echo basename($_SERVER['PHP_SELF']); ?>' id='main' >
		
		<div>
			<!-- top interface present button -->
	        <input class="button" id="present_button" type="submit" value="Present" name="present">
	    </div>
	   
	   	<div>
			<!-- top interface sign out button -->
			<input class="button" type="submit" value="Check Out" name="checkout" onclick="return confirm('Confirmation: \nAre you sure you want to check out?');">
		</div>
 
	    <div>
			<!-- top interface offsite -->
	        
<span id="cdropdown"><select id="offlocDropdown" name="offlocDropdown" class="offlocDropdown">
<option value=''>Offsite Location</option>
  <?php
		     $placeget = $db_server->query("SELECT * FROM offsiteloc ORDER BY place ASC");
		      while ($place_option = $placeget->fetch_assoc()) {
	        ?>  <option value= "<?php echo $place_option['place']; ?> "><?php echo $place_option['place']; ?></option> <?php } ?>
<option name="Custom" value="Custom" style="background-color:lightgrey;">Custom</option>
</select></span>
<span id="cdiv">

</span>
			<input type="text" name="offtime" placeholder="Return time" id="offtime">
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
					<option value= '<?php echo $facilitator_option; ?>'> <?php echo $facilitator_option; ?></option>
	        <?php
				}
	        ?>
	        </select>
	        <input type="text" name="fttime" placeholder="Return time" id="fttime">
	       <input class="button" type="submit" name="fieldtrip" value="Field Trip"> 
	    </div>
		<div class="statusview_button">
			<a href="statusview.php">Status View</a>
		</div>
        <!-- Link To Admin Page -->
        <?php        
    if (isset($_COOKIE['login'])) {
    if ($_COOKIE['login'] == $SecureAdminPW || $_COOKIE['login'] == $crypt) {
    echo '<div class="admin_button"><a href="a/p/index.php">Admin</a></div>';
        }
    } 
        ?>
		<div class="viewreports_button">
			<a href="viewreports.php">View Reports</a>
		</div>
        <div>
			<a href="secondary_login.php?logout=1">Logout</a>
		</div>
		</form>
	</div>
	
	<script>
		$(document).ready(function() {
			$(window).resize(function() {
				if($(window).width() < 1020) {
					$('.viewreports_button a').text('Reports');
					$('.statusview_button a').text('Status');
					$('.admin_button a').text('A');
					$("#present_button").prop('value', 'P');
					$("#present_button").css('width', '25px');
				}
				else {
					$('.viewreports_button a').text('View Reports');
					$('.statusview_button a').text('Status View');
					$('.admin_button a').text('Admin');
					$("#present_button").prop('value', 'Present');
					$("#present_button").css('width', '60px');
				}
				
				if ($(window).width() < 830) {
				    $(".l_button").prop('value', 'L');
				}
				else {
				    $('.l_button').prop('value', 'Late');
				}
				
			});
			
			if($(window).width() < 1020) {
				$('.viewreports_button a').text('Reports');
				$('.statusview_button a').text('Status');
				$('.admin_button a').text('A');
				$("#present_button").prop('value', 'P');
				$("#present_button").css('width', '25px');
			}
			else {
				$('.viewreports_button a').text('View Reports');
				$('.statusview_button a').text('Status View');
				$('.admin_button a').text('Admin');
				$("#present_button").prop('value', 'Present');
				$("#present_button").css('width', '60px');
			}
			
			if ($(window).width() < 830) {
			$(".l_button").prop('value', 'L');
			
		    }
		    else {
			$('.l_button').prop('value', 'Late');
		    }

		});
	</script>
        
        
	
        
	<!-- student information table rendering -->
	<div id="main_table">
	<table class='data_table' id='big_table'>
	    <tr>
	        <th class='select_col'><input type="checkbox" id="checkAll"/></th>
			<!-- clickable headers for the table, allows them to be sorted -->
	        <th class='student_col'><a href="index.php?<?php echo $getvar_sort_student; ?>">Student</a></th>
			<th class='blank_col'></th>
	        <th class='status_col' id='status_header'><a href="index.php?<?php echo $getvar_sort_status; ?>">Status</a></th>
			
	    </tr>
	   <?php
	//echo "<br /><br />";
	//print_r($currFieldTrips);

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
        // SETTING VERIBLES FOR CONTEXTUAL COLORING //
                
            // Get Current Time
                $cTime = new DateTime();
            // Format current time
                $currTime = $cTime->format('Y-m-d H:i:s');
            // Get ENTERED return time
                $mReturn= new DateTime($latestdata['returntime']);
            // Format ENTERED return time
                $myReturn = $mReturn->format('Y-m-d H:i:s');
            // Get globals.starttime
                $globals_query = "SELECT starttime FROM globals";
            // Setting query info as varible
                $globals_result = $db_server->query($globals_query);
            // Put query data into an array
                $globals_data = $globals_result->fetch_array();
            // Set globals.starttime as varible
                $ttStart = new DateTime($globals_data['starttime']);
            // Format globals.starttime
                $startTime = $ttStart->format('Y-m-d H:i:s');
            // These is for making the IF statment shorter
                $statName = $latestdata['statusname'];
                $GRtime = '$currTime > $myReturn';
        // Start IF statement for contextual coloring        
        if ($currTime > $startTime && $statName == 'Not Checked In' || $GRtime && $statName == 'Offsite' || $GRtime && $statName == 'Late') {
            
                 ?>  
        
                        <tr class="Status_Red">
                    
                        <?php  } else { ?>
                            
				        <tr>
                    
                        <?php } ?>
                
					<td class='select_col'>
						<!-- checkbox that gives student data to the form at the top -->
						<input type='checkbox' name='person[]' id='<?php echo $latestdata['studentid']; ?>' value='<?php echo $latestdata['studentid']; ?>' form='main' class='c_box'>
	
					</td>
				<?php 
   
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
                    if ($latestdata['statusname'] != 'Checked Out' && $latestdata['statusname'] != 'Absent' && $latestdata['statusname'] != 'Independent Study')
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
							<input class="tablebutton l_button" id="latebutton" type='submit' value='Late' name='Late'>
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
							echo "at " . $latestdata['info'] . " returning at " . $returntimeobject->format('g:i a');
						}
						if ($latestdata['statusname'] == "Field Trip") {
							echo "with " . $latestdata['info'] . " returning at " . $returntimeobject->format('g:i a');
						}
						if ($latestdata['statusname'] == "Late") {
							echo $latestdata['info'] . " arriving at " . $returntimeobject->format('g:i a');
						}
						if ($latestdata['statusname'] == "Independent Study") {
							echo $latestdata['info'] . " returning at " . $returntimeobject->format('g:i a');
						}
						?>
						</td>
				</tr>
	<?php		
			} 
		}
	}
	                
            	// SELECTION FOR GROUPS
		for ($k = 0; $k < count($groupsResult); $k++) {
			if (!empty($_POST[$groupsResult[$k]["name"]])) {
				$ids = explode(",", $groupsResult[$k]['studentid']);
				for ($l = 0; $l < count($ids); $l++) {
					//echo $ids[$l];
					$getRecentEvent = mysqli_fetch_assoc(mysqli_query($db_server, "SELECT statusid FROM events WHERE studentid = " . $ids[$l] . " ORDER BY timestamp DESC LIMIT 1"));
					$recentEvent = $getRecentEvent['statusid'];
 					if ($recentEvent == 1) {
					echo "<script>document.getElementById(" . $ids[$l] . ").checked = true;</script>";
					}
				}
			}	
		}
		// SELECTION FOR FIELD TRIP GROUPS
		//echo "<pre>";
		//print_r($_POST);
		//echo "</pre>";
		$tempExploded = array();
		foreach ($uniqueFacil as $sub) {
			if (!empty($_POST[$sub])) {
				foreach ($fieldTripArray as $child) {
					$tempExploded = explode("---", $child);
					if ($tempExploded[1] == $sub) {
						echo "<script>document.getElementById(" . $tempExploded[0] . ").checked = true;</script>";
					}
				}
			}
		}
             
	?>
	</table>
	</table>
	</div>
	<!-- CODE FOR GROUPS BOX -->
    <script>
        $(document).ready(function() {
			$('.groupsGUI').mouseenter(function() {
				$('.groupsGUI').stop().animate({ right: "0px"} , "fast");
                $('.groupHeader').addClass('active');
                $('.groupButton').addClass('active');
			});
			$('.groupsGUI').mouseleave(function() {
				$('.groupsGUI').stop().animate({ right: "-140px"} , "fast");
                $('.groupHeader').removeClass('active');
                $('.groupButton').removeClass('active');
			});
		});
</script>
	<!-- CODE FOR MULTI-CHECKBOX SELECT-->
	
	<script>
	    $.fn.shiftSelectable = function() {
		var lastChecked,
		    $boxes = this;
	     
		$boxes.click(function(evt) {
		    if(!lastChecked) {
			lastChecked = this;
			return;
		    }
	     
		    if(evt.shiftKey) {
			var start = $boxes.index(this),
			    end = $boxes.index(lastChecked);
			$boxes.slice(Math.min(start, end), Math.max(start, end) + 1)
			    .prop('checked', lastChecked.checked)
			    .trigger('change');
		    }
	     
		    lastChecked = this;
		});
	    };
	</script>
	
	<script>
	    $(document).ready(function() {
		$('.c_box').shiftSelectable();
          
	    });
	</script>

    <script>
        $(document).ready(function(){
            $("#checkAll").change(function () {
		if (document.getElementById("checkAll").checked == true) {
		var ok = confirm("Select All Students?");
			if (ok == true) {
                		$("input:checkbox").prop('checked', $(this).prop("checked"));
			} else {
				document.getElementById("checkAll").checked = false;
			}
		} else if (document.getElementById("checkAll").checked == false) {
		var ok = confirm("Deselect All Students?");
			if (ok == true) {
                		$("input:checkbox").prop('checked', $(this).prop("checked"));
			} else {
				document.getElementById("checkAll").checked = true;
			}
		}
            });
        });
	/*$("#offlocDropdown").change(function () {
alert($(this).val());
});
if you click on an option it gives an alert with that option*/
$("#offlocDropdown").change(function () {
if ($(this).val() == "Custom") {
//alert("hola");
//document.write("<style>#customtext { opacity:9.0; }</style>");
document.getElementById("cdropdown").innerHTML = '';
document.getElementById("cdiv").innerHTML = '<input type="text" name="customtext" id="customtext" placeholder="Custom Location" list="offlocDropdown" maxlength="25" class="offloc" style="width:100px;opacity:9.0;">';
}
});
    </script>



<script type="text/javascript">

  // Original JavaScript code by Chirp Internet: www.chirp.com.au
  // Please acknowledge use of this code by including this header.

  function getCookie(name)
  {
    var re = new RegExp(name + "=([^;]+)");
    var value = re.exec(document.cookie);
    return (value != null) ? unescape(value[1]) : null;
  }
    
    function del_cookie(name) {
        document.cookie = name +
        '=; expires=Thu, 01-Jan-70 00:00:01 GMT;';
    }

</script>


	
	</body>
	</html>
