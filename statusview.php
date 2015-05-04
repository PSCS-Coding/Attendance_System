	<!DOCTYPE html>
	<html>
	<head>
		<title>PSCS Attendance: Status View</title>
		<?php require_once('header.php') ?>
	    <script type="text/javascript">
		 
			$(document).ready(function(){
				$('#offtime').timepicker({ 'scrollDefaultNow': true, 'minTime': '9:00am', 'maxTime': '3:30pm', 'timeFormat': 'g:i', 'step': 5 });
				$('#fttime').timepicker({ 'scrollDefaultNow': true, 'minTime': '9:00am', 'maxTime': '3:30pm', 'timeFormat': 'g:i', 'step': 15 });
				$('.late_time').timepicker({ 'scrollDefaultNow': true, 'minTime': '9:00am', 'maxTime': '3:30pm', 'timeFormat': 'g:i', 'step': 5 });
			});
		</script>
		<link rel="stylesheet" type="text/css" href="a/css/jquery.datetimepicker.css" />
		<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
		<script src="a/p/js/scrollTo.js"></script>
		<script src="a/p/js/jquery.datetimepicker.js"></script>
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
	<body class="mainpage statusview">
	<!-- setup -->
	<?php
		$null_value = null;
		
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
	
				
	//individual present button querying -- "1" refers to "Present" in statusdata table
	if (!empty($_POST['present_bstudent'])) {
		$name = $_POST['present_bstudent'];
		changestatus($name, '1', '', $null_value);
	}
	
	//late status querying -- "5" refers to "Late" in statusdata table
	if (!empty($_POST['Late'])) {
		$name = $_POST['late_student'];
		$status = $_POST['late_time'];
		changestatus($name, '5', '', $status);
	}
	
	//absent buttons
	if (!empty($_POST['Absent'])) {
		$name = $_POST['absent_student'];
		changestatus($name, '7', '', $null_value);
	}
	
	?>
	<div id="top_header">
		<div>
			<a href="index.php">Return to main attendance view</a>
		</div>
	</div>

	
	<!-- student information table rendering -->
	<?php
		if(!empty($_POST['datetimepicker'])){
			$DateFromPicker = $_POST['datetimepicker'];
		}
		$student_data_array = array();
		if (empty($DateFromPicker)){
			$Date = new DateTime();
			//echo "the date from picker is " . $DateFromPicker;	
		} else {
			$Date = new DateTime($DateFromPicker);
		}
	?>
	<h1 class="statusview_header"><?php echo "Attendance status as of " . $Date->format('l F jS \a\t g:ia'); ?></h1>
	<form method='post' id="datepicker" action='<?php echo basename($_SERVER['PHP_SELF']); ?>'>
		<input type='text' id="datetimepicker" class = 'datetimepicker' name='datetimepicker' placeholder="select a date">
		<input type='submit' name='submit'>
	</form>
	<?php
		$TimeFromPicker = $Date->format('Y-m-d H:i:s');
		$DateFromPicker = $Date->format('Y-m-d');
		
			//loops through current students
				while ($current_student_id = $current_users_result->fetch_assoc()) {
					//fetches most recent data from the events table
					//joins with the tables that key student names/status names to the ids in the events table
					$result = $db_server->query("SELECT firstname,lastname,statusname,studentdata.studentid,info,timestamp,returntime
										 FROM events 
										 JOIN statusdata ON events.statusid = statusdata.statusid
										 RIGHT JOIN studentdata ON events.studentid = studentdata.studentid
										 WHERE studentdata.studentid = $current_student_id[studentid] 
										 AND timestamp BETWEEN '$DateFromPicker' AND '$TimeFromPicker' 
										 ORDER BY timestamp DESC
										 LIMIT 1") 
										 or die(mysqli_error($db_server));
					$result_array = $result->fetch_assoc();
					//pushed each individual students data into an array				
					array_push($student_data_array, $result_array);
				} ?>
                
                <div class="column_wrapper">
				<?php
				//using the above data from the query, this renders the alternate status view
				//creates a table header for each of the possible status'
				
				foreach ($status_array as $status) {
					//calls the sort function to sort the array of students by subkey status
					$sorted_data_array = subval_sort($student_data_array, 'statusname' , $status);
					//only renders the table headers for status' that have students assigned to that status
					if (!empty($sorted_data_array)) { ?> 
					
					<div class='altview_status'> 
						<p><?php echo $status; ?></p>
						<ul class='altview_list'>
						<?php
							foreach ($sorted_data_array as $student) {
								if (!empty($student['returntime'])) {
									$returntime_statusview = new DateTime($student['returntime']);
									$returntime_statusview = $returntime_statusview->format('g:i');
								}
								else {
									$returntime_statusview = '';
								}
								?>
								<li>
									<?php 
										echo $student['firstname'] . " " . substr($student['lastname'], 0, 1) . " ";
										if ($status == "Offsite" || $status == "Late" || $status == "Field Trip") {echo $returntime_statusview;}
									?>
								</li>
						<?php } ?>
						</ul> 
					</div> <?php } } ?>
                </div>
	
<script> 
	$('#datetimepicker').datetimepicker({
            onGenerate:function( ct ){
               jQuery(this).find('.xdsoft_date.xdsoft_weekend')
                  .addClass('xdsoft_disabled');
            },
            minDate:'2014/09/08',
            maxDate:'2015/6/17', // SET THESE TO GLOBALS FOR START DATE AND END DATE
            format:'Y-m-d H:i:s', 
            step: 5,
         }); 
</script>
	</body>
	</html>