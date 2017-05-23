<!DOCTYPE html>
<html lang="en">
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
    a {
        text-decoration:none;
        color:black;
    }
    </style>
</head>
<body>
<h3>Student Late Info</h3>
<?php
require_once("connection.php");
require_once("function.php");
require_once("login.php");

$queryAdd = "";
if(!empty($_POST['studentid'])){
    $current_student_id = $_POST['studentid'];
    if($_POST['studentid'] != "none"){
        $selectedStudent = $_POST['studentid'];
        $queryAdd = " AND studentid = $selectedStudent";
    }
}

$lateEvents = array();
$result = $db_server->query("SELECT * FROM events WHERE statusid=1"  . $queryAdd . " ORDER BY timestamp DESC");
$timeDiffs = [];
$lastRow = "null";
while($row = $result->fetch_assoc()){
    if($lastRow != "null" && $lastRow != $row['timestamp']){
        $timeObject = explode(" ", $row['timestamp'])[1];
        $rowTime = new DateTime($timeObject);
        $rowTime = $rowTime->format("H:i:s");
        if(!empty($_POST['getTime'])){
            $getTime = $_POST['getTime'];
        } else {
            $getTime = "9:30 AM";
        }
        if(new DateTime($rowTime) > new DateTime("9:00 AM") && new DateTime($rowTime) < new DateTime($getTime)){
            array_push($lateEvents,$row);
            $value1 = new DateTime($rowTime);
            $value2 = new DateTime("9:00 AM");
            array_push($timeDiffs, round((strtotime($value1->format("Y/m/d H:i:s")) - strtotime($value2->format("Y/m/d H:i:s"))) /60));
        }
    }
    $lastRow = $row['timestamp'];
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
	foreach($current_users_result as $student) {
		$lastinitial = substr($student['lastname'], 0, 1); ?>
		<option name='studentselect' value= '<?php echo $student['studentid']; ?>' <?php if (!empty($current_student_id)) { if ($current_student_id == $student['studentid']) { echo 'selected';};} ?>><?php echo $student['firstname']?><?php echo " "?><?php echo $lastinitial?></option>
	<?php
	}
	?>
	</select>
    <input type="text" name="getTime" placeholder="9:30 AM"/>
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
echo("<tr><td> Total </td><td>Average " . round(array_sum($timeDiffs)/count($timeDiffs),2) .  "</td><td>Count " . count($timeDiffs) . "</td></tr>");
foreach($lateEvents as $row){
    $currentDatetime = new DateTime($row["timestamp"]);
    echo("<tr><td><a href='viewreports.php?id=" . $row['studentid'] . "'target='_blank'/>" . idToName($row["studentid"]) . "</td><td>" . $currentDatetime->format("H:i:s") . "</td><td>" . $currentDatetime->format("Y-m-d") . "</td></tr>");
}
?>
</table>
</body>
</html>