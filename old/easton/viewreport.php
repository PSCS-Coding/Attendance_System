<DOCTYPE html>
<html>
<head>
<title>View Reports</title>
</head>
<body>
<form method="post">
<input type="text" name="name">
<input type="submit" name="submit">
</form>
<?php
// connect to sql
$db_server = mysql_connect("localhost", "pscs", "Courage!");

if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());

mysql_select_db("attendance", $db_server)

	or die("Error querying database ".mysql_error());
	
	if (isset($_POST['submit'])) {
        $name = $_POST['name'];
		$bettername = $_POST['name'];
		
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
		while ($elapseddata = mysql_fetch_array($querytwo)) {
			foreach ($elapseddata as $item)  {
			$total = $total + $item;
			}
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
			$readableoffsiteleft = $h_offsiteleft . " hours and " . $formattedoffsiteleft . " minutes of offsite left";
				echo $bettername;
				echo '<br />';
				echo $readableoffsiteleft;
				echo '<br />';
			$countelapsed = count($elapseddata, COUNT_RECURSIVE);
			echo $countelapsed;
		}
		?>
		</body>
		</html>