<html>
<head>
<title>Admin: Edit the facilitators table</title>
<link rel='stylesheet' href='style.css'/>
</head>
<body>
<h1>Edit Facilitators</h1>

<?php
// set up mysql connection
	require_once("../attendance/connection.php");
//function document
	require_once("function.php");

// ADD A FACILITATOR			
if (isset($_POST['addnew'])) {
		$stmt = $db_server->prepare("INSERT INTO facilitators (facilitatorname) VALUES (?)");
		$stmt->bind_param('s', $_POST['newname']);
		$stmt->execute(); 
		$stmt->close();
	}				

// EDIT (UPDATE) A FACILITATOR
if (isset($_POST['save'])) {
   	  $stmt = $db_server->prepare("UPDATE facilitators SET facilitatorname = ? WHERE facilitatorid = ?");
	  $stmt->bind_param('si', $_POST['name'], $_POST['id']);
	  $stmt->execute(); 
	  $stmt->close();
	} 

// DELETE A FACILITATOR
if(isset($_POST['delete'])) {
	$stmt = $db_server->prepare("DELETE FROM facilitators WHERE facilitatorid = ?");
	$stmt->bind_param('i', $_POST['id']);
	$stmt->execute(); 		
	$stmt->close();
	}

// GET THE LIST OF FACILITATORS
	$result = $db_server->query("SELECT * FROM facilitators ORDER BY facilitatorname");


?>

<form style="margin-bottom:1em;" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="text" name="newname" />
	<input type="submit" name="addnew" value="add new" />
</form>
	

<table border="1">
   <tr>
      <td>name</td>
      <td>edit</td>
	  <td>delete</td>
   </tr>

<?php
// loop through list of names 
while ($list = mysqli_fetch_assoc($result)) { ?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="id" value="<?php echo $list['facilitatorid']; ?>">
	<tr>
		<?php $editme = "edit-" . $list['facilitatorid'];
		if (isset($_POST[$editme])) { ?> 
		<td><input type="text" name="name" value="<?php echo $list['facilitatorname']; ?>"></td>
		<td><button type="submit" name="save" value="<?php echo $list['facilitatorname']; ?>">save</button></td>
		<?php } else { ?>
		<td><?php echo $list['facilitatorname']; ?></td>
		<td><input type="submit" name="edit-<?php echo $list['facilitatorid']; ?>" value="edit"></td>
		<?php } ?>	
		<td><button type="submit" name="delete" value="<?php echo $list['facilitatorname']; ?>">delete</button></td>
	</tr>
</form>
<?php 
} // end while
?>
</table>


</body>
</html>
