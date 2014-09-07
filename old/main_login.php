<html>
<head>
    <title>Login :>)</title>
</head>
<body style="background-color: dimgray;">
    <div id="puttheimagehere" style="position: fixed; opacity: 0.5; z-index: -1;">
	<img src="img/mobius.png">
    </div>
<?php
include("../connection.php");

session_start();

$_SESSION['set'] = 0;
$_SESSION['adminSet'] = 0;

if($result = $db_server->query("SELECT * FROM logintest WHERE username='pscs'"))
{
    $row = $result->fetch_assoc();
	
    $result->free();
}

$_SESSION['username'] = $row['username'];
$_SESSION['password'] = $row['password'];
$_SESSION['adminPass'] = $row['adminPass'];

if(isset($_SESSION['prevURL'])) 
   $url = $_SESSION['prevURL']; // holds url for last page visited.
else 
   $url = "attendance.php";
?>
<table width="300" border="0" align="center" cellpadding="0" cellspacing="1"
bgcolor="#CCCCCC">
<tr>
<form name="form1" method="post" action="main_login.php">
<td>
<table width="100%" border="0" cellpadding="3" cellspacing="1"
bgcolor="#FFFFFF">
<tr>
<td colspan="3"><strong>Login </strong>
</td>
<tr>
<td>Password</td>
<td>:</td>
<td><input name="mypassword" type="password" id="mypassword" required></td>
</tr>
<tr>
<td>&nbsp;</td>
<td>&nbsp;</td>
<td><input type="submit" name="Submit" value="Login"></td>
</tr>
</table>
</td>
</form>
</tr>
</table>
<?php
if(isset($_POST['Submit']))
{
	if($_POST['mypassword'] == $_SESSION['password'])
		{
			$_SESSION['set'] = 1;
			setcookie("login","student");
		}
	elseif($_POST['mypassword'] == $_SESSION['adminPass'])
		{
			$_SESSION['adminSet'] = 1;
				
				if($_SESSION['adminSet'])
					{
						$_SESSION['set'] = 1;
						setcookie("login","admin");
					}
		}
	if($_SESSION['set'] == 1 || $_SESSION['adminSet'] == 1)
		{
		         echo $url;
			header("location:$url");
		}
	else
		die("Wrong password :^)");
}
?>
</body>
</html>