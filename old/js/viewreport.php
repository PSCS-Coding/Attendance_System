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
a:
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
		$_SESSION['name1'] = $name;
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
		$style = '<p class="bettername">' . $bettername . '</p>
				<style>
				.bettername {
				font-size:20pt;
				}
				* {
				font-family:Arial;
				}
				</style>';
				echo $style;
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
						
						echo '<form method="post"><p>How many minutes per day could I spend offsite if I want to have ' . $_POST['hours'] . ' hours of offsite left at the end of the year?<input type="submit" name="submit4" value="submit4"></p>';
					
						echo $readable_offsiteif;
						echo '</b>';
						echo '<br />';
						echo '<br />';
						}
						if (!empty($_POST['submit4'])){
						completeCode();
						}
						
						echo $readable_offsiteleft;
							echo '<br />';
						echo $readable_averagetimeout;	
						$_SESSION['readable_offsiteleft'] = $readable_offsiteleft;
						$_SESSION['readable_averagetimeout'] = $readable_averagetimeout;
						
								
								//
								echo '<form method="post">
										<select name="calendar">
											<p>Log</p>
											<option value="0">All</option>
											<option value="2">Offsite</option>
											<option value="3">Field Trip</option>
										</select>
									<input type="submit" name="submit3" value="submit3">
						  </form>';
								//
							
									 $style1 = '<style>
								input[value="submit3"] {
			background:url(http://code.pscs.org/attendance/down.png);
			width:20px;
			height:20px;
			border-radius:15px;
			font-size:0.1;
			}
			input[value="Go"] {
			background:url(http://code.pscs.org/attendance/down.png);
			width:20px;
			height:20px;
			border-radius:15px;
			font-size:0.1;
			}
			input[value="submit4"] {
			background:url(http://code.pscs.org/attendance/up.png);
			width:20px;
			height:20px;
			border-radius:15px;
			font-size:0.1;
			}
		</style>
	';
		echo $style1;
			$_SESSION['style'] = $style;
			$_SESSION['style1'] = $style1;
								
									
						
					if (!empty($_POST['submit3'])){
						$calendarinput = $_POST['calendar'];
							if ($calendarinput == 0){
							$eventsquery = mysql_query("
								SELECT timestamp, elapsed, info, returntime, statusid
								FROM events
								WHERE studentid = '$name'
								ORDER BY timestamp DESC
								")
								or die("Error querying database ".mysql_error());
								} else {
								$eventsquery = mysql_query("
								SELECT timestamp, elapsed, info, returntime, statusid
								FROM events
								WHERE studentid = '$name'
								AND statusid = '$calendarinput'
								ORDER BY timestamp DESC
								")
								or die("Error querying database ".mysql_error());
								}
								
												echo '<table style="width:1000px"><tr>';
											if ($calendarinput == 0){
										echo '<th>Type</th>';
									}
								echo	'
										<th>Name</th>
										<th>Time</th>
										<th>Return Time</th>
										<th>Date</th>
										<th>Days Ago</th>
										<th>Elapsed</th>
										<th>Where</th>
										</th>
										';
								
							while ($eventsarray = mysql_fetch_assoc($eventsquery)){
								$strtotimetimestamp = strtotime($eventsarray['timestamp']);
									$totalsec = $strtotimetimestamp + $eventsarray['elapsed'] * 60;
										$daysago = date('z') - date('z', strtotime($eventsarray['timestamp']));
											$unixtime_returntime = strtotime($eventsarray['returntime']);
											$unixtime_difference = $unixtime_returntime - $totalsec;
											$unixtime_difference = $unixtime_difference + 
											$unixtime_difference = $unixtime_difference * 1;
												$early_late = 'earlier';
													if ($unixtime_difference < 0){
														$unixtime_difference = $unixtime_difference * -1;
														$early_late = 'later';
																			
																			}
																			
																		
														if ($eventsarray['elapsed'] > 0) {
													$smallerthanzero = 0;
												if ($eventsarray['elapsed'] < 0){
													$smallerthanzero = 1;
													}
													if ($calendarinput == 0) {
												if ($eventsarray['statusid'] > 3){
													$smallerthanzero = 1;
													}
													}
													if ($smallerthanzero == 0){
													echo '<tr>';
												$statusid = $eventsarray['statusid'];
											if ($calendarinput == 0){
								if ($eventsarray['statusid'] == 1){
									$eventsarray['statusid'] = 'Present';
									}
								if ($eventsarray['statusid'] == 2){
									$eventsarray['statusid'] = 'Offsite';
									}
								if ($eventsarray['statusid'] == 3){
									$eventsarray['statusid'] = 'Field Trip';
									}
									
									echo '<td>' . $eventsarray['statusid'] . '</td>';
									}
										$returntime = $eventsarray['returntime'];			
											$returntime_formatted = date('', strtotime($returntime));
												$returntime = date('', strtotime($returntime));
													$s_returntime = strtotime($eventsarray['returntime']);
														$s_totalsec = strtotime($totalsec);
															$s_difference = $s_returntime - $s_totalsec;
															
																$difference = $s_difference / 60;
																$difference = date('g', strtotime($eventsarray['returntime']));
																$difference = $difference * 1 * 60;
																$difference_x = date('i', strtotime($eventsarray['returntime']));
																$difference_x = $difference_x * 1;
																$difference = $difference + $difference_x;
																
															$timestamp = strtotime($eventsarray['timestamp']);
															$elapsed = $eventsarray['elapsed'] * 60;
															$fulltime = $timestamp + $elapsed;
															$difference = $s_returntime - $fulltime / 60;
															echo $unixtime_returntime;
															//unix return time is unix timestamp version of when you said you'd be back (works)
															//
															echo '<br />';
															$strtotimetimestamp;
															//strtotimetimestamp is unix time version of when you got back (works)
																
														$newtotalsec = strtotime($eventsarray['timestamp']);
														$newtotalsec = $newtotalsec / 60;
														$newtotalsec = $newtotalsec + $eventsarray['elapsed'];
														$newtotalhour = date('g', strtotime($newtotalsec));
														$newtotalhour = $newtotalhour * 1 * 60;
														$newtotalminute = date('i', strtotime($newtotalsec));
														$newtotal = $newtotalhour + $newtotalminute;
																
													echo '<br />';
																
													$totalsec_y = $difference * 1 * 60;
													$totalsec_x = date('i', strtotime($totalsec));
													$totalsec_x = $difference_x * 1;
													$totalsec_y = $difference + $difference_x;
																
															
								echo '
									<td>' . $bettername . '</td>
									<td><p>' . date('g:i A', strtotime($eventsarray['timestamp'])) . '  to ' . date('g:i A', $totalsec) . '</p></td>
									<td><p>You said you would be back by ' . date('g:i', strtotime($eventsarray['returntime'])) . '. You came back at ' .  date('g:i A', $totalsec) . ', ' . $difference . ' minutes ' . $early_late . ' than you said you would be.</p></td>
									<td>' . date('F j, o', strtotime($eventsarray['timestamp'])) . '</td>
									<td><p>' . $daysago . ' Days Ago</p></td>';
									if ($eventsarray['elapsed'] < 60){
									$elapsed_x = 'You were gone for ' . $eventsarray['elapsed'] . ' minutes.';
									echo '
									<td>' . $elapsed_x . '</td>';
									}
									
									$elapsed_m = $eventsarray['elapsed'];
									
								if ($elapsed_m > 60){
										$elapsed_m = $eventsarray['elapsed'];
								$elapsed_m = $elapsed_m - 60;
								if ($elapsed_m > 60) {
								$elapsed_m = $elapsed_m - 60;
								$elapsed = 'You were gone for 2 hours and ' . $elapsed_m . ' minutes.';
								echo '<td>' . $elapsed . '</td>';
								} else {
								$elapsed = 'You were gone for 1 hour and ' . $elapsed_m . ' minutes.';
									echo '<td>' . $elapsed . '</td>';
									}
									}
									if ($statusid == 2){
										echo '
										<td><p>You went to ' . $eventsarray['info'] . '</p></td>';
										}
									if ($statusid == 3){
										echo '
										<td><p>You went with ' . $eventsarray['info'] . '</p></td>';
										}
									if ($statusid == 1){
										echo '
										<td><p>Present</p></td>';
										}
													echo '</tr>';}}}
															
													
								echo '</table>';
								echo '<br />';
								}
							}
						
				//TO DO
				
			//$daysleft is me hardcoding in how many days there are left in the school year, we have to add in a query that pulls the amount of days left from a table or something.
			//SEE LINE 120
														
														if (!empty($_POST['submit3'])){
										completeCode();
								}
														
														if (!empty($_POST['submit4'])){
																$name = $_SESSION['name1'];
																echo $_SESSION['style'];
																echo $_SESSION['style1'];
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
																echo $_SESSION['readable_offsiteleft'];
																echo '<br />';
																echo $_SESSION['readable_averagetimeout'];
																echo '<br />';
																echo '<form method="post">
									<input type="text" name="selector3">
									<input type="submit" name="submit3" value="submit3">
									  </form>';
										echo '<br />';
																}
						
														if (!empty($_POST['submit'])) {
																completeCode();
																}
														if (!empty($_POST['go']) && !empty($_POST['hidden'])) {
																completeCode();
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