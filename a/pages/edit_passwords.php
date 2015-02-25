<html>
<body>
<?php 
    // CHANGE PASSWORD
if (!empty($_POST['saveadminpass'])) {
// Adding Crypt to admin password
    $AdminCrypt = crypt($_POST['adminpassword'], 'P9');
 $updatepass = $db_server->prepare("UPDATE login SET adminPass = ? WHERE password = ?");
	  $updatepass->bind_param('si', $AdminCrypt, $_POST['PassID']);
	  $updatepass->execute(); 
	  $updatepass->close();
	}
if (!empty($_POST['savestudentpass'])) {
// Adding Crypt to student password
    $StudentCrypt = crypt($_POST['password'], 'P9');
 $updatepass = $db_server->prepare("UPDATE login SET password = ? WHERE password = ?");
	  $updatepass->bind_param('si', $StudentCrypt, $_POST['PassID']);
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
<input type="hPassIDden" name="PassPassID" value="<?php echo $passlist['password']; ?>">
        <div id="adminpwd">
		<input type="password" name="adminpassword" placeholder="New Admin Password" autocomplete="off">
    <button type="submit" name="saveadminpass" value="<?php echo $passlist['password']; ?>">Update</button>
    </div>
    <div id="studentpwd">
		<input type="password" name="password" placeholder="New Student Password" autocomplete="off">
    <button type="submit" name="savestudentpass" value="<?php echo $passlist['password']; ?>">Update</button>
	    	</div>
</form>
<?php 
} // end while
?>
</div>
</body>
</html>