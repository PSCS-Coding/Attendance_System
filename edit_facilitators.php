<?php
			session_start();

			$_SESSION['prevURL'] = $_SERVER['REQUEST_URI'];
			
			//make this $_SESSION['adminSet'] if it's an admin-only page
			if(!$_SESSION['adminSet'])
				{
					header("location: main_login.php");
			}
		?>
<html>
<head></head>
<body>
<h1>Edit Facilitators</h1>
<?php
// set up mysql connection
	require_once("connection.php");
//function document
	require_once("function.php");
	//admin menu
       include 'admin-navbar.php';
	
if (isset($_POST['addnew'])) {
$stmt = $db_server->prepare("INSERT INTO facilitators (facilitatorname, email) VALUES (?, ?)");
$stmt->bind_param('ss', $_POST['newfacilitatorname'] , $_POST['newfacilitatoremail']);
$stmt->execute(); 
$stmt->close();				
}

// EDIT (UPDATE) A Facilitator
if (isset($_POST['save'])) {
 $stmt = $db_server->prepare("UPDATE facilitators SET facilitatorname = ? , email = ? WHERE facilitatorid = ?");
	  $stmt->bind_param('ssi', $_POST['facilitatorname'], $_POST['email'], $_POST['id']);
	  $stmt->execute(); 
	  $stmt->close();
	} 

// DELETE A STUDENT
if(isset($_POST['delete'])) {
	$stmt = $db_server->prepare("DELETE FROM facilitators WHERE facilitatorid = ?");
	$stmt->bind_param('i', $_POST['id']);
	$stmt->execute(); 		
	$stmt->close();
	}
	
	

// GET THE LIST OF Students
	$result = $db_server->query("SELECT * FROM facilitators ORDER BY facilitatorname");

?>

<form style="margin-bottom:1em;" action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
	<input type="text" name="newfacilitatorname" placeholder="Facilitator Name" required><br />
	<input type="text" name="newfacilitatoremail" placeholder="Facilitator Email"><br />
	<input type="submit" name="addnew" value="Add Facilitator" />
</form>
	 <style> 
.textbox { 
    background-color: #BDD7F1; 
    border: solid 1px #646464; 
    outline: none; 
    padding: 2px 2px; 
} 
tr:nth-child(odd)
{
background:#E7E7FF;
}
tr:nth-child(even)
{
background:#F7F7F7;
}
table
{
border-spacing:0px;
}
</style>
<table style="width:500px">
   <tr>
      <th style="text-align:left">Name</th>
      <th style="text-align:left">Email</th>
      <th style="text-align:left">Edit</th>
	  <th style="text-align:left">Delete</th>
   </tr>
<?php
// loop through list of names 
while ($list = mysqli_fetch_assoc($result)) { ?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="id" value="<?php echo $list['facilitatorid']; ?>">
	<tr>
		<?php $editme = "edit-" . $list['facilitatorid'];
		if (isset($_POST[$editme])) { 
		?> 
		<td><input type="text" name="facilitatorname" class="textbox" value="<?php echo $list['facilitatorname']; ?>" required></td>
		<td><input type="text" name="email" class="textbox" value="<?php echo $list['email']; ?>"></td>

		<td><button type="submit" name="save" value="<?php echo $list['facilitatorid']; ?>">Save</button></td>
		<?php } else { ?>
		<td><?php echo $list['facilitatorname']; ?></td>
		<td><?php echo $list['email']; ?></td>
		
		<td><input type="submit" name="edit-<?php echo $list['facilitatorid']; ?>" value="Edit"></td>
		<?php } ?>	
		<td><button type="submit" name="delete" value="<?php echo $list['facilitatorname']; ?>">Delete</button></td>
	</tr>
</form>
<?php 
} // end while
?>
</table>	
</body>
</html>