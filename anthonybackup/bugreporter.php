<?php
//login setup
	session_start();
	$_SESSION['prevURL'] = $_SERVER['REQUEST_URI'];
	//make this $_SESSION['adminSet'] if it's an admin-only page and $_SESSION['set'] if its a public one
	if(!$_SESSION['set'])
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
	<title>PSCS Attendance Bug Reporter</title>
	<link rel="stylesheet" type="text/css" href="attendance.css">
</head>


<body class="single-user">
	<div id="puttheimagehere">
		<img src="img/mobius.png">
	</div>
        
        
<!-- Links And Name-->
	<div id="single-body">
	<div id="links">
		<a href="index.php">Back to main page</a>  
	</div>	
	<h2 class="studentname">Bug Reporter</h2>
        
		<div class="statusmessage">
<p>Report bugs here!</p>
		</div>
                
                
                

<!-- form for sending bugs -->

<form name="contactform" method="post" action="bugreporter.php">
    <div>
    
		<!-- Creates the dropdown of names -->
		 <select name=name required><option selected>Your Name</option> <?php
		     $nameget = $db_server->query("SELECT * FROM studentdata WHERE current = 1 ORDER BY firstname ASC");
		      while ($name_option = $nameget->fetch_assoc()) {
	        ?>  <option value= '<?php echo $name_option['firstname']; ?> '> <?php echo $name_option['firstname']; ?></option> <?php } ?>> </select>
                
                <!-- List of categorys -->
                <select name=cat required>
                                <option value="null" selected>Bug Category</option>
                                <option value="Technical">Technical</option>
                                <option value="Design">Design</option>
                                <option value="Other">Other</option>
                                </select>
                
                <!-- Imput Box for bugs -->
       <input  type="text" name="comments" maxlength="500" placeholder="Short Desc of Bug" size="20" required>
        
        <!-- Other Stuff -->
        <input  type="hidden" name="email" value="Attendance-Bug-Reporter@code.pscs.org">
        <input  type="hidden" name="subject" value="Bug Reporter: New Bug!">
            
            <!-- Send Bug to emails -->
        <button type="submit" value="Submit">Report Bug</button>
    </div>
	<!-- Required for emailing bugs -->
 <?php require_once('bugsemailfunction.php');  ?>
</body>
</html>