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
    body {
    position:relative;
    margin:0;
    font-family:Verdana, Arial, Helvetica, sans-serif;
    font-size:80%;
    background-color:#4A4747;

    background-image: url('../static/mobius-transparent.png');
    background-repeat: no-repeat;
    background-position: center 100px;
    background-attachment: fixed;
    }
    tr:nth-child(odd) {
    background-color:#ddd;
    opacity:0.9;
    }

    tr:nth-child(even) {
        background-color:#fff;
        opacity:0.9;
    }
    a {
    float:left;
    margin-right:-16px;
    text-decoration:none;
    line-height:26px;
    color: #344486;
    }

    a:hover {
        text-decoration: none;
        color: rgb(0, 0, 97);
    }
    select {
        height: 21.33px;
    }
    h3 {
        color:white;
    }
    </style>
</head>
<body>
<h3>Student Late Info</h3>
<?php
require_once("../connection.php");
require_once("../function.php");
require_once("../login.php");

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
            $currentId = $row['studentid'];
            $currentEventId = $row['eventid'];
            $startQueryTime = new DateTime(explode(" ", $row['timestamp'])[0] . "12:00 AM");
            $startQueryTime = $startQueryTime->format("Y-m-d H:i:s");
            $endQueryTime = new DateTime(explode(" ", $row['timestamp'])[0] . $getTime);
            $endQueryTime = $endQueryTime->format("Y-m-d H:i:s");
            $prevQuery = $db_server->query("SELECT * FROM events
                                            WHERE studentid='$currentId'
                                            AND timestamp BETWEEN '$startQueryTime' AND '$endQueryTime'
                                            AND eventid < '$currentEventId'
                                            ORDER BY eventid DESC limit 1")
                                            or die(mysqli_error($db_server));
            $prevQueryResult = $prevQuery->fetch_row();
            if($prevQueryResult[1] == "8" or $prevQueryResult[1] == "5"){
                array_push($lateEvents,$row);
                $value1 = new DateTime($rowTime);
                $value2 = new DateTime("9:00 AM");
                array_push($timeDiffs, round((strtotime($value1->format("Y/m/d H:i:s")) - strtotime($value2->format("Y/m/d H:i:s"))) /60,2));
                }
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
sort($timeDiffs);
$count = 0;
$average = 0;
$median = 0;
if(count($timeDiffs) > 0){
    $count = count($timeDiffs);
    $average = round(array_sum($timeDiffs)/count($timeDiffs),2);
    if($count % 2 == 0){
        $median = round(($timeDiffs[round(count($timeDiffs)/2)-1] + $timeDiffs[round(count($timeDiffs)/2)]) / 2,2);
    } else {
        $median = $timeDiffs[round(count($timeDiffs)/2)-1];
    }

}

echo("<tr><td>Average " . $average . " </td><td> Median " . $median . "</td><td>Count " . $count . "</td></tr>");
foreach($lateEvents as $row){
    $currentDatetime = new DateTime($row["timestamp"]);
    echo("<tr><td><a href='../viewreports.php?id=" . $row['studentid'] . "'target='_blank'/>" . idToName($row["studentid"]) . "</td><td>" . $currentDatetime->format("g:i:s") . "</td><td>" . $currentDatetime->format("Y-m-d") . "</td></tr>");
}
?>
</table>
</body>
</html>