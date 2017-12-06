<?php
$admin = 1;
require_once('../../login.php');
?>
<!DOCTYPE html>
<html>
<head>
	<title>Offsite Stats</title>
	<?php require_once('header.php'); ?>
</head>
<body>
<?php 
// Header Info
    $HeaderStatus = null;
    $HeaderInfo = "Offsite Stats";

$studentData = $db_server->query("SELECT * FROM studentdata WHERE current = 1 ORDER BY firstname");
$studentTable = array();
$studentNames = array();
 while ($studentRow = mysqli_fetch_assoc($studentData)) {
	$currentStudentRow = array();
	$lastInit = $studentRow['lastname'][0];
	$stats = calculateStats($studentRow['studentid']);
	array_push($currentStudentRow,$studentRow['firstname'],$lastInit,$stats[2],$stats[0],$stats[3],$stats[4],$studentRow['studentid']);
	array_push($studentTable,$currentStudentRow);
	array_push($studentNames,$studentRow['firstname']);
}

$sortArray = array();

foreach ($studentTable as $key => $row){
	$sortArray[$key]  = $row[4]; // the position to sort the table by
}    

array_multisort($sortArray, SORT_DESC, $studentTable);

?>
<div id="TopHeader" class="<?php echo $HeaderStatus; ?>">
    <h1 class="Myheader"><?php echo $HeaderInfo; ?></h1>
</div>
    <div align="center" id="main">
<table id="OffsiteStats">
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
	<td> <?php echo "<a target=_blank class='unstyled-link' href='../../viewreports.php?id=" . $render[6] . "'>" . $render[0] . " " . $render[1]; ?> </a></td>
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