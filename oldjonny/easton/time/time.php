<DOCTYPE html>
<html>
<head>
<title>Time</title>
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

	or die("Unable to select database: " . mysql_error());
// query
	
			if (isset($_POST['submit'])) {
        $name = $_POST['name'];
	
	$querytimein = mysql_query("
		SELECT timeout 
		FROM studentInfo
		WHERE name = '$name' 
		ORDER BY time DESC
		LIMIT 1,1
		")
		or die("Error querying database ".mysql_error());
		$querytimeout = mysql_query("
		SELECT timein
		FROM studentInfo
		WHERE name = '$name' 
		ORDER BY time DESC
		LIMIT 1
		")
		or die("Error querying database ".mysql_error());
	while($minutestimein = mysql_fetch_array($querytimein)){
		$ltimein = $minutestimein['timeout'];
		}
			while($minutestimeout = mysql_fetch_array($querytimeout)){
		$ltimeout = $minutestimeout['timein'];
	  }
	  $timegone = $ltimeout - $ltimein; 
		//turn difference into minutes and subtract from total offsite time
	  $timegonerounded = round($timegone / 60)*60;
	  $timegoneminutes = $timegonerounded / 60;
		//timegoneminutes is how much time was gone REMEMBER
		echo $timegoneminutes;
		echo "<br />";
	  $hourtotaloffsite = 144;
	  $minutetotaloffsite = $hourtotaloffsite * 60;
	  $minuteoffsiteleft = $minutetotaloffsite - $timegoneminutes;
	  echo $minuteoffsiteleft;
	  echo "<br />";
	  $roundedtest = 60 * floor($minuteoffsiteleft / 60);
			echo $roundedtest;
			echo "<br />";
			$afterthecolon = $minuteoffsiteleft - $roundedtest;
			echo $hourtotaloffsite;
			echo "<br />";
			$offsitehour = $roundedtest / 60;
			$readableoffsiteleft = $offsitehour . " hours and " . $afterthecolon . " minutes of offsite left";
			echo $readableoffsiteleft;
		// $houroffsiteleft = round($minuteoffsiteleft 60);
	  }
?>
</body>
</html>