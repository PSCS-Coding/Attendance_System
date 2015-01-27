<html>
<body>
<h1 class="headerr">Edit Passwords</h1>
     
<?php 
    // CHANGE PASSWORD
if (isset($_POST['savepass'])) {
 $updatepass = $db_server->prepare("UPDATE logintest SET adminPass = ? , password = ? WHERE password = ?");
	  $updatepass->bind_param('ssi', $_POST['adminpassword'], $_POST['password'], $_POST['id']);
	  $updatepass->execute(); 
	  $updatepass->close();
	} 
	

// GET THE LIST OF PASSWORDS
	$passwordresult = $db_server->query("SELECT * FROM logintest ORDER BY password");
    ?>
    
<div class="passwords">
<table>
   <tr>
      <th>Admin Password:</th>
      <th>Default Password:</th>
      <th>Edit</th>
   </tr>
<?php
// loop through passwords
while ($passlist = mysqli_fetch_assoc($passwordresult)) { ?>

<form action="?p=Pws" method="post">
<input type="hidden" name="id" value="<?php echo $passlist['password']; ?>">
	<tr>
		<?php $editme = "editpass-" . $passlist['password'];
		if (isset($_POST[$editme])) { 
		?> 
		<td><input type="text" name="adminpassword" value="<?php echo $passlist['adminPass']; ?>" required></td>
		<td><input type="text" name="password" value="<?php echo $passlist['password']; ?>"></td>
	    
		<td><button type="submit" name="savepass" value="<?php echo $passlist['password']; ?>">Save</button></td>
		<?php } else { ?>
        
<?php
    // Admin Password
    $apass = $passlist['adminPass'];
    $newapass = str_ireplace($apass, "confidential", $apass);
    // Default Password
    $dpass = $passlist['password'];
    $newdpass = str_ireplace($dpass, "confidential", $dpass);
?>
                <td><?php echo $newapass ?></td>
                </div>
		<td><?php echo $newdpass ?></td>
		
		<td><input type="submit" name="editpass-<?php echo $passlist['password']; ?>" value="Edit"></td>
		<?php } ?>	
	</tr>
</form>
<?php 
} // end while
?>
</table>
</div>
</body>
</html>