<?php

include("connection.php");
session_start();

$disabled = FALSE;

// check if login is disabled
if (isset($_SESSION['timeout'])){
    if(time() - $_SESSION['timeout'] > 300){ // check if the timeout has been long enough
        $disabled = FALSE;
    } else {
        $disabled = TRUE;
    }
}



// Set current date & format to timestamp
    $phpdatetime = new dateTime();
    $currentDate = $phpdatetime->format('y-m-d h:i:s');

// Set query results as array
    $loginQuery = array();
    $timeoutQuery = array();

if ($loginQuery = $db_server->query("SELECT * FROM login WHERE username='pscs'")){
    $pwdRow = $loginQuery->fetch_assoc();
}

// Set varibles for student & admin passwords
$studentPassword = $pwdRow['password'];
$adminPassword = $pwdRow['adminPass'];

// IF USER HAS LOGGED OUT DELETE COOKIE
if (!empty($_GET["logout"])) {

    if (($_GET["logout"]) == "1") {
        setcookie("login", "", time()-3600); // deletes the cookie
    }
}

// GET EXPERATION DATES FOR COOKIES FROM DATABASE
if ($timeoutQuery = $db_server->query("SELECT * FROM globals"))
{
    $expRow = $timeoutQuery->fetch_assoc();
}

$DBstudentTimeout = $expRow['studentTimeout'];
$DBadminTimeout = $expRow['adminTimeout'];

// Convert database days to seconds
$studentTimeout = 86400 * $DBstudentTimeout;
$adminTimeout = 86400 * $DBadminTimeout;

// SET URL FOR WHEN LOGGED IN
$url = "index.php";

// SETTING LOGINS TO NULL
$StudentLogin = null;
$AdminLogin = null;

if(isset($_POST['Submit']))
{
	if (crypt($_POST['mypassword'], 'P9') == $studentPassword) {
            // SET LOGIN COOKIE
            $StudentLogin = 1;
			setcookie("login", $studentPassword, time()+$studentTimeout); // 8 hours

		}

    elseif (crypt($_POST['mypassword'], 'P9') == $adminPassword) {
            // SET LOGIN COOKIE
            $AdminLogin = 1;
            setcookie("login", $adminPassword, time()+$adminTimeout); // 8 hours

		}

    if ($AdminLogin == 1) {

    header("location:$url");

    } elseif ($StudentLogin == 1) {

        echo '<META http-equiv="refresh" content="0;URL=index.php">';

    } else {
        // echo("<br> <br>");
        // print_r($_SESSION);
        // echo($disabled);
        if (!empty($_SESSION['tries'])){
            if($_SESSION['tries'] == 4){
                $disabled = TRUE;
                $_SESSION['tries'] == 0;
                echo("<div class='error'>Too many tries, try again later.</div>");
                $_SESSION['timeout'] = time();
            } else {
                $_SESSION['tries'] += 1;
            }
        } else {
            $_SESSION['tries'] = 1;
        }
        echo("<div class='error'> Wrong password.</div>");
    }
}

?>
<html>
<head>
    <title>PSCS attendance system login</title>
    <link rel="shortcut icon" type="image/png" href="static/mobius.png"/>
    <link rel="stylesheet" type="text/css" href="static/attendance.css">
</head>
<style>

    #loginform {
	text-align: center;
	margin-top: 3em;
	background-color: grey;
	padding: 4px 17px;
	width: 274px;
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
    <meta name="viewport" content="user-scalable=no, initial-scale=1, maximum-scale=1, minimum-scale=1, width=device-width">
</head>


<body style="background-color: dimgray;">



<div id="loginform">
<form name="form1" method="post" action="secondary_login.php" style="padding: 10px 0;">
    <strong class="logintext">Login </strong>
    <div class="spacer"></div>
    Password :
    <?php if ($disabled){
        echo('<input class="textbox" name="mypassword" type="text" value="try again later" id="mypassword" disabled required class="loginpassword">');
    } else {
        echo('<input class="textbox" name="mypassword" type="password" id="mypassword" required autofocus="autofocus" class="loginpassword">');
    }?>
    <a href="#" class="loginbutton"><input class="button" type="submit" name="Submit" value="Login"></a>
</form>
</div>

</body>
</html>
