<html>
<body>
<h1 class="headerr">Edit Passwords</h1>
     
<?php 
    // CHANGE PASSWORD
if (!empty($_POST['saveadminpass'])) {
 $updatepass = $db_server->prepare("UPDATE logintest SET adminPass = ? WHERE password = ?");
	  $updatepass->bind_param('si', md5($_POST['adminpassword']), $_POST['id']);
	  $updatepass->execute(); 
	  $updatepass->close();
	}
if (!empty($_POST['savestudentpass'])) {
 $updatepass = $db_server->prepare("UPDATE logintest SET password = ? WHERE password = ?");
	  $updatepass->bind_param('si', md5($_POST['password']), $_POST['id']);
	  $updatepass->execute(); 
	  $updatepass->close();
	} 
	

// GET THE LIST OF PASSWORDS
	$passwordresult = $db_server->query("SELECT * FROM login ORDER BY password");
    ?>
    
<div class="passwords">
<?php
// loop through passwords
while ($passlist = mysqli_fetch_assoc($passwordresult)) { ?>

<form action="?p=Pws" method="post">
<input type="hidden" name="id" value="<?php echo $passlist['password']; ?>">
        <div id="adminpwd">
		<input type="password" name="adminpassword" placeholder="New Admin Password">
    <button type="submit" name="saveadminpass" value="<?php echo $passlist['password']; ?>">Update</button>
    </div>
    <div id="studentpwd">
		<input type="password" name="password" placeholder="New Student Password">
    <button type="submit" name="savestudentpass" value="<?php echo $passlist['password']; ?>">Update</button>
	    	</div>
</form>
<?php 
} // end while
?>
</div>
</body>
</html>