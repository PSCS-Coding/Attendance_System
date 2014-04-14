<!DOCTYPE html>
<?php session_start(); ?>
<html>
<head>
	<title>PSCS Attendance student interface</title>
	<link rel="stylesheet" type="text/css" href="attendance.css">
		
	<link rel="stylesheet" type="text/css" href="css/jquery.timepicker.css">    
    <script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js" ></script>
    <script src="js/jquery.timepicker.min.js" type="text/javascript"></script>
    <script type="text/javascript">
		$(document).ready(function(){
			$('#offtime').timepicker({ 'scrollDefaultNow': true, 'minTime': '9:00am', 'maxTime': '3:30pm', 'timeFormat': 'g:i', 'step': 5 });
			$('#fttime').timepicker({ 'scrollDefaultNow': true, 'minTime': '9:00am', 'maxTime': '3:30pm', 'timeFormat': 'g:i', 'step': 15 });
			$('#late_time').timepicker({ 'scrollDefaultNow': true, 'minTime': '9:00am', 'maxTime': '3:30pm', 'timeFormat': 'g:i', 'step': 5 });
		});
	</script>
</head>
<body>

<a href=attendance.php>Back to main page</a>
<div>
<div>
	
<?php
    require_once("connection.php");
   	require_once("function.php");
	
	
	if (!empty($_GET['name'])){
		$name=$_GET['name'];
		
		if ($name != $_COOKIE['name']){
		setcookie('name', $name);
		}
		
	} elseif (!empty($_COOKIE['name'])){
		$name=$_COOKIE['name'];
	}
	
	if (!empty($_GET['id'])){
		$id=$_GET['id'];
		
		if ($id != $_COOKIE['id']){
		setcookie('id', $id);
		}
		
	} elseif (!empty($_COOKIE['id'])){
		$id=$_COOKIE['id'];
	}
	
	$facget = $db_server->query("SELECT * FROM facilitators ORDER BY facilitatorname ASC");
    $facilitators = array();
    while ($fac_row = $facget->fetch_row()) {
		array_push ($facilitators, $fac_row[0]);
    }

	
if (!empty($_POST)){

    //present    
	if (!empty($_POST['present'])){
			changestatus($id, '1', '', '', '');
	}

    //offsite
	if (!empty($_POST['offsite'])) {
		if (!empty($_POST['offloc'])){
        		$info = $_POST['offloc'];
			if (validTime($_POST['offtime'])){
				changestatus($id, '2', $info, $_POST['offtime']);
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
				changestatus($id, '3', $info, $_POST['fttime']);
			} else { 
				echo "that's not a valid time";
			}
		} else {
			echo "you need to chose a facilitator before signing out to field trip";
		}
	}

//Sign out querying -- "4" refers to "Checked Out" in statusdata table
	if (!empty($_POST['signout'])) {
			changestatus($id, '4', '', '');
	}
}

$info = $db_server->query("SELECT statusid FROM events WHERE studentid = '".$id."'ORDER BY timestamp DESC LIMIT 1");
	$rowdata=mysqli_fetch_row($info);
	$currentstatusid=$rowdata[0];
	$convert = $db_server->query("SELECT statusname FROM statusdata WHERE statusid = '".$currentstatusid."'");
	$currentstatus=mysqli_fetch_row($convert);
	
	if (!empty($name) || !empty($id)){
		if ($currentstatus[0] == "Field Trip"){
			echo $name . " is currently on a " . $currentstatus[0];
		} else {
			echo $name . " is currently " . $currentstatus[0];
		}
	} else {
		echo "Please go back to the main page and make a student selection";
	}
?>

<!-- top form for change status -->

<div id="top_header">
<form method='post' action='<?php echo basename($_SERVER['PHP_SELF']); ?>' id='main'>
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
</body>
</html>