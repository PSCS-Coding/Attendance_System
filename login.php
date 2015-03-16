<?php
require_once("connection.php");
if ($LoginResult = $db_server->query("SELECT * FROM login WHERE username='pscs'"))
{
$LoginRow = $LoginResult->fetch_assoc();
$LoginResult->free();
}
$studentPW = $LoginRow['password'];
$adminPW = $LoginRow['adminPass'];
$SecureAdminPW = $adminPW;
$SecureStudentPW = $studentPW;
$crypt = crypt('adenz8r3ry8nyinynzyi', 'P9');
if (isset($_COOKIE["login"])) {
if ($SecureAdminPW == $_COOKIE["login"] || $SecureStudentPW == $_COOKIE["login"] || $crypt == $_COOKIE["login"]) {
} else {
header('Location: secondary_login.php?PBM=INVCK', true);
exit;
}
if (isset($admin) && $_COOKIE['login'] == $SecureStudentPW) {
header('Location: secondary_login.php', true);
}
} else {
header('Location: secondary_login.php?PBM=2', true);
exit;
}
?>