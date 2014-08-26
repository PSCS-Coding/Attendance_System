<html>
<head>
<body>
<h1>Edit Passwords</h1>
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
// set up mysql connection
	require_once("../connection.php");
//function document
	//require_once("function.php");
	//admin menu
       include 'admin-navbar.php';
				

// EDIT (UPDATE) A Facilitator
if (isset($_POST['save'])) {
 $stmt = $db_server->prepare("UPDATE logintest SET adminPass = ? , password = ? WHERE password = ?");
	  $stmt->bind_param('ssi', $_POST['adminpassword'], $_POST['password'], $_POST['id']);
	  $stmt->execute(); 
	  $stmt->close();
	} 
	
	

// GET THE LIST OF Students
	$result = $db_server->query("SELECT * FROM logintest ORDER BY password");

?>

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
.admincolor {
color:red;
}
</style>
<table style="width:400px">
   <tr>
      <th style="text-align:left">Admin Password:</th>
      <th style="text-align:left">Default Password:</th>
      <th style="text-align:left">Edit</th>
   </tr>
<?php
// loop through list of names 
while ($list = mysqli_fetch_assoc($result)) { ?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="id" value="<?php echo $list['password']; ?>">
	<tr>
		<?php $editme = "edit-" . $list['password'];
		if (isset($_POST[$editme])) { 
		?> 
		<td><input type="text" name="adminpassword" value="<?php echo $list['adminPass']; ?>" required></td>
		<td><input type="text" name="password" value="<?php echo $list['password']; ?>"></td>
	    
		<td><button type="submit" name="save" value="<?php echo $list['password']; ?>">Save</button></td>
		<?php } else { ?>
                <td><?php echo $list['adminPass']; ?></td>
                </div>
		<td><?php echo $list['password']; ?></td>
		
		<td><input type="submit" name="edit-<?php echo $list['password']; ?>" value="Edit"></td>
		<?php } ?>	
	</tr>
</form>
<?php 
} // end while
?>
</table>	
</body>
</html>