<?php
// connect to db and import functions
require_once("../connection.php");
require_once("../function.php");

// fetch admin credentials
if ($LoginResult = $db_server->query("SELECT * FROM login WHERE username='pscs'")){
    $LoginRow = $LoginResult->fetch_assoc();
}

// setup variables
$info = NULL;
$returntime = NULL;
$password = crypt($_GET['crypt'], 'P9');

// if the password matches, change the status
if($password == $LoginRow['password']){
    if($_GET['studentid'] && $_GET['statusid']){
        if(!empty($_GET['info']))
            $info = $_GET['info'];
        if(!empty($_GET['returntime']))
            $returntime = $_GET['returntime'];
        changestatus($_GET['studentid'], $_GET['statusid'], $info, $returntime);
    }
}
?>