<?php
session_start();
require_once("connection.php");
$globalsQuery = $db_server->query("SELECT *
								  FROM globals
								  ");
								  while($globalsArray = $globalsQuery->fetch_assoc()) {
									$date = $globalsArray['startdate'];
									}
$date = date_create($date);
date_add($date, date_interval_create_from_date_string('10 days'));
echo date_format($date, 'Y-m-d');
?>