<html>
<head>
	<title>Leaderboards</title>
	<?php require_once('header.php'); ?>
</head>
<body>
<?php 
$studentData = $db_server->query("SELECT * FROM studentdata WHERE current = 1 ORDER BY firstname");
$studentTable = array();
 while ($studentRow = mysqli_fetch_assoc($studentData)) {
	$currentStudentRow = array();
	$studentNames = array();
	$lastInit = $studentRow['lastname'][0];
	$stats = calculateStats($studentRow['studentid']);
	array_push($currentStudentRow,$studentRow['firstname'],$lastInit,$stats[2]);
	array_push($studentNames,$stats[2]);
	array_push($studentTable,$currentStudentRow);
}

foreach ($studentTable as $key => $row)
{
    $studentTable[2][$key]  = $row[2];
}    


array_multisort($studentTable[2], SORT_ASC, $studentTable);

?>
<br>
<?php 
foreach ($studentTable as $render){
		echo $render[2];
		?> <br> <?php
}

?>
</body>
</html>