<?php
include("connection.php");

//echo htmlspecialchars($_GET["logout"]);
//get logout from url
if (htmlspecialchars($_GET["logout"]) == "1"){  
    // delete cookie
   setcookie("login", "", time()-3600);
}

if ($_COOKIE["login"] == "admin") {
    //remove cookie if admin loads page
    setcookie("login", "", time()-3600);
} elseif ($_COOKIE["login"] == "student") {
    //redirect if student loads page
    echo '<META http-equiv="refresh" content="0;URL=index.php">';
} else {
    //delete login cookie
    setcookie("login", "", time()-3600);
}

if($result = $db_server->query("SELECT * FROM logintest WHERE username='pscs'"))
{
    $row = $result->fetch_assoc();
	
    $result->free();
}
$logindefault = 0;
$loginadmin = 0;
$defaultpassword = $row['password'];
$adminpassword = $row['adminPass'];
//if(isset($_SESSION['prevURL'])) 
//   $url = $_SESSION['prevURL']; // holds url for last page visited.
//else 
   $url = "index.php";
?><?php
if(isset($_POST['Submit']))
{
	if($_POST['mypassword'] == $defaultpassword)
		{
            $logindefault = 1;
			setcookie("login", "student", time()+3600); // 1 hour
		}
	elseif($_POST['mypassword'] == $adminpassword)
		{
            $loginadmin = 1;
            setcookie("login", "admin", time()+3600); // 1 hour
					
		}
	if ($loginadmin == 1)
		{
			header("location:$url");
		} elseif ($logindefault == 1) {
        echo '<META http-equiv="refresh" content="0;URL=index.php">';
    }
	else
		die("Wrong password :^)");
}
?>
<html>
<head>
<title>PSCS attendance system login</title>
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