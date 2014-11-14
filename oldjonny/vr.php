<!DOCTYPE html>
<html>
<?php 

//start session
session_start();

// set up mysql connection
require_once("../connection.php");

//function document
require_once("function.php");



$studentList = $db_server->query("SELECT * FROM events WHERE studentid = 2");
$total = 0;
$lastTimestamp = NULL;

while($rowdata = $studentList->fetch_assoc()){
$timeconvert = $rowdata['timestamp'];

$currentTimestamp = new dateTime($timeconvert);

if($lastTimestamp == NULL){

$lastTimestamp = new dateTime();

}

$piece = $currentTimestamp->diff($lastTimestamp);

echo $lastTimestamp->format('Y-m-d H:i:s');
?> <br> <?php
echo $currentTimestamp->format('Y-m-d H:i:s');
?> <br> <?php
echo $piece->format('%H:%i:%s');
?> <br> <?php

}
?>
</html>