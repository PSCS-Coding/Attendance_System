<html>
<head>
	<title>Leaderboards</title>
	<?php require_once('header.php'); ?>
</head>
<body>
<?php 
$studentData = $db_server->query("SELECT * FROM studentdata WHERE current = 1 ORDER BY firstname");
$studentTable = array();
$studentNames = array();
 while ($studentRow = mysqli_fetch_assoc($studentData)) {
	$currentStudentRow = array();
	$lastInit = $studentRow['lastname'][0];
	$stats = calculateStats($studentRow['studentid']);
	array_push($currentStudentRow,$studentRow['firstname'],$lastInit,$stats[2]);
	array_push($studentTable,$currentStudentRow);
	array_push($studentNames,$studentRow['firstname']);
}

foreach ($studentTable as $key => $row){
    $studentTable[2][$key]  = $row[2];
}    

array_multisort($studentTable[2], SORT_ASC, $studentTable);

for ($i = 1; $i < count($studentTable); $i++){
	
		if(!in_array($studentTable[$i][0],$studentNames)){
			unset($studentTable[$i]); 
		}
}

?>
<br>
<table>
<tr> 
<th> name </th>
<th> minutes per day </th>
</tr>
<?php 
foreach ($studentTable as $render){
	?> 
	<tr>
	<td> <?php echo $render[0]; ?> </td>
	<td> <?php echo $render[2]; ?> </td>
	</tr>
<?php
}

?>
</table>
</body>
</html>