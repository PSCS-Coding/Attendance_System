<!DOCTYPE html>
<html>
<head>
    <title> Lates </title>
    <style>
    table {
        width:60%;
        margin-left:20%
    }
    table,tr,td,th{
        border:1px solid black;
        border-collapse:collapse
    }
    td,th{
        padding:5px
    }
    div,h3{
        text-align:center;
        width:60%;
        margin-left:20%
    }
    </style>
</head>
<body>
<h3>Student Late Info</h3>
<?php
require_once("connection.php");
require_once("function.php");

$queryAdd = "";
if(!empty($_POST['studentid'])){
    if($_POST['studentid'] != "none"){
        $selectedStudent = $_POST['studentid'];
        $queryAdd = " AND studentid = $selectedStudent";
    }
}

$lateEvents = array();
$result = $db_server->query("SELECT * FROM events WHERE statusid=1"  . $queryAdd . " ORDER BY timestamp DESC");

while($row = $result->fetch_assoc()){
    $timeObject = explode(" ", $row['timestamp'])[1];
    $rowTime = new DateTime($timeObject);
    $rowTime = $rowTime->format("H:i:s");
    if(new DateTime($rowTime) > new DateTime("9:00 AM") && new DateTime($rowTime) < new DateTime("10:00 AM")){
        array_push($lateEvents,$row);
    }
}
?>
<div>
<form name="search" method="post" action="late.php">
    <select name='studentid'>
    <option value="none">All students</options>
	<?php
    $studentquery = "SELECT studentid, firstname, lastname FROM studentdata WHERE current=1 ORDER BY firstname";
    $current_users_query = $db_server->query($studentquery);
    $current_users_result = array();
    while ($student = $current_users_query->fetch_array()) {
        array_push($current_users_result, $student);
    }
    $current_student_id = $_POST['studentid'];
	foreach($current_users_result as $student) {
		$lastinitial = substr($student['lastname'], 0, 1); ?>
		<option name='studentselect' value= '<?php echo $student['studentid']; ?>' <?php if (!empty($current_student_id)) { if ($current_student_id == $student['studentid']) { echo 'selected';};} ?>><?php echo $student['firstname']?><?php echo " "?><?php echo $lastinitial?></option>
		<?php
	}
	?>
	</select>
    <input type="submit" value="Go!" name="submit"/>
</form>
</div>
<br />
<table>
<tr>
    <th>Studentid</th>
    <th>Time</th>
    <th>Date</th>
</tr>
<?php
foreach($lateEvents as $row){
    $currentDatetime = new DateTime($row["timestamp"]);
    echo("<tr><td>" . idToName($row["studentid"]) . "</td><td>" . $currentDatetime->format("H:i:s") . "</td><td>" . $currentDatetime->format("Y-m-d") . "</td></tr>");
}
?>
</table>
</body>
</html>