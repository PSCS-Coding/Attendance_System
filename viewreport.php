<DOCTYPE html>
<html>
<head>
<title>View Reports</title>
</head>
<body>
<?php
	session_start();
if (!empty($_POST['submit'])) {
} else {
echo '<form method="post" action="viewreport.php">
<input type="text" name="name">
<input type="submit" name="submit">
</form>';
}
?>
<?php
function completeCode() {
if (!empty($_POST['submit'])) {
echo '<form method="post" action="viewreport.php">
<input type="text" name="name">
<input type="submit" name="submit">
</form>';
}
// connect to sql
$db_server = mysql_connect("localhost", "pscs", "Courage!");

if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());

mysql_select_db("attendance", $db_server)

	or die("Error querying database ".mysql_error());
		if (!empty($_POST['submit'])) {
        $name = $_POST['name'];
		$_SESSION['name']=$name;
		$bettername = $name;
		} else {
		$name = $_SESSION['name'];
		}
		
		$bettername = $name;
		$studentdataquery = mysql_query("
		SELECT studentid, firstname 
		FROM studentdata
		WHERE firstname = '$name' 
		ORDER BY studentid ASC
		")
		or die("Error querying database ".mysql_error());
		
		while($studentdata = mysql_fetch_array($studentdataquery)) {
		$studentnumber = $studentdata['studentid'];
		}
		$name = $studentnumber;
		//$name just got turned from the name string the user put in into their ID integer string!
		$querytwo = mysql_query("
		SELECT elapsed
		FROM events
		WHERE studentid = '$name' 
		")
		or die("Error querying database ".mysql_error());
		$querythree = mysql_query("
		SELECT offsitehours
		FROM allottedhours
		")
		or die("Error querying database ".mysql_error());
		while ($nic = mysql_fetch_array($querythree)) {
			$h_totaloffsite = $nic['offsitehours'];
			}
			$total = 0;
			$rowcount = mysql_num_rows($querytwo);
		while ($elapseddata = mysql_fetch_assoc($querytwo)) {
			foreach ($elapseddata as $item)  {
			$total = $total + $item;
			}
		}
		echo '<p class="bettername">' . $bettername . '</p>
				<style>
				.bettername {
				font-size:20pt;
				}
				* {
				font-family:Arial;
				}
				</style>';
				if (!empty($_POST['submit'])) {
	echo '
	<b>
	<form method="post"><input type="hidden" value="' . $name . '" name="hidden">
	<p>How many minutes per day could I spend offsite if I want to have
	<select name="hours">
	<option value="5">5</option>
	<option value="10">10</option>
	<option value="20">20</option>
	<option value="30">30</option>
	<option value="40">40</option>
	<option value="50">50</option>
	</select>
	 hours of offsite left at the end of the year?<input type="submit" name="go" value="Go"></p>
	</form>
	</b>';
	}
	
	
			//$total is how many minutes of offsite you've spent
				$m_totaloffsite = $h_totaloffsite * 60;
			//$m_totaloffsite is how many minutes of offsite you have total
				$m_offsiteleft = $m_totaloffsite - $total;
			//$m_offsiteleft is how many minutes of offsite you have left to spend
				$roundedminutes = 60 * floor($m_offsiteleft / 60);
				$h_offsiteleft = $roundedminutes / 60;
			//$h_offsiteleft is how many hours off offsite you have left to spend
				$formattedoffsiteleft = $m_offsiteleft - $roundedminutes;
				$readable_offsiteleft = "You have " . $h_offsiteleft . " hours and " . $formattedoffsiteleft . " minutes of offsite left.";
				$m_averagetimeoutdecimal = $total / $rowcount;
				$m_averagetimeout = floor($m_averagetimeoutdecimal);
				$readable_averagetimeout = "You spend an average of " . $m_averagetimeout . " minutes away when you go offsite.";
					
					
					$daysleft = 50;
							//$daysleft is me hardcoding in how many days there are left in the school year, we have to add in a query that pulls the amount of days left from a table or something.
							if (!empty($_POST['hours'])){
						$userhours = $_POST['hours'];
						$m_userhours = $userhours * 60;
						$m_availableoffsite = $m_totaloffsite - $m_userhours;
						$newavailableoffsite = $m_availableoffsite / $daysleft;
						$newavailableoffsite = floor($newavailableoffsite);
						$readable_offsiteif = 'If you want to have ' . $userhours . ' hours of offsite at the end of the year, you can spend ' . $newavailableoffsite . ' minutes a day offsite.';
						echo '<b>';
						echo $readable_offsiteif;
						echo '</b>';
						echo '<br />';
						echo '<br />';
						}
						
						echo $readable_offsiteleft;
							echo '<br />';
						echo $readable_averagetimeout;	
							echo '<br />';
							echo '<br />';
								
								echo '<form method="post">
									<input type="text" name="selector3">
									<input type="submit" name="submit3" value="submit3">
									  </form>';
									  
								
									 if (isset($_POST['selector3'])) {
						
							$eventsquery = mysql_query("
								SELECT studentid, statusid, timestamp, elapsed
								FROM events
								WHERE studentid = '$name'
								AND statusid = '$_POST['selector3']'
								ORDER BY timestamp DESC
								LIMIT 1
								")
								or die("Error querying database ".mysql_error());
								$eventsarray = mysql_fetch_array($eventsquery);
								
								echo $eventsarray['timestamp'];
								echo '<br />';
								
								if ($eventsarray['statusid'] == 1) {
									$eventsarray['statusid'] = 'Present';
									}
									if ($eventsarray['statusid'] == 2) {
									$eventsarray['statusid'] = 'Offsite';
									}
									if ($eventsarray['statusid'] == 3) {
									$eventsarray['statusid'] = 'Offsite';
									}
								echo	'<table style="width:300px">
										<th>Name</th>
										<th>Status</th>
										<tr>
										<td>' . $bettername . '</td>
										<td>' . $eventsarray['statusid'] . '</td>
										</tr>
										</table>';
						
								echo $eventsarray['studentid'];
								echo '   ';
								echo $eventsarray['statusid'];
								echo '   ';
								echo $eventsarray['timestamp'];
								echo '   ';
								echo $eventsarray['elapsed'];
								}
				}
				//TO DO
				
			//$daysleft is me hardcoding in how many days there are left in the school year, we have to add in a query that pulls the amount of days left from a table or something.
			//SEE LINE 120
				
			
	if (!empty($_POST['submit'])) {
				completeCode();
				}
					if (!empty($_POST['go']) && !empty($_POST['hidden'])) {
					completeCode();
					echo '<br />';
					}
					
		?>
			<style>
			table {
			border-style:solid;
			border-width:2px;
			}
			th {
			border-style:solid;
			border-width:2px;
			}
			tr {
			border-style:solid;
			border-width:2px;
			}
			td {
			border-style:solid;
			border-width:2px;
			}
			</style>
		</body>
		</html>