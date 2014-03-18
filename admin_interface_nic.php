<html>
<head>
<title>Admin: Edit the studentdata table</title>
<link rel='stylesheet' href='style.css'/>
<link rel='stylesheet' href="css/pikaday.css" />
</head>
<body>
<?php

	require_once("../attendance/connection.php");

	if (isset($_POST['delete']) && isset($_POST['deleteid']))
	{
		$deleteid  = get_post('deleteid');
		
		$stmt = $db_server->prepare("UPDATE studentdata SET current = 0 WHERE studentid = ?");
		$stmt->bind_param('s', $deleteid);
		$stmt->execute(); 		
		$stmt->close();
	}

	if (isset($_POST['activate']) && isset($_POST['activateid']))
	{
		$activateid  = get_post('activateid');
		
		$stmt = $db_server->prepare("UPDATE studentdata SET current = 1 WHERE studentid = ?");
		$stmt->bind_param('s', $activateid);
		$stmt->execute(); 		
		$stmt->close();
	}
	
			
	if (isset($_POST['firstname']) && isset($_POST['lastname']) && isset($_POST['startdate'])) {
		$firstname = get_post('firstname');
		$lastname = get_post('lastname');
		$startdate = get_post('startdate');
		$timestamp = strtotime($startdate);
	
		$stmt = $db_server->prepare("INSERT INTO studentdata (firstname, lastname, startdate) VALUES (?, ?, FROM_UNIXTIME(?))");
		$stmt->bind_param('sss', $firstname, $lastname, $timestamp);
		$stmt->execute(); 
		$stmt->close();
	}				
?>

<form action="admin_interface_nic.php" method="post">
	<pre>
	First Name <input type="text" name="firstname" />
	Last Name <input type="text" name="lastname" />
	Start Date <input type="text" name="startdate" id="startdate" />
	<input type="submit" value="ADD RECORD" />	
	</pre>
</form>
<?php
	$result = $db_server->query("SELECT * FROM studentdata WHERE current = 1  ORDER BY firstname ASC");
	$rows = $result->num_rows;
	?>
    <h2>Current Students</h2>
	<table class='table'>
		<tr>
        <th class='table_head'> Student Name </th>
		<th class='table_head'> Start Date </th>
		<th class='table_head'> Delete </th>
        </tr>
	<?php
		for ($j = 0 ; $j < $rows ; ++$j)
		{

		$row = $result->fetch_assoc();
	?>
	
	<?php
		echo "<tr>";
		echo "<td>" . $row['firstname'] . " " . $row['lastname'] . "</td>";
		echo "<td>" . $row['startdate'] . "</td>";
		echo "<td>";
	
	?>
	
    <form action="admin_interface_nic.php" method="post">
      <input type="hidden" name="delete" value="yes" />
      <input type="hidden" name="deleteid" value="<?php echo $row['studentid']; ?>" />
      <input type="submit" value="DELETE STUDENT" />
    </form>
    </td>
    </tr>
	<?php } ?> 
    </table> 
<?php
	$result = $db_server->query("SELECT * FROM studentdata WHERE current = 0 ORDER BY firstname ASC");
	$rows = $result->num_rows;
	?>
    <h2>Deleted Students</h2>
	<table class='table'>
		<tr>
        <th class='table_head'> Student Name </th>
		<th class='table_head'> Start Date </th>
		<th class='table_head'> Delete </th>
        </tr>
	<?php
		for ($j = 0 ; $j < $rows ; ++$j)
		{

		$row = $result->fetch_assoc();
	?>
	
	<?php
		echo "<tr>";
		echo "<td>" . $row['firstname'] . " " . $row['lastname'] . "</td>";
		echo "<td>" . $row['startdate'] . "</td>";
		echo "<td>";
	
	?>
	
    <form action="admin_interface_nic.php" method="post">
      <input type="hidden" name="activate" value="yes" />
      <input type="hidden" name="activateid" value="<?php echo $row['studentid']; ?>" />
      <input type="submit" value="REACTIVATE STUDENT" />
    </form>
    </td>
    </tr>
	<?php } ?> 
    </table> 



	<?php
	
	$db_server->close();
	
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
