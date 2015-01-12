<DOCTYPE html>
<head>
<title>My Edit Events</title>
</head>
<?php
	session_start();

	$_SESSION['prevURL'] = $_SERVER['REQUEST_URI'];
	
	//make this $_SESSION['adminSet'] if it's an admin-only page
	if(!$_SESSION['adminSet'])
	{
		header("location: main_login.php");
	}
?>
<?php
require("../connection.php");
$studentlistquery = $db_server->query("
		SELECT firstname, lastname, studentid
		FROM studentdata
		WHERE current = 1
		ORDER BY firstname ASC
		")
		or die("Error querying database ".mysqli_error());
echo '<form method="post" action="new_edit_events.php">
				<select name="studentlist" class="studentlist">';
					while ($a_studentlist = $studentlistquery->fetch_assoc()){
								$studentoption = "<option class='name' value='" . $a_studentlist['firstname'] . "'>" . $a_studentlist['firstname'] . " " . $a_studentlist['lastname'][0] . "</option>";
								echo $studentoption;
								}
								echo '</select>
								<input type="submit" name="submit">
								</form>';
								
								
								if (!empty($_POST['studentlist'])){
									$name = $_POST['studentlist'];
									$idquery = $db_server->query("
								SELECT studentid, firstname, lastname
								FROM studentdata
								WHERE firstname = '$name'
								")
					or die("Error querying database in id query".mysqli_error());
		
					while($studentdata = $idquery->fetch_assoc()) {
					$id = $studentdata['studentid'];
					}
					$eventsquery = $db_server->query("
								SELECT *
								FROM events
								WHERE studentid = $id
								ORDER BY timestamp DESC
								")
					or die("Error querying database in events query".mysqli_error());
					echo '<table class="logtable" style="width:1000px">
					<th>Date</th>
					<th>Type</th>
					<th>Info</th>
					<th>Elapsed</th>
					';
					while($eventsarray = $eventsquery->fetch_assoc()){
					echo '<br />';
					$heeemsecund = $eventsarray['elapsed'] * 60;
					echo '<tr><td><p>' . date('g:i A', strtotime($eventsarray['timestamp'])) . '  to ' . date('g:i A', $heeemsecund) . '</p></td>';
					echo '<td><p>' . $eventsarray['statusid'] . '</p></td>';
					echo '<td><p>' . $eventsarray['info'] . '</p></td>';
					echo '<td><p>' . $eventsarray['elapsed'] . '</p></td></tr>';
					}
					
					}
			
										
										
?>
<style>
	.logtable {
		border-style:solid;
		border-width:2px;
		}
	.logtable th {
		border-style:solid;
		border-width:2px;
		}
	.logtable tr {
		border-style:solid;
		border-width:2px;
		}
	.logtable td {
		border-style:solid;
		border-width:2px;
		}
</style>