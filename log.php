<?php
require_once("connection.php");
require_once("function.php");
if ($LoginResult = $db_server->query("SELECT * FROM login WHERE username='pscs'")){
$LoginRow = $LoginResult->fetch_assoc();
}
$info = NULL;
$returntime = NULL;
$password = crypt($_GET['crypt'], 'P9');
if($password == $LoginRow['password']){
    if($_GET['studentid'] && $_GET['statusid']){
        if(!empty($_GET['info'])){
            $info = $_GET['info'];
        }
        if(!empty($_GET['returntime'])){
            $returntime = $_GET['returntime'];
        }
        changestatus($_GET['studentid'], $_GET['statusid'], $info, $returntime);
    }
}
?>