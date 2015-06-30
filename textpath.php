<?php

// PATH TO IMAGES AS VARIABLES (ACTUAL PATHS TO IMAGE FILES)
$pizzapath = "<img src='img/text/pizza.png' style='width:15px;height:15px;'>";
$uwajipath = "<img src='img/text/uwaji.png' style='width:20px;height:20px;'>";
$lennypath = "<img src='img/text/lenny.png' style='width:20px;height:20px;'>";
$bteapath = "<img src='img/text/btea.png' style='width:20px;height:20px;'>";
$brickspath = "<img src='img/text/12.png' style='width:20px;height:20px;'>";
$pinkpath = "<img src='img/text/pink.png' style='width:20px;height:20px;'>";
$yumyumpath = "<img src='img/text/yumyum.png' style='width:20px;height:20px;'>";
$sammypath = "<img src='img/text/sammy.png' style='width:20px;height:20px;'>";
$sbuxpath = "<img src='img/text/starbucks.png' style='width:20px;height:20px;'>";
$thumbsuppath = "<img src='img/text/thefinger.png' style='width:20px;height:20px;'>";
// THESE ARE THE WORDS YOU WANT TO DETECT
$keywords = array(
    "!smile",
    "!pizza",
    "!cp",
    "!uwaji",
    "!lenny",
    "!btea",
    "!12",
    "!pink",
    "!yumyum",
    "!sammy",
    "!*shakes head*",
    "!thefinger");

// THESE ARE THE REPLACEMENT WORDS/IMAGE PATHS
$replacewords = array(
    ":) ",
    "$pizzapath",
    "&copy; ",
    "$uwajipath",
    "$lennypath",
    "$bteapath",
    "$brickspath",
    "$pinkpath",
    "$yumyumpath",
    "$sammypath",
    "$sbuxpath",
    "$thumbsuppath");
?>