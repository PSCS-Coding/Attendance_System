<!DOCTYPE html>
<html>
<link rel="stylesheet" type="text/css" href="attendance.css">
<title>attendance system tests</title>
<form method='post' action='attendance.php'>
<body>

<table style="width : 100%">
   <select name="status">
  <option value="Present">Present</option>
  <option value="Offsite">Offsite</option>
  <option value="Field Trip">Field Trip</option>
  <option value="Checked Out">Checked Out</option>
  </select>
I think I'll be back in:<select name="comment">
	<option value="10">10 Minutes</option>
	<option value="20">20 Minutes</option>
	<option value="40">40 Minutes</option>
	<option value="60">An Hour</option>
	<option value="90">An Hour and a half</option>
	<option value="180">2 Hours</option>
	</select>
	  <input type="submit" value="Submit" name="submit">
</table>    
<?php
$hour = date('G');
$minute = date('i');
?>
<?php
	if(isset($_POST['comment']))
	{
		$comments = $_POST['comment'];
	}
	?>
<?php
// connect to sql
$db_server = mysql_connect("localhost", "pscs", "Courage!");

if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());

mysql_select_db("attendance", $db_server)

	or die("Unable to select database: " . mysql_error());
	//query time column

if (isset($_POST['submit'])) {
        $name = $_POST['person'];
        $status = $_POST['status'];
        $comments = $_POST['comment'];
		
		$commentminute = $comments + $minute;
		if ($commentminute > 59) {
		$hour = $hour + 1;
		$commentminute = $commentminute - 60;
		}
		$commenttext = "Expected at " . $hour . ":" . $commentminute;
		foreach ($name as $student) {

            $query = "INSERT INTO studentInfo (name, status, comments)
        VALUES ('$student', '$status', '$commenttext')";
        $result = mysql_query($query)
        or die('Error querying database.');
}
    }
	
$userdata = mysql_query("SELECT DISTINCT name FROM studentInfo ORDER BY name ASC");
$rows = mysql_num_rows($userdata);
$users = array();

for ($j = 0 ; $j < $rows ; ++$j)
		{
		$namedata = mysql_fetch_array($userdata);
		array_push($users, $namedata[0]);
		}
        
?>
    
<table style="width:80%">
    <tr>
        <th></th>
        <th>Student</th>
        <th>Status</th>
        <th>Comment</th>
    </tr>
    <?php
	
	$checkboxes = array();
    foreach ($users as $user) {
	$raw = mysql_query("SELECT * FROM studentInfo WHERE name ='".$user."' ORDER BY time DESC LIMIT 1");
	$rowdata = mysql_fetch_array($raw);
	
        echo "<tr>";
        echo "<td><input type='checkbox' name='person[]' value='" . $rowdata[0] . "'></td>";
        echo "<td>" . $rowdata[0] . "</td>";
        echo "<td>" . $rowdata[1] . "</td>";
        echo "<td>" . $rowdata[2] . "</td>";
        echo "</tr>";
	}	
    unset($_POST['submit']);  
   ?>
</table>
</select>
</form>
</body>
</html>