<?php
include("connection.php");

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

//if(isset($_SESSION['prevURL'])) 
//   $url = $_SESSION['prevURL']; // holds url for last page visited.
//else 
   $url = "index.php";
?><?php
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
			header("location:$url");
		}
	else
		die("Wrong password :^)");
}
?>
<html>
<head>
<title>Log in to PSCS attendance system</title>
</head>

<style>
    #loginform {
	text-align: center;
	margin-top: 3em;
	background-color: grey;
	padding: 3px 12px;
	width: 270px;
	margin-left: auto;
	margin-right: auto;
	border-radius: 5px;
	opacity: 0.9;
    }
    .logintext {
	margin: 100px;
    }
    .textbox { 
	border: 1px solid #848484; 
	-webkit-border-radius: 30px; 
	-moz-border-radius: 30px; 
	border-radius: 30px; 
	outline:0; 
	height:20px; 
	width: 150px; 
	padding-left:10px; 
	padding-right:10px; 
      }
      .spacer {
	padding: 6px;
      }
      p {
	color: lightgey;
      }
    .button {
      border-radius: 5px;
      border: 0;
      font-family: Arial;
      color: black;
      font-size: 12px;
      background: lightgray;
      text-decoration: none;
    }
    a.button:last-child {
	border-right: 0;
    }
    
    .button:hover {
	background: rgb(190, 190, 190);
	text-decoration: none;
	color: gray;
    }

</style>

<body style="background-color: dimgray;">
    <div id="puttheimagehere" style="position: fixed; opacity: 0.5; z-index: -1;">
	<img src="img/mobius.png">
    </div>
    <div id="loginform">
<form name="form1" method="post" action="main_login.php" style="padding-top: 10px;">

<strong class="logintext">Login </strong>
<div class="spacer"></div>
    Password :
<input class="textbox" name="mypassword" type="password" id="mypassword" required class="loginpassword">
<a href="#" class="loginbutton"><input class="button" type="submit" name="Submit" value="Login"></a>
</form>
        </div>
</body>
</html>