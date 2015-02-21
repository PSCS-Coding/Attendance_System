<?php
include("connection.php");

$phpdatetime = new dateTime();
$currentDate = $phpdatetime->format('dym');

if ($result = $db_server->query("SELECT * FROM login WHERE username='pscs'"))
{
    $row = $result->fetch_assoc();
	
    $result->free();
}
$studentPW = $row['password'];
$adminPW = $row['adminPass'];
// Appdends date to password
$SecureAdminPW = $adminPW;
$SecureAdminPW .= crypt($currentDate, 'M7');
$SecureStudentPW = $studentPW;
$SecureStudentPW .= crypt($currentDate, 'M7');


$StudentLogin = 0;
$AdminLogin = 0;
//echo htmlspecialchars($_GET["logout"]);
//get logout from url
if (!empty($_GET["logout"])) {
    
if (($_GET["logout"]) == "1") {
    // delete cookie
    setcookie("login", "", time()-3600);
}
    
}
if (!empty($_GET["logout"])) {
    
if ($_COOKIE["login"] == $SecureAdminPW) {
    //remove cookie if admin loads page
    setcookie("login", "", time()-3600);
} elseif ($_COOKIE["login"] == $SecureStudentPW) {
    //remove cookie if student loads page
    setcookie("login", "", time()-3600);
}
} else {
    //delete login cookie
    setcookie("login", "", time()-3600);
}

//if(isset($_SESSION['prevURL'])) 
//   $url = $_SESSION['prevURL']; // holds url for last page visited.
//else 
   $url = "index.php";
?><?php
if(isset($_POST['Submit']))
{
	if (crypt($_POST['mypassword'], 'P9') == $studentPW)
		{
            $StudentLogin = 1;
			setcookie("login", $SecureStudentPW, time()+28800); // 8 hours
		}
	elseif (crypt($_POST['mypassword'], 'P9') == $adminPW)
		{
            $AdminLogin = 1;
            setcookie("login", $SecureAdminPW, time()+28800); // 8 hours		
		}
	if ($AdminLogin == 1) {
        
     header("location:$url");
        
		} elseif ($StudentLogin == 1) {
        
        echo '<META http-equiv="refresh" content="0;URL=index.php">';
    } else
		die("Wrong password :^)");
}
?>
<html>
<head>
<title>PSCS attendance system login</title>
<link rel="shortcut icon" type="image/png" href="img/mobius.png"/>
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
<form name="form1" method="post" action="secondary_login.php" style="padding-top: 10px;">

<strong class="logintext">Login </strong>
<div class="spacer"></div>
    Password :
<input class="textbox" name="mypassword" type="password" id="mypassword" required class="loginpassword">
<a href="#" class="loginbutton"><input class="button" type="submit" name="Submit" value="Login"></a>
</form>
        </div>
</body>
</html>