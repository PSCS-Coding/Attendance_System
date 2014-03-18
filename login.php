<?php
$db_server = mysql_connect("localhost", "pscs", "Courage!");
if (!$db_server) die("Unable to connect to MySQL: " . mysql_error());
mysql_select_db("attendance", $db_server)
	or die("Unable to select database: " . mysql_error());

if(isset($_POST['submit'])){
    $uname = mysql_escape_string($_POST['username']);
    $pass = mysql_escape_string($_POST['password']);
    $pass = md5($pass);
    
    $sql = mysql_query("SELECT * FROM `logintest` WHERE `username` = '$username' AND `password` = '$password'");
    if(mysql_num_rows($sql) > 0) {
        echo "You are now logged in.";
        exit();
        
    }else{
        echo "Wrong username or password.";
        
    }
    
}else{
    
    
$form = <<<EOT
<div align="center">
<h1>Please log in.</h1>
<form action="login.php" method="POST">
Username: <input type="text" name="username" /><br />
Password: <input type="password" name="password" /><br />
<input type="submit" name="submit" value="Log in" />
</form>
</div>
EOT;
echo $form;
}





?>