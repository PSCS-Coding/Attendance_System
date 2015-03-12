<?php
//load name and id from either get or cookie and sets a cookie for name if name is set

	if (!empty($_GET['name'])){
		$name=$_GET['name'];
		
	if (isset($name)){	
		setcookie('name', $name);
	}	
	} elseif (!empty($_COOKIE['name'])){
		$name=$_COOKIE['name'];
	}
	
	if (empty($id)){
	
		if (!empty($_GET['id'])){
			$id=$_GET['id'];
			setcookie('id', $_GET['id']);
			$_SESSION['idd']=$id;

		} elseif (empty($_GET['id']) and !empty($_COOKIE['id'])) {
			$id=$_COOKIE['id'];
			
		} else{
			echo "<div class='error'>Go back to the main page and select a student.</div>";
		}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>PSCS Attendance student interface</title>
    <?php require_once('header.php'); ?>
    <script type="text/javascript">
		$(document).ready(function(){
			$('#offtime').timepicker({ 'scrollDefaultNow': true, 'minTime': '9:00am', 'maxTime': '3:30pm', 'timeFormat': 'H:i', 'step': 5 });
			$('#fttime').timepicker({ 'scrollDefaultNow': true, 'minTime': '9:00am', 'maxTime': '3:30pm', 'timeFormat': 'H:i', 'step': 15 });
			$('#latetime').timepicker({ 'scrollDefaultNow': true, 'minTime': '9:00am', 'maxTime': '3:30pm', 'timeFormat': 'H:i', 'step': 5 });
			$('#istime').timepicker({ 'scrollDefaultNow': true, 'minTime': '9:00am', 'maxTime': '3:30pm', 'timeFormat': 'H:i', 'step': 5 });
			$('#starttime').timepicker({ 'scrollDefaultNow': true, 'minTime': '9:00am', 'maxTime': '3:30pm', 'timeFormat': 'H:i', 'step': 5 });
		});
	</script>
</head>


<?php
	//query faclitators from sql to get a list
	$facget = $db_server->query("SELECT * FROM facilitators ORDER BY facilitatorname ASC");
    $facilitators = array();
    while ($fac_row = $facget->fetch_row()) {
		array_push ($facilitators, $fac_row[0]);
    }

// if another date has not been chosen, and the submit button has been pressed, change status
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
				favorite($id, '7', '', ''); // this line adds favorites for the current user if the add to favorites checkbox is checked
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
			echo "<div class='error'>Please enter a valid return time.</div>";
			}
		} else {
			echo "<div class='error'>Please fill out the location box before signing out to offsite.</div>";
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
			echo "<div class='error'>Please enter a valid late time.</div>";
			}
		} else {
			echo "<div class='error'>You must choose a late time before signing out to late.</div>";
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
			echo "<div class='error'>Please enter a valid return time.</div>";
			}
		} else {
			echo "<div class='error'>You must choose a return time before signing out to independent study.</div>";
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
				echo "<div class='error'>Please enter a valid return time.</div>";
			}
		} else {
				echo "<div class='error'>Please chose a facilitator before signing out to field trip.</div>";
		}
	}

//Sign out querying -- "4" refers to "Checked Out" in statusdata table
	if (!empty($_POST['checkout'])) {
			changestatus($id, '4', '', '');
			if (!empty($_POST['favorite'])){
				favorite($id, '4', '', '');
			}
	}
}
} else { // sign out for a later date ==================================================


	$endchoosedate=strtotime($_POST['chooseday']);
// plan syntax is plan(id, statusid, chosen date, returntime, info, thru date);	
	   //present    
	if (!empty($_POST['present'])){
			?><div class="error">You cannot pre-plan being present</div><?php
	}
	//absent    
	if (!empty($_POST['absent'])){
			plan($id, '7', $endchoosedate, '', '', $_POST['secondchoosedate']);
			}
    //offsite
	if (!empty($_POST['offsite'])) {
		if (!empty($_POST['offloc'])){
        		$info = $_POST['offloc'];
			if (validTime($_POST['offtime'])){
				?><div class="error">You cannot pre-plan being offsite</div><?php
			} else {
				echo "<div class='error'>Please enter a valid return time.</div>";
			}
		} else {
				echo "<div class='error'>Please fill out the location box before signing out to offsite.</div>";
		}
	}
	
    //late
	if (!empty($_POST['late'])) {
		if (!empty($_POST['latetime'])){
				$info="";
			if (validTime($_POST['latetime'])){
				plan($id, '5', $endchoosedate, $_POST['latetime'], $info, $_POST['secondchoosedate']);
			} else {
			echo "<div class='error'>Please enter a valid late time.</div>";
			}
		} else {
			echo "<div class='error'>You must choose a late time before signing out to late.</div>";
		}
	}
	
	 //indi study 
	if (!empty($_POST['isstudy'])) {
		if (!empty($_POST['istime'])){
				$info="";
			if (validTime($_POST['istime'])){
				plan($id, '6', $endchoosedate, $_POST['istime'], $info, $_POST['secondchoosedate']);
			} else {
				echo "<div class='error'>Please enter a valid return time.</div>";
			}
		} else {
			echo "<div class='error'>You must choose a return time before signing out to independent study.</div>";
		}
	}
	
    //fieldtrip
	if (!empty($_POST['fieldtrip'])) {

		if (!empty($_POST['facilitator'])){
        		$info = $_POST['facilitator'];
			if (validTime($_POST['fttime'])){
				plan($id, '3', $endchoosedate, $_POST['fttime'], $info, $_POST['secondchoosedate']);
			} else { 
				echo "<div class='error'>Please enter a valid return time.</div>";
			}
		} else {
				echo "<div class='error'>Please chose a facilitator before signing out to field trip.</div>";
		}
	}

//Sign out querying -- "4" refers to "Checked Out" in statusdata table
	if (!empty($_POST['signout'])) {
			
			?><div class="error">You cannot pre-plan being checked out, choose absent instead.</div><?php
	}
}

	//query sql to get list of favorites for the current user and checkes if the favorite button or del favorite button has been pressed
	$getfav = $db_server->query("SELECT * FROM cookiedata WHERE studentid = '".$id."'");
	$rowcnt =  $getfav->num_rows;
	while ($rowcnt>0){
	$favorite=mysqli_fetch_row($getfav);
	$postfav=$favorite[4];
	$delstring="del:" . $postfav;
	$pieces = explode(":", $delstring);
	
	if (!empty($_POST[$postfav])){ // if the favorite button has been pressed
		changestatus($favorite[0], $favorite[1], $favorite[2],$favorite[3]);
	}
	if (!empty($_POST[$delstring])){ // if the delete favorite has been pressed
	$stmt = $db_server->prepare("DELETE FROM cookiedata WHERE favid = ?");
	$stmt->bind_param('i', $pieces[1]);
	$stmt->execute(); 		
	$stmt->close();
	}
	$rowcnt=$rowcnt-1;
	}
	//query to get current status and another to convert the status id to the clear text statusname
	$info = $db_server->query("SELECT statusid FROM events WHERE studentid = '".$id."'ORDER BY timestamp DESC LIMIT 1");
	$rowdata=mysqli_fetch_row($info);
	$currentstatusid=$rowdata[0];
	$convert = $db_server->query("SELECT statusname FROM statusdata WHERE statusid = '".$currentstatusid."'");
	$currentstatus=mysqli_fetch_row($convert);
	
	//query returntime
	$getreturn = $db_server->query("SELECT returntime FROM events WHERE studentid = '".$id."'ORDER BY timestamp DESC LIMIT 1");
	$returntime=mysqli_fetch_row($getreturn);
	$finalreturn=$returntime[0];
	$returntimeobject = new DateTime($finalreturn);
	
	//query info
	$getwith = $db_server->query("SELECT info FROM events WHERE studentid = '".$id."'ORDER BY timestamp DESC LIMIT 1");
	$withrow=mysqli_fetch_row($getwith);
	$finalwith=$withrow[0];
	
	//query timestamp
	$getdate = $db_server->query("SELECT timestamp FROM events WHERE studentid = '".$id."'ORDER BY timestamp DESC LIMIT 1");
	$datedata=mysqli_fetch_row($getdate);
	$currentdate=$datedata[0];
	
	//set user to not checked in if the last status change was yesterday
		$day_data = new DateTime($currentdate);
				//the day it was yesterday
				$yesterday = new DateTime('yesterday 23:59:59');
				if ($day_data < $yesterday) {
						changestatus($id, '8', '', '');
				}
	?>			

<body class="single-user">
	<div id="puttheimagehere">
		<img src="img/mobius.png">
	</div>
<!-- render buttons and current status-->
	<div id="single-body">
	<div id="links">
		<a href="index.php">Back to main page</a>  
		<a href="viewreports.php?id=<?php echo $id; ?>">View reports for <?php echo $name; ?></a>
	</div>	
	<?php if (!empty($name) || !empty($id)) { ?>
	<h2 class="studentname"><?php echo $name; ?></h2>
		<div class="statusmessage">
		<?php //render current status
			if ($currentstatus[0] == "Field Trip"){
				echo "is currently on a " . $currentstatus[0] . " with " . $finalwith . " and will be back at " . $returntimeobject->format('h:i');
				
			} elseif ($currentstatus[0] == "Offsite") {
				echo "is " . $currentstatus[0] . " at " . $finalwith . " and will be at school at " . $returntimeobject->format('h:i');
			
			} elseif ($currentstatus[0] == "Late"){
				echo "is " . $currentstatus[0] . " and will be at school at " . $returntimeobject->format('h:i');
			
			} elseif ($currentstatus[0] == "Not Checked In") {
				echo "has not checked in today"; 
			
			} elseif ($currentstatus[0] == "Independent Study") {
				echo "is currently on an " . $currentstatus[0] . " and will be back at " . $returntimeobject->format('h:i');
			
			} else {
				echo "is currently " . $currentstatus[0];
			} ?>
		</div>			
	<?php } else { // if a student is not chosen..
		echo "Please go back to the main page and make a student selection";
		}
	?>

<!-- top form for change status -->

<form method='post' action='<?php echo basename($_SERVER['PHP_SELF']); ?>' id='main'>
	<?php if ($currentstatus[0] != "Present"){ //if the user is present, hide the present button
		?>
    <div>
        <input type="submit" value="Present" name="present">
    </div>
    <?php } ?> 
    <div>
        <input type="text" name="offloc" placeholder='Location' autocomplete='on' maxlength="25" id="offloc">
		<input type="text" name="offtime" placeholder='Return time' id="offtime">
        <input type="submit" name="offsite" value="Offsite">
    </div>
    <div>
    
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
       <input type="submit" name="fieldtrip" value="Field Trip"> 
    </div>
	<div>
		<input type="text" name="istime" placeholder="Return time" id="istime">
		<input type="submit" value="Independent Study" name="isstudy">
	</div>
	<div>
		<input type="text" name="latetime" placeholder="Arrival time" id="latetime">
		<input type="submit" value="Late" name="late">
	</div>
	<div>
		<input type="submit" value="Absent" name="absent">
	</div>
	<div>
		<input type="submit" value="Check Out" name="checkout">
	</div>
	<div>
		<input type="checkbox" name="favorite">Do this now, and also save this to favorites
	</div>
	
		<?php /// SHOW FAVORITES BOX IF APPROPRIATE
		$getfav = $db_server->query("SELECT * FROM cookiedata WHERE studentid = '".$id."'");
		$rowcnt =  $getfav->num_rows;
		if (!$rowcnt == 0) { 
	?>
		<div id="favorites">
		<h3>Favorites</h3>	
	<?php // render users favorites
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
			
		} elseif ($outfav[0] == "Absent"){
			$fullstring = "be " . $outfav[0];
			
		} elseif ($outfav[0] == "Checked Out"){
			$fullstring = "be " . $outfav[0];
		
		} elseif ($outfav[0] == "Independent Study") {
			$fullstring = "go on an " . $outfav[0] . " and be back at " . $returntimeobject->format('h:i');
		
		} else {
			echo "be " . $outfav[0];
		}
		$postfav=$favorite[4];
		$delstring="del:" . $postfav;
	?>
	<div class="singlefav"> <!-- actual buttons for favorites---->
		<input form='main' type="submit" value="<?php echo $fullstring ?>"name="<?php echo $postfav ?>">
		<input form='main' type="submit" value="<?php echo "X" ?>" name="<?php echo $delstring ?>" class="deletefave">
	</div>
	<?php 
		$rowcnt=$rowcnt-1; 
		} 
	?>
	</div>
	<?php 
		}
		
		
		//render preplannedevents gui
	?>

		<div>
		<input type="checkbox" name="otherdate">Don't do this now, but make this take effect for these future dates: (leave second date blank for one-day plans)
		<br>
		<input type="text" name="chooseday" id="chooseday" placeholder="<?php echo date("D M j Y")?>">
		<input type="text" name="secondchoosedate" id="secondchoosedate" placeholder="<?php echo date("D M j Y")?>">
	</div>

	<?php
		$_SESSION['idd']=$id; //pass id for view reports
		
		//query pre-planed events
		$preplannedquery = $db_server->query("SELECT * FROM preplannedevents WHERE studentid = '".$id."'");
		$precnt =  $preplannedquery->num_rows;
		if (!$precnt == 0) { 
		while ($precnt>0){
		$preEvent=mysqli_fetch_row($preplannedquery);
		$delstatid=$preEvent[5]; 
		if (!empty($_POST[$delstatid])){ //if delete preplannedevents pressed
		$stmt = $db_server->prepare("DELETE FROM preplannedevents WHERE eventid = ?");
		$stmt->bind_param('i', $delstatid);
		$stmt->execute(); 		
		$stmt->close();
		}
		$precnt=$precnt-1; 
		} 
	}	
		//pre planned events actual button insert
		$preplannedquery = $db_server->query("SELECT * FROM preplannedevents WHERE studentid = '".$id."'");
		$precnt =  $preplannedquery->num_rows;
		if (!$precnt == 0) { 
		while ($precnt>0){
		$preEvent=mysqli_fetch_row($preplannedquery);
		$preEventDate=new DateTime($preEvent[2]);
		$preEventTime=new DateTime($preEvent[3]);
		$statconvert = $db_server->query("SELECT statusname FROM statusdata WHERE statusid = '".$preEvent[1]."'");
		$outstatconvert=mysqli_fetch_row($statconvert);
		$today = new dateTime();
		if ($preEventDate > $today) { 
		if ($outstatconvert[0] == "Late"){
			echo $name . " will be " . strtolower($outstatconvert[0]) . " on " . $preEventDate->format('l, M j, Y') . ", arriving at " . $preEventTime->format('g:i');
			?>
			<input type="submit" name="<?php echo $preEvent[5] ?>" value="X">
			<?php
			
		} elseif ($outstatconvert[0] == "Field Trip"){
			echo $name . " will be on a " . strtolower($outstatconvert[0]) . " with " . $preEvent[4] . " on " . $preEventDate->format('l, M j, Y') . ", and will return at " . $preEventTime->format('g:i');
			?>
			<input type="submit" name="<?php echo $preEvent[5] ?>" value="X">
			<?php
		} else {
			echo $name . " will be " . strtolower($outstatconvert[0]) . " on " . $preEventDate->format('l, M j, Y');
			?>
			<input type="submit" name="<?php echo $preEvent[5] ?>" value="X">
			<?php
		}
		?>
		</br>
		</br>
		<?php
		}
		$precnt=$precnt-1; 
		} 
	}
	?>	
	</form>	
	</div>
<script src="js/pikaday.js"></script>
<script>
    var picker = new Pikaday({ field: document.getElementById('chooseday') });
	var picker = new Pikaday({ field: document.getElementById('secondchoosedate') });
</script>
</body>
</html>