<!DOCTYPE html>
<?php session_start(); ?>
<html>
<head>
	<title>PSCS Attendance student interface</title>
	<link rel="stylesheet" type="text/css" href="InUse.css">
	<link rel='stylesheet' href="css/pikaday.css" />
	<link rel="stylesheet" type="text/css" href="css/jquery.timepicker.css">    
	<script src="//ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js" ></script>
    <script src="js/jquery.timepicker.min.js" type="text/javascript"></script>
    <script type="text/javascript">
		$(document).ready(function(){
			$('#offtime').timepicker({ 'scrollDefaultNow': true, 'minTime': '9:00am', 'maxTime': '3:30pm', 'timeFormat': 'g:i', 'step': 5 });
			$('#fttime').timepicker({ 'scrollDefaultNow': true, 'minTime': '9:00am', 'maxTime': '3:30pm', 'timeFormat': 'g:i', 'step': 15 });
			$('#latetime').timepicker({ 'scrollDefaultNow': true, 'minTime': '9:00am', 'maxTime': '3:30pm', 'timeFormat': 'g:i', 'step': 5 });
			$('#istime').timepicker({ 'scrollDefaultNow': true, 'minTime': '9:00am', 'maxTime': '3:30pm', 'timeFormat': 'g:i', 'step': 5 });
			$('#starttime').timepicker({ 'scrollDefaultNow': true, 'minTime': '9:00am', 'maxTime': '3:30pm', 'timeFormat': 'g:i', 'step': 5 });
		});
	</script>
</head>
<body>

<?php
    require_once("../connection.php");
   	require_once("function.php");
	
	
	if (!empty($_GET['name'])){
		$name=$_GET['name'];
	
	if (isset($name)){	
		if ($name != $_COOKIE['name']){
		setcookie('name', $name);
		}
	}	
	} elseif (!empty($_COOKIE['name'])){
		$name=$_COOKIE['name'];
	}
	
	if (empty($id)){
		
		if (!empty($_GET['id'])){
			$id=$_GET['id'];
			setcookie('id', $_GET['id']);
			
		} elseif (empty($_GET['id']) and !empty($_COOKIE['id'])) {
			$id=$_COOKIE['id'];
			
		} else{
			echo "go back to the main page and select a student";
		}
	}
	
	?>
	
	<a style="text-decoration: none; color: black;" href=attendance.php>Back to main page </a>  <a style="text-decoration: none; color: black;" href=viewreports.php>  View reports for this user</a>
	<br>
	<?php
	$facget = $db_server->query("SELECT * FROM facilitators ORDER BY facilitatorname ASC");
    $facilitators = array();
    while ($fac_row = $facget->fetch_row()) {
		array_push ($facilitators, $fac_row[0]);
    }

if(empty($_POST['otherdate'])){
	
if (!empty($_POST)){

    //present    
	if (!empty($_POST['present'])){
			changestatus($id, '1', '', '', '');
	}
	
	//absent    
	if (!empty($_POST['absent'])){
			changestatus($id, '7', '', '', '');
			if (!empty($_POST['favorite'])){
				favorite($id, '7', '', '');
			}
	}

    //offsite
	if (!empty($_POST['offsite'])) {
		if (!empty($_POST['offloc'])){
        		$info = $_POST['offloc'];
			if (validTime($_POST['offtime'])){
				changestatus($id, '2', $info, $_POST['offtime']);
				if (!empty($_POST['favorite'])){
				favorite($id, '2', $info, $_POST['offtime']);
			}
			} else {
			echo "that's not a valid time";
			}
		} else {
			echo "you need to fill out the location box before signing out to offsite";
		}
	}
	
    //late
	if (!empty($_POST['late'])) {
		if (!empty($_POST['latetime'])){
				$info="";
			if (validTime($_POST['latetime'])){
				changestatus($id, '5', $info, $_POST['latetime']);
				if (!empty($_POST['favorite'])){
				favorite($id, '5', $info, $_POST['latetime']);
			}
			} else {
			echo "that's not a valid time";
			}
		} else {
			echo "you mush choose a return time before signing out to late";
		}
	}
	
	 //indi study 
	if (!empty($_POST['isstudy'])) {
		if (!empty($_POST['istime'])){
				$info="";
			if (validTime($_POST['istime'])){
				changestatus($id, '6', $info, $_POST['istime']);
				if (!empty($_POST['favorite'])){
				favorite($id, '6', $info, $_POST['istime']);
			}
			} else {
			echo "that's not a valid time";
			}
		} else {
			echo "you must choose a return time before signing out to independent study";
		}
	}
	
    //fieldtrip
	if (!empty($_POST['fieldtrip'])) {

		if (!empty($_POST['facilitator'])){
        		$info = $_POST['facilitator'];
			if (validTime($_POST['fttime'])){
				changestatus($id, '3', $info, $_POST['fttime']);
				if (!empty($_POST['favorite'])){
				favorite($id, '3', $info, $_POST['fttime']);
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
			changestatus($id, '4', '', '');
			if (!empty($_POST['favorite'])){
				favorite($id, '4', '', '');
			}
	}
}
} else { // sign out for a later date ==================================================


	$endchoosedate=strtotime($_POST['chooseday']);
// plan syntax is plan(id, statusid, chosen date, returntime, info);	
	   //present    
	if (!empty($_POST['present'])){
			plan($id, '1', $endchoosedate, '', '');
	}
	//absent    
	if (!empty($_POST['absent'])){
			plan($id, '7', $endchoosedate, '', '');
			}
    //offsite
	if (!empty($_POST['offsite'])) {
		if (!empty($_POST['offloc'])){
        		$info = $_POST['offloc'];
			if (validTime($_POST['offtime'])){
				plan($id, '2', $endchoosedate, $_POST['offtime'], $info);
			} else {
			echo "that's not a valid time";
			}
		} else {
			echo "you need to fill out the location box before signing out to offsite";
		}
	}
	
    //late
	if (!empty($_POST['late'])) {
		if (!empty($_POST['latetime'])){
				$info="";
			if (validTime($_POST['latetime'])){
				plan($id, '5', $endchoosedate, $_POST['latetime'], $info);
			} else {
			echo "that's not a valid time";
			}
		} else {
			echo "you mush choose a return time before signing out to late";
		}
	}
	
	 //indi study 
	if (!empty($_POST['isstudy'])) {
		if (!empty($_POST['istime'])){
				$info="";
			if (validTime($_POST['istime'])){
				plan($id, '6', $endchoosedate, $_POST['istime'], $info);
			} else {
			echo "that's not a valid time";
			}
		} else {
			echo "you must choose a return time before signing out to independent study";
		}
	}
	
    //fieldtrip
	if (!empty($_POST['fieldtrip'])) {

		if (!empty($_POST['facilitator'])){
        		$info = $_POST['facilitator'];
			if (validTime($_POST['fttime'])){
				plan($id, '3', $endchoosedate, $_POST['fttime'], $info);
			} else { 
				echo "that's not a valid time";
			}
		} else {
			echo "you need to chose a facilitator before signing out to field trip";
		}
	}

//Sign out querying -- "4" refers to "Checked Out" in statusdata table
	if (!empty($_POST['signout'])) {
			plan($id, '4', $endchoosedate, '', '');
	}
}

	
	$getfav = $db_server->query("SELECT * FROM cookiedata WHERE studentid = '".$id."'");
	$rowcnt =  $getfav->num_rows;
	
	while ($rowcnt>0){
	
	$favorite=mysqli_fetch_row($getfav);
	$postfav=$favorite[4];
	$delstring="del:" . $postfav;
	$pieces = explode(":", $delstring);
	
	$datetimeconvert=new DateTime($favorite[3]);
	
	if (!empty($_POST[$postfav])){
		changestatus($favorite[0], $favorite[1], $favorite[2], $datetimeconvert);
	}
	if (!empty($_POST[$delstring])){
	$stmt = $db_server->prepare("DELETE FROM cookiedata WHERE favid = ?");
	$stmt->bind_param('i', $pieces[1]);
	$stmt->execute(); 		
	$stmt->close();
	}
		$rowcnt=$rowcnt-1;
}

	$info = $db_server->query("SELECT statusid FROM events WHERE studentid = '".$id."'ORDER BY timestamp DESC LIMIT 1");
	$rowdata=mysqli_fetch_row($info);
	$currentstatusid=$rowdata[0];
	$convert = $db_server->query("SELECT statusname FROM statusdata WHERE statusid = '".$currentstatusid."'");
	$currentstatus=mysqli_fetch_row($convert);
	
	$getreturn = $db_server->query("SELECT returntime FROM events WHERE studentid = '".$id."'ORDER BY timestamp DESC LIMIT 1");
	$returntime=mysqli_fetch_row($getreturn);
	$finalreturn=$returntime[0];
	$returntimeobject = new DateTime($finalreturn);
	
	$getwith = $db_server->query("SELECT info FROM events WHERE studentid = '".$id."'ORDER BY timestamp DESC LIMIT 1");
	$withrow=mysqli_fetch_row($getwith);
	$finalwith=$withrow[0];
	
	$getdate = $db_server->query("SELECT timestamp FROM events WHERE studentid = '".$id."'ORDER BY timestamp DESC LIMIT 1");
	$datedata=mysqli_fetch_row($getdate);
	$currentdate=$datedata[0];
	
	
		$day_data = new DateTime($currentdate);
				//the day it was yesterday
				$yesterday = new DateTime('yesterday 23:59:59');
				if ($day_data < $yesterday) {
						changestatus($id, '8', '', '');
				}
				
	if (!empty($name) || !empty($id)){
		?> <br> <?php
		if ($currentstatus[0] == "Field Trip"){
			echo $name . " is currently on a " . $currentstatus[0] . " with " . $finalwith . " and will be back at " . $returntimeobject->format('h:i');
			
		} elseif ($currentstatus[0] == "Offsite") {
			echo $name . " is " . $currentstatus[0] . " at " . $finalwith . " and will be at school at " . $returntimeobject->format('h:i');
		
		} elseif ($currentstatus[0] == "Late"){
			echo $name . " is " . $currentstatus[0] . " and will be at school at " . $returntimeobject->format('h:i');
		
		} elseif ($currentstatus[0] == "Not Checked In") {
			echo $name . " has not checked in today"; 
		
		} elseif ($currentstatus[0] == "Independent Study") {
			echo $name . " is currently on an " . $currentstatus[0] . " and will be back at " . $returntimeobject->format('h:i');
		
		} else {
			echo $name . " is currently " . $currentstatus[0];
		}
		
	} else {
		echo "Please go back to the main page and make a student selection";
	}
?>
<br>
<!-- top form for change status -->

<div id="top_header">
<form method='post' action='<?php echo basename($_SERVER['PHP_SELF']); ?>' id='main'>
	<?php if ($currentstatus[0] != "Present"){
		?>
		<br>
    <div>
        <input type="submit" value="Present" name="present">
    </div>
    <?php } ?> 
	<br>
    <div>
        <input type="submit" name="offsite" value="Offsite">
        <input type="text" name="offloc" placeholder='Location' autocomplete='on'>
		<input type="text" name="offtime" placeholder='Return time' id="offtime">
    </div>
	<br>
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
<!-- indi study form -->
		<div>
		<br>
		<input type="submit" value="Independent Study" name="isstudy">
		<input type="text" name="istime" placeholder="Return time" id="istime">
	</div>
	<div>
		
		<br>
		<input type="submit" value="Late" name="late">
		<input type="text" name="latetime" placeholder="Arrival time" id="latetime">
			
	</div>
	<div>
		
		<br>
		<input type="submit" value="Absent" name="absent">

	</div>
	<div>
		<br>
		<input type="submit" value="Sign Out" name="signout">
	</div>
	<br>
		<?php
	$getfav = $db_server->query("SELECT * FROM cookiedata WHERE studentid = '".$id."'");
	$rowcnt =  $getfav->num_rows;
		?>
		<input type="checkbox" name="favorite"> save to favorites
		<input type="checkbox" name="otherdate"> for other date
	<?php if (!$rowcnt == 0){ ?>
	<br>
	<br>		
	<h3>Favorites</h3>	
	<?php 
	
	
	while ($rowcnt>0){
	
	$favorite=mysqli_fetch_row($getfav);
	
	$favconvert = $db_server->query("SELECT statusname FROM statusdata WHERE statusid = '".$favorite[1]."'");
	$outfav=mysqli_fetch_row($favconvert);

	
		if ($outfav[0] == "Field Trip"){
			$fullstring =  "go on a " . $outfav[0] . " with " . $favorite[2] . "and be back at " . $returntimeobject->format('h:i');
			
		} elseif ($outfav[0] == "Offsite") {
			$fullstring =  "go " . $outfav[0] . " to " . $favorite[2] . " and be at school at " . $returntimeobject->format('h:i');
		
		} elseif ($outfav[0] == "Late"){
			$fullstring = "be " . $outfav[0] . " and be at school at " . $returntimeobject->format('h:i');
		
		} elseif ($outfav[0] == "Independent Study") {
			$fullstring = "go on an " . $outfav[0] . " and be back at " . $returntimeobject->format('h:i');
		
		} else {
			echo "be " . $outfav[0];
		}
	$postfav=$favorite[4];
	
	$delstring="del:" . $postfav;
	
	?> 	<input type="submit" value="<?php echo $fullstring ?>"name="<?php echo $postfav ?>">
		<input type="submit" value="<?php echo "X" ?>" name="<?php echo $delstring ?>">
	<?php $rowcnt=$rowcnt-1; ?> 	
	<br>
	<br>
	<?php	
	}
	}
	?>
<div>
<div>
<div>
<div>
	
	<h3>choose a date</h3>
	<div>
	<input type="text" name="chooseday" id="chooseday" placeholder="<?php echo date("D M j Y")?>">
	</div>
	</form>	

<script src="js/pikaday.js"></script>
<script>
    var picker = new Pikaday({ field: document.getElementById('chooseday') });
</script>
</body>
</html>