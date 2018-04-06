<!DOCTYPE html>
<html>
	<head>
		<title>Offsite Stats</title>
		<?php require_once('header.php'); ?>
	</head>

	<?php
	// check to make sure the user is logged in
	$admin = 1;
	require_once('../../login.php');

	// store header info for session tracking
	$HeaderStatus = null;
	$HeaderInfo = "Offsite Stats";

	// query studentdata
	$studentData = $db_server->query("SELECT * FROM studentdata WHERE current = 1 ORDER BY firstname");
	$studentTable = array();
	$studentNames = array();

	// build arrays
	while ($studentRow = mysqli_fetch_assoc($studentData)) {
		$currentStudentRow = array();
		$lastInit = $studentRow['lastname'][0];
		$stats = calculateStats($studentRow['studentid']);
		array_push($currentStudentRow,$studentRow['firstname'],$lastInit,$stats[2],$stats[0],$stats[3],$stats[4],$studentRow['studentid']);
		array_push($studentTable,$currentStudentRow);
		array_push($studentNames,$studentRow['firstname']);
	}

	// setup single key array to sort the bigger array on
	$sortArray = array();

	foreach ($studentTable as $key => $row){
		$sortArray[$key]  = $row[4]; // the position in the big table to sort by
	}    

	// sort the big table by the little table
	array_multisort($sortArray, SORT_DESC, $studentTable);

	?>
	<body>
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
				<?php foreach ($studentTable as $render){ // loop through sorted big table, add a row for each student?> 
					<tr>
						<td> <?php echo "<a target=_blank class='unstyled-link' href='../../viewreports.php?id=" . $render[6] . "'>" . $render[0] . " " . $render[1]; // the <a> tag is a link to view reports?> </a></td>
						<td> <?php echo $render[2]; ?> </td>
						<td> <?php echo $render[3]; ?> </td>
						<?php if($render[4] >= 100){ // if the student is using offsite unsustainably, turn the percentage red ?> 
							<td style = "color:red;"> <?php echo $render[4] . "%"; ?> </td>
						<?php } elseif($render[3] <= $render[4]){ ?>
							<td style = "color:firebrick;"> <?php echo $render[4] . "%"; ?> </td>
						<?php } else { ?>
							<td> <?php echo $render[4] . "%"; ?> </td>
						<?php } ?>
					</tr>
				<?php } ?>
			</table>
		</div>
	</body>
</html>