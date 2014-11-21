<!DOCTYPE html>
<html>
<head>
	<title>View Reports</title>
			<link rel="stylesheet" type="text/css" href="attendance.css">

</head>
<body class="view-reports">

<?php
session_start();

require_once("connection.php");
require_once("function.php");
$_SESSION['prevURL'] = $_SERVER['REQUEST_URI'];

//loggin stuffs - make this $_SESSION['adminSet'] if it's an admin-only page
if(!$_SESSION['set']) {
	header("location: main_login.php");
}

// query database 

//current students array
$studentquery = "SELECT studentid, firstname FROM studentdata WHERE current=1 ORDER BY firstname";
$current_users_query = $db_server->query($studentquery);
$current_users_result = array();
while ($student = $current_users_query->fetch_array()) {
	array_push($current_users_result, $student);
}

//query faclitators from sql to get a list
$facget = $db_server->query("SELECT * FROM facilitators ORDER BY facilitatorname ASC");
   $facilitators = array();
while ($fac_row = $facget->fetch_row()) {
	array_push ($facilitators, $fac_row[0]);
}

//set id and name

if(!empty($_POST['studentselect'])){
$selected = $_POST['studentselect'];
$idToName = $db_server->query("SELECT firstname FROM studentdata WHERE studentid = $selected");
$tempRow = $idToName->fetch_assoc();
$name = $tempRow['firstname'];
setcookie('name', $name);
}
	if(!empty($_POST['studentselect'])){
	$id = $_POST['studentselect'];
	setcookie('id', $id);
	echo $id;
	}
	
	if (empty($id)){
		if(!empty($_COOKIE['id'])){
		$id = $_COOKIE['id'];
		}
	
		} else {
			setcookie('id', $id);
		} 
	$name = idToName($id);
	echo "name: " . $name;
	
?>

<div class="choose-report">
	<p>View report for: <?php echo $name ?></p>
	<form method='post' id='studentform' class='studentselect' action='<?php echo basename($_SERVER['PHP_SELF']); ?>'>
	<select name='studentselect' class='studentselect'>
	<?php 
	foreach($current_users_result as $student) {
		?>
		<option name='<?php echo $student['studentid']; ?>' value= '<?php echo $student['studentid']; ?>'><?php echo $student['firstname']?></option>
		<?php
	}
	?>
	</select>
	<input type='submit' name='studentsubmit' class='studentselect'>
	</form>
	</div>
		<div>
			<a href="index.php">Return to main attendance view</a>
		</div>
	</div>

<?php
// query events
//$studentData = array();
//$all = $db_server->query("SELECT info,statusname,studentdata.studentid,studentdata.firstname,timestamp,returntime,events.eventid, yearinschool
//		FROM events 
//		JOIN statusdata ON events.statusid = statusdata.statusid
//		RIGHT JOIN studentdata ON events.studentid = studentdata.studentid
/*		WHERE studentdata.studentid = $id
		ORDER BY timestamp DESC") or die(mysqli_error($db_server));
while ($student_data_result = $all->fetch_assoc()) {
	array_push($studentData, $student_data_result);
	print_r($student_data_result);
	?> <br> <?php
}
*/
?>

</body>
</html>