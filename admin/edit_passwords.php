<html>
<head>
<body>
<h1>Edit Passwords</h1>

<?php
				
// CHANGE PASSWORD
if (isset($_POST['savepass'])) {
 $updatepass = $db_server->prepare("UPDATE logintest SET adminPass = ? , password = ? WHERE password = ?");
	  $updatepass->bind_param('ssi', $_POST['adminpassword'], $_POST['password'], $_POST['id']);
	  $updatepass->execute(); 
	  $updatepass->close();
	} 
	
	require_once("../function.php");
require_once("../../connection.php");

// GET THE LIST OF PASSWORDS
	$passwordresult = $db_server->query("SELECT * FROM logintest ORDER BY password");

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
// loop through passwords
while ($passlist = mysqli_fetch_assoc($passwordresult)) { ?>

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
<input type="hidden" name="id" value="<?php echo $passlist['password']; ?>">
	<tr>
		<?php $editme = "editpass-" . $passlist['password'];
		if (isset($_POST[$editme])) { 
		?> 
		<td><input type="text" name="adminpassword" value="<?php echo $passlist['adminPass']; ?>" required></td>
		<td><input type="text" name="password" value="<?php echo $passlist['password']; ?>"></td>
	    
		<td><button type="submit" name="savepass" value="<?php echo $passlist['password']; ?>">Save</button></td>
		<?php } else { ?>
                <td><?php echo $passlist['adminPass']; ?></td>
                </div>
		<td><?php echo $passlist['password']; ?></td>
		
		<td><input type="submit" name="editpass-<?php echo $passlist['password']; ?>" value="Edit"></td>
		<?php } ?>	
	</tr>
</form>
<?php 
} // end while
?>
</table>	
</body>
</html>