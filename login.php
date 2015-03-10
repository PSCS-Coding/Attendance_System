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
$SecureAdminPW .= crypt($currentLoginDate, 'M7');
$SecureStudentPW = $studentPW;
$SecureStudentPW .= crypt($currentLoginDate, 'M7');
$crypt = crypt('adenz8r3ry8nyinynzyi', 'P9');
if (isset($_COOKIE["login"])) {
if ($SecureAdminPW == $_COOKIE["login"] || $SecureStudentPW == $_COOKIE["login"] || $crypt == $_COOKIE["login"]) {
} else {
header('Location: http://localhost:8888/secondary_login.php?PBM=1', true);
exit;
}
} else {
header('Location: http://localhost:8888/secondary_login.php?PBM=2', true);
exit;
}
?>