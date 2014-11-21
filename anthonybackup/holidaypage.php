<?php
	include("connection.php");
	session_start();
	$_SESSION['set'] = 0;
	$_SESSION['adminSet'] = 0;
	
	if($result = $db_server->query("SELECT * FROM logintest WHERE username='pscs'")){
		$row = $result->fetch_assoc();
		$result->free();
	}

	$_SESSION['adminPass'] = $row['adminPass'];
	
	if(isset($_SESSION['prevURL']))    $url = $_SESSION['prevURL'];
	// holds url for last page visited. else    $url = "index.php";
	?><?php
	
	if(isset($_POST['Submit'])){
		
		if($_POST['mypassword'] == $_SESSION['adminPass']){
			$_SESSION['adminSet'] = 1;
			
			if($_SESSION['adminSet']){
				$_SESSION['set'] = 1;
				setcookie("login","admin");
			}

		}

		
		if($_SESSION['adminSet'] == 1){
			header("location:$url");
		} else {
			echo "    <div id='banner'>
    <div id='banner-content'>
    <center><b>Incorrect Password</b></center>
    </div>
  </div>";
		}

	}

	?>
<html>
<head>
<link rel="stylesheet" type="text/css" href="holidaypage.css">
<title>Log in to PSCS attendance system</title>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8"/>
</head>
<body style="background-color: dimgray;">
    <div id="puttheimagehere" style="position: fixed; opacity: 0.5; z-index: -1;">
	<img src="img/mobius.png">
    </div>
    <?php require('holidaypageJS.php');  ?>
  <div id="main-content">
<form name="form1" method="post" action="holidaypage.php">
    <table width="300" border="0" align="center" cellpadding="0" cellspacing="1" bgcolor="#CCCCCC">
    <td>
<table width="100%" border="0" cellpadding="3" cellspacing="1"
bgcolor="#FFFFFF">
<tr>
<td colspan="3">
    <center>
	<font color="red">
	    <strong>NO SCHOOL TODAY</strong>
	</font>
    </center>
    <center>
	<a href="#" id="ab1">Login as admin</a>
    </center>
</td>
</tr>
<div id="adminlogin">
<tr>
<td>
    <center>
	<input name="mypassword" type="password" id="mypassword" placeholder="Admin Password" required> <input type="submit" name="Submit" value="Login">
</center>
    </td>
</tr>
</div>
</table>
</td>
</table>
</form>
</div>
</body>
</html>