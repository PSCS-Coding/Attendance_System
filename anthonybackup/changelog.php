<?php
//login setup
	session_start();
	$_SESSION['prevURL'] = $_SERVER['REQUEST_URI'];
	//make this $_SESSION['adminSet'] if it's an admin-only page and $_SESSION['set'] if its a public one
	if(!$_SESSION['adminSet'])
	{
		header("location: main_login.php");
	}
//load required external files
    require_once("connection.php");
   	require_once("function.php");
        
?>
<!DOCTYPE html>
<html>
    
    
<head>
	<title>PSCS Attendance Changelog</title>
	<link rel="stylesheet" type="text/css" href="attendance.css">
</head>



<?php

// ADD A NEW HOLIDAY

if (isset($_POST['addlog'])) {
    $addfeatures = $_POST['feature'];
$stmt = $db_server->prepare("INSERT INTO changelog (CLinfo, CLdate) VALUES (?, ?)");
$stmt->bind_param('ss', $addfeatures , $_POST['logdate']);
$stmt->execute(); 
$stmt->close();

}				
	

// GET THE LIST OF HOLIDAYS
	$result = $db_server->query("SELECT * FROM changelog ORDER BY CLid");

?>




<body class="single-user">
	<div id="puttheimagehere">
		<img src="img/mobius.png">
	</div>
        
        
<!-- Links And Name-->
	<div id="single-body">
            <div id="links">
                <?php
                
                if (isset($_POST['addlog'])) {
                    
                    echo "<a>New feature: $addfeatures</a>";
                    
                }else{
                    
                   echo "<a>No feature added yet.</a>"; 
                }
                
                ?>
	</div>
	<h2 class="studentname">Change Log</h2>
        
		<div class="statusmessage">
<p>Add Items to the changelog!</p>
		</div>
                
                
                

<!-- form for sending bugs -->

<form action="<?php echo $_SERVER['PHP_SELF']; ?>" method="post">
    <div>
    
		<!-- Get Date Of new thing -->
		 <input type="date" name="logdate">
                
                <!-- Imput Box for bugs -->
       <input  type="text" name="feature" maxlength="100" placeholder="Added New Feature" size="20" required>
        
            <!-- Send Bug to emails -->
        <input type="submit" name="addlog" value="Add to Changelog" />
    </div>
    </form>
    
    
    
</body>
</html>