<!DOCTYPE html>
<html>
<head>
	<title>PSCS Attendance student interface</title>
	<link rel="stylesheet" type="text/css" href="attendance.css">
</head>
<body>
	<?php
	//this doc depends on these variables being passed in thru the $_SESSION super global
	session_start();

	?><?php
    require_once("connection.php");
   	require_once("function.php");
    
    //$id = $_SESSION['name'];
	if (!empty($_GET['name'])) {
	$_SESSION['name']=$_GET['name'];
	}
	
	if (!empty($_GET['id'])) {
	$_SESSION['id']=$_GET['id'];
	}
	
	$id = $_SESSION['id'];
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
	$finalresult=mysqli_fetch_row($convert);
	
if ($finalresult[0] == "Field Trip"){
echo $_SESSION['name'] . " is currently on a " . $finalresult[0];
} else {
echo $_SESSION['name'] . " is currently " . $finalresult[0];
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