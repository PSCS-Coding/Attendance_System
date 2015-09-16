<?php
$admin = 1;
require_once('../../login.php');
?>
<html>
<head>
	<title>Edit Passwords</title>
	<?php require_once('header.php'); ?>
</head>
<body class="adminpage edit-passwords">
<?php 
// Header Info
$HeaderStatus = null;
$HeaderInfo = "Update Passwords";
    // CHANGE PASSWORD
if (!empty($_POST['saveadminpass'])) {
    if ($_POST['adminpassword'] != null) {
// Adding Crypt to admin password
    $AdminCrypt = crypt($_POST['adminpassword'], 'P9');
 $updatepass = $db_server->prepare("UPDATE login SET adminPass = ?");
	  $updatepass->bind_param('s', $AdminCrypt);
	  $updatepass->execute(); 
	  $updatepass->close();
     $HeaderStatus = "Sussess";
     $HeaderInfo = "Sussessfully updated admin password.";
	} else {
     $HeaderStatus = "Error";
     $HeaderInfo = "Please enter a valid password.";
    }
}
if (!empty($_POST['savestudentpass'])) {
    if ($_POST['password'] != null) {
// Adding Crypt to student password
    $StudentCrypt = crypt($_POST['password'], 'P9');
    echo "studentcrypt = " . $StudentCrypt . " and post password = " . $_POST['password'];
    
 $updatepass = $db_server->prepare("UPDATE login SET password = ?");
	  $updatepass->bind_param('s', $StudentCrypt);
	  $updatepass->execute(); 
	  $updatepass->close();
     $HeaderStatus = "Sussess";
     $HeaderInfo = "Sussessfully updated student password.";
	} else {
     $HeaderStatus = "Error";
     $HeaderInfo = "Please enter a valid password.";
    }
}

// GET THE LIST OF PASSWORDS
	$passwordresult = $db_server->query("SELECT * FROM login ORDER BY password");
    ?>
            <div id="TopHeader" class="<?php echo $HeaderStatus; ?>">
              <h1 class="Myheader"><?php echo $HeaderInfo; ?></h1>
                </div>
            <div align="center" id="main">
<div class="admintable">
<?php
// loop through passwords
while ($passlist = mysqli_fetch_assoc($passwordresult)) { ?>

<form action="" method="post">
<input type="hidden" name="PassPassID" value="<?php echo $passlist['password']; ?>">
        <div id="adminpwd">
		<input type="password" name="adminpassword" placeholder="New Admin Password" autocomplete="off" size="20">
    <button type="submit" name="saveadminpass" value="<?php echo $passlist['password']; ?>">Update</button>
    </div>
    <div id="studentpwd">
		<input type="password" name="password" placeholder="New Student Password" autocomplete="off" size="20">
    <button type="submit" name="savestudentpass" value="<?php echo $passlist['password']; ?>">Update</button>
	    	</div>
</form>
<?php 
} // end while
?>
</div>
        </div>
<script>
       $(document).ready(function() {
	       $('#TopHeader').delay(1500);
	       setTimeout(function() {
		       $('#TopHeader').removeClass();
		       $('#TopHeader .MyHeader').text('Update Passwords');
	       }, 1700);
	       
       
       });
</script>
</body>
</html>