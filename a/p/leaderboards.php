<html>
<head>
	<title>Leaderboards</title>
	<?php require_once('header.php'); ?>
</head>
<body>
    <div align="center">
<?php 
$studentData = $db_server->query("SELECT * FROM studentdata WHERE current = 1 ORDER BY firstname");
$studentTable = array();
$studentNames = array();
 while ($studentRow = mysqli_fetch_assoc($studentData)) {
	$currentStudentRow = array();
	$lastInit = $studentRow['lastname'][0];
	$stats = calculateStats($studentRow['studentid']);
	array_push($currentStudentRow,$studentRow['firstname'],$lastInit,$stats[2],$stats[0],$stats[3],$stats[4]);
	array_push($studentTable,$currentStudentRow);
	array_push($studentNames,$studentRow['firstname']);
}

foreach ($studentTable as $key => $row){
    $studentTable[4][$key]  = $row[4];
}    

array_multisort($studentTable[4], SORT_DESC, $studentTable);

for ($i = 0; $i < count($studentTable); $i++){
	
		//if(!in_array($studentTable[$i][0],$studentNames)){
			//print_r($studentTable[$i]);
			//unset($studentTable[$i]); 
		//}
}

?>
<br>
<table id="LeaderBoards">
<tr> 
<th> Name </th>
<th> Minutes Per Day </th>
<th> Offsite Hours</th>
<th> Percentage of offsite used <br> The school year is <?php echo $studentTable[0][5] . "%"; ?> complete</th>
</tr>
<?php 
foreach ($studentTable as $render){
	?> 
	<tr>
	<td> <?php echo $render[0]; ?> </td>
	<td> <?php echo $render[2]; ?> </td>
	<td> <?php echo $render[3]; ?> </td>
	<?php 
	if($studentTable[0][5] <= $render[4]){
	?> 
	<td style = "color:red;"> <?php echo $render[4] . "%"; ?> </td>
	<?php
	} else {
	?>
	<td> <?php echo $render[4] . "%"; ?> </td>
	<?php
	}
	?>
	</tr>
<?php
}

?>
</table>
        </div>
</body>
</html>