<html>
<head>
<title>Admin options</title>
<link rel='stylesheet' href='style.css'/>
<link rel='stylesheet' href="css/pikaday.css" />
</head>
<body>
<?php
			$db_hostname= 'localhost';
			$db_database= 'attendance';
			$db_username= 'pscs';
			$db_password= 'Courage!';
			
			$db_server= mysql_connect($db_hostname, $db_username, $db_password);
			
			if (!$db_server) die("You did it wrong." . mysql_error());
			
			mysql_select_db($db_database)
				or die("Unable to select database: " . mysql_error());
			
if (isset($_POST['delete']) && isset($_POST['name']))
{
	$name  = get_post('name');
	echo $name;
	$query = "UPDATE studentdata SET current = 0 WHERE studentid='$name'";

	if (!mysql_query($query, $db_server))	
		echo "DELETE failed: $query<br />" .
		mysql_error() . "<br /><br />";
}
			
				if (
	isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['startdate'])) {
		$firstname = get_post('firstname');
		$lastname = get_post('lastname');
		$startdate = get_post('startdate');
		$timestamp = strtotime($startdate);
		

		$query = "INSERT INTO studentdata (firstname, lastname, startdate) VALUES" .
		"('$firstname', '$lastname', FROM_UNIXTIME($timestamp))";

	if (!mysql_query($query, $db_server)) {
		echo "INSERT failed: $query<br />" .
		mysql_error() . "<br /><br />";
	}
}				
?>

<form action="admin_interface.php" method="post">
	<pre>
	First Name <input type="text" name="firstname" />
	Last Name <input type="text" name="lastname" />
	Start Date <input type="text" name="startdate" id="startdate" />
	<input type="submit" value="ADD RECORD" />	
	</pre>
</form>
<?php
			$query = "SELECT * FROM `studentdata` WHERE current = 1  ORDER BY firstname ASC";
			$result = mysql_query($query);
			
			if (!$result) die ("Database access didn't work: " . mysql_error());
			
			$rows = mysql_num_rows($result);
	?>
	<table class='table'>
		<th class='table_head'> Student Name </th>
		<th class='table_head'> Start Date </th>
		<th class='table_head'> </th>
	<?php
			for ($j = 0 ; $j < $rows ; ++$j)
			{
			$row = mysql_fetch_row($result);
	?>
	
	<?php
		echo "<tr class='table_row'>";
		echo "<td class='table_data'>" . mysql_result($result,$j,'firstname') . " " . mysql_result($result,$j,'lastname') . "</td>";
		echo "<td class='table_data'>" . mysql_result($result,$j,'startdate') . "</td>";
		echo "<td class='table_data'>";
	
	?>
	
<form action="admin_interface.php" method="post">
  <input type="hidden" name="delete" value="yes" />
  <input type="hidden" name="name" value="<?php echo $row[0]; ?>" />
  <input type="submit" value="DELETE RECORD" />
</form>
</td>
</tr>
<?php
			}
	?> </table> <?php
	
	mysql_close($db_server);
	function get_post($var) {
		return mysql_real_escape_string($_POST[$var]);
	}
			?>
            
<script src="js/pikaday.js"></script>
<script>
    var picker = new Pikaday({ field: document.getElementById('startdate') });
</script>


</body>
</html>
